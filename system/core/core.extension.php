<?php 
namespace Pusaka\Extension;

class Manager {

	public $disabled = [];
	public $boot;
	public $shutdown;

	function install( $extensions = [] ) {

		foreach ($extensions as $extension) {
		
			$dir = ROOTDIR . 'app/service/' . strtr(strtolower($extension), ['.' => '/']);

			if( is_dir($dir) ) {

				foreach (glob($dir . "/*.service.php") as $service) {
					$GLOBALS['__load_files'][] = $service;
				}

			}

		}

	}

	function disable( $extension ) {

		$this->disabled[] = $extension;
	
	}

	function is_active( $extension ) {

		return !in_array($extension, $this->disabled);

	}

	function onboot( $closure ) {

		$this->boot = $closure;

	}

	function onshutdown( $closure ) {

		$this->shutdown = $closure;

	}

}

$extension = new \Pusaka\Extension\Manager();