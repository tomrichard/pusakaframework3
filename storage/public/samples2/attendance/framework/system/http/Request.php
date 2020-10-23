<?php 
namespace Pusaka\Http;

use Pusaka\Utils\FileUtils;

class Request {

	static function post($key = NULL) {

		$post = [];

		if(!empty($GLOBALS['_FILTERED_POST'])) {
			$post = $GLOBALS['_FILTERED_POST'];
		}else if(!empty($_POST)) {
			$post = $_POST;
		}else {
			$post = json_decode(file_get_contents('php://input'), True);
		}

		if($key === NULL) {		
			return $post;
		}

		if(isset($post[$key])) {
			return $post[$key];
		}

		return NULL;

	}

	static function get($key = NULL) {

		if(!empty($GLOBALS['_FILTERED_GET'])) {
			return $GLOBALS['_FILTERED_GET'];
		}else if($key === NULL) {
			return $_GET;
		}

		if(isset($_GET[$key])) {
			return $_GET[$key];
		}
		
		return NULL;

	}	

	static function file($key = NULL) {

		$file = NULL;

		if($key === NULL) {
			$file = $_FILES;
		}

		if(isset($_FILES[$key])) {
			$file = $_FILES[$key];
		}

		$File = new FileUtils($file);

		$File->category(FileUtils::FORM);

		return $File;
		
	}

	static function ip() {

		return ($_SERVER['REMOTE_ADDR']=='::1'?'127.0.0.1':$_SERVER['REMOTE_ADDR']);
	
	}

}