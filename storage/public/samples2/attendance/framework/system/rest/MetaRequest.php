<?php 
namespace Pusaka\Rest;

use ReflectionClass;

class MetaRequest {

	public static $namespace;
	public static $version;

	static function using($namespace, $version) {

		include_once(ROOTDIR . 'meta/' . trim($version, '/') . '/' . trim($namespace, '/') . '.meta.php' );
		
		self::$namespace 	= $namespace;
		self::$version 		= $version;

	}

	function getJson() {

		$reflectionClass = new ReflectionClass($this);
		
		return $reflectionClass->getConstants();

	}

}