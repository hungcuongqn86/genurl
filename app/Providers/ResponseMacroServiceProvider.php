<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		Response::macro('success', function ($data) {
			return Response::json([
				'error' => false,
				'data' => $data,
				'status_code' => 200,
				'message' => '',
			]);
		});

		Response::macro('error', function ($message, $status = 400, $params = [], $data = []) {
			return Response::json([
				'error' => true,
				'messages' => $message,
				'message' => $message,
				'params' => $params,
				'data' => $data,
				'status_code' => $status,
				'redirect' => config('const.TIMEOUT_REDIRECT_URL'),
			], $status);
		});

        Response::macro('redirect', function ($location) {
            return response('',301)
                ->header('location', $location);
        });
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
