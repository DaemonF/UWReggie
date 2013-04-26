<?php
	session_start();
	if(isset($_SESSION['userId'], $_SESSION['userPassword'])){
		require_once('config.php');
		$mysql = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

		$result = $mysql->query("SELECT * FROM user_accounts WHERE userId = '".$_SESSION['userId']."' AND userPassword = '".$_SESSION['userPassword']."'");
		if($result->num_rows == 1){
			$row = $result->fetch_array();
			$_SESSION['emailVerified'] = $row['emailVerified'] == "true";
			$_SESSION['phoneVerified'] = $row['phoneVerified'] == "true";
			$_SESSION['emailEnabled'] = $row['emailEnabled'] == "true";
			$_SESSION['phoneEnabled'] = $row['phoneEnabled'] == "true";
			$_SESSION['userPhone'] = $row['userPhone'];
			$_SESSION['userEmail'] = $row['userEmail'];
			$_SESSION['phoneCarrier'] = $row['phoneCarrier'];
		} else {
			header('location: logout.php');
			exit(0);
		}
	} else {
		header('location: logout.php');
		exit(0);
	}
?>
