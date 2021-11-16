<?php

namespace App\Http\Controllers;

use App\ShortLinks;
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
        $urls = Url::with(['Logs', 'ShortLinks'])->orderBy('created_at', 'desc')->paginate(100);
        if ($request->ajax()) {
            return view('urldata', compact('urls'));
        }
        return view('index', compact('urls'));
    }

    public function getUrl($id, Request $request)
    {
        $urldata = Url::with('ShortLinks')->where('id', '=', $id)->first();
        if ($request->ajax()) {
            return view('detaildata', compact('urldata'));
        }

        // dd($urldata);
        return view('detail', compact('urldata'));
    }

    public function analytics($id, $time, Request $request)
    {
        $query = Url::with('Logs')->where('id', '=', $id);
        if ($time !== 'all_time') {
            $date = new \DateTime();

            if ($time === 'two_hours') {
                $date->sub(new \DateInterval('PT2H'));
            }

            if ($time === 'day') {
                $date->sub(new \DateInterval('P1D'));
            }

            if ($time === 'week') {
                $date->sub(new \DateInterval('P7D'));
            }

            if ($time === 'month') {
                $date = clone $date;
                $date->sub(new \DateInterval("P1M"));
            }

            $query = Url::with(['Logs' => function ($query) use ($date) {
                $query->where('created_at', '>=', $date);
            }])->where('id', '=', $id);
        }

        $urldata = $query->first();
        $cl_country = [];
        if ($urldata) {
            $logs = $urldata->Logs;
            $cl_country = $logs->groupBy('countryCode')->map(function ($log) {
                return $log->count();
            });

            $cl_referer = $logs->groupBy('referer')->map(function ($log) {
                return $log->count();
            });

            $cl_device_type = $logs->groupBy('device_type')->map(function ($log) {
                return $log->count();
            });

            $cl_device = $logs->groupBy('device_name')->map(function ($log) {
                return $log->count();
            });

            $cl_platform = $logs->groupBy('platform')->map(function ($log) {
                return $log->count();
            });

            $cl_browser = $logs->groupBy('browser')->map(function ($log) {
                return $log->count();
            });
        }
        if ($request->ajax()) {
            return view('anldata', compact('urldata', 'cl_country', 'cl_referer', 'cl_device_type', 'cl_device', 'cl_platform', 'cl_browser', 'time'));
        }
        return view('analytics', compact('urldata', 'cl_country', 'cl_referer', 'cl_device_type', 'cl_device', 'cl_platform', 'cl_browser', 'time'));
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

        $count = 1;
        if(!empty($input['count']) && $input['count'] > 1){
            $count = $input['count'];
        }

        if($count > 50){
            return response()->error('MSG_MAX_LINK_50_Error', 400);
        }

        $arrUri = [];
        for ($i=0; $i< $count; $i++){
            $arrUri[] = new ShortLinks(['uri' => $this->genUri()]);
        }

        $imageName = "";
        if(!empty($input['image'])){
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
        }

        DB::beginTransaction();
        try {
            $url = new Url;
            $url->original = $input['original_url'];
            $url->title = $input['title'];
            $url->description = $input['description'];
            $url->image = $imageName;
            $url->save();

            $url->ShortLinks()->saveMany($arrUri);
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

    public function addLink($id, Request $request)
    {
        $input = $request->all();
        $count = 0;
        if(!empty($input['count']) && $input['count'] > 1){
            $count = $input['count'];
        }

        if($count > 50){
            return response()->error('MSG_MAX_LINK_50_Error', 400);
        }

        $arrUri = [];
        for ($i=0; $i< $count; $i++){
            $arrUri[] = new ShortLinks(['uri' => $this->genUri()]);
        }

        DB::beginTransaction();
        try {
            $url = Url::find($id);
            if(empty($url)){
                return response()->error('URl_EMPTY', 400);
            }
            foreach ($arrUri as $uri) {
                $url->ShortLinks()->save($uri);
            }
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

    public function updateUrl($id, Request $request)
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

        $imageName = "";
        if(!empty($input['image'])){
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
        }

        DB::beginTransaction();
        try {
            $url = Url::find($id);
            if(empty($url)){
                return response()->error('URl_EMPTY', 400);
            }
            $url->original = $input['original_url'];
            $url->title = $input['title'];
            $url->description = $input['description'];
            if(!empty($imageName)){
                $url->image = $imageName;
            }
            $url->save();

            DB::commit();
            return response()->success([]);
        } catch (\PDOException $e) {
            DB::rollBack();
            // throw $e;
            return response()->error('MSG_PDO_Error', 400);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->error('MSG_Error', 400);
        }
    }

    public function deleteUrl($id)
    {
        DB::beginTransaction();
        try {
            $url = Url::find($id);
            if(empty($url)){
                return response()->error('URl_EMPTY', 400);
            }
            $url->ShortLinks()->delete();
            $url->Logs()->delete();
            $url->delete();
            DB::commit();
            return response()->success([]);
        } catch (\PDOException $e) {
            DB::rollBack();
            // throw $e;
            return response()->error('MSG_PDO_Error', 400);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->error('MSG_Error', 400);
        }
    }

    public function getUri()
    {
        $uri = $this->genUri();
        return response()->success($uri);
    }

    private function genUri($length = 6, $rec = 0)
    {
        $uri = str_random($length);
        $existing = Url::where('uri', '=', $uri)->count();
        if ($existing > 0) {
            if ($rec < 25) {
                $uri = $this->genUri($length, $rec + 1);
            } else {
                $uri = '';
            }
        }
        return $uri;
    }

    private function getCountry($code)
    {
        $names = json_decode(file_get_contents("http://country.io/names.json"), true);
        return $names[$code];
    }
}
