<?php 
use Pusaka\Http\Response;
use Pusaka\Hmvc\View;

class ErrorMiddleware {

	public function terminate( $request, $response ) {

		if( $response->code === 404 ) {

			// page not found
			return $response->html(new View('error/404'));

		}

		return $response;

	}

}