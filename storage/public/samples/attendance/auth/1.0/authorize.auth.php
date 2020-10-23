<?php 
namespace App\Rest;

use Pusaka\Rest\AuthRequest;

use Pusaka\Http\Request;

class AuthorizeAuth extends AuthRequest {

	public function handle() {

		$api = config('api');

		$key = $api['key'];

		// get token
		$pwtoken = $this->header('Pwtoken');

		if($key !== $pwtoken) {
			http_response_code(401);
			die('Unauthorized.');
		}

	}

}