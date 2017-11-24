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
        $urls = Url::with('Logs')->orderBy('created_at', 'desc')->paginate(5);
        if ($request->ajax()) {
            return view('urldata', compact('urls'));
        }
        return view('index', compact('urls'));
    }

    public function getUrl($id)
    {
        $urldata = Url::where('id', '=', $id)->first()->toArray();
        if ($urldata) {
            return response()->success($urldata);
        }
        return response()->error('MSG_PDO_Error', 400);
    }

    public function analytics($uri, $time, Request $request)
    {
        $query = Url::with('Logs')->where('uri', '=', $uri);
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
                $date->add(new \DateInterval("P1M"));
            }

            $query = Url::with(['Logs' => function ($query) use ($date) {
                $query->where('created_at', '>=', $date);
            }])->where('uri', '=', $uri);
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
            return view('anldata', compact('urldata', 'cl_country', 'cl_referer', 'cl_device_type', 'cl_device', 'cl_platform', 'cl_browser'));
        }
        return view('analytics', compact('urldata', 'cl_country', 'cl_referer', 'cl_device_type', 'cl_device', 'cl_platform', 'cl_browser'));
    }

    public function shortener(Request $request)
    {
        $input = $request->all();
        $arrRules = [
            'uri' => 'required|unique:urls,uri',
            'original_url' => 'required | url'
        ];
        $arrMessages = [
            'uri.required' => 'ERRORS_MS.EMPTY_URI',
            'uri.unique' => 'ERRORS_MS.UNIQUE_URI',
            'original_url.required' => 'ERRORS_MS.EMPTY_ORIGINAL_URL',
            'original_url.url' => 'ERRORS_MS.NOT_ORIGINAL_URL'
        ];

        $validator = Validator::make($input, $arrRules, $arrMessages);
        if ($validator->fails()) {
            return response()->error($validator->errors()->all(), 400);
        }

        DB::beginTransaction();
        try {
            $url = new Url;
            $url->original = $input['original_url'];
            $url->uri = $input['uri'];
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

    public function updateUrl($id, Request $request)
    {
        $input = $request->all();
        $arrRules = [
            'uri' => 'required|unique:urls,uri,' . $id,
            'original' => 'required | url'
        ];
        $arrMessages = [
            'uri.required' => 'ERRORS_MS.EMPTY_URI',
            'uri.unique' => 'ERRORS_MS.UNIQUE_URI',
            'original.required' => 'ERRORS_MS.EMPTY_ORIGINAL_URL',
            'original.url' => 'ERRORS_MS.NOT_ORIGINAL_URL'
        ];

        $validator = Validator::make($input, $arrRules, $arrMessages);
        if ($validator->fails()) {
            return response()->error($validator->errors()->all(), 400);
        }

        DB::beginTransaction();
        try {
            $url = Url::find($id);
            $url->update($input);
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
