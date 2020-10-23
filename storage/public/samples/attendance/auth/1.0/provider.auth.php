<?php 
namespace App\Rest;

use Pusaka\Rest\AuthRequest;

use Pusaka\Http\Request;

use Pusaka\Microframework\Loader;

use Pusaka\Library\Authorize;

class ProviderAuth extends AuthRequest {

	public function handle() {

		header("Access-Control-Allow-Origin: *");

		// token from method post or get
		//------------------------------------------------------------ 
		$token 	 = Request::post('token') ?? Request::get('token');

		if($token === NULL) {
			http_response_code(401);
			die('Unauthorized.');
		}
		//-------------------------------------------------------------

		$api 	 = config('api');

		Loader::lib('authorize/authorize');

		$auth 	 = [];

		$isvalid = Authorize::validate($api['key'], $token, $auth);

		if(!$isvalid) {
			http_response_code(401);
			die('Unauthorized.');
		}

		// access it in controller with $this->auth
		//-----------------------------------------
		return $auth;

	}

}