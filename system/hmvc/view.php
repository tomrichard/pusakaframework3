<?php 
namespace Pusaka\Hmvc;

class View {

	private $vars;

	function __construct( $name, $vars = [], $request = NULL ) {

		$this->name 		= $name;
		$this->vars 		= $vars;
		$this->request 		= $request;

	}

	function scripts() {

		$dir = ROOTDIR . 'assets/scripts/';

		foreach(glob($dir . '*.js') as $file ) {
			
			$file = strtr($file, [$dir => '']);

			$file = url('assets/scripts/' . $file);

			echo '<script src="'.$file.'"></script>';

		}

	}

	function view($name, $vars = []) {

		$this->vars 		= array_merge($this->vars, $vars);

		$this->name 		= $name;

		$this->render();

	}

	function render() {

		$__wwwdir 	= ROOTDIR . 'app/hmvc/views/';

		$__name 	= $this->name;

		extract($this->vars);

		if( $__name === NULL ) {
			
			if( $this->request === NULL ) {
				return;
			}

			$__ui 	= $this->request->controller . '.ui.php';

			$__vui 	= path($this->request->path) . $__ui;

			if( file_exists( $__vui ) ) {

				$GLOBALS['__Views'][] = $__vui;
				
				include_once( $__vui );

				return; // Response::code(404)->json([]);

				// Response::ok()->json([]);

			}

			return;
			
		}

		$__ui 	= $__name . '.ui.php';

		$__vui 	= path($this->request->path ?? '') . path('views') . $__ui;

		if( file_exists($__vui) ) {

			$GLOBALS['__Views'][] = $__vui;

			include_once( $__vui );

			return;

		}

		$__vui 	= $__wwwdir . $__ui;

		if( file_exists($__vui) ) {

			$GLOBALS['__Views'][] = $__vui;

			include_once( $__vui );

			return;

		}

		// log view not found
		return;

	}

}