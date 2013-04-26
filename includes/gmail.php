<?php

require_once('Mail.php');

define('HOST', "ssl://smtp.gmail.com");
define('PORT', "465");

class Gmail {
  private $user;
  private $name;
  private $smtp;
	private $mail;
	private $pear;

	function __construct($user, $pass, $name){
		$this->mail = new Mail();
		$this->pear = new PEAR();
		$this->user = $user;
		$this->name = $name;
		$this->smtp = $this->mail->factory('smtp', array (
				'host' => HOST,
				'port' => PORT,
				'auth' => true,
				'username' => $user,
				'password' => $pass));
	}

	function send($toAddress, $subject, $body) {
		$headers = array (
				'From' => $this->name." <".$this->user.">",
				'To' => "<".$toAddress.">",
				'Subject' => $subject);


		$mail = $this->smtp->send($toAddress, $headers, $body);

		return !$this->pear->isError($mail);
	}
}

?>
