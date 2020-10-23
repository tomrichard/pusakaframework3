<?php 
namespace Pusaka\Rest;

class Controller {

	private $auth;

	public function __get($key) {
		return $this->{$key};
	}

	public function __set($key, $val) {
		$this->{$key} = $val;
	} 

}