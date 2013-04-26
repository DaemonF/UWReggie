<?php

require_once('config.php');

if(DEBUG == "true"){
	echo "DEBUG MODE: ".DEBUG;
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

$message = "";
$error = "";
$cssBase = "";
$salt = "L{@deB<DvR}l3uK7~=oS>2ew";

?>
