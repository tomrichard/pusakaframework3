<?php
function config()
{

    $args   = func_get_args();

	$config = $GLOBALS['config'];

    $copy   = $config;

	foreach ($args as $key) {
		
        if( isset($copy[$key]) ) {
			$copy = $copy[$key];
		}

	}

	return $copy;
	
}

function path( $path ) 
{

	if($path === '') {
		return $path;
	}

	return rtrim($path, '/') . '/';
	
}

function storage( $path ) {

    return path( ROOTDIR . 'storage/' . $path );

} 

function d() {

	$args = func_get_args();

	if(is_cli()) {
		
		foreach ($args as $var) {
			print_r( $var );
		}

	}

	if(is_development()) {

		foreach ($args as $var) {
			echo '<pre>';
			print_r( $var );
			echo '</pre>';
		}
	
	}

}

function url( $path = NULL ) {
    
    $baseurl = BASEURL;

    if( is_string($path) ) {
        return $baseurl . $path;
    }

    if( $path === NULL ) {

        if( !isset($GLOBALS['__Controller']) ) {
            return;
        }

        $caller = $GLOBALS['__Controller'];

        $loader = $caller->__loader();

        $path   = $loader->request()->url;

        $Url    = new \Pusaka\Http\Url($path);

        $Url->setParams( $loader->request()->params );

        return $Url;

    }

    return '';

}

function is_cli() {

    return (php_sapi_name() === 'cli');

}

function dot_vars() {

	return json_decode(file_get_contents(ROOTDIR . '.vars'));

}

function is_development() {
	
	if(defined('ENVIRONMENT')) {
		return ENVIRONMENT === 'DEVELOPMENT';
	}

	return FALSE;

}

function is_production() {
	return ENVIRONMENT === 'PRODUCTION';
}

function redirect( $location ) {

	if( class_exists('Pusaka\Http\Response') ) {
		return Pusaka\Http\Response::code(302)
					->header([
						'Location' => $location
					])
					->json([
						'Response' => 'Redirect URL'
					]);
	}

}

function view($vars = [], $name = NULL) {

	$caller = NULL;

	if( !isset($GLOBALS['__Controller']) ) {
		return;
	}

	$caller = $GLOBALS['__Controller'];

	$loader = $caller->__loader();

	$loader->view($name, $vars);

}

function rcopy($src,$dst, $fun = NULL) {
    
    $dir = opendir($src);
    
    @mkdir($dst, 0700, true);
    
    while(false !== ( $file = readdir($dir)) ) {
    
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
            	
            	if($fun !== NULL) {
            		$fun($src . '/' . $file);
            	}

                rcopy($src . '/' . $file,$dst . '/' . $file, $fun);
            
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }

    }

    closedir($dir);

}

function get_server_memory_usage($getPercentage=true)
{
    $memoryTotal = null;
    $memoryFree = null;

    if (stristr(PHP_OS, "win")) {
        // Get total physical memory (this is in bytes)
        $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
        @exec($cmd, $outputTotalPhysicalMemory);

        // Get free physical memory (this is in kibibytes!)
        $cmd = "wmic OS get FreePhysicalMemory";
        @exec($cmd, $outputFreePhysicalMemory);

        if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) {
            // Find total value
            foreach ($outputTotalPhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memoryTotal = $line;
                    break;
                }
            }

            // Find free value
            foreach ($outputFreePhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memoryFree = $line;
                    $memoryFree *= 1024;  // convert from kibibytes to bytes
                    break;
                }
            }
        }
    }
    else
    {
        if (is_readable("/proc/meminfo"))
        {
            $stats = @file_get_contents("/proc/meminfo");

            if ($stats !== false) {
                // Separate lines
                $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                $stats = explode("\n", $stats);

                // Separate values and find correct lines for total and free mem
                foreach ($stats as $statLine) {
                    $statLineData = explode(":", trim($statLine));

                    //
                    // Extract size (TODO: It seems that (at least) the two values for total and free memory have the unit "kB" always. Is this correct?
                    //

                    // Total memory
                    if (count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") {
                        $memoryTotal = trim($statLineData[1]);
                        $memoryTotal = explode(" ", $memoryTotal);
                        $memoryTotal = $memoryTotal[0];
                        $memoryTotal *= 1024;  // convert from kibibytes to bytes
                    }

                    // Free memory
                    if (count($statLineData) == 2 && trim($statLineData[0]) == "MemFree") {
                        $memoryFree = trim($statLineData[1]);
                        $memoryFree = explode(" ", $memoryFree);
                        $memoryFree = $memoryFree[0];
                        $memoryFree *= 1024;  // convert from kibibytes to bytes
                    }
                }
            }
        }
    }

    if (is_null($memoryTotal) || is_null($memoryFree)) {
        return null;
    } else {
        if ($getPercentage) {
            return (100 - ($memoryFree * 100 / $memoryTotal));
        } else {
            return array(
                "total" => $memoryTotal,
                "free" => $memoryFree,
            );
        }
    }
}

function get_server_cpu_usage($windows = false){
    $os=strtolower(PHP_OS);
    if(strpos($os, 'win') === false){
        if(file_exists('/proc/loadavg')){
            $load = file_get_contents('/proc/loadavg');
            $load = explode(' ', $load, 1);
            $load = $load[0];
        }elseif(function_exists('shell_exec')){
            $load = explode(' ', `uptime`);
            $load = $load[count($load)-1];
        }else{
            return false;
        }

        if(function_exists('shell_exec'))
            $cpu_count = shell_exec('cat /proc/cpuinfo | grep processor | wc -l');        

        return array('load'=>$load, 'procs'=>$cpu_count);
    }elseif($windows){
        if(class_exists('COM')){
            $wmi=new COM('WinMgmts:\\\\.');
            $cpus=$wmi->InstancesOf('Win32_Processor');
            $load=0;
            $cpu_count=0;
            if(version_compare('4.50.0', PHP_VERSION) == 1){
                while($cpu = $cpus->Next()){
                    $load += $cpu->LoadPercentage;
                    $cpu_count++;
                }
            }else{
                foreach($cpus as $cpu){
                    $load += $cpu->LoadPercentage;
                    $cpu_count++;
                }
            }
            return array('load'=>$load, 'procs'=>$cpu_count);
        }
        return false;
    }
    return false;
}

function writelog( $log, $code = 0 ) {

	$path 	= ROOTDIR . 'storage/private/logs/';
	
	$file  	= $path . 'log_' . date('Ymd_His') . '_' . uniqid() . '.log'; 

	$dbg 	= debug_backtrace();

	array_shift($dbg);

	if( is_string($log) ) {

		$write = json_encode([
			'code' 		=> $code,
			'message' 	=> $log,
			'backtrace'	=> $dbg
		] ,JSON_PRETTY_PRINT);


		@file_put_contents($file , $write);

		return true;

	}

	if( is_array($log) ) {

		$write = json_encode([
			'code' 		=> $code,
			'message' 	=> $log,
			'backtrace'	=> $dbg
		] ,JSON_PRETTY_PRINT);


		@file_put_contents($file , $write);

		return true;

	}

}

function get_extension() {

	return $GLOBALS['extension'];

}