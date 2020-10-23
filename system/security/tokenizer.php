<?php 
namespace Pusaka\Security;

use Pusaka\Security\Jwt\Algorithm;

use Exception;

class Tokenizer {

	private $encrypt 	= false;
	private $algorithm 	= NULL;
	private $secret 	= '';

	function __construct() {

		if( !isset($GLOBALS['config']['application']['key']) ) {
			
			throw new Exception('Application::Key not setup yet.');

		}

		$this->secret 		= $GLOBALS['config']['application']['key'];

		$this->algorithm 	= Algorithm::get('HS256');
	
	}

	function useAlgorithm(Algorithm $algorithm) {

		$this->algorithm = $algorithm;

	}

	function useEncryption( $use = false ) {

		$this->encrypt 	 = $use; 

	}

	function create( $payload, $secret = NULL ) {
		
		$header  = [];

		if( !is_array($payload) ) {
			
			throw new Exception('Tokenizer::create(array $payload, string $secret, bool $encrypt = false), $payload must be an array.');

		}

		if( $secret === NULL ) {
			
			$secret = $this->secret;

		}

		$this->algorithm
			->setPayload(	$payload )
			->setHeader(	$header  )
			->setSecret(	$secret  );

		$token = $this->algorithm->generate();

		return $token;

	}

	/**
	 * @return boolean
	 */
	function verify( $token, $secret = NULL ) {
		
	}

	/**
	 * @return stdClass
	 */
	function payload() {
		
	}

}