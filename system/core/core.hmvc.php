<?php 

// /* 
//  * Exceptions
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/exceptions/Exception.php');
// include(ROOTDIR . 'system/exceptions/ClassNotFoundException.php');
// include(ROOTDIR . 'system/exceptions/ControllerNotFoundException.php');
// include(ROOTDIR . 'system/exceptions/InvalidArgumentException.php');
// include(ROOTDIR . 'system/exceptions/IOException.php');
// include(ROOTDIR . 'system/exceptions/MethodNotFoundException.php');
// include(ROOTDIR . 'system/exceptions/ViewNotFoundException.php');
// include(ROOTDIR . 'system/exceptions/LibraryNotFoundException.php');
// include(ROOTDIR . 'system/exceptions/ModelNotFoundException.php');
// include(ROOTDIR . 'system/exceptions/ResourceNotFoundException.php');

// /*
//  * Core
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/core/Benchmark.php');
// include(ROOTDIR . 'system/core/Functions.php');
// include(ROOTDIR . 'system/core/Loader.php');

// /*
//  * Component
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/component/Linker.php');
// include(ROOTDIR . 'system/component/Widget.php');

// /*
//  * Config Files
//  * ----------------------------------------- */
// include(ROOTDIR . 'config/date.php');
// include(ROOTDIR . 'config/application.php');
// include(ROOTDIR . 'config/upload.php');
// include(ROOTDIR . 'config/security.php');
// include(ROOTDIR . 'config/databases.php');
// include(ROOTDIR . 'config/mail.php');
// include(ROOTDIR . 'config/routes.php');
// include(ROOTDIR . 'config/vendor.php');

// /*
//  * MEMORY
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/memory/Session.php');

// /*
//  * HTTP
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/http/HttpClient.php');
// include(ROOTDIR . 'system/http/Middleware.php');
// include(ROOTDIR . 'system/http/Header.php');
// include(ROOTDIR . 'system/http/Request.php');
// include(ROOTDIR . 'system/http/Response.php');

// /*
//  * HMVC
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/hmvc/Controller.php');
// include(ROOTDIR . 'system/hmvc/Router.php');

// /*
//  * UTILS
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/utils/Date.php');
// include(ROOTDIR . 'system/utils/Byte.php');
// include(ROOTDIR . 'system/utils/IO.php');
// include(ROOTDIR . 'system/utils/File.php');
// include(ROOTDIR . 'system/utils/Directory.php');
// include(ROOTDIR . 'system/utils/Validator.php');

// /*
//  * CONSOLE
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/console/Console.php');

// /*
//  * SERVICES
//  * ----------------------------------------- */
// include(ROOTDIR . 'app/middleware/runtime.mw.php');

// /*
//  * AUTOLOADER
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/database/Autoloader.php');

// if(defined('MAINTENANCE')) {
// 	die("Website Under Maintenance.");
// }

// /*
//  * ROUTER SERVICE
//  * ----------------------------------------- */
// Pusaka\Hmvc\Router::run();

$runWebEngine = true;

if( defined('APPLICATION_TYPE') ) {

	if( APPLICATION_TYPE === 'MICROSERVICE' ) {
		$runWebEngine = false;
	}

}

// runWebEngine if True
// ------------------------------------------
if( $runWebEngine ) :

/**
 * HMVC
 * ----------------------------------------- */
include(ROOTDIR . 'system/hmvc/loader.php');
include(ROOTDIR . 'system/hmvc/model.php');
include(ROOTDIR . 'system/hmvc/view.php');
include(ROOTDIR . 'system/hmvc/controller.php');
include(ROOTDIR . 'system/hmvc/application.php');

// set app
// ------------------------------------------
$app = Pusaka\Hmvc\Application::instance();
	
endif;
// ------------------------------------------
