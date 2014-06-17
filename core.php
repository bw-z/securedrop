<?php

$config['version'] = "v0.3";

// Start a session and create a userID if one does not already exist
session_start();
if (!isset($_SESSION['userid'])) {
	$_SESSION['userid'] = genRandomString(40);
	$_SESSION['encrypt'] = "off";
	$_SESSION['loggedin'] = false;
}
$userid = $_SESSION['userid'];

// Connect to the DB
// uses variables from config.php
$db = mysqli_connect($db_server, $db_username, $db_password, $db_name);
if (mysqli_connect_errno($db)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//get the secret key to encrypt files
if ($config['encrypt']) {
	$query = $db->prepare('SELECT secret FROM config');
	$query->execute();
	
	$query->bind_result($a);
	while ($query->fetch()) {
		$config['secret'] = $a;
	}	
}

//connect to ad auth
if ($config['auth_type'] == "adldap") {
	include ("adLDAP/adLDAP.php");
	try {
	    $adldap = new adLDAP($config);
	}
	catch (adLDAPException $e) {
	    echo $e; 
	    die(); 
	}
}

//function to generate a random string
function genRandomString($l) {
	$alfa = "23456789qwertyuiopasdfghjkzxcvbnm";
    $string = "";
    for($i = 0; $i < $l; $i++) {
      	$string .= $alfa[rand(0, strlen($alfa)-1)];
    }
    return $string;
}

// Check and redirect user to HTTPS if required
function validateHTTPS($config) {
	if ($config['requireHTTPS']) {
		if (empty($_SERVER['HTTPS'])) {
		    header('Location: https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		    die();
		}
	}
}

?>