<?php 
namespace Pusaka\Security;

use Exception;

class SSL {

	private $config;
	private $private_key;
	private $public_key;

	function __construct() {

		$this->config = array(
			"digest_alg" => "sha512",
			"private_key_bits" => 4096,
			"private_key_type" => OPENSSL_KEYTYPE_RSA,
		);

	}
	
	function generateKey() {

		$res 		= openssl_pkey_new($this->config);

		var_dump(openssl_error_string());

		die();

		openssl_pkey_export($res, $private_key);

		$detail 	= openssl_pkey_get_details($res);

		$public_key = $detail["key"];

		$this->private_key 	= $private_key;

		$this->public_key 	= $public_key;

		unset($res);

	}

	function open() {

	}

	function setPrivateKey( $private_key ) {

		$this->private_key 	= $private_key;

	}

	function setPublicKey( $public_key ) {

		$this->public_key 	= $public_key;

	}

	function getPrivateKey() {
	
		return $this->private_key;
	
	}

	function getPublicKey() {
		
		return $this->public_key;

	}

	function encrypt($data) {

	}

	function decrypt($data) {

	}

}