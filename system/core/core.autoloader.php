<?php 
if(isset($__load_files)) {
	foreach ($__load_files as $file) {
		if(file_exists($file)) {
			include($file);
		}
	}
}

if(defined('APPLICATION_TYPE')) {

	if(APPLICATION_TYPE === 'HMVC') {

		if( isset($extension) ) {
			if($extension->boot instanceof Closure) {
				$booting = $extension->boot;
				$booting();
			}	
		}
		
	}

}