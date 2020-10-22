<?php 
namespace Pusaka\Console;

class Application {

	static function instance() {
		
		return new self;

	}

	function internal( $command, $argv ) {

		if( $command === 'extension' ) {

			$extdir = ROOTDIR . 'extensions/';

			$module = $argv[2] ?? NULL;

			if( $module === 'install' ) {

				$extension = $argv[3] ?? NULL;

				if( $extension === NULL ) {
					return true; // exit
				}

				// check if exists
				$extension 	= strtr($extension, ['@' => '', '.' => '/']);

				$extension 	= strtolower($extension);

				$extdir 	= $extdir . strtolower($extension) . '/';

				$proj 		= $extdir . 'extension.json';

				if(!file_exists($proj)) {
					return true; // exit
				}

				$proj 		= json_decode(file_get_contents($proj));

				if(!isset($proj->extension)) {
					return true; // exit
				}

				// start copy

				// 1. console
				//-------------------------------------------------
				$from = $extdir . 'console';
				$dest = ROOTDIR . 'app/console/' . $extension;
				
				rcopy($from, $dest, function($file){
					echo $file . "\r\n";
				});

				// 2. scripts
				//-------------------------------------------------
				$from = $extdir . 'scripts';
				$dest = ROOTDIR . 'assets/scripts';
				
				if( is_dir($from) ) {

					rcopy($from, $dest, function($file) {
						echo $file . "\r\n";
					});

				}

				// 3. service
				//-------------------------------------------------
				$from = $extdir . 'services';
				$dest = ROOTDIR . 'app/service/' . $extension;
				
				if( is_dir($from) ) {

					rcopy($from, $dest, function($file) {
						echo $file . "\r\n";
					});

				}

			}// endif

			return true;

		}

		return false;

	}

	function serve($argv) {

		$root = ROOTDIR;

		array_shift($argv);

		array_unshift($argv, $root);

		$extension_dir 	= ROOTDIR . 'extensions/';
		$app_dir 		= ROOTDIR . 'app/console/';

		// search extension
		//---------------------------
		$command = $argv[1] ?? NULL;

		if($command === NULL) {

			return $this->shutdown(0);
		
		}

		if( $this->internal($command, $argv) ) {
			return;
		}

		if(preg_match('/([\w|\.]+):(\w+)/', $command, $match)) {

			$app 	= strtolower($match[1]);
			$call 	= strtolower($match[2]);
			
			$wdir 	= $app_dir . strtr($app, ['.' => '/']) . '/';
			
			$frun 	= $wdir . 'commands/' . $call . '.cmd.php';

			if( file_exists($frun) ) {

				include($frun);

				$class 	= [];

				$parts 	= explode('.', $app);

				foreach ($parts as $part) {
					
					$class[] = ucfirst($part);

				}

				$class[] = 'Console';
				$class[] = ucfirst($call);

				$class 	 = implode('\\', $class);

				$class 	 = new $class($argv);

				$class->handle();

				return; // completed

			}

		}

		$this->shutdown(0);

	}

	function shutdown( $code ) {

		if( $code === 0 ) {
			
			echo "Command not found.";
			return;

		}

	}

}