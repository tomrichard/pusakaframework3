<?php 
use Pusaka\Hmvc\Controller;

use Pusaka\Http\Response;

use Pusaka\Database\Manager;

use Pusaka\Utils\IOUtils;

/**
 *
 * @define::middleware: admin, token
 * 
 */
class OneCS extends Controller {

	function index( $request ) {

		return [];

	}

	function alpha( $request ) {

		$hello = $request->input->hello;

		$request->translate([
			'param:0' => 'id'
		], $param);

		$this->load->submit([
			'hello' => "Hello World"
		]);

		return $this->load->view(NULL, compact('param'));

	}

	function upload( $request ) {

		if( $request->file->has('upload') ) {
			
			$file 	= $request->file->get('upload');

			$name 	= $file->getRandomName();

			$file->move(storage('public/upload'), $name);

			d(
				$file->name, 
				$file->ext,
				$file->type
			);

			// $files 	= $request->file->multiple('upload');

			// $files->forEach(function($file){
				
			// 	$file->move( '' );

			// });

		}

		return $this->load->view('upload');

	}

	/**
	 *
	 * @define::middleware: admin, token, freeze
	 * @define::method: post, get, put, delete
	 *
	 */
	function aloooo( $request, $alpha ) {
		
		var_dump( $middleware );

		return ['hello' => 'world']; //Response::code(400)->json();

	}

	function database( $request ) {

		$manager = Manager::on('default');

		$manager->open();

		$result  = $manager->query("SELECT * FROM employee");

		$row 	 = $result->fetch(function($row) {

			$row->a = uniqid();

		});

		$manager->close();

		return $row;

	}

	function builder( $request ) {

		$query = Manager::on('default')->builder();

		try {

			$query->transaction();

			$query
				->select('*')
				->table('a', function($query) {
					
					$query
						->select('*')
						->table('employee');

					$query
						->join('employee');
				
				});

			$row   = $query->get();

			$query
				->table('employee')
				->insert([
					'Id' 	=> date('YmdHis').uniqid(),
					'Name'	=> date('YmdHis').uniqid()
				]);

			$query
				->select('*')
				->table('a', function($query) {
					
					$query
						->select('*')
						->table('employee');
				
				});

			$row   = $query->get();
			
			$query->commit();

			return $row;

		}catch(Exception $e) {

			$query->rollback();

			return [];

		}

	}

	function utils( $request ) {

		$from 	= ROOTDIR . 'storage/public/samples';
		$dest 	= ROOTDIR . 'storage/public/samples2';

		$iscopy = IOUtils::directory($from)->copy($dest);

	}

}