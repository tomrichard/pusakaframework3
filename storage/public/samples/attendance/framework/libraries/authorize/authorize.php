<?php 
namespace Pusaka\Library;

use Pusaka\Microframework\Log;

use Pusaka\Http\Request;
use Pusaka\Http\Response;

use Pusaka\Database\Manager as Database;
use Pusaka\Database\DatabaseException;

use Pusaka\Utils\CryptoUtils;

class Authorize {

	static function register($key, $signature, $data) {

		$json = json_encode($data);

		$zip  = json_encode([
			'signature' => $signature,
			'security'	=> md5($signature.$json),
			'data'		=> $data
		]);

		return CryptoUtils::base64_encode($zip);

	} 

	static function validate($key, $token, &$save = NULL) {

		$schema = json_decode(CryptoUtils::base64_decode($token), true);

		if( !(isset($schema['signature']) AND isset($schema['security']) AND isset($schema['data'])) ) {
			return false;
		}

		$security 	= $schema['security'];
		$signature	= $schema['signature'];
		$json 		= json_encode($schema['data']);

		if( md5($signature.$json) !== $security ) {
			return false;
		}

		if($save !== NULL) {
			$save = $schema['data'];
		}

		return true;

	}

}