<?php

namespace App\Http\Controllers;

use App\Url;
use App\Logs;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class ProcessController extends Controller
{
    public function index($uri)
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
}
