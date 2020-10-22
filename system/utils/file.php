<?php 
namespace Pusaka\Utils;

use closure;

class FileUtils {

	private $path;
	private $size;
	private $name;
	private $type;
	private $ext;

	function __construct() {

	}

	function __set($key, $value) {

		if( in_array($key, ['path', 'size', 'name', 'type', 'ext']) ) {

			$this->{$key} = $value;

		}

	}

	function __get($key) {

		if(isset($this->{$key})) {
			return $this->{$key};
		}

		return NULL;

	}

	function move( $dest, $name ) {

		return move_uploaded_file($this->path, $dest . $name . '.' . $this->ext );

	}

	function getRandomName() {

		return 'f' . date('YmdHis') . uniqid();

	}

}