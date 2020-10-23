<?php 
namespace Pusaka\Security\Jwt;

class HS256 extends HMAC {
	
	function name() {

		return 'HS256';

	}

	function algorithm() {

		return 'sha256';

	}
	
}