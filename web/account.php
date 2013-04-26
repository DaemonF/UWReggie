<?php
	require_once('./snippets/constants.php');
	require_once('./snippets/checkSession.php');

	if(isset($_GET['cancelAlert'])){
		$cancelAlert = $mysql->escape_string($_GET['cancelAlert']);
		$mysql->query("DELETE FROM alerts WHERE userId = '".$_SESSION['userId']."' AND id = '$cancelAlert'");
	}

	$title = "My Alerts";
	$cssBase = "account";
	require_once('./snippets/header.php');
?>
<div id="box">
	<h2>My Alerts</h2>
<?php
	require_once('./snippets/topNav.php');
	$result = $mysql->query("SELECT * FROM alerts WHERE userId = '".$_SESSION['userId']."'");
	if($result->num_rows == 0){
		echo '<div id="noAlerts"><h5 class="noAlerts">You have no alerts set.</h5></h6>Click \'New Alert\' to set up a course notifier!</h6></div>';
	} else{
		while($row = $result->fetch_array()):?>
			<div class="alertBox">
				<!--<img class="alertExclaim" height="50" width="50" src="http://uwreggie.com/images/excl.png" />-->
				<a class="alertCancel" href="?cancelAlert=<?= $row['id'] ?>">(Remove Alert)<!--<img height="35" width="35" src="http://uwreggie.com/images/x.png" />--></a>
				<h5 class="alertText">Alert set for <b><?= $row['classDept']." ".$row['classNumber']." ".$row['classSection']."</b> (".ucfirst($row['classTerm'])." ".$row['classYear'].")" ?></h5>
				<div class="clear"></div>
			</div>
		<?php endwhile;
	}
?>
</div>
<?php include('./snippets/footer.php'); ?>
