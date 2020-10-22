<?php 
namespace Pusaka\Hmvc;

class Controller {

	protected $load;

	function __construct( $request ) {

		$r = new \ReflectionClass($this);
		
		$GLOBALS['__Controller'] = $this;
		$GLOBALS['__Views'] 	 = [
			$r->getFileName()
		];

		$this->load = new Loader( $request );

		unset($r);

	}

	function __loader() {

		return $this->load;

	}

}