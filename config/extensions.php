<?php 
$extension->install([
	//'pusaka.debugger',
	//'pusaka.easyui'
]);

$extension->onboot(function() {

	// compile template easyui
	//------------------------------------------
	
	// \Pusaka\Easyui\Service\Booting::run();

});

$extension->onshutdown(function($terminate, $error) {

	// write your shutdown function
	//-------------------------------------

	// run debugger at the end
	//-------------------------------------
	// \Pusaka\Debugger\Service\Debug::run($error);
	
	$terminate();

});
