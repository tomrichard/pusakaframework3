<?php 
namespace App\Rest;

use Pusaka\Rest\MetaRequest;

class ContactMeta extends MetaRequest {

	const mailing = [
		"url"	 	=> "/help/contact.php/mailing",
		"desc"		=> "Send email message to admin helpdesk.",
		"method" 	=> "POST",
		"params" 	=> [
			"email"  	=> ["varchar", "255"],
			"subject"  	=> ["varchar", "255"],
			"message"	=> ["text", "0"]
		],
		"query"	 => [],
		"path"	 => []
	];

}