<?php 
namespace Pusaka\Http;

use stdClass;

class Request {

	private $controller;
	private $path;
	private $params;
	private $input;
	private $file;

	static function instance() {

		return new Request();

	}

	function __construct() {

		$this->input = new Input();
		$this->file  = new File();

	}

	function __set( $prop, $val ) {

		if( in_array($prop, ['path', 'controller']) ) {
			$this->{$prop} = $val;
		}

	}

	function __get( $prop ) {

		if( in_array($prop, ['path', 'controller', 'input', 'file']) ) {
			return $this->{$prop};
		}

		return NULL;

	}

	function setParams( $params ) {
	
		$this->params = $params;
	
	}

	function addParams( $params = [] ) {

		$this->params = array_merge( $this->params, $params );

	}

	function param( $key ) {

		return $this->params[$key] ?? NULL;

	}

	function translate( $map = [], &$var ) {

		$var = new stdClass;

		foreach ($map as $from => $to) {
			
			if( isset($this->params[$from]) ) {
				$var->{$to} = $this->params[$from];
			}

		}

		return $var;

	}

	function header( $key ) {

	}

	function headers() {

	}



}