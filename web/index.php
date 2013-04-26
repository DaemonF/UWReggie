<?php
	require_once('./snippets/constants.php');
	session_start();

	if(isset($_POST['action'])){
		require_once('config.php');
		$mysql = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

		$action = trim($_POST['action']);

		if($action == "login"){
			$email = $mysql->escape_string( trim($_POST['email']) );
			$password = $mysql->escape_string(hash("sha512",md5(trim($_POST['password'])).$salt));
			$result = $mysql->query("SELECT * FROM user_accounts WHERE (userEmail = '$email') AND (userPassword = '$password')");
			if($result->num_rows == 1){
				$row = $result->fetch_array();
				$_SESSION['userId'] = $row['userId'];
				$_SESSION['userEmail'] = $row['userEmail'];
				$_SESSION['userPassword'] = $row['userPassword'];
				$_SESSION['userPhone'] = $row['userPhone'];
				$_SESSION['emailVerified'] = $row['emailVerified'] == "true";
				$_SESSION['phoneVerified'] = $row['phoneVerified'] == "true";
				$mysql->query("UPDATE user_accounts SET lastLogin='".time()."' WHERE userId = '".$_SESSION['userId']."'");
			} else {
				$error = "Wrong username or password.";
			}
		}
		if($action == "signup"){
			$email = trim($_POST['email']);
			$phone = preg_replace("/[^0-9]*/", "", trim($_POST['phone']));
			$password = $mysql->escape_string(hash("sha512",md5(trim($_POST['password'])).$salt));
			$password2 = $mysql->escape_string(hash("sha512",md5(trim($_POST['passwordAgain'])).$salt));

			$result = $mysql->query("SELECT * FROM user_accounts WHERE userEmail='".$mysql->escape_string($email)."'");

			if ($result->num_rows != 0){
				$error = "This email address has already been registered for Reggie. If this is you, click Lost Password below.";
			} else if(!preg_match ( "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/" , $email)){
				$error = "The email you provided is not valid email.";
			} else if(preg_match ( "/^[A-Za-z0-9._%+-]+@(uw|u.washington)(|\.com|\.com\.edu|\.edu\.com)$/" , $email)){
				$error = "UW emails addresses must end 'uw.edu' or 'u.washington.edu'. 'uw.com' or 'uw.edu.com' are not real email addresses.";
			} /*else if(strlen($phone) != 10){
				$error = "The phone number you provided is not valid.";
			} */else if(strlen($password) < 6){
				$error = "The password is too short.";
			} else if($password2 != $password){
				$error = "The passwords do not match.";
			} else {
				$mysql->query("INSERT INTO user_accounts (userEmail, userPassword) VALUES ('".$mysql->escape_string($email)."', '$password')") or die(mysqli_error($mysql));
				$result = $mysql->query("SELECT * FROM user_accounts WHERE (userEmail = '$email') AND (userPassword = '$password')");
				if($result->num_rows == 1){
					$row = $result->fetch_array();
					$_SESSION['userId'] = $row['userId'];
					$_SESSION['userEmail'] = $row['userEmail'];
					$_SESSION['userPassword'] = $row['userPassword'];
					$_SESSION['userPhone'] = $row['userPhone'];
					$_SESSION['emailVerified'] = $row['emailVerified'] == "true";
					$_SESSION['phoneVerified'] = $row['phoneVerified'] == "true";
					//$_GET['sendCode'] = "both";
				} else {
					$error = "Unknown error.";
				}
			}
		}
		if($action == "lostPass"){
			$email = $mysql->escape_string(trim($_POST['email']));
			$result = $mysql->query("SELECT * FROM user_accounts WHERE userEmail='$email'");
			$randomString = substr(md5(time().$salt),0,10);
			if($result->num_rows){
				$newPassword= $mysql->escape_string(hash("sha512",md5(trim($randomString)).$salt));
				$mysql->query("UPDATE user_accounts SET userPassword='$newPassword' WHERE userEmail = '$email'") or die(mysqli_error($mysql));
				$emailText = "We have recieved your password reset request. You can now access your account with the following password: \n\n\t$randomString\n\nPlease visit uwreggie.com/settings.php to change your password to something memorable.";
				require_once('./snippets/gmail.php');
				$gmail = new Gmail(GMAIL_USER, GMAIL_PASS, GMAIL_NAME);
				$gmail->send($email, "Password Reset - UWReggie", $emailText);
				$message = "Your password has been reset and sent to you by email.";
			} else {
				$error ="I cant find a user with that email address.";
			}
		}
	}
	if(isset($_SESSION['userId'], $_SESSION['userEmail'], $_SESSION['userPassword'], $_SESSION['userPhone'],$_SESSION['emailVerified'], $_SESSION['phoneVerified'])){
		isset($_GET['code']) ? header('location: settings.php?code='.$_GET['code']) : header('location: account.php');
		exit(0);
	}

	$cssBase = "login";
	require_once("./snippets/header.php");
?>
<div id="box">
	<div class="center">
		<h4>University of Washington registration made simple.</h4>
		<h5>Learn more <a href="about.php">here</a>.</h5>
		<h6 class="errorText"><?= $error ? $error : "" ?></h6>
		<h6 class="messageText"><?= $message ? $message : "" ?></h6>
	</div>
	<form id="loginForm" method="post" action="<?= isset($_GET['code']) ? $_SERVER['PHP_SELF'].'?code='.$_GET['code'] : $_SERVER['PHP_SELF']; ?>">
		<div id="email">
			<label for="emailBox">Email</label>
			<input id="emailBox" <?= $error ? "class=\"error\" " : "" ?>name="email" type="text" title="example@uw.edu" autocomplete="off" />
		</div>
		<div id="pass">
			<label for="password">Password</label>
			<input id="password" <?= $error ? "class=\"error\" " : "" ?>name="password" type="password" title="password" autocomplete="off" />
		</div>
		<div id="signUp" class="hide">
			<label for="passwordAgain" >Re-Enter Password</label>
			<input id="passwordAgain" name="passwordAgain" type="password" title="password" autocomplete="off" />
			<!--<label for="phone" >Cell Phone (Text Alerts)</label>
			<input id="phone" name="phone" type="text" title="(206) 867-5309" autocomplete="off" />-->
		</div>
		<button id="submit" class="right" name="action" type="submit" value="login">Log In</button>
		<div class="clear"></div>
	</form>
</div>
<div id="navigationLinks">
	<span>
		<a id="logInLink" class="hide" href="javascript:void(0)" onclick="showForm('login');">Log In</a>
		<a id="signUpLink" href="javascript:void(0)" onclick="showForm('signup');">Sign Up</a>
	</span>
	<span class="right">
		<a id="lostPassLink" href="javascript:void(0)" onclick="showForm('lostPass');">Lost Password</a>
		<a id="signUpLink2" class="hide" href="javascript:void(0)" onclick="showForm('signup');">Sign Up</a>
	</span>
</div>
<?php require_once("./snippets/footer.php") ?>
