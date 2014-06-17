<?php


include_once("config.php");
include_once("core.php");

validateHTTPS($config);

if ($_SESSION['loggedin'] || !$config['allow_accounts']) {
	header("Location: ../");
}

if (isset($_POST['email'])) {
	
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	
	if ($config['auth_type'] == "local") {
		
		$query = $db->prepare('SELECT userid, password FROM users WHERE email = ?');
		$query->bind_param('s', $email);
		$query->execute();
			
		$query->bind_result($a, $b);
		
	    while ($query->fetch()) {	    
		    // account already exists
		    $new_userid = $a;
		    $hash = $b;
		}
		
		if (!isset($new_userid)) {
			$badlogin = true;
			include("html/login.php");
			die();
		}
		
		include_once("Bcrypt.php");
		$bcrypt = new Bcrypt(10);
		
		if ($bcrypt->verify($password, $hash)) {
			$loginok = true;
		} else {
			$badlogin = true;
			include("html/login.php");
			die();
		}
		
		
	} else if ($config['auth_type'] == "adldap") {
	
		if ($adldap->authenticate($email, $password)){
			//establish your session and redirect
			
			$query = $db->prepare('SELECT userid FROM users WHERE email = ?');
			$query->bind_param('s', $email);
			$query->execute();
				
			$query->bind_result($a);
			
		    while ($query->fetch()) {	    
			    // account already exists
			    $new_userid = $a;
			}
			
			if (!isset($new_userid)) {
				$query = $db->prepare('INSERT INTO users (email) VALUES (?)');
				$query->bind_param('s', $email);
				$query->execute();
				$new_userid = $query->insert_id();
			}
			
			$loginok = true;
			
		} else {
			$badlogin = true;
			include("html/login.php");
			die();
		}
	
	}
	
	
	if ($loginok) {
		// login the user
		$old_userid = $userid;
		// update existing files to the new user
		$query = $db->prepare('UPDATE FILES SET userid = ? WHERE userid = ?');
		$query->bind_param('ss', $new_userid, $old_userid);
		$query->execute();
		//
		$_SESSION['userid'] = $new_userid;
		$_SESSION['loggedin'] = true;
		
		header("Location: ./");
		die();
	}
	
	
	
}

include("html/login.php");


?>