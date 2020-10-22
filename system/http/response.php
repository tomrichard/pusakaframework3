<?php 
namespace Pusaka\Http;

use stdClass;

class Response {

	private $content_type 	= 'text/html';
	private $code 			= 200;
	private $body;
	private $header;

	function __construct( $code = 200 ) {
		$this->code 	= $code;
		$this->header 	= [];
	}

	function __get( $prop ) {
		
		if( in_array($prop, ['content_type', 'code', 'body', 'header']) ) {
			return $this->{$prop};
		}

		return NULL;

	}

	static function ok() {

		return new Response( 200 );

	}

	static function code( $code ) {

		return new Response( $code );

	}

	function header( $header = [] ) {
		
		$this->header = $header;

		return $this;

	}

	function json( $body = [] ) {

		$this->content_type = 'application/json';

		//$this->body 		= gzdeflate(json_encode($body), 9);

		$this->body 		= json_encode($body);

		return $this;

	}

	function html( $body = '' ) {

		$this->content_type = 'text/html';

		$this->body 		= $body;

		return $this;

	}

	function xml( $body ) {

		$this->content_type = 'application/xml';

		$this->body 		= $body;

		return $this;

	}

}