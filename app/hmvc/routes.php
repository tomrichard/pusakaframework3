<?php 

$router->get('/', 'welcome@index');

// $router->get('/{id:string}', 'welcome@index');

// $router->get('/{@method}/{index:number}', 'welcome');

// $router->get('/alpha', 'welcome@alpha');

// $router->get('/alpha/{index:number}', 'welcome@alpha');


// $router->get('/beta/{index}', [

// 	'middleware' => 'admin',

// 	function($request) {

// 		// $request->input->param('index');

// 		// $request->input->post();

// 		// $request->input->get();

// 		return [];

// 	}// end function

// ]);// end router

// use to auto search controller if not found on your route declarations
//----------------------------------------------------------------------
$router->auto();