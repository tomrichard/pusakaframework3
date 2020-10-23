<?php 
namespace App\Rest;

/** 
|---------------------------------------------------------
| Create  : 2019-10-07 08:54:21
| File    : <{filename}>
| Author  : @author
|---------------------------------------------------------
*/

include('../../index.php');

use Pusaka\Rest\Kernel;
use Pusaka\Rest\Controller;
use Pusaka\Rest\MetaRequest;
use Pusaka\Rest\AuthRequest;

use Pusaka\Http\Request;
use Pusaka\Http\Response;

use Pusaka\Database\Manager as Database;
use Pusaka\Database\DatabaseException;

use Pusaka\Microframework\Log;
use Pusaka\Microframework\Loader;

use Pusaka\Library\Datatable;
use Pusaka\Library\Authorize;

MetaRequest::using('auth/provider',  '1.0');
AuthRequest::using('authorize',    '1.0');

class ProviderApi extends Controller 
{

	/**
	* Register new token.
	* @param 
	* @return array
	*/
	function register() {

		$api = config('api');

		Loader::lib('authorize/authorize');

		$data 	= [];
		$token 	= '';

		try {

			$query = Database::on('default')->builder();

			// Selector
			$query
				->select('username', 'password', 'userlevel', 'provider', 'provider.full_name')
				->from('users_hospital')
				->joinLeft('provider', 'provider.id', 'users_hospital.provider');

			// Condition
			$query
				->where('md5(username)', md5(Request::post('username')))
				->where('md5(password)', md5(Request::post('password')));

			// Get user info
			$data 		= $query->first();

			$data 		= [
				'userid' 	=> $data->username,
				'name'		=> $data->full_name,
				'level'		=> $data->userlevel,
				'provider'	=> $data->provider
			];

			$signature 	= md5(md5($data->username) . md5($data->password));

			$token 		= Authorize::register(
							$api['key'], 
							$signature,
							$data
						);

			unset($query);

		}catch(DatabaseException $e) {

			Log::create('Register Token - Provider Login', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		if($data === NULL) {
		
			return [
				"error" => "Username or Password invalid."
			];
		
		}

		return [
			'token' => $token,
			'data'	=> $data
		];

	}

	function validate() {

		$api 	 = config('api');

		Loader::lib('authorize/authorize');

		$isvalid = Authorize::validate($api['key'], Request::post('token'));

		if(!$isvalid) {
			return [
				'valid'	=> false,
				'error' => 'Invalid token'
			];
		}

		return [
			'valid' => true
		];

	}

}

Kernel::handle(
  ProviderApi::class, 
	AuthorizeAuth::class, 
	  ProviderMeta::class
);