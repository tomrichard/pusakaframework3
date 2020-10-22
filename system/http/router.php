<?php 
namespace Pusaka\Http;

use Closure;
use ReflectionFunction;
use ReflectionClass;
use ReflectionMethod;

class Router {

	private $is_callback_execute;
	private $middlewares;
	private $controller;
	private $pathfolder;
	private $meta;
	private $pathinfo;
	private $found;

	function __construct( $pathinfo ) {
		
		$this->is_callback_execute = false;

		$this->next 		= true;
		$this->pathinfo 	= $pathinfo;
		$this->found 		= false;
		$this->meta 		= [];
		$this->middlewares 	= [];

	}

	/**
	 *
	 * @method handleMiddleware
	 * @return void
	 * @param ( array $middlewares, Pusaka\Http\Request $request, Closure $callback )
	 * 
	 * ==========================================================
	 */
	function handleMiddleware( $middlewares, $request, $callback, $call = FALSE ) {

		$response 		= NULL;

		$midpath 		= ROOTDIR . 'app/middleware/';

		foreach ($middlewares as $middleware) {

			$middleware = trim($middleware);
			
			$file 		= $midpath . $middleware . '.mw.php';

			if( file_exists($file) ) {
				include($file);
			}

			$midclass 	= ucfirst(trim($middleware)).'Middleware';

			if(class_exists($midclass)) {

				$midclass 				= new $midclass;
				$this->middlewares[]	= $midclass;

				if( method_exists($midclass, 'handle') ) {
				
					$rm 	 				= new ReflectionMethod($midclass, 'handle');

					$response 				= $rm->invokeArgs($midclass, [ $request, $callback ]);

					if( $response instanceof Response ) {
						break;
					}

					unset($midclass);

				}


			}

		}

		if( $response instanceof Response ) {
			
			if($response->code !== 200) {
				return $this->shutdown( $response );
			}

		}

		if(!$this->is_callback_execute) {
			$response = $callback( $request );
		}

		if( is_array($response) ) {

			$response = Response::ok()->json($response);

		}

		if( !($response instanceof Response) ) {
			
			$response = Response::ok();

		}

		// callback controller response
		//-----------------------------------------------

		foreach ($this->middlewares as $middleware) {
			
			if( method_exists($middleware, 'terminate') ) {
				
				$rm 		= new ReflectionMethod($middleware, 'terminate');

				$response 	= $rm->invokeArgs($middleware, [ $request, $response ]);

				if( $response instanceof Response ) {
					break;
				}

			}

		}

		// shutdown response
		return $this->shutdown( $response );

	}

	/**
	 *
	 * @method shutdown
	 * @return void
	 * @param ( Pusaka\Http\Response $response )
	 * 
	 * ==========================================================
	 */

	function shutdown( $response ) {

		if( is_array($response) ) {

			$response = Response::ok()->json($response);

		}

		if( $response instanceof Response ) {

			http_response_code( $response->code );

			foreach ($response->header as $key => $value) {
				header("{$key}: {$value}");
			}

			if( $response->body instanceof \Pusaka\Hmvc\View ) {

				$response->body->render();

				exit;				

			}

			if( $response->body !== NULL ) {

				header('Content-Type: ' . $response->content_type);

				echo $response->body;

			}

			exit;

		}

	}

	/**
	 *
	 * @method handleController
	 * @return void
	 * @param ( string $class, string $method )
	 * 
	 * ==========================================================
	 */
	function handleController( $class, $method ) {

		$rm 			 = new ReflectionMethod($class, $method);

		$params 		 = $rm->getParameters();

		$arguments 		 = [];

		$Request 	 	 		= new Request();

		$Request->path   		= $this->pathfolder;

		$Request->controller 	= $this->controller; 

		$Middleware 			= new Middleware();

		$this->middleware 		= &$Middleware;

		if( isset($params[0]) ) {

			$_set 		 = [];

			foreach ($this->meta as $meta) {
				$_set[$meta['name']] = $meta['value'];
			}

			$Request->setParams( $_set );

		}

		// define injectable instance
		//---------------------------------------
		foreach($params AS $argument) {

			$type = $argument->getClass();

			if($type === NULL) {

				switch ($argument->getName()) {
					case 'request':
						$arguments[] = $Request;
						break;
					case 'middleware':
						$arguments[] = $Middleware;
						break;
					default:
						$arguments[] = NULL;
						break;
				}				

			}

			else {
			
				$param_type 	= $type->getName();
				
				$arguments[]  	= new $param_type;

			}

		}

		$class 		= new $class( $Request );

		$rc 		= new ReflectionClass($class);

		$docc 		= $rc->getDocComment();

		$docm 		= $rm->getDocComment();

		// parse document => middleware
		$middlewares 	= [];
		$preg 			= preg_match('/@define::middleware:\s*(.+)/', $docm, $match);
		$middlewares	= explode(',', $match[1] ?? '');

		if( $preg <= 0 ) :
		$preg 			= preg_match('/@define::middleware:\s*(.+)/', $docc, $match);
		$middlewares	= explode(',', $match[1] ?? '');
		endif;

		array_unshift($middlewares, 'error');
		array_unshift($middlewares, 'auth');

		// run midleware
		$this->handleMiddleware( $middlewares, $Request, 

			function( $request ) use ( $rm, $class, $arguments ) {

				$this->is_callback_execute = true;

				$response 	= $rm->invokeArgs($class, $arguments);
			
				return $response;

			}// end function

		);

	}

	/**
	 *
	 * @method handleClosure
	 * @return void
	 * @param ( closure $handle )
	 * 
	 * ==========================================================
	 */
	function handleClosure( $handle ) {
		
		$rf 			 = new ReflectionFunction( $handle );

		$params 		 = $rf->getParameters();

		$arguments 		 = [];

		$Request 	 	 		= new Request();

		$Request->path   		= $this->pathfolder;

		$Request->controller 	= $this->controller;

		if( isset($params[0]) ) {

			$_set 		 = [];

			foreach ($this->meta as $meta) {
				$_set[$meta['name']] = $meta['value'];
			}

			$Request->setParams( $_set );

		}

		// define injectable instance
		//---------------------------------------
		foreach($params AS $argument) {

			$type = $argument->getClass();

			if($type === NULL) {

				switch ($argument->getName()) {
					case 'request':
						$arguments[] = $Request;
						break;
					default:
						$arguments[] = NULL;
						break;
				}				

			}

			else {
			
				$param_type 	= $type->getName();
				
				$arguments[]  	= new $param_type;

			}

		}

		$doc 			= $rf->getDocComment();

		$middlewares 	= [];
		$preg 			= preg_match('/@define::middleware:\s*(.+)/', $doc, $match);
		$middlewares	= explode(',', $match[1] ?? '');

		array_unshift($middlewares, 'error');
		array_unshift($middlewares, 'auth');

		// run midleware
		$this->handleMiddleware( $middlewares, $Request, 

			function( $request ) use ( $handle ) {

				$this->is_callback_execute = true;

				$response = $handle( $request ); // invoke function arguments

				return $response;

			}// end function

		);



	}

	function handle( $handle ) {

		if( is_string($handle) ) {
			
			$wwwdir   	= ROOTDIR . 'app/hmvc/routes/';

			/**
			 * 
			 * get last part from ex : $router->get('/', 'sample/welcome@index'); in routes.php
			 * so explode wil be : [welcome, index] => [controller, method]
			 * 
			 * -> begin 001
			 * 
			 */
			$array 		= explode('/', rtrim($handle));

			$last 		= explode('@', end($array));

			$controller = $last[0];

			$method 	= $last[1] ?? NULL;
			
			/**
			 * -> end 001
			 */

			/**
			 *  
			 * create path destination from ex : 'sample/welcome@index'
			 * $array 	= [sample]
			 * $dir 	= ROOTDIR . 'app/hmvc/routes/' . 'sample/' . 'welcome/' 
			 * 
			 * -> begin 002
			 * 
			 */
			array_pop($array);

			$join 				= implode('/', $array);

			$dir 	  			= $wwwdir . path( $join ) . path( $controller );

			$this->controller 	= $controller;

			$this->pathfolder 	= $dir;

			/**
			 * -> end 002
			 */

			/**
			 *  
			 * check path destination if exists
			 * $dir 	= ROOTDIR . 'app/hmvc/routes/' . 'sample/' . 'welcome/' 
			 * $file 	= ROOTDIR . 'app/hmvc/routes/' . 'sample/' . 'welcome/' . 'welcome.cs.php' 
			 * check if $file exists
			 * check class IndexCS is exists
			 * create new instance from IndexCS class
			 * check meta type from $router->get('/{@method}/{index:number}', 'welcome'); on routes.php
			 * if meta type is method then method will be defined as a route destination.
			 * default method is index() then 
			 * if method defined and found in the right class instance then
			 * flag found is turn on = true it means skip another routes in the next of statement in routes.php
			 * the end of script will be call $this->handleController( InstanceOf::WelcomeCS, 'index' ) method
			 * 
			 * -> begin 003
			 * 
			 */
			if( is_dir($dir) ) {

				if( file_exists($file = $dir . $controller . '.cs.php') ) {
					
					include($file);

					$class = ucfirst(strtolower($controller)) . 'CS';

					if( class_exists( $class ) ) {

						$instance = $class;

						foreach ($this->meta as $i => $meta) {
							
							if($meta['type'] === 'method') {
								
								$method 	= $meta['value'];

								array_splice($this->meta, $i, 1);

							}

						}

						if($method === NULL) {
							
							$this->found = true;

							return $this->handleController( $instance, 'index' );

						}

						if(method_exists( $class, $method)){
							
							$this->found = true;

							return $this->handleController( $instance, $method );

						}

					}

				}
			
			}
			/**
			 * -> end 003
			 */

		}// end handle controller

		// handle function
		if( $handle instanceof Closure ) {

			$response = $this->handleClosure( $handle );

		} 

		return;

	}

	function parse( $key ) {

		$pathinfo = $this->pathinfo;

		$key 	  = preg_replace_callback('/\{@method\}|\{\w+\}|\{\w+:\w+\}/', function($match) {

			if($match[0] === '{@method}') {
				
				$this->meta[] = [
					"type" 	=> "method",
					"class"	=> "",
					"name"	=> "",
					"value"	=> ""
				];

				return '(\w+)';

			}

			if( preg_match('/{(\w+):(\w+)}/', $match[0], $args) > 0 ) {

				$token = '(\w+)';
				$class = 'string';
				$name  = $args[1];

				if($args[2] === 'number') {
					$class = 'number';
					$token = '(\d+)';
				}

				if($args[2] === 'string') {
					$class = 'string';
					$token = '(\w+)';
				}

				$this->meta[] = [
					"type" 	=> "param",
					"class"	=> $class,
					"name"	=> $name,
					"value"	=> ""
				];

				return $token;

			}

			if( preg_match('/{(\w+)}/', $match[0], $args) > 0 ) {

				$this->meta[] = [
					"type" 	=> "param",
					"class"	=> "string",
					"name"	=> "",
					"value"	=> ""
				];

				return '(\w+)';

			}

			return '';

		}, $key);

		$parse 	  = strtr(strtolower($key), [
			'/' => '\/'
		]);

		$parse 	  = '^'.$parse.'$';

		if( preg_match('/'.$parse.'/', $pathinfo, $match) > 0 ) {

			array_shift($match);

			foreach ($match as $i => $value) {
				
				$this->meta[$i]['value'] = $value;
				
			}

			return true;
		
		}

		return false;

	}

	function get( $key, $handle ) {

		$response 	= NULL;

		$this->meta = [];

		if($this->found) {
			return;
		}

		if( $this->parse( $key ) ) {

			header('Access-Control-Allow-Methods: GET');

			$this->handle( $handle );
			
		}

	}

	function post( $key, $handle ) {

		$response 	= NULL;

		$this->meta = [];

		if($this->found) {
			return;
		}

		if( $this->parse( $key ) ) {

			header('Access-Control-Allow-Methods: OPTIONS, POST');

			$this->handle( $handle );
			
		}

	}

	function put( $key, $handle ) {

		$response 	= NULL;

		$this->meta = [];

		if($this->found) {
			return;
		}

		if( $this->parse( $key ) ) {

			header('Access-Control-Allow-Methods: OPTIONS, PUT');

			$this->handle( $handle );
			
		}

	}

	function delete( $key, $handle ) {

		$response 	= NULL;
		
		$this->meta = [];

		if($this->found) {
			return;
		}

		if( $this->parse( $key ) ) {

			header('Access-Control-Allow-Methods: OPTIONS, DELETE');

			$this->handle( $handle );
			
		}

	}

	private $handle = [];

	private function __autoSearch( $destination, $segments ) {

		// root/index/param/1

		if( !is_dir($destination) ) {
			return;
		}

		if( empty($segments) ) {
			return;
		}

		$scan = scandir( $destination );

		foreach ($segments as $segment) {

			/**
			 * 
			 * check directory exists ex : index
 			 * root/app/hmvc/routes/index
 			 * 
 			 */

			if( !is_dir( $dir = $destination . path($segment) ) ) {

				array_shift($segments);

				$this->__autoSearch( $dir, $segments );

				break;

			}

			$this->handle[] = $segment;

			/**
			 *
			 * directory found -> check file exists
			 *
			 */
			if( !file_exists( $file = $dir . $segment . '.cs.php' ) ) {

				array_shift($segments);

				$this->__autoSearch( $dir, $segments );

				break;

			}

			/**
			 *
			 * file exists -> check class
			 *
			 */
			if( file_exists( $file ) ) {

				//include( $file );

				array_shift($segments);

				$method = $segments[0] ?? 'index';

				$method = $method === '' ? 'index' : $method;

				$handle = implode('/', $this->handle);

				$handle = $handle . '@' . $method;

				array_shift($segments);

				foreach ($segments as $index => $value) {
					
					$this->meta[] = [
						"type" 	=> "param",
						"class"	=> "",
						"name"	=> "param:{$index}",
						"value"	=> $value
					];

				}

				$this->handle( $handle );

				break;

			}

			break;

		}

		return;

	} 

	function auto() {

		$this->handle 	= [];

		$this->meta 	= [];

		if($this->found) {
			return;
		}

		$pathinfo 	= $this->pathinfo;

		$wwwdir   	= ROOTDIR . 'app/hmvc/routes/';

		$segments 	= explode('/', path($pathinfo));

		array_shift($segments);

		/** 
		 * 
		 * search controller from directory to next directory
		 *
		 */

		$this->__autoSearch( $wwwdir, $segments );

		//var_dump($segments);

		if(!$this->found) {
			
			$middlewares 	= ['error'];
			$request 		= new Request();

			// 404 Page Not Found !
			$this->handleMiddleware( $middlewares, $request, function($request){
				return Response::code(404);
			}, true);
			
			exit;

		}

	}

}