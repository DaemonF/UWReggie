<?php
	require_once('./snippets/constants.php');
    require_once('./snippets/checkSession.php');
    require_once('text2email.php');

    if(isset($_GET['code'])){
        $code = trim($_GET['code']);
        if(substr(md5($_SESSION['userId'].$_SESSION['userEmail'].date("W").$salt),0,4) == $code){
					$mysql->query("UPDATE user_accounts SET emailVerified='true' WHERE userId = '".$_SESSION['userId']."'");
					$_SESSION['emailVerified'] = true;
					$message = "Your email has been verified.";
        }
        if(substr(md5($_SESSION['userId'].$_SESSION['userPhone'].date("W").$salt),0,4) == $code){
					$mysql->query("UPDATE user_accounts SET phoneVerified='true' WHERE userId = '".$_SESSION['userId']."'");
					$_SESSION['phoneVerified'] = true;
					$message = "Your phone number has been verified.";
		    }
        if($message == ""){
        	$error = "The verification code was not valid for your email or phone number. Perhaps it has expired. Click 'Send me a code' below.";
        }
    }


		if(isset($_GET['sendCode'])){
			if($_GET['sendCode'] == "phone" || $_GET['sendCode'] == "both"){
				if(isset($_SESSION['userPhone'])){
					$phoneCode = substr(md5($_SESSION['userId'].$_SESSION['userPhone'].date("W").$salt),0,4);
					require_once('googleVoice.php');
					$gvoice = new GoogleVoice(GOOGLE_VOICE_USER, GOOGLE_VOICE_PASS);
					$gvoice->sms($_SESSION['userPhone'], $_SESSION['phoneCarrier'], "Please verify this number by visiting uwreggie.com/?code=".$phoneCode);
				}
			}
			if($_GET['sendCode'] == "email" || $_GET['sendCode'] == "both") {
				if(isset($_SESSION['userEmail'])){
					$emailCode = substr(md5($_SESSION['userId'].$_SESSION['userEmail'].date("W").$salt),0,4);
					require_once('gmail.php');
					$gmail = new Gmail(GMAIL_USER, GMAIL_PASS, GMAIL_NAME);
					$gmail->send($_SESSION['userEmail'], "Please verify your email - UWReggie", "Please verify this email address by visiting uwreggie.com/?code=".$emailCode);
				}
			}
		}

    if(isset($_POST['submit'])){
    	$action = trim($_POST['submit']);

    	if($action == "updateInfo"){
    		if(isset($_POST['email'], $_POST['phone'], $_POST['carrier'])){
    			$email = trim($_POST['email']);
    			$phone = trim($_POST['phone']);
    			$carrier = trim($_POST['carrier']);
    			if($email != "" && $email != $_SESSION['userEmail']){
						if(!preg_match ( "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/" , $email)){
							$error .= "The email you provided is not valid email.";
						} else if(preg_match ( "/^[A-Za-z0-9._%+-]+@(uw|u.washington)(|\.com|\.com\.edu|\.edu\.com)$/" , $email)){
							$error .= "UW emails addresses must end 'uw.edu' or 'u.washington.edu'. 'uw.com' or 'uw.edu.com' are not real email addresses.";
						} else if($mysql->query("SELECT * FROM user_accounts WHERE (userEmail = '$email')")->num_rows != 0) {
							$error .= "That email address is already registered to another user.";
						} else {
		  				$mysql->query("UPDATE `user_accounts` SET `userEmail` = '".$mysql->escape_string($email)."', `emailVerified` = 'false' WHERE `userId` = ".$_SESSION['userId'].";");
		  				$_SESSION['emailVerified'] = false;
		  				$_SESSION['userEmail'] = $email;
		  				$message .= "Your email has has been changed. This is also your new log in email.";
    				}
    			}
    			if(($phone != "" && $phone != $_SESSION['userPhone'])){
		  			$phone = preg_replace("/[^0-9]*/", "", $phone);
		  			if(strlen($phone) != 10){
							$error .= "The phone number you provided is not valid.";
						} else {
		  				$mysql->query("UPDATE `user_accounts` SET `userPhone` = '".$mysql->escape_string($phone)."', `phoneVerified` = 'false' WHERE `userId` = ".$_SESSION['userId'].";");
		  				$_SESSION['phoneVerified'] = false;
		  				$_SESSION['userPhone'] = $phone;
		  				$message .= "Your phone number has has been changed.";
    				}
    			}
    			if(($carrier != $_SESSION['phoneCarrier'])){
	  				$mysql->query("UPDATE `user_accounts` SET `phoneCarrier` = '".$mysql->escape_string($carrier)."', `phoneVerified` = 'false' WHERE `userId` = ".$_SESSION['userId'].";");
	  				$_SESSION['phoneVerified'] = false;
	  				$_SESSION['phoneCarrier'] = $carrier;
	  				$message .= "Your mobile carrier has has been changed.";
    			}
    		}
    	} else if ($action == "changePass"){
    		if(isset($_POST['curPass'], $_POST['newPass'], $_POST['newPass2']) && strlen($_POST['curPass']) != 0){
    			$password = $mysql->escape_string(hash("sha512",md5(trim($_POST['curPass'])).$salt));
    			if($_SESSION['userPassword'] == $password){
    				if(strlen($_POST['newPass']) > 6 && $_POST['newPass'] == $_POST['newPass2']){
    					$newPass = $mysql->escape_string(hash("sha512",md5(trim($_POST['newPass'])).$salt));
    					$mysql->query("UPDATE `user_accounts` SET `userPassword` = '".$newPass."' WHERE `userId` = ".$_SESSION['userId'].";");
    					$message = "Your password has been changed.";
    				} else {
    					$error = "Passwords do not match, or are shorter than 6 characters.";
    				}
    			} else {
    				$error = "Incorrect password.";
    			}
    		} else {
    			$error = "You'll need to enter your current password to change it.";
    		}
    	} else if ($action == "deleteAccount"){
    		if(isset($_POST['curPass']) && strlen($_POST['curPass']) != 0){
    			$password = $mysql->escape_string(hash("sha512",md5(trim($_POST['curPass'])).$salt));
    			if($_SESSION['userPassword'] == $password){
    				$uid = $_SESSION['userId'];
    				$mysql->query("DELETE FROM `user_accounts` WHERE `userId` = ".$uid.";");
    				$mysql->query("DELETE FROM `alerts` WHERE `userId` = ".$uid.";");
    				header('location: logout.php');
						exit(0);
    			} else {
    				$error = "Incorrect password.";
    			}
    		} else {
    			$error = "You'll need to enter your password to delete your account.";
    		}
    	}
    }

    $title = "Settings";
   	$cssBase = "settings";
   	require_once('./snippets/header.php');
?>
	<div id="box">
		<h2>Settings</h2>
<?php require_once('snippets/topNav.php'); ?>
		<form method="post" action="">
			<h4>Contact Info</h4>
			<hr />
			<div id="phoneSettings">
				<table>
				<tr>
				<td>
				<label for="phone">Phone - </label><?= $_SESSION['phoneVerified'] ? "<span class=\"messageText\">Verified</span>" : (0 == strlen($_SESSION['userPhone']) ? "<span class=\"errorText\">Please add a phone number</span>" : "<span class=\"errorText\">Unverified <a href=\"settings.php?sendCode=phone\">(Send me a code)</a></span>") ?>
				<input type="text" name="phone" id="phone" title="<?= preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/","(\\1) \\2-\\3",$_SESSION['userPhone']) ?>" />

				</td><td>
				<label for="carrier">Carrier</label>
				<select name="carrier" id="carrier">
					<option value="" <?= $_SESSION['phoneCarrier'] == "" ? 'selected="selected"' : "" ?>>Select a carrier:</option>
					<option value="Other" <?= $_SESSION['phoneCarrier'] == "Other" ? 'selected="selected"' : "" ?>>== Not Listed ==</option>
					<?php
						foreach($text2email as $carrier=>$email){
							echo '<option value="'.$carrier.'"'.($_SESSION['phoneCarrier'] == $carrier ? ' selected="selected"' : "").'>'.$carrier.'</option>';
						}
					?>
				</select>
				</td>
				</tr>
				</table>
			</div>
			<label for="newEmail">Email - </label><?= $_SESSION['emailVerified'] ? "<span class=\"messageText\">Verified</span>" : "<span class=\"errorText\">Unverified <a href=\"settings.php?sendCode=email\">(Send me a code)</a></span>" ?>
			<input type="text" name="email" title="<?= $_SESSION['userEmail'] ?>" />
			<button name="submit" class="right" type="submit" value="updateInfo">Update</button>
			<div class="clear"></div>
		</form>
		<form method="post" action="">
			<h4>Change Password</h4>
			<hr />
			<label for="curPass">Current Password</label>
			<input type="password" name="curPass" title="password" />
			<label for="newPass">New Password</label>
			<input type="password" name="newPass" />
			<label for="newPass2">New Password Again</label>
			<input type="password" name="newPass2" />
			<button name="submit" class="right" type="submit" value="changePass">Change</button>
			<div class="clear"></div>
		</form>
		<form method="post" action="">
			<h4>Delete Account</h4>
			<hr />
			<label for="curPass">Current Password</label>
			<input type="password" name="curPass" title="password" />
			<button name="submit" class="right" type="submit" value="deleteAccount">Delete Account</button>
			<div class="clear"></div>
		</form>
	</div>
<?php require_once('./snippets/footer.php'); ?>
