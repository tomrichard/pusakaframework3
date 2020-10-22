<?php 
namespace Pusaka\Utils;

use Exception;
use Closure;
use Pusaka\Utils\FilterUtils;

class DirectoryUtils {

	private $source;

	public function __construct($source) {
		$this->source = $source;
	}

	public function scan($options) {

		$path 			= $options['path'] 		?? $this->source;
		$filter 		= $options['filter'] 	?? NULL;
		$callback 		= $options['callback'] 	?? NULL;

		if($filter instanceof Closure) {

			$FilterUtils = new FilterUtils();
			$filter($FilterUtils);
		
		}// end init filter

		if(is_file($path)) {
			if(isset($FilterUtils)) {
				if($FilterUtils->match($path, ['path', 'extension'])) {
					$callback($path);	
				}
			}else {
				$callback($path);
			}
		}

		if(is_dir($path)) {

			$scan = scandir($path);

			foreach ($scan as $newpath) {
				
				if(in_array($newpath, ['.', '..'])) {
					continue;
				}

				$newpath = path($path) . $newpath;

				if(is_dir($newpath)) {
					
					if(isset($FilterUtils)) {
						if($FilterUtils->match($newpath, ['path', 'extension'])) {
							$callback($newpath);	
						}
					}else {
						$callback($newpath);
					}

				}

				$options['path'] = $newpath;
				$this->scan($options);
			
			}
		
		}

		// free memory
		if(isset($FilterUtils)) {
			unset($FilterUtils);
		}

	}

	public function make( $mode = 0777 ) {

		if(is_dir($this->source)) {
			return true;
		}

		if($mode === NULL) {
			$mode = 0777;
		}

		return mkdir($this->source, $mode, true);

	}

	public function copy( $to ) {

		if( !is_dir($to) ) {
			return false;
		}

		$root = path($this->source);
		$to   = path($to);

		try {

			$this->scan([
				'callback'	=> function($path) use ($root, $to) {

					$relative = strtr($path, [$root => '']);
					
					// make directory
					//-------------------------------------
					if(is_dir($path)) {
						mkdir($to . $relative, 0777, true);
					}

					// make file copy
					//-------------------------------------
					else if(is_file($path)) {
						
						$iscopied = copy($path, $to . $relative);
						
						if(!$iscopied) {
							throw new Exception("Cannot copy file : " . $path);
						}

					}

				}
			]);

		}catch(Exception $e) {
			return false;
		}

		return true;

	}

	public function delete() {

		$root = path($this->source);

		if( !is_dir($root) ) {
			return false;
		}

		try {

			$this->scan([
				'callback'	=> function($path) {
					// remove file
					//-------------------------------------
					if(is_file($path)) {
						unlink($path);
					}
					// remove directory
					//-------------------------------------
					if(is_dir($path)) {
						rmdir($path);
					}
				}
			]);

		}catch(Exception $e) {
			return false;
		}

		return true;

	}



	// private function __recursiveDelete($path) {

	// 	$del = $files = glob($path . '/*');
		
	// 	foreach ($files as $file) {
	// 		is_dir($file) ? $this->__recursiveDelete($file) : unlink($file);
	// 	}

	// 	rmdir($path);

	// 	return !is_dir($path);

	// }

	// public function make() {

	// 	if(is_dir($this->src)) {
	// 		return true;
	// 	}

	// 	return mkdir($this->src, true);

	// }

	// public function delete() {

	// 	return $this->__recursiveDelete($this->src);

	// }

}