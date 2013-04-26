<?php

require_once('config.php');
require_once('text2email.php');
require_once('gmail.php');
$gmail = new gmail(GMAIL_USER, GMAIL_PASS, GMAIL_NAME);

class GoogleVoice{
	function __construct($user, $password){
		$this->auth = false;
		$this->auth=$this->googleapi('https://www.google.com/accounts/ClientLogin',array(
								'accountType'=>'GOOGLE',
								'Email'=>$user,
								'Passwd'=>$password,
								'service'=>'grandcentral',
								'source'=>'googlevoice.php'));
		if(preg_match('/Error=([A-z]+)/',$this->auth,$error))
			die($error[1]);
		if(preg_match('/Auth=([A-z0-9_-]+)/',$this->auth,$this->auth))
			$this->auth = $this->auth[1];
		preg_match("/'_rnr_se'\: '([^']+)'/",$this->googleapi('https://www.google.com/voice/'),$this->rnr);
		$this->rnr = $this->rnr[1];
	}

	function query($data=array(),$fields=array()){
		foreach($data as $k=>$v)
			$fields[]=$k.'='.urlencode(stripslashes($v));
		return implode('&',$fields);
	}

	function googleapi($url,$post=null){
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		if(is_array($post)){
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$this->query($post));
		}
		if($this->auth)
			curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: GoogleLogin auth='.$this->auth));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		$data=curl_exec($ch) or curl_error($ch);
		curl_close($ch);
		return $data;
	}

	function call($them,$me){
		return $this->googleapi('https://www.google.com/voice/call/connect/',array(
			'outgoingNumber'=>$them,
			'forwardingNumber'=>$me,
			'subscriberNumber'=>'undefined',
			'phoneType'=>2,
			'remember'=>'0',
			'_rnr_se'=>$this->rnr)
		);
	}

	function hangup(){
		return $this->googleapi('https://www.google.com/voice/call/cancel/',array(
			'outgoingNumber'=>'undefined',
			'forwardingNumber'=>'undefined',
			'cancelType'=>'C2C',
			'_rnr_se'=>$this->rnr)
		);
	}

	function sms($them, $carrier, $text){
		global $gmail, $text2email;
		if($carrier != "Other" && array_key_exists($carrier, $text2email)){
			if($gmail->send($them.$text2email[$carrier], "", $text))
				return '{"ok":"true"}';
			return false;
		} else {
			$result = $this->googleapi('https://www.google.com/voice/sms/send/',array(
				'id'=>'',
				'phoneNumber'=>$them,
				'text'=>$text,
				'_rnr_se'=>$this->rnr)
			);

			if(!json_decode($result)->ok){
				$gmail->send("nksemail@gmail.com", "Could not send to $them on $carrier", "Phone: $them\nCarrier: $carrier Original message:\n\n$text\n");
				return false;
			}
			return true;
		}
	}
}

?>
