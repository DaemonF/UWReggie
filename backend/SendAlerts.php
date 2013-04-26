<?php

require_once('googleVoice.php');
require_once('gmail.php');
require_once('config.php');

$gvoice = new GoogleVoice(GOOGLE_VOICE_USER, GOOGLE_VOICE_PASS);
$gmail = new Gmail(GMAIL_USER, GMAIL_PASS, GMAIL_NAME);

$fifo = fopen("sendQueue.fifo", "r");

while (($line = fgets($fifo)) !== false) {
	$sendRequest = json_decode($line, true);
	if($sendRequest == null){
		file_put_contents('php://stderr', 'Could not parse JSON: $line\n',FILE_APPEND);
		continue;
	}

	foreach($sendRequest["phones"] as $phoneInfo){
		echo "Sending to ".$phoneInfo[0]." on ".$phoneInfo[1]."\n";
		$gvoice->sms($phoneInfo[0], $phoneInfo[1], $sendRequest["textBody"]);
	}
	foreach($sendRequest["emails"] as $email){
		echo "Sending to ".$email."\n";
		$gmail->send($email, $sendRequest["emailSubject"], $sendRequest["emailBody"]);
	}
}

fclose($fifo);

?>
