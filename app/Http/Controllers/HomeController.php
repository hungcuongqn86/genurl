<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $urls = Url::orderBy('created_at', 'desc')->paginate(5);
        if ($request->ajax()) {
            return view('data', compact('urls'));
        }
        return view('home', compact('urls'));
    }

    public function shortener(Request $request)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            $arrRules = [
                'original_url' => 'required'
            ];
            $arrMessages = [
                'original_url.required' => 'ERRORS_MS.BAD_REQUEST',
            ];

            $validator = Validator::make($input, $arrRules, $arrMessages);
            if ($validator->fails()) {
                return response()->error($validator->errors()->all(), 400);
            }

            $uri = self::genUri();
            if ($uri==='') {
                return response()->error('NOT_CREATE_URI', 400);
            }

            $url = new Url;
            $url->original = $input['original_url'];
            $url->status = 1;
            $url->uri = self::genUri();
            $url->save();
            DB::commit();
            return response()->success([]);
        } catch (\PDOException $e) {
            DB::rollBack();
            throw $e;
            return response()->error(trans('messages.MSG_PDO_Error'), 400);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->error(trans('messages.MSG_Error'), 400);
        }
    }

    private function genUri($length = 6, $rec = 0)
    {
        $uri = str_random($length);
        $existing = Url::where('uri', '=', $uri)->where('status', '=', 1)->count();
        if ($existing > 0){
            if($rec < 10){
                $uri = $this->genUri($length, $rec + 1);
            }else{
                $uri = '';
            }
        }
        return $uri;
    }
}
