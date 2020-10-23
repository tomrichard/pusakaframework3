<?php 
namespace Pusaka\Easyui\Service;

use Pusaka\Utils\IOUtils;

class Compiler {

	/** 
	 * Define directive
	 */ 
	public static function defineDirectives() {

		$directives = [
			[
				'begin' 	=> '/@url\s*\((.*?)\s*\)/',
				'resolve'	=> function($match) {

					return '<?php echo url('.$match[1].') ?>';

				}
			],
			[
				'begin' 	=> '/@assets\s*\((.*?)\s*\)/',
				'resolve'	=> function($match) {

					return '<?php echo assets('.$match[1].') ?>';

				}
			],
			[
				'begin' 	=> '/@d\s*\((.*?)\s*\)/',
				'resolve'	=> function($match) {

					return '<?php d('.$match[1].') ?>';

				}
			]
		];

		return $directives;

	}

	/** 
	 * Define pipe
	 */ 
	public static function definePipes() {

		$pipes = [
		];

		return $pipes;

	}

	/** 
	 * Define components
	 */ 
	public static function defineComponents() {

		$components = [];

		$dir 		= ROOTDIR . 'app/hmvc/routes/easyui/'; 

		IOUtils::directory($dir)->scan([

			'filter' 	=> function($filter) {

				$filter->file();
				$filter->ext('easyui.php');

			},

			'callback' 	=> function($path) use ($dir, &$components) {

				// begin compiles
				$script 	= file_get_contents($path);

				$component 	= strtr(dirname($path), [$dir => '']);

				$components[$component] = $script;

			}

		]);

		return $components;

	}

}