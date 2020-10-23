<?php 

use Pusaka\Http\Response;

$router->get('/inventory/items', 
	
	/**
	 * @define::middleware: admin
	 */
	function( $request ) {

		return Response::ok()->json(['hello' => 'world_aaa']);

	}
	
);