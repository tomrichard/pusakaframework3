<?php 
namespace Pusaka\Utils;

use closure;

class CryptoUtils {

	public static function token($length, $idxset) {

		$charset[0] = '0123456789';
		$charset[1] = 'abcdefghijklmnopqrstuvwxyz';
		$charset[2] = $charset[0].$charset[1];
		$charset[3] = $charset[2].'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$pool 		= isset($charset[$idxset]) ? $charset[$idxset] : NULL;
		$plen 	 	= strlen($pool) - 1;

		if($pool == NULL) {
			return NULL;
		}

		$token 		= '';

		for($i=0; $i<$length; $i++) {
			$char 	= substr($pool, rand(0, $plen), 1);
			$token .= $char; 
		}

		return $token;

	}

	public static function iv() {

		$config 	= config('app');

		$file 		= $config['iv'];

		if(!file_exists($file)) {
			$iv 		= openssl_random_pseudo_bytes(16);
			$fhandle 	= fopen($file, 'w');
			fwrite($fhandle, $iv);
			fclose($fhandle);
		}
		
		$fhandle = fopen($file, 'rb');
		$iv 	 = fread($fhandle, 16);
		fclose($fhandle);

		return $iv;

	}

	public static function encode($plaintext) {
		$config 		= config('app');
		$key 			= $config['key'];
		$key 			= substr(sha1($key, true), 0, 16);
		$ciphertext 	= openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, self::iv());
		return $ciphertext;
	}
	
	public static function decode($encoded) {
		$config 		= config('app');
		$key 			= $config['key'];
		$key 			= substr(sha1($key, true), 0, 16);
		$original 		= openssl_decrypt($encoded, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, self::iv());
		return $original;
	}

	public static function base64_encode($plaintext) {
		return base64_encode(self::encode($plaintext));
	}

	public static function base64_decode($base64_text) {
		return self::decode(base64_decode($base64_text));
	}

	public static function saveUrlEncode($url) {

		$url = base64_encode(base64_encode(self::encode($url)));
		$rev = strrev($url);
		$i   = 0;
		$c   = 0;
		$rep = '';
		while(isset($rev[$i])) {
			if($rev[$i]!=='=') {
				break;
			}
			$rep .= '=';
			$c++;
			$i++;
		}
		if($rep=='') {
			$rev = '0_'.$rev;
		}else {
			$i   = 1;
			$rev = str_replace($rep, ($c.'_'), $rev, $i);
		}
		return $rev;

	}

	public static function saveUrlDecode($url_encoded) {

		$piece = explode('_', $url_encoded);
		$ori 	 = '';
		if(is_numeric($piece[0])) {
			$with = '';
			for($i=$piece[0]; $i>0; $i--) {
				$with .= '=';
			}
			$m 	= 1;
			$ori  = str_replace($piece[0].'_', $with, $url_encoded, $m);
			$ori  = strrev($ori);
			$ori  = self::base64_decode(base64_decode($ori));
			return $ori;
		}
		
	}



}