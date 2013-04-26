<?php

//xdebug_start_trace('/home/nick/trace');

require_once('config.php');
$mysql = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

$result = $mysql->query("SELECT * FROM alerts");

$reduced = array();
$time =  time();

// Stats
$optimum = 0;

while($request = $result->fetch_array()){
	$year = $request["classYear"];
    $term = $request["classTerm"];
    $dept = $request["classDept"];
    $number = $request["classNumber"];
    $section = $request["classSection"];

	$account = $request["userId"];
    $lastContacted = $request["lastContacted"];
    $result2 = $mysql->query("SELECT * FROM user_accounts WHERE userId = $account");
    $contactInfo = $result2->fetch_array();

	$contactInterval = $contactInfo["contactInterval"];
	$emailVerif = $contactInfo['emailVerified'] == "true";
    $phoneVerif = $contactInfo['phoneVerified'] == "true";
    $emailEnabled = $contactInfo['emailEnabled'] == "true";
    $phoneEnabled = $contactInfo['phoneEnabled'] == "true";

	if($time >= ($lastContacted + $contactInterval)){
		if(($phoneVerif && $phoneEnabled) || ($emailVerif && $emailEnabled)){
			$optimum++;
			$key = $year.$term.$dept.$number.$section;
			$reduced[$key][] = [$request,$contactInfo];
		}
	}
}

$mapped = count($reduced);
echo "Total: ".$result->num_rows."\nMapped: $mapped\nOptimum: $optimum\n";

$fifo = fopen("sendQueue.fifo", "a");

$done = 0;
foreach($reduced as $requestList){
	$info = $requestList[0][0];

	$year = $info["classYear"];
	$term = $info["classTerm"];
	$dept = $info["classDept"];
	$number = $info["classNumber"];
	$section = $info["classSection"];

	usleep(500000);
	$data = getClassData($year,$term,$dept,$number,$section);
	$spaceAvailable = $data->LimitEstimateEnrollment - $data->CurrentEnrollment;
	$sln = $data->SLN;
	$className = $data->CourseTitleLong;

	// For debug
	$done++;
	echo "$done of $mapped - $year $term $dept $number $section: $spaceAvailable\n";

	if($spaceAvailable > 0){
		$s = $spaceAvailable > 1 ? "s" : "";
		$count = 0;
		$sendAlerts = [
				"textBody"=>"Hey there! $dept $number $section (SLN #$sln) has $spaceAvailable open position$s! You're welcome :D -Reggie",
				"emailSubject"=>"$dept $number $section has $spaceAvailable opening$s!",
				"emailBody"=>"Hey there! $dept $number $section - $className (SLN #$sln) has $spaceAvailable opening$s! -Reggie",
				"phones"=>[],
				"emails"=>[]
		];
		foreach($requestList as $requestPair){
			$request = $requestPair[0];
			$contactInfo = $requestPair[1];

			$emailVerif = $contactInfo['emailVerified'] == "true";
			$phoneVerif = $contactInfo['phoneVerified'] == "true";
			$emailEnabled = $contactInfo['emailEnabled'] == "true";
			$phoneEnabled = $contactInfo['phoneEnabled'] == "true";
			$phone = $contactInfo["userPhone"];
			$email = $contactInfo["userEmail"];
			$carrier = $contactInfo["phoneCarrier"];
			$contactInterval = $contactInfo["contactInterval"];
			$alertsSent = $request["alertsSent"];
			$id = $request["id"];

			// For debug
			echo "\tAlertID: $id";

			$contacted = false;
			if($phoneVerif && $phoneEnabled){
				$contacted = true;
				// For debug
				echo " - $phone";
				$sendAlerts["phones"][] = [$phone, $carrier];
			}

			if($emailVerif && $emailEnabled){
				$contacted = true;
				// For debug
				echo " - $email";
				$sendAlerts["emails"][] = $email;
			}

			if($contacted){
				$count++;
				$mysql->query("UPDATE alerts SET lastContacted='".($time + rand(-$contactInterval/20,$contactInterval/20))."', alertsSent='".($alertsSent+1)."' WHERE id='$id'");
			}

			// For debug
			echo "\n";
		}

		if($count > 0)
			fwrite($fifo , json_encode($sendAlerts)."\n");
	}
}

fclose($fifo);

//xdebug_stop_trace();

function getClassData($year,$term,$dept,$number,$section){
	$dept = rawurlencode($dept);
	$url = "https://ws.admin.washington.edu/student/v4/public/course/".$year.",".$term.",".$dept.",".$number."/".$section.".xml";
	return new SimpleXMLElement(file_get_contents($url));
}

?>
