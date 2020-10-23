<?php 
use Pusaka\Hmvc\Controller;

class HomeCS extends Controller {

	function index( $request ) {

		return view([], 'blog.layout');

	}

}