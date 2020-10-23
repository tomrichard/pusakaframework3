<?php 
namespace Pusaka\Library;

use Pusaka\Http\Request;
use Pusaka\Http\Response;

use Pusaka\Database\Manager as Database;
use Pusaka\Database\DatabaseException;

use Pusaka\Microframework\Log;

use closure;

use ReflectionMethod;

class Datatable {

	private $database 	= 'default';
	private $table 		= '';
	private $select		= [];
	private $filter 	= [];
	private $options 	= [];
	private $additional = NULL;
	private $where 		= NULL;

	private $first 		= '';

	private $index 		= 0;
	private $pages 		= 0;
	private $count 		= 0;
	private $limit 		= 10;
	private $having		= [];


	function __construct() {
		
		$this->options 	= [
			'limit' 	=> '_limit',
			'page' 		=> '_page',
			'sort'		=> '_sort',
			'untouch'	=> []
		];

	}

	function on($database) {
		$this->database = $database;
		return $this;
	}

	function table($table) {
		$this->table = $table;
		return $this;
	}

	function select($select) {
		$this->select = $select;
		return $this;
	}

	function options($options) {

		foreach ($options as $key => $value) {
			$this->options[$key] = $value;
		}

		return $this;
	}

	function where($closure) {

		$this->where = $closure;

		return $this;

	}

	function additional($closure) {

		$this->additional = $closure;

		return $this;

	}

	function defineHaving($key, $select_key) {

		if(isset($this->select[$select_key])) {
			$this->having[$key] = $this->select[$select_key];
		};

	}

	private function __filter($query) {

		if($this->where instanceof closure) {
			$query->where($this->where);
		}

		if($this->additional instanceof closure) {
			$funct 	= $this->additional;
			$funct($query);
		}

		$get = Request::get();

		if(!empty($get)) {

			foreach ($get as $key => $value) {

				if(preg_match('/^_/', $key)) {
					continue;
				}

				if(in_array($key, $this->options['untouch'])) {
					continue;
				}

				$field = $this->filter[$key] ?? NULL;

				if(!is_null($field)) {

					$likes = explode(' ', $value);

					$query->where(function($query) use ($likes, $field) {

						foreach ($likes as $like) {
							
							$query->whereOR($field, 'LIKE', '%'.$like.'%');

						}

					
					});

				}

			}

		}

		return $query;

	}

	private function __paging($query) {

		$get = Request::get();

		if(!empty($get)) {

			$limit 		= 10;
			$page 		= 1;
			$sort 		= [];

			$limitKey 	= $this->options['limit'];
			$pageKey 	= $this->options['page'];
			$sortKey 	= $this->options['sort'];

			if( is_array($get[$sortKey] ?? NULL) ) {

				$sort 	= $get[$sortKey];
				
				foreach ($sort as $key => $value) {

					$value = strtolower($value);
					
					if(in_array($value, ['desc', 'asc'])) {
						$query->orderBy($key, $value);
					}
				
				}

			}

			$get[$limitKey] = (int) ($get[$limitKey] ?? 10);
			$get[$pageKey] 	= (int) ($get[$pageKey] ?? 1);

			if( is_int($get[$pageKey] ?? NULL) ) {
				
				$page 	= $get[$pageKey];
			
			}

			$page 		= (($page-1) < 0 ) ? 0 : ($page-1);

			if( is_int($get[$limitKey] ?? NULL) ) {
				
				$limit 	= $get[$limitKey];

				$offset = $page * $limit;
				
				$query->limit($limit, $offset);

			}

			$this->index = $page + 1;

			$this->limit = $limit;

		}

		return $query;

	}

	function calculate() {

		$having 		= $this->having;

		$query 			= Database::on($this->database)->builder();

		$query
    		->select(function($query) use ($having) {

    			$query
            		->alias('count', $query->count($this->first) );

            	if(!empty($having)) {
            		
            		foreach ($having as $key => $funct) {
            			$query
	            			->alias($key, $funct);
            		}
            	
            	}

    		});

    	$query
    		->from($this->table);

    	if($this->where instanceof closure) {
			$query->where($this->where);
		}

		$query 			= $this->__filter($query);

		$count 			= $query->first();

		unset($query);

		$count 			= $count->count ?? 0;

		$count 			= intval($count);

		$this->limit 	= ( $this->limit == 0 ) ? 10 : $this->limit;

		$pages 			= $count / $this->limit;

		$mod 			= $count % $this->limit;

		$pages 			= ($mod > 0) ? intval($pages) + 1 : intval($pages);

		$this->pages 	= $pages;
		$this->count 	= $count;

	}

	function json($is_debug = FALSE) {

		$query 	= Database::on($this->database)->builder();

		$first 	= TRUE;

		$select = []; 

		foreach ($this->select as $key => $value) {

			if($first) {
				$this->first = $value;
				$first = FALSE;
			}

			if(is_string($value)) {
				
				if(preg_match('/@string:(.+)/', $value, $match) > 0) {
					$select[] 			= "'".$match[1]."'" . ' AS ' . $key;
					$this->filter[$key] = "'".$match[1]."'";
				}else {
					$select[] = $value . ' AS ' . $key;
					$this->filter[$key] = $value;
				}

			}
			if(is_numeric($value)) {

				$select[] 			= $value . ' AS ' . $key;
				$this->filter[$key] = $value;
			
			}
			else if($value instanceof closure) {

				$nested   = function($query) use ($key, $value) {
					
					if(method_exists($query, 'alias')) {
						$query
			            	->alias($key, $value);
					}else {
						$query
			            	->as($key, $value);
					}

				};

				$select[] = $nested;

				$this->filter[$key] = $nested;

			}

		}

		foreach ($select as $value) {
			$query->select($value);
		}

		$query->from($this->table);

		$query 		= $this->__filter($query);

		$query 		= $this->__paging($query);

		if($is_debug) {
			$response 	= $query->getQuery();
			var_dump($response);
			die();
		}

		try {
		
			$response 	= $query->get();
		
		}catch(DatabaseException $e) {

			Log::create('Datatable', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}catch(Error $e) {

			Log::create('Datatable', __FILE__, $e->getMessage(), $e->getTrace());

			Response::http(500, 'Server Internal Error.');

		}

		unset($query);

		$this->calculate();

		return [
			'index' 	=> $this->index,
			'pages' 	=> $this->pages,
			'count'		=> $this->count,
			'data' 		=> $response
		];

	}
	

} 