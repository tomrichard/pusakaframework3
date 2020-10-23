<?php 
namespace Pusaka\Easyui\Service;

class Booting {

	static function run() {

		$root 	= path(ROOTDIR);

		$cmd 	= "cd {$root} & php pusaka pusaka.easyui:compile";

		exec($cmd);

	}

}