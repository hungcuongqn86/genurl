<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;

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
        $urls = Url::paginate(5);
        if ($request->ajax()) {
            return view('data', compact('urls'));
        }
        return view('home',compact('urls'));
    }

    public function shortener(Request $request)
    {
        $url = new Url;
        $url->original = $request->original_url;
        $url->status = 1;
        $url->uri = self::genUri();
        $url->save();
        exit;
    }

    private function genUri($length = 6)
    {
        $uri = str_random($length);
        /*$existing = Offer::whereIn('coupon', $coupons)->count();
        if ($existing > 0)
            $coupons += $this->genUri($existing, $length);*/
        return $uri;
    }
}
