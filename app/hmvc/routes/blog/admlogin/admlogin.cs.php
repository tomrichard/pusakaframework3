<?php 
use Pusaka\Hmvc\Controller;

class AdmloginCS extends Controller {

	function index( $request ) {

		return view([], NULL);

	}

	function auth( $request ) {

		// $Tokenizer = new \Pusaka\Security\Tokenizer();

		// $payload   	= [
		// 	'id'	=> 'bbbbb',
		// 	'user' 	=> 'aaaa'
		// ];

		// $token 		= $Tokenizer->create($payload, 'saya');

		// echo '<textarea>';
		// echo $token;

		//$SSL = new \Pusaka\Security\SSL();

		//var_dump(openssl_get_cipher_methods());

		// $SSL->generateKey();

		// echo '<textarea>';
		// echo $SSL->getPrivateKey();
		// echo '</textarea>';

		// echo '<textarea>';
		// echo $SSL->getPublicKey();
		// echo '</textarea>';


		$encrypt = \Pusaka\Security\Crypto::encryptString('heloooooo yoo man');

		echo '<textarea>';
		echo $encrypt;
		echo '</textarea>';

		$decrypt = \Pusaka\Security\Crypto::decryptString($encrypt);

		echo '<textarea>';
		echo $decrypt;
		echo '</textarea>';


		// var_dump($request->params);

		// return view([], NULL);

	}

}