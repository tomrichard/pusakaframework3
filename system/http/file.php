<?php 
namespace Pusaka\Http;

use Pusaka\Utils\FileUtils;

class File {

	private $files;

	function __construct() {

		$this->files = $_FILES;

	}

	function has( $key ) {

		if( isset($this->files[$key]) ) {
			return true;
		}

		return false;

	}

	function get( $key ) {

		$file = $this->files[$key];

		if(isset($file[0])) {
			$file = $file[0];
		}

		$name = $file['name'];
		$ext  = (explode(".", $name));

		$File = new FileUtils();

		$File->path = $file['tmp_name'];
		$File->name = $file['name'];
		$File->type = $file['type'];
		$File->size = $file['size'];
		$File->ext 	= end($ext);

		return $File;

	}

	function multiple( $key ) {

		$Files  = [];
		$files 	= [];

		$file 	= $this->files[$key];

		if(!isset($file[0])) {
			$files[] = $file;
		}

		foreach ($files as $file) {
			
			$name = $file['name'];
			$ext  = (explode(".", $name));

			$File = new FileUtils();

			$File->path = $file['tmp_name'];
			$File->name = $file['name'];
			$File->type = $file['type'];
			$File->size = $file['size'];
			$File->ext 	= end($ext);
			
			$Files[] 	= $File;

		}

		return $Files;

	}



}