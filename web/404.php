<?php
	include("./snippets/constants.php");
	$title = "Page Not Found";
	$cssBase = "error404 center";
	$noBranding = true;
	include("./snippets/header.php");
?>

<h1 id="sorry">I'm sorry...</h1>
<h2 id="cantFind">I can't find that page.</h2>
<img height="409px" width="350px" src="images/sadreggie.png">
<div>
		<br/>Return to <a href="/">UWReggie.com</a> &raquo;
</div>

<?php include("./snippets/footer.php"); ?>

