<?php 
namespace Pusaka\Http;

use stdClass;

class Input {

	function __construct() {

		$prop = array_merge($this->gets(), $this->posts());

		foreach ($prop as $key => $value) {
			$this->{$key} = $value;
		}

	}

	function __get($key) {

		if(isset($this->{$key})) {
			return $this->{$key};
		}

		return NULL;

	}

	function get( $key ) {

		$gets =	$this->gets();

		if( isset( $gets[$key] ) ) {
			return $gets[$key];
		}

		return NULL;

	}

	function gets() {

		if(!empty($_GET)) {
			return $_GET;
		}

		return [];

	}

	function post( $key ) {

		$posts = $this->posts();

		if( isset( $posts[$key] ) ) {
			return $posts[$key];
		}

		return NULL;

	}

	function posts() {

		if(!empty($_POST)) {
			
			return $_POST;

		}else {
			
			$posts = json_decode(file_get_contents('php://input'), True);
			
			if($posts !== NULL) {
				return $posts;
			}

		}

		return [];

	}

}