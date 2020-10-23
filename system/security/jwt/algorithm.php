<?php 
namespace Pusaka\Security\Jwt;

class Algorithm {
	
	static function get( $name ) {

		$registered = [
			'HS256' => HS256::class,
			'HS384' => HS384::class,
			'HS512' => HS512::class
		];

		return isset($registered[$name]) ? (new $registered[$name]) : (new HS256);

	}

}