<?php
namespace Pusaka\Easyui\Service;

use Pusaka\Hmvc\Controller;
use ReflectionObject;
use ReflectionProperty;
use ReflectionMethod;

if(APPLICATION_TYPE === 'HMVC') :

class Component extends Controller {

	public function index( $request ) {

		$extension 	= get_extension();

		$extension->disable('pusaka.debugger');

		$state 		= $request->input->state;
		$action 	= $request->input->action;

		$variable 	= json_decode(base64_decode($request->input->token));

		$vars 		= (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);

		if($state === 'update') {
			
			foreach ($vars as $var) {
				$this->{$var->name} = $variable->{$var->name} ?? NULL;
			}

		}

		if($action !== NULL) {
			
			$rm 		= new ReflectionMethod($this, $action);

			if($rm->isPublic()) {
				$this->{$action}();
			}

			unset($rm);
		
		}

		ob_start();

		$this->render();

		$render = ob_get_contents();

		ob_end_clean();

		return [
			'token'		=> base64_encode(json_encode($this->compact())),
			'render' 	=> $render
		];

	}

	protected function compact() {

		$send = [];

		$vars = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);

		foreach ($vars as $var) {
			$send[$var->name] = $this->{$var->name};
		}

		return $send;

	} 

}

endif;