<?php 
namespace Pusaka\Hmvc;

use Pusaka\Http\Router;

class Application {

	static function instance() {
		
		return new self;

	}

	function serve() {

		$__path_info 		= $_SERVER['PATH_INFO'] ?? '/';

		$router 			= new Router( $__path_info );

		$__app_dir 			= ROOTDIR . 'app/hmvc/';

		include( $__app_dir . 'routes.php' );

	}

}