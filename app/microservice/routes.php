<?php 
if( defined('API_RUN_VERSION') ) :
//----------------------------------------------------	
$routes = ROOTDIR . 'app/microservice/routes/';
//----------------------------------------------------

	if( API_RUN_VERSION == 'ver1' ) {

	}
	
	if( API_RUN_VERSION == 'ver2' ) {

	}

	if( API_RUN_VERSION == 'devl' ) {

		include( $routes . 'inventory/inventory.routes.php');

	}

//----------------------------------------------------
endif;