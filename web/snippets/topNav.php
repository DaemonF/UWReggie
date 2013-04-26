<?php
	if(!isset($_SESSION))
		session_start();
	if(isset($_SESSION['userId'])):
?>
<div class="center">
	<hr>
	<ul id="topNav">
		<li><a href="account.php">My Alerts</a></li>
		<li><a href="course-search.php">New Alert</a></li>
		<li><a href="about.php">About Reggie</a></li>
		<li><a href="settings.php">Settings</a></li>
		<li><a href="logout.php">Log Out</a></li>
	</ul>
	<hr>
<?php
		if($cssBase != "settings" && $error == "" && $message == "" && isset($_SESSION['userEmail'], $_SESSION['userPhone'])): ?>
		<div id="notices">
		<?= $_SESSION['emailVerified'] ? '<div class="flavorText">Email alerts will be sent to '.$_SESSION['userEmail'].' <a href="settings.php">edit</a></div>' : 
			'<div class="errorText">Verify your email to recieve email alerts.<a href="settings.php">settings</a></div>'; ?>
		<?= $_SESSION['phoneVerified'] ? '<div class="flavorText">Text alerts will be sent to '.preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/","(\\1) \\2-\\3",$_SESSION['userPhone']).' <a href="settings.php">edit</a></div>' : 
			'<div class="errorText">Verify your phone to recieve text alerts.<a href="settings.php">settings</a></div>'; ?>
		</div>
<?php
		endif;
?>

	<h6 class="errorText"><?= isset($error) ? "$error" : "" ?></h6>
	<h6 class="messageText"><?= isset($message) ? "$message" : "" ?></h6>
	<br />
</div>
<?php endif; ?>
