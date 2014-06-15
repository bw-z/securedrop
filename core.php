
<?php

// Start a session and create a userID if one does not already exist
session_start();
if (!isset($_SESSION['userid'])) {
	$_SESSION['userid'] = genKey(40);
}
$userid = $_SESSION['userid'];

// Connect to the DB
// uses variables from config.php
$db = mysqli_connect($db_server, $db_username, $db_password, $db_name);
if (mysqli_connect_errno($db)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>