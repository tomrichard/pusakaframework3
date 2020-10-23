<?php 
namespace App\Rest;

use Pusaka\Rest\MetaRequest;

class FaqMeta extends MetaRequest {

	const create = [
		"url"	 	=> "/master/faq.php/create",
		"desc"		=> "Create new FAQ.",
		"method" 	=> "POST",
		"params" 	=> [
			"question"  => ["text", "0"],
			"answer"  	=> ["text", "0"]
		],
		"query"	 => [],
		"path"	 => []
	];

	const update = [
		"url"	 => "/master/faq.php/update/{id}",
		"desc"	 => "Update FAQ.",
		"method" => "PUT",
		"params" => [
			"question"  => ["text", "0"],
			"answer"  	=> ["text", "0"]
		],
		"query"	 => [],
		"path"	 => [
			"id"		=> ["int", "11"]
		]
	];

	const delete = [
		"url"	 => "/master/faq.php/delete/{id}",
		"method" => "DELETE",
		"params" => [],
		"query"	 => [],
		"path"	 => [
			"id"  => ["int", "11"]
		]
	];

	const filter = [
		"url"	 => "/master/faq.php/filter?{query}",
		"desc" 	 => "Filter and get query of FAQ.",
		"method" => "GET",
		"params" => [],
		"query"	 => [
			"_limit"	=> ["int", "5", true],
			"_page"		=> ["int", "5", true],
			"_sort"		=> ["json", "255", true],
			"question"  => ["string", "255", true],
			"answer"  	=> ["string", "255", true]
		],
		"path"	 => []
	];

}