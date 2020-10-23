<?php 
namespace App\Rest;

use Pusaka\Rest\MetaRequest;

class ProviderMeta extends MetaRequest {

	const register = [
		"url"	 	=> "/auth/provider.php/register",
		"desc"		=> "Register new Token.",
		"method" 	=> "POST",
		"params" 	=> [
			"username"  => ["string", "255"],
			"password"  => ["string", "255"]
		],
		"query"	 => [],
		"path"	 => []
	];

	const validate = [
		"url"	 	=> "/auth/provider.php/validate",
		"desc"		=> "Validate POST Token.",
		"method" 	=> "POST",
		"params" 	=> [
			"token"  => ["text", "0"]
		],
		"query"	 => [],
		"path"	 => []
	];

}