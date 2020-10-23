<?php 
namespace App\Rest;

/** 
|---------------------------------------------------------
| Create 	: 2019-10-18 11:19:21
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
use Pusaka\Microframework\Loader;

use Pusaka\Library\Mailer;

MetaRequest::using('help/contact', '1.0');
AuthRequest::using('provider', 		'1.0');

composer();

class ContactApi extends Controller 
{

	/**
      * Create Contact.
      * @param
      * @return
      */
	function mailing() {

		Loader::lib('mailer/mailer');

		header("Access-Control-Allow-Origin: *");

		try {

			$mail = Mailer::on('default');

			$mail->setFrom('admin@aa-international.co.id', 'Mediweb');
			$mail->addAddress('tom.richard@aa-international.co.id', 'Tom Richard');
			$mail->addBCC('henro.sutrisno@aa-international.co.id', 'Wulan Herawati');

			$mail->setTemplate('contact', 'text');

			$mail->setData([
				"email" 	=> Request::post('email'),
				"message"	=> Request::post('message')
			]);

			$mail->setSubject(Request::post('subject'));

			$mail->send();

			return [
				'send' => true
			];

		}catch(Exception $e) {

			return [
				'send' 	=> false,
				'error'	=> $e->getMessage()
			];

		}

	}

}

Kernel::handle(ContactApi::class, 
	ProviderAuth::class, 
		ContactMeta::class
);