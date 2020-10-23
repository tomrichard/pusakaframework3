<?php 
namespace App\Rest;

/** 
|---------------------------------------------------------
| Create 	: 2019-10-07 08:54:21
| File 		: <{filename}>
| Author 	: @author
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

MetaRequest::using('master/faq', 	'1.0');
AuthRequest::using('provider', 		'1.0');

class FaqApi extends Controller 
{

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function create() {

		$data = array_merge(Request::post(), ['client' => 30]);

		try {
		
			$query = Database::on('default')->builder();

			$query
				->into('faq')
				->insert($data);
		
		}catch(DatabaseException $e) {
			
			Log::create('insert faq', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		return $data;

	}

	/**
      * Create Faq.
      * @param string client, string question
      * @return
      */
	function update($id) {

		$data 	= Request::post();

		try {
		
			$query = Database::on('default')->builder();

			$query
				->into('faq')
				->where('id', $id)
				->update($data);
		
		}catch(DatabaseException $e) {
			
			Log::create('update faq', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		return $data;

	}

	/**
      * Create Faq.
      * @param string client, string question
      * @return
      */
	function delete($id) {

		try {
		
			$query = Database::on('default')->builder();

			$query
				->into('faq')
				->where('id', $id)
				->delete();
		
		}catch(DatabaseException $e) {
			
			Log::create('delete faq', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		return ['id' => $id];
	
	}

	/**
      * Create Faq.
      * @param
      * @return
      */
	function filter() {

		header("Access-Control-Allow-Origin: *");

		Loader::lib('datatable/datatable');

		$res 		= [];

		$Datatable 	= new Datatable();

		$Datatable
			->on('default')
			->table('faq f')
			->select([
				'question' 
					=> 'question',
				'answer'	
					=> 'answer'
			])
			->options([
				'limit' 	=> '_limit',
				'sort'		=> '_sort',
				'untouch'	=> ['question']
			]);

		return $Datatable->json();

		// limit
		// page
		// 

		// $get 	= Request::get();

		// $query 	= Database::on('default')->builder();

		// try {
		
		// 	$query
		// 		->select('*')
		// 		->from('faq');

		// 	$query->where('false');

		// 	foreach($get as $key => $value) {

		// 		$value = explode(' ', $value);

		// 		foreach ($value as $search) {
		// 			$query->whereOR($key, 'LIKE', '%'.$search.'%');
		// 		}

		// 	}
		
		// 	$res = $query->get();

		// }catch(DatabaseException $e) {
		// }

		// unset($query);

		return $res;

	}

}

Kernel::handle(
	FaqApi::class, 
		ProviderAuth::class, 
			FaqMeta::class
);