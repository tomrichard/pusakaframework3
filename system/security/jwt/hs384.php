<?php 
namespace Pusaka\Security\Jwt;

class HS384 extends HMAC {
	
	function name() {

		return 'HS384';

	}

	function algorithm() {

		return 'sha384';

	}
	
}