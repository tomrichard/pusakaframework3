<?php 
namespace Pusaka\Easyui\Console;

use Pusaka\Easyui\Library\Compiler;
use Pusaka\Console\Command;
use Pusaka\Utils\IOUtils;

include( __DIR__ . '/../data/libraries/compiler.php' );
include( ROOTDIR . 'app/service/pusaka/easyui/compiler.service.php');

class Compile extends Command {

	protected $signature 	= 'pusaka.easyui:compile';

	protected $description 	= 'Start Compile Component';

	function handle() {

		$root 	= ROOTDIR;

		$dir 	= $root . 'app/hmvc/';

		$this->info('Begin::Compile');
		$this->info('------------------------------------------');

		//$files	= glob($dir . '*.easyui.php');

		IOUtils::directory($dir)->scan([

			'filter' 	=> function($filter) {

				$filter->file();
				$filter->ext('easyui.php');

			},

			'callback' 	=> function($path) {

				// begin compiles
				$script = file_get_contents($path);

				if($script !== '' AND is_string($script)) {

					$engine = new Compiler( preg_split('/\n/', $script) );
					
					$engine->registerDirectives( 
						\Pusaka\Easyui\Service\Compiler::defineDirectives()
					);

					$engine->registerComponents( 
						\Pusaka\Easyui\Service\Compiler::defineComponents()
					);

					$script = $engine->compile()->getCompiled();

					unset($engine);

				}

				$name 	= basename($path, '.easyui.php');

				$save 	= path(dirname($path)) . $name . '.ui.php';

				file_put_contents($save, $script);

				$this->info("compiled : " . $save);

			}

		]);

		$this->info('------------------------------------------');
		$this->info('Compiled::Success');

	}

}