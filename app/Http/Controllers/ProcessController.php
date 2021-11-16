<?php

namespace App\Http\Controllers;

use App\ShortLinks;
use App\Url;
use App\Logs;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class ProcessController extends Controller
{
    public function index($uri)
    {
        $sLink = ShortLinks::with(['Url'])->where('uri', '=', $uri)->first();
        if ($sLink) {
            if (strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") !== false
                || strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") !== false
                // || (1==1)
                || strpos($_SERVER["HTTP_USER_AGENT"], "https://developers.google.com/") !== false) {
                // it is probably Facebook's bot
                return view('ogview', compact('sLink'));
            } else {
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
                    $log->url_id = $sLink->url_id;
                    $log->short_link_id = $sLink->id;
                    $log->ip = $ip;
                    $log->countryCode = $countryCode;
                    $log->referer = $source;
                    $log->device_type = $deviceType;
                    $log->device_name = $agent->device();
                    $log->browser = $agent->browser();
                    $log->platform = $agent->platform();
                    $log->save();
                    DB::commit();
                    return response()->redirect($sLink->Url->original);
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
}
