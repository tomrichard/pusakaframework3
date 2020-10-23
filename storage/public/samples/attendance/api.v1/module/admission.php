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

use DateTime;
use DateInterval;

#MetaRequest::using('master/faq', 	'1.0');
AuthRequest::using('provider', 		'1.0');

class AdmissionApi extends Controller 
{

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function check() {

		header("Access-Control-Allow-Origin: *");

		$row = [];
		
		try {
		
			$query = Database::on('default')->builder();

			$query
				->select(
					'member_id',
					'member_card', 
					'member_name',
					'policy_no',
					'policy_status',
					'client_id',
					'client_full_name'
				)->from('patient_hospital_portal')
				->where('member_card', Request::get('card_number'));
			
			$row 	= $query->first();
			
			unset($query);

		}catch(DatabaseException $e) {

			Log::create('check admission', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		if($row === NULL) {
			return [
				"error" => "Member card not registered yet."
			];
		}

		$row->policy_status_text = ($row->policy_status == 0) ? 'Tidak Aktif' : 'Aktif';

		return $row;

	}

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function benefits() {

		header("Access-Control-Allow-Origin: *");

		$row = [];
		
		try {
		
			$query = Database::on('default')->builder();

			// SELECT
			// 	`perawatan`.`client` AS `client`,
			// 	`perawatan`.`category` AS `category`,
			// 	`perawatan`.`id` AS `id_perawatan`,
			// 	`perawatan`.`name` AS `name`,
			// 	`plan_detail`.`id` AS `id`,
			// 	`member`.`plan` AS `plan`,
			// 	`member`.`id` AS `member_id`,
			// 	`member`.`member_name` AS `member_name`,
			// 	`member`.`member_card` AS `member_card`
			// FROM `perawatan`
			// 	LEFT JOIN `general` ON
			// 		`general`.`id_perawatan` = `perawatan`.`id`
			// 	LEFT JOIN `plan_detail` ON
			// 		`plan_detail`.`id` = `general`.`plan_detail`
			// 	LEFT JOIN `member` ON
			// 		`member`.`plan` = `plan_detail`.`plan`
			// WHERE
			// 	member.member_card = 'BLUE000022'
			// GROUP BY 
			// 	id_perawatan;

			// $query
			// 	->select(
			// 		'id_perawatan',
			// 		'category',
			// 		'name'
			// 	)->from('`x-perawatan`')
			// 	->where('member_card', Request::get('card_number'));
			
			$query
				->select(
					'`perawatan`.`client` AS `client`',
					'`perawatan`.`category` AS `category`',
					'`perawatan`.`id` AS `id_perawatan`',
					'`perawatan`.`name` AS `name`',
					'`plan_detail`.`id` AS `id`',
					'`member`.`plan` AS `plan`',
					'`member`.`id` AS `member_id`',
					'`member`.`member_name` AS `member_name`',
					'`member`.`member_card` AS `member_card`'
				)
				->from('perawatan')
					->joinLeft('general', 'general.id_perawatan', 'perawatan.id')
					->joinLeft('plan_detail', 'plan_detail.id', 'general.plan_detail')
					->joinLeft('member', 'member.plan', 'plan_detail.plan')
				->where('member.member_card', Request::get('card_number'))
				->groupBy('general.id_perawatan');

			$row 	= $query->get();
			
			unset($query);

		}catch(DatabaseException $e) {

			Log::create('benefits admission', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		if($row === NULL) {
			return [
				"error" => "Member card not found."
			];
		}

		return $row;

	}


	/**
	 * Download pdf policy Terms and Conditions
	 * @param
	 * @return byte
	 */
	function policy() {

		$row = NULL;

		try {
		
			$query = Database::on('default')->builder();

			$query
				->select(
					'upload'
				)->from('conditions_polis')
				->where('client', Request::get('id'));
			
			$row 	= $query->first();
			
			unset($query);

		}catch(DatabaseException $e) {

			Log::create('policy admission', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		if($row === NULL) {
			Response::http(403, 'Not found');
		}

		$file = $row->upload;

		$root = ROOTDIR . 'storage/policies/';

		if(!file_exists($root . $file)) {
			Response::http(403, 'Not found');
		}

		$size = filesize($root . $file);

		$date = new DateTime();
		$date->add(new DateInterval('PT5M'));

		$date = $date->format('D, j M Y G:i:s \G\M\T');
		
		header('Expires: '.$date);
		header('Content-Disposition: inline; filename=police_toc.pdf');
		header('Content-Length: '.$size);
		header('Accept-Ranges: bytes');
		header('Content-Type: application/pdf');
		header('Connection: close');

		readfile($root . $file);

		die();

	}

	/**
	 * Submit to admission
	 * @param
	 * @return
	 */
	function submit() {

		header("Access-Control-Allow-Origin: *");

		$auth 							= $this->auth; // get from provider.auth.php

		$session['id_provider'] 		= $auth['provider'];
		$session['created_by'] 			= $auth['userid'];

		// $input['member_card']		= 'BLUE000022';//Request::post('member_card');
		// $input['admission_date']		= date('Y-m-d');//Request::post('admission_date');
		// $input['code_rawat'] 		= 7;//Request::post('code_rawat');

		$input['member_card']			= Request::post('member_card');
		$input['admission_date']		= Request::post('admission_date');
		$input['code_rawat'] 			= Request::post('code_rawat');

		// static value
		//--------------------------------------------------------
		$static['created_by']			= $session['created_by'];
		$static['create_date'] 			= 'NOW()';//date("Y-m-d H:i:s");
		$static['admission_date']		= $input['admission_date'];
		$static['admission_hour'] 		= 'HOUR(NOW())';
		$static['admission_minute'] 	= 'MINUTE(NOW())';
		$static['source']  				= 5;
		$static['type'] 				= 2;
		$static['is_panel'] 			= 1;
		$static['currency_01']			= 85;
		$static['currency_02']			= 85;
		$static['currency_rate']		= 1.0;
		$static['status']				= 2;
		$static['is_service_provided']	= 1;
		$static['userlevel'] 			= -1;

		// src->table 	: member 
		$member = [
			'id AS patient',
			'member_name',
			'member_dob AS dob',
			'member_gender AS gender',
			'member_id',
			'member_card',
			'member_card_edc',
			'member_principle AS principle',
			'member_relation AS relation',
			'member_mobile AS mobile',
			'client AS client',

			'branch AS branch',
			'company AS company',
			'policy_status',
			'policy_no',
			'policy_holder',
			'policy_effective_date',
			'policy_expiry_date',
			'policy_issue_date',
			'policy_declare_date',
			'policy_suspend_date',

			'policy_unsuspend_date',
			'policy_lapse_date',
			'policy_revival_date',
			'policy_termination_date',
			'exclusion',
			'special_condition',
			'member_remarks',
			'remarks_by AS member_remarks_by',
			'remarks_date AS member_remarks_date',
			'program',
			
			'plan',
			'plan_attach_date',
			'plan_expiry_date',
			'rider',
			'rider_attach_date',
			'rider_expiry_date'
		];

		// src->view 	: provider
		$provider = [
			'id AS provider',
			'`status` AS provider_status',
			'email AS provider_email',
			'id_edc',
			'multiple_cashier AS is_multiple_cashier'
		];

		// src->table 	: perawatan
		$perawatan = [
			'id AS code_rawat',
			'category'
		];

		// start query->builder
		//-------------------------------
		$query 	= Database::on('default')->builder();

		// maping :: member
		//-------------------------------
			foreach ($member as $key => $value) {

				if(preg_match('/\sAS\s(\w+)/', $value, $match) > 0) {
					
					$query->map($match[1]);

				}else {
					
					$query->map($value);

				}

				$member[$key] = 'member.' . $value;

			}// end mapping
		//-------------------------------

		// maping :: provider
		//-------------------------------
			foreach ($provider as $key => $value) {

				if(preg_match('/\sAS\s(\w+)/', $value, $match) > 0) {
					
					$query->map($match[1]);

				}else {
					
					$query->map($value);

				}

				$provider[$key] = 'provider.' . $value;

			}// end mapping
		//-------------------------------

		// maping :: perawatan
		//-------------------------------
			foreach ($perawatan as $key => $value) {

				if(preg_match('/\sAS\s(\w+)/', $value, $match) > 0) {
					
					$query->map($match[1]);

				}else {
					
					$query->map($value);

				}

				$perawatan[$key] = 'perawatan.' . $value;

			}// end mapping
		//-------------------------------

		// maping :: static
		//-------------------------------
			$staticClosure = function($query) use ($static) {

				foreach ($static as $key => $value) {
				
					$query->map($key);

					$query
                		->alias($key, $value, !in_array($key, ['create_date', 'admission_hour', 'admission_minute']));

				}// end mapping

			};
		//-------------------------------

		try {

			$sql   = 
				$query
					->select($member)
					->select($provider)
					->select($perawatan)
					->select($staticClosure)
					->from('member,provider,perawatan')
					->where('member.member_card', $input['member_card'])
					->where('provider.id', $session['id_provider'])
					->where('perawatan.id', $input['code_rawat'])
					->into('admission')
					->insert();

			$query
				->select('max(id) AS last_id')
				->from('admission')
				->where('source', 5)
				->where('member_card', $input['member_card'])
				->where('provider', $session['id_provider']);

			$code =
				$query
					->first();

			$success = true;

			unset($query);
		
		}catch(DatabaseException $e) {

			try {
			
				$query
					->select('max(id) AS last_id')
					->from('admission')
					->where('source', 5)
					->where('member_card', $input['member_card'])
					->where('provider', $session['id_provider']);

				$code =
					$query
						->first();

			}catch(DatabaseException $e) {

				Response::http(500, 'Server Internal Error.');
			
			}

			unset($query);

			$message = 'Cannot add Admission.';

			if(preg_match('/Sudah Pernah Ada Pendaftaran/', $e->getMessage()) > 0) {
				$message = 'Billing belum selesai. Silahkan selesaikan billing terlebih dahulu.';
			}

			Log::create('submit admission', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, json_encode([
				'message' 	=> $message,
				'admission'	=> $code->last_id
			]));

		}

		return [
			'success' => true,
			'admission' => $code->last_id
		];

	}

	/**
	 * Submit to admission
	 * @param
	 * @return
	 */
	function detail() {

		header("Access-Control-Allow-Origin: *");

		$auth 							= $this->auth; // get from provider.auth.php

		$session['id_provider'] 		= $auth['provider'];
		$session['created_by'] 			= $auth['userid'];

		try {

			// client
			// data patient card_number, 
		
			$query = Database::on('default')->builder();

			$query
				->select(
					function($query) {

						$query->alias('perawatan', function($query){

							$query
								->select('name')
								->from('perawatan')
								->where('id = a.code_rawat');

						});
					
					},
					function($query) {

						$query->alias('client', function($query){

							$query
								->select('full_name')
								->from('client')
								->where('id = a.client');

						});
					
					},
					function($query) {

						$query->alias('provider', function($query){

							$query
								->select('full_name')
								->from('provider')
								->where('id = a.provider');

						});
					
					},
					"member_card", 
					"member_name",
					"CONCAT(member_name, ', ', member_card) AS patient",
					"CONCAT(DATE_FORMAT(admission_date, '%d/%m/%Y'), ' ', admission_hour, ':', admission_minute) AS admission_date"
				)->from('admission a')
				->where('provider', $session['id_provider'])
				->where('id', Request::get('id'));

			$row 	= $query->first();
			
			unset($query);

		}catch(DatabaseException $e) {

			Log::create('check admission', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		return [
			'data' => $row
		];

	}

}// end class

Kernel::handle(
	AdmissionApi::class, 
		ProviderAuth::class, 
			NULL //FaqMeta::class
);