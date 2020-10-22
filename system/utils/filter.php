<?php 
namespace Pusaka\Utils;

class FilterUtils {

	private $path 		= [];
	private $extension 	= [];

	public function __set($prop, $value) {
		$this->{$prop} = $value;
	}

	public function __get($prop) {
		return $this->{$prop};
	}

	public function file() {
		$this->path[] = 'file';
	}

	public function dir() {
		$this->path[] = 'dir';
	}

	public function ext( $ext ) {
		$this->extension[] = $ext;
	}

	public function match( $item, $types = [] ) {

		// match path
		if(in_array('path', $types)) {

			$match = false;

			if(in_array('file', $this->path)) {
				if( (!$match) AND is_file($item)) {
					$match = true;
				}
			}

			if(in_array('dir', $this->path)) {
				
				if( (!$match) AND is_dir($item)) {
					$match = true;
					return true;
				}

			}

			if(!$match) {
				return false;
			}

		}

		// match extension
		if(in_array('extension', $types)) {
			
			$match = false;

			foreach ($this->extension as $ext) {
				
				$cmatch = preg_match('/'.$ext.'$/', $item);

				if($cmatch > 0) {
					$match = true;
					break;
				}

			}

			if(!$match) {
				return false;
			}

		}

		return true;

	}

}