<?php 
namespace App\Rest;

/** 
|---------------------------------------------------------
| Create 	: 2019-10-25 06:45:50
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

# MetaRequest::using('api.v1/module/claim', '1.0');
AuthRequest::using('provider', 		'1.0');

class ClaimApi extends Controller 
{

	/**
      * Create Claim.
      * @param
      * @return
      */
	function filter() {

		$stat_table = "
		(
			SELECT
				1 AS id,
				'Pending' AS stat_name
			UNION ALL 
			SELECT
				2 AS id,
				'Dijaminkan' AS stat_name
			UNION ALL
			SELECT
				3 AS id,
				'Tidak Dijaminkan' AS stat_name
		) stat_table";


		header("Access-Control-Allow-Origin: *");

		Loader::lib('datatable/datatable');

		// Create filter
		//----------------------------------------------------------------
		$auth 			= $this->auth; // get from provider.auth.php

		$filter 		= [];

		$provider_id 	= $auth['provider'];
		$member_card 	= Request::get('member_card');
		$admission_date = Request::get('admission_date');

		if($member_card === NULL and $admission_date === NULL) {
			Response::http(403, 'Bad request.');
		}

		if($member_card !== NULL AND $member_card !== '') {
			$filter['member_card'] 		= $member_card;
		}

		if($admission_date !== NULL) {
			$filter['admission_date'] 	= $admission_date;
		}

		//----------------------------------------------------------------

		$Datatable 		= new Datatable();

		$Datatable
			->on('default')
			->table('`case` c')
			->select([

				'id'
					=> 'id',
				
				'admission_date' 
					=> 'admission_date',
				
				'client_name'
					=> 
						function($query) {
							$query
								->select('full_name')
								->from('client_hospital_portal')
								->where('id = c.client');
						},

				'provider_name'
					=> 
						function($query) {
							$query
								->select('full_name')
								->from('provider')
								->where('id = c.provider');
						},

				'member_card'
					=> 'member_card',

				'member_name'
					=> 
						function($query) {
							$query
								->select('member_name')
								->from('member')
								->where('id = c.patient');
						},

				'perawatan'	
					=> 
						function($query) {
							$query
								->select('`name`')
								->from('perawatan')
								->where('id = c.code_rawat');
						},

				'button_status'
					=>	
						function($query) {
							$query
								->select("
									IF(
										c.category=1, 'Monitoring', 
										IF(c.category=0 AND status=4, 'Pasien Pulang',
											'Monitoring'
										)
									)");
						},

				'status_perawatan'
					=>	
						function($query) {
							$query
								->select("name")
								->from("status_hospital")
								->where('id = c.status');
						},

				'init_log_stat'
					=>
						function($query) use ($stat_table) {

							$query
								->select('stat_name')
								->from($stat_table)
								->where('stat_table.id = c.issue_initial_log');

						},

				'final_log_stat'
					=>
						function($query) use ($stat_table) {

							$query
								->select('stat_name')
								->from($stat_table)
								->where('stat_table.id = c.issue_log');

						}


			])
			->options([
				'limit' 	=> '_limit',
				'sort'		=> '_sort',
				'untouch'	=> ['member_card', 'admission_date']
			]);

		// Implementation Filter
		//----------------------------------------------------------------
		if(!empty($filter)) {

			$Datatable->where(function($query) use ($filter, $provider_id) {

				$query->where('source', 5);
				
				$query->where('provider', $provider_id);

				foreach ($filter as $key => $value) {

					$query
						->where($key, $value);
				
				}
			
			});

		}
		//----------------------------------------------------------------

		return $Datatable->json();

	}

	/**
	 * 
	 */
	function info() {

		header("Access-Control-Allow-Origin: *");

		$auth 			= $this->auth;

		$provider_id 	= $auth['provider'];

		$return 		= [];

		try {
			
			$query = Database::on('default')->builder();

			$query
				->select('id', 'admission_date', '`status`',

					function($query) {

						$query->alias('status_perawatan', 
								function($query) {
									$query
										->select('`name`')
										->from('status_hospital')
										->where('id = c.status');
								});

						$query->alias('client_name', 
								function($query) {
									$query
										->select('full_name')
										->from('client_hospital_portal')
										->where('id = c.client');
								});

						$query->alias('data_pasien', 
								function($query) {
									$query
										->select("CONCAT(member_card, ',', member_name)")
										->from('member')
										->where('id = c.patient');
								});

						$query->alias('perawatan',
								function($query) {
									$query
										->select('`name`')
										->from('perawatan')
										->where('id = c.code_rawat');
								});

						$query->alias('provider_name', 
								function($query) {
									$query
										->select('full_name')
										->from('provider')
										->where('id = c.provider');
								});

				})
				->from('`case` c')
				->where('source', 5)
				->where('provider', $provider_id)
				->where('id', Request::get('case'));

			$return = $query->first();

			unset($query);
		
		}catch(DatabaseException $e) {

			Log::create('info claim', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');
		
		}

		return $return;

	}

	/**
	 * 
	 */
	function preworksheet() {

		header("Access-Control-Allow-Origin: *");

		$auth 			= $this->auth;

		$provider_id 	= $auth['provider'];

		$return 		= [];

		try {
			
			$query = Database::on('default')->builder();

			$query
				->select('p.id', 'g.name', 'p.amount')
				->from('pre_worksheet p')
				->joinLeft('general g', 'g.id', 'p.general')
				->joinLeft('`case` c', 'c.id', 'p.case')
				->where('c.source', 5)
				->where('c.provider', $provider_id)
				->where('c.id', Request::get('case'));

			$return =
				$query
					->get();

			unset($query);

		}catch(DatabaseException $e) {

			Log::create('preworksheet claim', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		return $return;

	}

}

Kernel::handle(
	ClaimApi::class,
		ProviderAuth::class
		#ClaimMeta::class
);