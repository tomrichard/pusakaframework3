<?php 
// smtp config.
$config['smtp']['default'] = [
	'host' 		=> 'smtp.gmail.com',
	'auth' 		=> true,
	'username'	=> '<your username>',
	'password'	=> '<your password>',
	'secure'	=> 'tls', // tls | ssl
	'port'		=> 587, // 587 | 465
	'log'		=> FRAMEWORK_DIR . 'logs/smtp.log'
];