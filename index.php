<?php 
define( 'ROOTDIRECTORY', __DIR__ );

if(!defined('APPLICATION_TYPE')) {
define( 'APPLICATION_TYPE', 'HMVC' );
}

include( 'system/core/core.constants.php' 		); 	// core constants register
include( 'system/core/core.functions.php' 		); 	// core functions
include( 'system/core/core.extension.php' 		);

include( 'config/databases.php' );
include( 'config/extensions.php' );

include( 'system/core/core.utils.php' 			);
include( 'system/core/core.microservice.php' 	); 	// switch microservice
include( 'system/core/core.database.php' 		);
include( 'system/core/core.hmvc.php' 			);
include( 'system/core/core.http.php' 			);
include( 'system/core/core.shutdown.php' 		);
include( 'system/core/core.autoloader.php' 		);

$app->serve(); // serve application