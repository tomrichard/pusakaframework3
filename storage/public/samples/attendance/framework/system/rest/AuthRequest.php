<?php 
namespace Pusaka\Rest;

abstract class AuthRequest {

	abstract function handle();

	public static $namespace;
	public static $version;

	static function using($namespace, $version) {
		
		include_once(ROOTDIR . 'auth/' . trim($version, '/') . '/' . trim($namespace, '/') . '.auth.php' );

		self::$namespace 	= $namespace;
		self::$version 		= $version;

	}

	function header($key) {

		$key = strtoupper($key);

		return $_SERVER['HTTP_' . $key] ?? NULL;

	}

}