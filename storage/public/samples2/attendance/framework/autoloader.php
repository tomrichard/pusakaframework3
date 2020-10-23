<?php
define('FRAMEWORK_DIR', str_replace('\\', '/', __DIR__ . '/'));

// load config
include(FRAMEWORK_DIR . 'config/microframework.config.php');
include(FRAMEWORK_DIR . 'config/database.config.php');
include(FRAMEWORK_DIR . 'config/smtp.config.php');
//include(FRAMEWORK_DIR . 'config/global.config.php');
include(FRAMEWORK_DIR . 'config/path.config.php');

// load http
include(FRAMEWORK_DIR . 'system/http/Request.php');
include(FRAMEWORK_DIR . 'system/http/Response.php');

// load rest
include(FRAMEWORK_DIR . 'system/rest/AuthRequest.php');
include(FRAMEWORK_DIR . 'system/rest/MetaRequest.php');
include(FRAMEWORK_DIR . 'system/rest/Controller.php');
include(FRAMEWORK_DIR . 'system/rest/Kernel.php');

// load utils
include(FRAMEWORK_DIR . 'system/core/Functions.php');

// load utils
foreach(scandir(FRAMEWORK_DIR . 'system/utils/') as $file) {
	
	if($file === '.' || $file === '..') {
		continue;
	}

	include(FRAMEWORK_DIR . 'system/utils/'.$file);

}

// load database
include(FRAMEWORK_DIR . 'system/database/driver/mysql/driver.php');
include(FRAMEWORK_DIR . 'system/database/driver/mysql/builder.php');
include(FRAMEWORK_DIR . 'system/database/driver/mysql/result.php');
include(FRAMEWORK_DIR . 'system/database/Exception.php');
include(FRAMEWORK_DIR . 'system/database/Database.php');

// load core
include(FRAMEWORK_DIR . 'system/core/Loader.php');
include(FRAMEWORK_DIR . 'system/core/Log.php');