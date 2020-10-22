<?php 
namespace Pusaka\Debugger\Console;

use Pusaka\Console\Command;

class Start extends Command {

	protected $signature 	= 'pusaka.debugger:start';

	protected $description 	= 'Start Debug Website';

	function handle() {

		$root 	= ROOTDIR;

		$dir 	= __DIR__ . '/../data/push/';

		if( !is_dir($dir . 'node_modules') ) {

			$cmd 	= "cd {$dir} & npm install";
			exec($cmd);

		}

		$this->line("Server starting...");

		//--------------------------------------------------------

		// $descriptorspec = [
		// 	0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		// 	1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		// 	2 => array("file", "server.log", "a") // stderr is a file to write to
		// ];

		// $cmd 	= "cd {$root} & php -S localhost:8000";

		// $proc1 	= proc_open($cmd, $descriptorspec, $pipes);

		//exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, "server.log", "server.pid"));

		//--------------------------------------------------------

		// $descriptorspec = [
		// 	0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		// 	1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		// 	2 => array("file", "debug.log", "a") // stderr is a file to write to
		// ];

		$cmd 	= "cd {$dir} & npm start \"{$root}\"";
		
		// $proc2 	= proc_open($cmd, $descriptorspec, $pipes);

		exec($cmd);

		//exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, "debug.log", "debug.pid"));

		//--------------------------------------------------------

		// $descriptorspec = [STDIN, STDOUT, STDOUT];
		
		// $cmd 	= "start http://localhost:8000";

		// $proc3 	= proc_open($cmd, $descriptorspec, $pipes);

		// proc_close($proc1);
		// proc_close($proc2);
		// proc_close($proc3);

		// $this->line("Server stopped.");

	}

}