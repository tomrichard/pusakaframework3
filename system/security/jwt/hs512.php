<?php 
namespace Pusaka\Security\Jwt;

class HS512 extends HMAC {
	
	function name() {

		return 'HS512';

	}

	function algorithm() {

		return 'sha512';

	}

}