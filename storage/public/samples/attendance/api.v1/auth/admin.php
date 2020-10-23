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

#MetaRequest::using('auth/provider',  '1.0');
#AuthRequest::using('authorize',    '1.0');

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
				->select('users.username', 'users.password', 
					'users.userlevel', 'users.email', 'users.employee',
					'employee.nik', 'employee.name'
				)
				->from('users')
				->joinLeft('employee', 'employee.id', 'users.employee');

			// Condition
			$query
				->where('md5(users.username)', md5(Request::post('username')))
				->where('md5(users.password)', md5(Request::post('password')))
				->where('users.userlevel', '-1');

			// Get user info
			$data 		= $query->first();

			$signature 	= md5(md5($data->username) . md5($data->password));

			$data 		= [
				'userid' 	=> $data->username,
				'name'		=> $data->name,
				'level'		=> $data->userlevel,
				'nik'		=> $data->nik
			];

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
  ProviderApi::class
  	#,AuthorizeAuth::class
  		#,ProviderMeta::class
);