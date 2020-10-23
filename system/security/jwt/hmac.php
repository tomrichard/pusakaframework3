<?php 
namespace Pusaka\Security\Jwt;

use Pusaka\Security\Crypto;

abstract class HMAC {
	
	protected $payload;
	protected $header;
	protected $signature;
	protected $secret;

	protected $name;
	protected $algorithm;

	abstract function name();
	abstract function algorithm();

	function __construct() {

		$this->payload 		= [];
		$this->header 		= [];
		$this->algorithm 	= $this->algorithm();

	}

	function setPayload( $payload ) {

		$this->payload 	= $payload;

		return $this;

	}

	function setHeader(  $header ) {

		$this->header 	= array_merge($header, [
			"alg" 	=> $this->name(),
			"typ"	=> "JWT"
		]);

		return $this;

	}

	function setSecret(  $secret ) {

		$this->secret 	= $secret;

		return $this;

	}

	function generate() {

		$algorithm 	= $this->algorithm;

		$header 	= json_encode($this->header);

		$header 	= Crypto::base64UrlEncode($header);

		$payload 	= json_encode($this->payload);

		$payload 	= Crypto::base64UrlEncode($payload);

		$secret 	= $this->secret;

		$signature 	= hash_hmac($algorithm, "$header.$payload", $secret, true);

		$signature 	= Crypto::base64UrlEncode($signature);

		$token 		= $header . '.' . $payload . '.' . $signature;

		return $token;

	}

}