<?php

error_reporting(E_ALL ^E_NOTICE);

$db = "replic_sl";
$dbusr = "replic_sldb";
$dbpwd = "tdm5880z";
$dbhost = "localhost";

$mysqli = new mysqli($dbhost, $dbusr, $dbpwd, $db);
	
	if ($mysqli->connect_errno) {

		echo "Error: Failed to make a MySQL connection, here is why: \n";
		echo "Errno: " . $mysqli->connect_errno . "\n";
		echo "Error: " . $mysqli->connect_error . "\n";
		
		exit;
	}
	
	/* change character set to utf8 */
	if (!$mysqli->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $mysqli->error);
		exit();
	} else {
		//printf("Current character set: %s\n", $mysqli->character_set_name());
	}
?>