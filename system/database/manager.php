<?php 
namespace Pusaka\Database;

class Manager {

	static function on( $name ) {

		if (!defined('PUSAKA_DATABASE_CONFIG')) {
			throw new \Exception("PUSAKA_DATABASE_CONFIG not defined.");
		}

		$configs 	= PUSAKA_DATABASE_CONFIG;

		$config 	= $configs[$name] ?? NULL;

		if ($config === NULL) {
			
			throw new \Exception("Configuration database[$name] not found");
			return NULL;

		}

		if ($config['driver'] == 'mysql') {
			return new \Pusaka\Database\Product\Mysql\Driver( $config );
		}

		return NULL;

	}


}