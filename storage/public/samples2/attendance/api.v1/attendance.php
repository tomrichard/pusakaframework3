<?php 
namespace App\Rest;

/** 
|---------------------------------------------------------
| Create 	: 2019-10-07 08:54:21
| File 		: <{filename}>
| Author 	: @author
|---------------------------------------------------------
*/

include('../index.php');

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
use Pusaka\Library\Excel;

use DateTime;
use DateInterval;

use Pusaka\Utils\IOUtils;

#MetaRequest::using('master/faq', 	'1.0');
#AuthRequest::using('provider', 		'1.0');

class AttendanceApi extends Controller 
{

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function workday( $employee_id ) {

		Loader::lib('datatable/datatable');

		header("Access-Control-Allow-Origin: *");

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;

		$range_min 		= 21;
		$range_max 		= 20;

		$query = Database::on('default')->builder();

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);

		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to', 'employee_id');

		try {

			$Datatable 		= new Datatable();

			$Datatable
				->on('default')
				->table('attendance a LEFT JOIN employee e ON e.id=a.employee')
				->select([
					'nik'
						=> 'e.nik',
					'name'
						=> 'e.name',
					'`date`'
						=> 'a.`date`',
					'`in`'
						=> 'a.`in`',
					'`out`'
						=> 'a.`out`',
					'`arrive`'
						=> 'a.`arrive`',
					'`return`'
						=> 'a.`return`',
					'`status`'
						=> 'a.`status`'
				])
				->options([
					'limit' 	=> '_limit',
					'sort'		=> '_sort',
					'untouch'	=> []
				]);

			$Datatable->where(function($query) use($vars){

				$query->where('e.nik', $vars['employee_id']);

				$query->where(
							'a.`date` BETWEEN '
								."'".$vars['from']
								."'".' AND '
								."'".$vars['to']."'"
						);

			});

			$Datatable->additional(function($query) use($vars) {

				$query->orderBy('date', 'desc');

			});

			return $Datatable->json();

		}catch(DatabaseException $e) {
			
			Log::create('info claim', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}// end workday

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function absent( $employee_id ) {

		Loader::lib('datatable/datatable');

		header("Access-Control-Allow-Origin: *");

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;

		$range_min 		= 21;
		$range_max 		= 20;

		$query = Database::on('default')->builder();

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);

		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to', 'employee_id');

		try {

			$Datatable 		= new Datatable();

			$Datatable
				->on('default')
				->table('attendance a LEFT JOIN employee e ON e.id=a.employee')
				->select([
					'nik'
						=> 'e.nik',
					'name'
						=> 'e.name',
					'`date`'
						=> 'a.`date`',
					'`in`'
						=> 'a.`in`',
					'`out`'
						=> 'a.`out`',
					'`arrive`'
						=> 'a.`arrive`',
					'`return`'
						=> 'a.`return`',
					'`status`'
						=> 'a.`status`',
					'`remarks`'
						=> 'a.`remarks`'
				])
				->options([
					'limit' 	=> '_limit',
					'sort'		=> '_sort',
					'untouch'	=> []
				]);

			$Datatable->where(function($query) use($vars){

				$query->where('e.nik', $vars['employee_id']);

				$query->where(
							'a.`date` BETWEEN '
								."'".$vars['from']
								."'".' AND '
								."'".$vars['to']."'"
						);

				$query->where('a.`status`', 1);

			});

			$Datatable->additional(function($query) use($vars) {

				$query->orderBy('date', 'desc');

			});

			return $Datatable->json();

		}catch(DatabaseException $e) {
			
			Log::create('info claim', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}// end workday

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function lateday( $employee_id ) {

		Loader::lib('datatable/datatable');

		header("Access-Control-Allow-Origin: *");

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;

		$range_min 		= 21;
		$range_max 		= 20;

		$query = Database::on('default')->builder();

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);

		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to', 'employee_id');

		try {

			$Datatable 		= new Datatable();

			$Datatable
				->on('default')
				->table('attendance a LEFT JOIN employee e ON e.id=a.employee')
				->select([
					'nik'
						=> 'e.nik',
					'name'
						=> 'e.name',
					'`date`'
						=> 'a.`date`',
					'`in`'
						=> 'a.`in`',
					'`out`'
						=> 'a.`out`',
					'`arrive`'
						=> 'a.`arrive`',
					'`return`'
						=> 'a.`return`',
					'`status`'
						=> 'a.`status`',
					'`late`'
						=> 'a.`arrive_late` - 15'
				])
				->options([
					'limit' 	=> '_limit',
					'sort'		=> '_sort',
					'untouch'	=> []
				]);

			$Datatable->where(function($query) use($vars){

				$query->where('e.nik', $vars['employee_id']);

				$query->where(
							'a.`date` BETWEEN '
								."'".$vars['from']
								."'".' AND '
								."'".$vars['to']."'"
						);

				$query->where('shift', '!=', '-99');
				$query->where('arrive_late > 15');

			});

			$Datatable->additional(function($query) use($vars) {

				$query->orderBy('date', 'desc');

			});

			return $Datatable->json();

		}catch(DatabaseException $e) {
			
			Log::create('info claim', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}// end lateday

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function overtime( $employee_id ) {

		Loader::lib('datatable/datatable');

		header("Access-Control-Allow-Origin: *");

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;

		$range_min 		= 21;
		$range_max 		= 20;

		$query = Database::on('default')->builder();

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);

		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to', 'employee_id');

		try {

			$Datatable 		= new Datatable();

			// $query
			// 	->select(
			// 		"(TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600) AS overtime", 
			// 		"employee_id"
			// 	)
			// 	->from('form_overtime')
			// 	->where(
			// 		'ot_time_from BETWEEN '
			// 			."'".$vars['from']
			// 			."'".' AND '
			// 			."'".$vars['to']."'"
			// 	)
			// 	->where('approved_head_status', 3);

			$Datatable
				->on('default')
				->table('form_overtime ot LEFT JOIN employee e ON e.id=ot.employee_id')
				->select([
					'nik'
						=> 'e.nik',
					'`name`'
						=> 'e.name',
					'`head`'
						=> 'ot.`approved_head_by`',
					'`from`'
						=> 'ot.`ot_time_from`',
					'`to`'
						=> 'ot.`ot_time_to`',
					'`task`'
						=> 'ot.`ot_task_done`',
					'`overtime`'
						=> '(TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600)'
				])
				->options([
					'limit' 	=> '_limit',
					'sort'		=> '_sort',
					'untouch'	=> []
				]);

			$Datatable->where(function($query) use($vars) {

				$query->where('e.nik', $vars['employee_id']);

				$query->where(
							'ot.`ot_time_from` BETWEEN '
								."'".$vars['from']
								."'".' AND '
								."'".$vars['to']."'"
						);

				$query->where('approved_head_status', 3);

			});

			$Datatable->additional(function($query) use($vars) {

				$query->orderBy('ot.ot_time_from', 'desc');

			});

			return $Datatable->json();

		}catch(DatabaseException $e) {
			
			Log::create('info claim', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}// end overtime

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function leave( $employee_id ) {

		Loader::lib('datatable/datatable');

		header("Access-Control-Allow-Origin: *");

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;

		$range_min 		= 21;
		$range_max 		= 20;

		$query = Database::on('default')->builder();

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);

		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to', 'employee_id');

		try {

			$Datatable 		= new Datatable();

			// $query
			// 	->select(
			// 		"(TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600) AS overtime", 
			// 		"employee_id"
			// 	)
			// 	->from('form_overtime')
			// 	->where(
			// 		'ot_time_from BETWEEN '
			// 			."'".$vars['from']
			// 			."'".' AND '
			// 			."'".$vars['to']."'"
			// 	)
			// 	->where('approved_head_status', 3);

			$Datatable
				->on('default')
				->table('form_leave_application lv LEFT JOIN employee e ON e.id=lv.employee_id')
				->select([
					'nik'
						=> 'e.nik',
					'`name`'
						=> 'e.name',
					'`head`'
						=> 'lv.`approved_head_by`',
					'`from`'
						=> 'lv.`leave_date_from`',
					'`to`'
						=> 'lv.`leave_date_to`',
					'`reason`'
						=> 'lv.`leave_reason`',
					'`leave`'
						=> 'lv.`leave_day`'
				])
				->options([
					'limit' 	=> '_limit',
					'sort'		=> '_sort',
					'untouch'	=> []
				]);

			$Datatable->where(function($query) use($vars){

				$query->where('e.nik', $vars['employee_id']);

				$query->where(
							'lv.`leave_date_from` BETWEEN '
								."'".$vars['from']
								."'".' AND '
								."'".$vars['to']."'"
						);

				$query->where('approved_hrd_status', 3);

			});

			$Datatable->additional(function($query) use($vars) {

				$query->orderBy('lv.leave_date_from', 'desc');

			});

			return $Datatable->json();

		}catch(DatabaseException $e) {
			
			Log::create('attendance/leave', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}// end overtime

	/**
      * Create new Faq record.
      * @param string client, string question
      * @return array
      */
	function summary( $path = NULL ) {

		Loader::lib('datatable/datatable');

		header("Access-Control-Allow-Origin: *");

		//$query = Database::on('default')->builder();

		$employee 		= Request::get('employee');
		$employeeid 	= Request::get('employeeid');
		$stat 			= Request::get('stat');

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;
		
		$range_min 		= 21;
		$range_max 		= 20;

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);

		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to');

		try {

			$Datatable 		= new Datatable();

			$Datatable
				->on('default')
				->table('employee e')
				->select([

					'id'
						=> 'e.id',

					'nik'
						=> 'e.nik',
					
					'name'
						=> 'e.name', 

					'month_selected'
						=> $month_now,

					'date_from'
						=> $range_min,

					'date_to'
						=> $range_max,

					'workday' 
						=> 
							function($query) use ($vars) {
								$query
									->select('COUNT(id)')
									->from('attendance')
									->where('employee = e.id')
									->where(
										'attendance.date BETWEEN '
										."'".$vars['from']
										."'".' AND '
										."'".$vars['to']."'"
									)->whereIn('`status`', [2, 3]);

							},

					'absent' 
						=> 
							function($query) use ($vars) {
								$query
									->select('COUNT(id)')
									->from('attendance')
									->where('employee = e.id')
									->where(
										'attendance.date BETWEEN '
										."'".$vars['from']
										."'".' AND '
										."'".$vars['to']."'"
									)->whereIn('`status`', [1]);

							},

					'late' 
						=> 
							function($query) use ($vars) {
								$query
									->select('COUNT(id)')
									->from('attendance')
									->where('employee = e.id')
									->where(
										'attendance.date BETWEEN '
										."'".$vars['from']
										."'".' AND '
										."'".$vars['to']."'")
									->where('shift', '!=', '-99')
									->where('arrive_late > 15');

							},

					'overtime' 
						=> 
							function($query) use ($vars) {

								// SELECT SUM(overtime) AS overtime FROM (
								// 	SELECT 
								// 		TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600 AS overtime FROM form_overtime
								// 		WHERE 
								// 			ot_time_from BETWEEN date('2019-09-21 00:00:00') AND date('2019-10-21 00:00:00')
								// 		AND employee_id = 423
								// ) TableOvertime

								$query
									->select('IF(SUM(overtime) IS NULL, 0, SUM(overtime))')
									->tableAlias('OvertimeTable', function($query) use($vars) {

										$query
											->select(
												"(TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600) AS overtime", 
												"employee_id"
											)
											->from('form_overtime')
											->where(
												'ot_time_from BETWEEN '
													."'".$vars['from']
													."'".' AND '
													."'".$vars['to']."'"
											)
											->where('approved_head_status', 3);
											//->where('approved_hrd_status', 3);

									})
									->where('OvertimeTable.employee_id = e.id');

							},

					'leave_day' 
						=> 
							function($query) use ($vars) {

								$query
									->select('IF(SUM(leave_day) IS NULL, 0, SUM(leave_day))')
									->tableAlias('LeaveTable', function($query) use($vars) {

										$query
											->select(
												'leave_day',
												"employee_id"
											)
											->from('form_leave_application')
											->where(
												'leave_date_from BETWEEN '
													."'".$vars['from']
													."'".' AND '
													."'".$vars['to']."'"
											)
											->where('form_status', 3);
									})
									->where('LeaveTable.employee_id = e.id');

							}

			])
			->options([
				'limit' 	=> '_limit',
				'sort'		=> '_sort',
				'untouch'	=> []
			]);

			if($path !== NULL) {

				$Datatable->defineHaving('overtime', 'overtime');
				$Datatable->defineHaving('leave_day', 'leave_day');

				$Datatable->additional(function($query) use ($path) {

					if($path == 'overtime') {
						$query->having('overtime > 0');
					}

					if($path == 'leave') {
						$query->having('leave_day > 0');
					}

				});

			}

			if(in_array($stat, ['active', 'terminate', 'resign', 'all'])) {

				if($stat == 'active') {
					
					$Datatable->where(function($query){

						$query->where('`status`', '1');

					});

				}

				if($stat == 'terminate') {

					$Datatable->where(function($query){

						$query->where('`status`', '2');

					});

				}

				if($stat == 'resign') {

					$Datatable->where(function($query){

						$query->where('`status`', '3');

					});

				}

			}

			return $Datatable->json();

		}catch(DatabaseException $e) {
			
			Log::create('attendance/summary', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}

	function export( $type = NULL ) {

		composer();

		Loader::lib('Excel/Excel');
	
		header("Access-Control-Allow-Origin: *");

		$map = [
			'A' => 'nik',
			'B'	=> 'name',
			'C' => 'month_selected',
			'D'	=> 'workday',
			'E'	=> 'absent',
			'F'	=> 'late',
			'G'	=> 'overtime',
			'H'	=> 'leave_day'
		];

		$path 			= Request::get('path');
		$employee 		= Request::get('employee');
		$employeeid 	= Request::get('employeeid');
		$stat 			= Request::get('stat');

		$month_now 		= ((int) Request::get('month'));
		$month_before 	= ($month_now - 1) == 0 ? 12 : $month_now - 1;
		
		$range_min 		= 21;
		$range_max 		= 20;

		$month_now 		= str_pad($month_now, 2, '0', STR_PAD_LEFT);
		$month_before 	= str_pad($month_before, 2, '0', STR_PAD_LEFT);
		
		$year_from 		= ($month_before == 12) ? ( ((int) date('Y')) - 1 ) : date('Y');
		
		$from 			= date($year_from . '-'.$month_before.'-'.$range_min);
		$to 			= date('Y-'.$month_now.'-'.$range_max);

		$vars 			= compact('from', 'to');

		$mapSelect 		= [
					'nik'
						=> 'e.nik',
					
					'name'
						=> 'e.name', 

					'month_selected'
						=> $month_now,

					'date_from'
						=> $range_min,

					'date_to'
						=> $range_max,

					'workday' 
						=> 
							function($query) use ($vars) {
								$query
									->select('COUNT(id)')
									->from('attendance')
									->where('employee = e.id')
									->where(
										'attendance.date BETWEEN '
										."'".$vars['from']
										."'".' AND '
										."'".$vars['to']."'"
									)->whereIn('`status`', [2, 3]);

							},

					'absent' 
						=> 
							function($query) use ($vars) {
								$query
									->select('COUNT(id)')
									->from('attendance')
									->where('employee = e.id')
									->where(
										'attendance.date BETWEEN '
										."'".$vars['from']
										."'".' AND '
										."'".$vars['to']."'"
									)->whereIn('`status`', [1]);

							},

					'late' 
						=> 
							function($query) use ($vars) {
								$query
									->select('COUNT(id)')
									->from('attendance')
									->where('employee = e.id')
									->where(
										'attendance.date BETWEEN '
										."'".$vars['from']
										."'".' AND '
										."'".$vars['to']."'")
									->where('shift', '!=', '-99')
									->where('arrive_late > 15');

							},

					'overtime' 
						=> 
							function($query) use ($vars) {

								// SELECT SUM(overtime) AS overtime FROM (
								// 	SELECT 
								// 		TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600 AS overtime FROM form_overtime
								// 		WHERE 
								// 			ot_time_from BETWEEN date('2019-09-21 00:00:00') AND date('2019-10-21 00:00:00')
								// 		AND employee_id = 423
								// ) TableOvertime

								$query
									->select('IF(SUM(overtime) IS NULL, 0, SUM(overtime))')
									->tableAlias('OvertimeTable', function($query) use($vars) {

										$query
											->select(
												"(TIME_TO_SEC(TIMEDIFF(ot_time_to, ot_time_from))/3600) AS overtime", 
												"employee_id"
											)
											->from('form_overtime')
											->where(
												'ot_time_from BETWEEN '
													."'".$vars['from']
													."'".' AND '
													."'".$vars['to']."'"
											)
											->where('approved_head_status', 3);
											//->where('approved_hrd_status', 3);

									})
									->where('OvertimeTable.employee_id = e.id');

							},

					'leave_day' 
						=> 
							function($query) use ($vars) {

								$query
									->select('IF(SUM(leave_day) IS NULL, 0, SUM(leave_day))')
									->tableAlias('LeaveTable', function($query) use($vars) {

										$query
											->select(
												'leave_day',
												"employee_id"
											)
											->from('form_leave_application')
											->where(
												'leave_date_from BETWEEN '
													."'".$vars['from']
													."'".' AND '
													."'".$vars['to']."'"
											)
											->where('form_status', 3);
									})
									->where('LeaveTable.employee_id = e.id');

							}
				];

		try {
		
			$query 	= Database::on('default')->builder();

			$query
				->select(function($query) use ($mapSelect){

					foreach ($mapSelect as $key => $funct) {
						$query->alias($key, $funct);
					}

				})
				->from('employee e');

			if($path !== NULL) {

				if($path == 'overtime') {
					$query->having('overtime > 0');
				}

				if($path == 'leave') {
					$query->having('leave_day > 0');
				}

			}

			if(in_array($stat, ['active', 'terminate', 'resign', 'all'])) {

				if($stat == 'active') {
					$query->where('`status`', '1');
				}

				if($stat == 'terminate') {
					$query->where('`status`', '2');
				}

				if($stat == 'resign') {
					$query->where('`status`', '3');
				}

			}

			$rows 	= $query->get();

			// $map = [
			// 	'A' => 'nik',
			// 	'B'	=> 'name',
			// 	'C' => 'month_selected',
			// 	'D'	=> 'workday',
			// 	'E'	=> 'absent',
			// 	'F'	=> 'late',
			// 	'G'	=> 'overtime',
			// 	'H'	=> 'leave_day'
			// ];

			$file 	= 'export_summary_attendance_'.date('YmdHis').'.xlsx';

			$export = Excel::export($saveto = ROOTDIR . 'storage/' . $file);

			$export->setMap($map);

			$export->createSheet('exports');

			// create header
			//--------------------------------------
			$export->setValue('nik', 1, 			'NIK');
			$export->setValue('name', 1, 			'Employee Name');
			$export->setValue('month_selected', 1, 	'Month');
			$export->setValue('workday', 1, 		'Workday (days)');
			$export->setValue('absent', 1, 			'Absent (days)');
			$export->setValue('late', 1, 			'Late (days)');
			$export->setValue('overtime', 1, 		'Overtime (hours)');
			$export->setValue('leave_day', 1, 		'Leave (days)');
			//--------------------------------------

			foreach($rows as $idx => $row) {
				
				foreach($row as $key => $val) {
					$export->setValue($key, ($idx+2), $val);
				}

			}

			if($export->save()) {
				
				$size = filesize($saveto);

				header('Content-Disposition: inline; filename='.basename($saveto));
				header('Content-Length: '.$size);
				header('Accept-Ranges: bytes');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Connection: close');

				readfile($saveto);

				die();

			}else {
				Response::http(404, 'File not found.');
			}

		}catch(DatabaseException $e) {

			Log::create('attendance/export', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}catch(\Error $e) {
			var_dump($e);
		}catch(\Exception $e) {
			var_dump($e->getMessage());
		}

	}

}// end class

Kernel::handle(
	AttendanceApi::class 
		#,roviderAuth::class, 
			#NULL //FaqMeta::class
);