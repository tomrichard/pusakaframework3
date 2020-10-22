<?php 
namespace Pusaka\Hmvc;

use Pusaka\Hmvc\View;

class Loader {

	private $vars;
	private $request;

	function __construct( $request ) {

		$this->vars 	= [];
		$this->request 	= $request;

	}

	function submit( $vars = [] ) {
		
		$this->vars = array_merge($this->vars, $vars);

	}

	function view( $name = NULL, $vars = [] ) {

		$this->vars = array_merge($this->vars, $vars);

		$View 		= new View($name, $this->vars, $this->request);

		$View->render();

		return;

	}

}