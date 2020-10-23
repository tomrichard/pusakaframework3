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

class DashboardApi extends Controller 
{

	// function hasleave( $type = NULL ) {

	// 	Loader::lib('Excel/Excel');
	
	// 	header("Access-Control-Allow-Origin: *");



	// 	}catch(DatabaseException $e) {

	// 		Log::create('attendance/export', __FILE__, $e->getMessage(), $e->getTrace());

	// 		Response::http(500, 'Server Internal Error.');

	// 	}catch(\Error $e) {
	// 		var_dump($e);
	// 	}catch(\Exception $e) {
	// 		var_dump($e->getMessage());
	// 	}

	// }

	function lateness_chart() {

		header("Access-Control-Allow-Origin: *");

		$month_now 		= ((int) date('m'));
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

			$query = Database::on('default')->builder();

			// absent employee
			//-----------------------------------------------------
			$absent_employee = function($query) use ($vars) {

				$query
					->select('empid')
					->tableAlias('AbsentTable', function($query) use ($vars) {
						// AbsentTable
						//-----------------------------
						$query
							->select(
								"'absent' AS category",
								"a.employee AS empid"
							)->from('attendance a')
							->joinLeft('employee e', 'e.id', 'a.employee')
							->where(
								'a.`date` BETWEEN '
									."'".$vars['from']
									."'".' AND '
									."'".$vars['to']."'"
							)
							->where('a.status', 1)
							->where('e.status', 1)
							->groupBy('`empid`');
					});

			};

			// late employee
			//-----------------------------------------------------
			$late_employee = function($query) use ($vars) {

				$query
					->select(
						//"'late' AS category",
						"a.employee AS empid"
					)->from('attendance a')
					->joinLeft('employee e', 'e.id', 'a.employee')
					->where(
						'a.`date` BETWEEN '
							."'".$vars['from']
							."'".' AND '
							."'".$vars['to']."'"
					)
					->where('a.`arrive_late`', '>', 0)
					->where('e.status', 1)
					->groupBy('`empid`');

			};

			// null_arrive_employee
			//-----------------------------------------------------
			$null_arrive_employee = function($query) use ($vars) {

				$query
					->select(
						"a.employee AS empid"
					)->from('attendance a')
					->joinLeft('employee e', 'e.id', 'a.employee')
					->where(
						'a.`date` BETWEEN '
							."'".$vars['from']
							."'".' AND '
							."'".$vars['to']."'"
					)
					->where('a.`arrive_late` IS NULL')
					->where('a.`status`', 2)
					->where('e.status', 1)
					->groupBy('`empid`');

			};

			// has_no_absent_employee
			//-----------------------------------------------------
			$has_no_absent_employee = function($query) use ($vars) {

				$query
					->select('id')
					->tableAlias('ExAttTable', function($query) use ($vars){

						$query
							->select(
								"id", 
								"(
									NOT EXISTS("."
										SELECT a.id FROM attendance a WHERE a.employee = e.id
										AND a.`date` BETWEEN "
											."'".$vars['from']
											."'".' AND '
											."'".$vars['to']."'"
											."
									)
								) AS ex_attendance"
							)
							->from('employee e')
							->where('`status`', 1);

					})
					->where('ex_attendance', 1);

			};

			$queries = compact('absent_employee', 'late_employee', 'null_arrive_employee', 'has_no_absent_employee');

			// perfect employee
			//-----------------------------------------------------
			$perfect_employee = function($query) use($vars, $queries) {

				$query
					->select('id')
					->from('employee')
					->where('`status`', 1)
					->where(function($query) use ($queries) {

							$query
								->whereNotIn('id', function($query) use ($queries) {

									$query
										->select('id')
										->from('employee')
										->where('`status`', 1)
										->where(function($query) use ($queries) {

											$query->whereIn('id', $queries['absent_employee']);

											$query->whereOr(function($query) use ($queries) {
											
												$query->whereIn('id', $queries['late_employee']);
											
											});

											$query->whereOr(function($query) use ($queries) {

												$query->whereIn('id', $queries['null_arrive_employee']);

											});

											$query->whereOr(function($query) use ($queries) {

												$query->whereIn('id', $queries['has_no_absent_employee']);

											});

										});

							});

					});

			};

			// echo '<pre>';

			// $query
			// 	->select('*')
			// 	->tableAlias('PerfectEmployee', $perfect_employee);

			// var_dump($query->get());
			// die();

			$count_perfect_employee = function($query) use ($perfect_employee) {

				$query
					->select('count(id)')
					->tableAlias('tempTablePerfect', $perfect_employee);

			};

			$count_absent_employee 		= function($query) use ($absent_employee) {

				$query
					->select('count(*)')
					->tableAlias('tempTableAbsent', $absent_employee);

			};

			$count_no_tap_in_employee 	= function($query) use ($null_arrive_employee) {

				$query
					->select('count(*)')
					->tableAlias('tempTableNoTapIn', $null_arrive_employee);

			};

			// build counter
			$query
				->select(function($query) use (
						$count_perfect_employee, 
							$count_absent_employee,
								$count_no_tap_in_employee ) {

					$query->alias('perfect_employee', $count_perfect_employee);

					$query->alias('absent_employee', $count_absent_employee);

					$query->alias('no_tap_in_employee', $count_no_tap_in_employee);

				});	

			return $query->first();

		}catch(DatabaseException $e) {

			Log::create('dashboard/lateness_chart', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

	}

}// end class

Kernel::handle(
	DashboardApi::class 
		#,roviderAuth::class, 
			#NULL //FaqMeta::class
);