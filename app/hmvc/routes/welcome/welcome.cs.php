<?php 
use Pusaka\Hmvc\Controller;

use Pusaka\Database\Manager;

class WelcomeCS extends Controller {

	function index( $request ) {

		$greeting = 'Welcome to Pusaka Framework v' . PUSAKA_VERSION;

		$say 	  = 'Hello World';

		//throw new Exception("Error Neh", 0);

		//writelog('error neh');
			
		return view(compact('greeting', 'say', 'row'));

	}

	function component() {

		return view([], 'component');

	}

}