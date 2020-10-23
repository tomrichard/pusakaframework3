<?php 
namespace Pusaka\Http;

use stdClass;

class Url {

	private $replace;
	private $params;
	private $path;

	function __construct( $path = NULL ) {

		$this->path 	= $path;
		$this->replace 	= [];

	}

	function setParams( $params ) {

		$this->params 	= $params;

	}

	function back() {

		return $this;

	}

	function method( $method ) {

		if( is_string($this->path)) {
		
		 	$this->path = strtr($this->path, ['{@method}' => $method]);
		
		}

		return $this;

	}

	function param( $key, $val ) {

		$this->replace[$key] = $val;

		return $this;

	}

	function params( $params ) {

		$this->params = $params;

		return $this;

	}

	function __toString() {

		if(is_string($this->params)) {

			$this->path = strtr($this->path, ['{@params}' => $this->params]);

		}else {

			foreach ($this->params as $key => $val) {
				
				if( isset($this->replace[$key]) ) {
					$val = $this->replace[$key];
				}

				$this->path = preg_replace('/{('.$key.'):(\w+)}/', $val, $this->path);

			}

		}

		$this->path = strtr($this->path, ['{@params}' => '']);

		return path(BASEURL) . ltrim($this->path, '/');

	}

}