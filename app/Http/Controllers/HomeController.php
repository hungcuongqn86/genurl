<?php

namespace App\Http\Controllers;

use App\Url;
use App\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

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
        $arrRules = [
            'original_url' => 'required | url'
        ];
        $arrMessages = [
            'original_url.required' => 'ERRORS_MS.EMPTY_ORIGINAL_URL',
            'original_url.url' => 'ERRORS_MS.NOT_ORIGINAL_URL'
        ];

        $validator = Validator::make($input, $arrRules, $arrMessages);
        if ($validator->fails()) {
            return response()->error($validator->errors()->all(), 400);
        }

        $uri = self::genUri();
        if ($uri === '') {
            return response()->error('NOT_CREATE_URI', 400);
        }
        DB::beginTransaction();
        try {
            $url = new Url;
            $url->original = $input['original_url'];
            $url->status = 1;
            $url->uri = self::genUri();
            $url->save();
            DB::commit();
            return response()->success([]);
        } catch (\PDOException $e) {
            DB::rollBack();
            // throw $e;
            return response()->error('MSG_PDO_Error', 400);
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e;
            return response()->error('MSG_Error', 400);
        }
    }

    private function genUri($length = 6, $rec = 0)
    {
        $uri = str_random($length);
        $existing = Url::where('uri', '=', $uri)->where('status', '=', 1)->count();
        if ($existing > 0) {
            if ($rec < 10) {
                $uri = $this->genUri($length, $rec + 1);
            } else {
                $uri = '';
            }
        }
        return $uri;
    }

    public function process($uri)
    {
        $url = Url::where('uri', '=', $uri)->first();
        if ($url) {
            $ip = \Request::ip();
            $data = \Location::get($ip);
            $countryCode = '';
            if ($data) {
                $countryCode = $data->countryCode;
            }
            // detec
            $agent = new Agent();
            $deviceType = 0;
            if ($agent->isDesktop()) {
                $deviceType = 1;
            } elseif ($agent->isPhone()) {
                $deviceType = 2;
            } elseif ($agent->isRobot()) {
                $deviceType = 3;
            }

            $referer = request()->headers->get('referer');
            $source = 'direct';
            if ($referer) {
                $refData = parse_url($referer);
                if (strrpos($refData['host'], 'facebook')) {
                    $source = 'facebook';
                } else if (strrpos($refData['host'], 'google')) {
                    $source = 'google';
                } else {
                    $source = $refData['host'];
                }
            }
            DB::beginTransaction();
            try {
                $log = new Logs;
                $log->url_id = $url->id;
                $log->ip = $ip;
                $log->countryCode = $countryCode;
                $log->referer = $source;
                $log->device_type = $deviceType;
                $log->device_name = $agent->device();
                $log->browser = $agent->browser();
                $log->platform = $agent->platform();
                $log->save();
                DB::commit();
                return response()->redirect($url->original);
            } catch (\PDOException $e) {
                DB::rollBack();
                throw $e;
                return response()->error('MSG_PDO_Error', 400);
            } catch (\Exception $e) {
                DB::rollBack();
                // throw $e;
                return response()->error('MSG_Error', 400);
            }
        }
    }

    private function getCountry($code)
    {
        $names = json_decode(file_get_contents("http://country.io/names.json"), true);
        return $names[$code];
    }
}
