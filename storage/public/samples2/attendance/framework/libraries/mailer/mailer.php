<?php 
namespace Pusaka\Library;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

	private $mail;
	private $template;
	private $html = false;
	private $data = [];

	static function on($name) {
		return new Mailer($name);
	}

	public function __construct($name = 'default') {
		
		$config = $GLOBALS['config']['smtp'];

		if(!isset($config[$name])) {
			throw new Exception("smtp config [$name] not found.");
			return false;
		}

		$config = $config[$name];

		$mail 	= new PHPMailer(true);

		//Server settings
	    $mail->SMTPDebug 	= SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       	= $config['host'];                    // Set the SMTP server to send through
	    $mail->SMTPAuth   	= $config['auth'];                                   // Enable SMTP authentication
	    $mail->Username   	= $config['username'];                     // SMTP username
	    $mail->Password   	= $config['password'];                               // SMTP password
	    $mail->SMTPSecure 	= $config['secure']; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
	    $mail->Port       	= $config['port'];

	    $mail->Debugoutput 	= function($str, $level) {
		    
		    file_put_contents(FRAMEWORK_DIR . 'logs/smtp.log',
		    	"\r\n"."=========================================". 
		    	"\r\n".date('Y-m-d H:i:s').
		    	"\r\n"."=========================================".
		    	"\r\n". $mail->Host . ' | ' . $mail->Username . ' | ' . $mail->Password .
		    	"\r\n"."=========================================". 
		    	"\r\n".$level.
		    	"\r\n".$str.
		    	"\r\n=========================================", FILE_APPEND | LOCK_EX
		    );

		};

	    $this->mail = $mail;

	}

	public function setFrom($email, $name) {
		$this->mail->setFrom($email, $name);
	}

	public function addAddress($email, $name) {
		$this->mail->addAddress($email, $name);
	}

	public function addCC($email, $name) {
		$this->mail->addCC($email, $name);
	}

	public function addBCC($email, $name) {
		$this->mail->addBCC($email, $name);
	}
	
	public function setTemplate($template, $type = "html") {

		$template = strtr(__DIR__, '\\', '/') . '/' . 'templates/' . $template . '.tpl';

		if(!file_exists($template)) {
			throw new Exception('Template not found.');
		}

		$this->template = file_get_contents($template);

		if($type = "html") {
			$this->html 	= true;
		}

	}

	public function setData($data) {

		if(!is_array($data)) {
			throw new Exception("Data must be an array.");
		}

		$this->data = $data;
	
	}

	public function setSubject($subject) {

		$this->mail->Subject = $subject;

	}

	public function send() {

		if($this->html) {
			$this->mail->isHTML(true);
		}

		$replace = [];

		foreach ($this->data as $key => $value) {
			$replace['{{'.$key.'}}'] = $value;
		}

		$body 	 = strtr($this->template, $replace);

		$this->mail->Body = $body;
		
		try {
			$this->mail->send();
		}catch(Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}


}