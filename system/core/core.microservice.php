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
//  * HTTP
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/http/HttpClient.php');
// include(ROOTDIR . 'system/http/Middleware.php');
// include(ROOTDIR . 'system/http/Header.php');
// include(ROOTDIR . 'system/http/Request.php');
// include(ROOTDIR . 'system/http/Response.php');

// /*
//  * MICROSERVICES
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/microservices/Controller.php');
// include(ROOTDIR . 'system/microservices/Router.php');
// include(ROOTDIR . 'system/microservices/Application.php');

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
//  * AUTOLOADER
//  * ----------------------------------------- */
// include(ROOTDIR . 'system/database/Autoloader.php');

$runWebEngine = false;

if( defined('APPLICATION_TYPE') ) {

	if( APPLICATION_TYPE === 'MICROSERVICE' ) {
		$runWebEngine = true;
	}

}

// runWebEngine if True
if( $runWebEngine ) {

include(ROOTDIR . 'system/microservices/application.php');

$app = Pusaka\Microservices\Application::instance();

}
//end 