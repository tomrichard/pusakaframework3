<?php 
use Pusaka\Http\Response;

class AdminMiddleware {

	public function handle( $request, $next ) {

		$request->addParams([
			'id' => 'X001787'
		]);

		//return redirect('encode');

		//return Response::code(403)->json(['error' => 'Not Autorized.']);

		return $next($request);

	}

	public function terminate( $request, $response ) {

		return $response;

	}

}