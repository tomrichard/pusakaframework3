<?php 
use Pusaka\Http\Response;

class AdminMiddleware {

	public function handle( $request, $next ) {

		return $next($request);

	}

	public function terminate( $request, $response ) {

		return $response;

	}

}