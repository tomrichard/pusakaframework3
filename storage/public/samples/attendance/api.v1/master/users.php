<?php 
namespace App\Rest;

include('../../index.php');

use Pusaka\Rest\Kernel;
use Pusaka\Rest\Controller;
use Pusaka\Rest\MetaRequest;
use Pusaka\Rest\AuthRequest;

use Pusaka\Http\Request;

use Pusaka\Database\Manager as Database;

MetaRequest::using('master/users', '1.0');
AuthRequest::using('member', '1.0');

class UsersApi extends Controller {

	/**
      * Create users.
      * @param string $a
      * @return array
      */
	function create() {

          $query = Database::on('default')->builder();

          $query
               ->select('Id', 'Name', 'Role')
               ->from('users');

          var_dump($query->get());

          unset($query);

		var_dump(Request::post());
		//$this->request->is('POST') ? '';

	}

	/**
      * Create users.
      * @method post
      * @param string $a 
      * @return array
      */
	function update() {

          // PUT

	}

	/**
      * Create users.
      * @param string $a, 
      * @return array
      */
	function delete($id) {

          // DELETE
          var_dump($id);

	}

     function filter() {

          $data = [
               ['Id' => '134', 'Name' => 'Jones'],
               ['Id' => '138', 'Name' => 'Amat'],
               ['Id' => '245', 'Name' => 'Oke']
          ];

          $query = Request::get();

          $idx   = -1;

          foreach($query as $key => $value) {
               
               $found = array_search($value, array_column($data, $key));
               
               if(!is_bool($found)) {
                    $idx = $found;
               }

          }

          if($idx < 0) {
               return ['Not found.'];
          }

          return $data[$idx];

     }

     function media($category, $user) {

          // PUT
          // return [
          //      "ContentUrl" => file_get_contents('php://input'),
          // ];
          $data = file_get_contents('php://input');

          echo var_dump($data);
          //file_put_contents(ROOTDIR . 'in.png', $data);

     }

}

Kernel::handle(UsersApi::class, MemberAuth::class, UsersMeta::class);