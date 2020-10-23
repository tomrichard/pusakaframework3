<?php 
namespace Pusaka\Rest;

use Exception;
use Error;
use ReflectionMethod;

use Pusaka\Http\Request;
use Pusaka\Http\Response;

use Pusaka\Microframework\Log;

class Kernel {

	private $next;
	private $handle;
	private $meta_class;
	private $auth_class;
	private $meta;

	function __construct($handle, $auth_class = NULL, $meta_class = NULL) {
		
		$this->handle 		= new $handle;
		$this->meta_class 	= $meta_class;
		$this->auth_class 	= $auth_class;

		$this->resolve();

	}

	static function handle($class, $auth_class = NULL, $meta_class = NULL) {

		return new Kernel(new $class(), $auth_class, $meta_class);

	}

	function right_type($type, $value) {

		if($type === 'text') {

			if(!is_string($value)) {
				http_response_code(400);
				die("Bad request.");
			}

		}

		if($type === 'string') {
								
			if(!is_string($value)) {
				http_response_code(400);
				die("Bad request.");
			}

		}

		if($type === 'json') {
			
			if(!is_string($value)) {
				http_response_code(400);
				die("Bad request.");
			}

			if(!is_array($value = json_decode($value, true))) {
				http_response_code(400);
				die("Bad request.");
			}

		}

		else if($type === 'int') {

			if( (preg_match('/\./', $value) > 0) ) {
				http_response_code(400);
				die("Bad request.");
			}

			if( !(preg_match('/^\d+$/', $value) > 0) ) {
				http_response_code(400);
				die("Bad request.");
			}

			$value = intval($value);

		}

		return $value;

	}

	function middleware($handle, $method_name, &$segments) {

		$method = new ReflectionMethod($handle, $method_name);

		if( $this->auth_class !== NULL ) {

			if(!class_exists($this->auth_class)) {
				http_response_code(403);
				die('Forbidden');
			}

			$auth = new $this->auth_class();

			$this->next = $auth->handle();

		}

		if( $this->meta_class !== NULL ) {
			
			// using meta
			if(defined($this->meta_class . "::$method_name")) {

				$meta = constant($this->meta_class . "::$method_name");

				$this->meta = $meta;

				// check const method defined
				$allow_method = $meta['method'] ?? NULL;

				if(!NULL) {

					$return_post 	= ['DELETE', 'PUT'];

					$allow_method 	= strtoupper($allow_method);

					$allow_methods 	= in_array($allow_method, $return_post) ? ['POST', $allow_method] : [$allow_method];
					
					if( !in_array(strtoupper($_SERVER['REQUEST_METHOD']), $allow_methods) ) {
						http_response_code(405);
						die("Method not allowed.");
					}

					if(!empty($meta['params'])) {

						$post 	= Request::post();
						
						unset($_POST);

						$params = $meta['params'];

						foreach ($params as $param_key => $param_type) {

							if(!isset($post[$param_key])){
								http_response_code(400);
								die("Bad request.");
							}

							$param_value = $post[$param_key];

							$param_value = $this->right_type($param_type[0], $param_value);

							$GLOBALS['_FILTERED_POST'][$param_key] = $param_value;

						}

					}

					if(!empty($meta['query'])) {

						$get 	= Request::get();

						unset($_GET);

						$queries = $meta['query'];

						foreach ($queries as $query_key => $query_type) {

							$skip = (($query_type[2] ?? FALSE) === TRUE);

							if(!isset($get[$query_key]) AND $skip) {
								continue;
							}

							if(!isset($get[$query_key])){
								http_response_code(400);
								die("Bad request.");
							}

							$query_value = $get[$query_key];

							$query_value = $this->right_type($query_type[0], $query_value);

							$GLOBALS['_FILTERED_GET'][$query_key] = $query_value;
						
						}

					}

					if(!empty($meta['path'])) {

						foreach ($method->getParameters() as $param) {
							
							$param_value = $segments[$param->getPosition()] ?? '';
							$param_type  = $meta['path'][$param->getName()] ?? ['',''];

							$param_value = $this->right_type($param_type[0], $param_value);

							$segments[$param->getPosition()] = intval($param_value);

						}

					}

				}

			}

		}


		// next to resolve method
		return $method;

	}

	function resolve() {

		$pathinfo = $_SERVER['PATH_INFO'];

		$segments = explode("/", trim($pathinfo, '/'));

		$handle = $this->handle;

		$method = $segments[0] ?? '';

		if($method === '') {
			// throw not found
			http_response_code(403);
			die('Forbidden [001]');
			throw new Exception('Method not found.');
		}

		if(!method_exists($handle, $method)) {
			http_response_code(403);
			die('Forbidden [002]');
			throw new Exception("Method not found.");
		}
		
		array_shift($segments);

		$method 	= $this->middleware($handle, $method, $segments);

		try {
			$this->handle->auth = $this->next;
			$output 			= $method->invokeArgs($handle, $segments);
		}
		catch(Exception $e) {
			http_response_code(403);
			die('Forbidden');
		}
		catch(Error $e) {
			http_response_code(500);
			Log::create('Kernel', $e->getFile() . '('.$e->getLine().')', 
				$e->getMessage(), 
				$e->getTraceAsString()
			);
			die('Server Internal Error');
		}

		if($output!==NULL) {
			Response::out($output);
		}

		unset($pathinfo);
		unset($segments);
		unset($output);
		unset($method);


	}

}