<?php 

header_remove('X-Powered-By');

function ob_gzhandler_optimize( $buffer ) {

	ob_gzhandler($buffer, 1);

}

if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {

	$encoding = $_SERVER['HTTP_ACCEPT_ENCODING'];

	$pos  	  = strpos($encoding, 'gzip');

	if(!$pos) {
		ob_start("ob_gzhandler");
	}

}

include( ROOTDIR . 'system/http/middleware.php');
include( ROOTDIR . 'system/http/file.php' );
include( ROOTDIR . 'system/http/input.php' );
include( ROOTDIR . 'system/http/url.php');
include( ROOTDIR . 'system/http/response.php');
include( ROOTDIR . 'system/http/request.php');
include( ROOTDIR . 'system/http/router.php' );