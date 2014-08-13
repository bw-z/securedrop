<?php

$config['version'] = "v0.5";

// Start a session and create a userID if one does not already exist
session_start();
if (!isset($_SESSION['userid'])) {
	$_SESSION['userid'] = genRandomString(40);
	$_SESSION['encrypt'] = "off";
	$_SESSION['loggedin'] = false;
}
$userid = $_SESSION['userid'];

// CSRF token for sensitive functions
if (!isset($_SESSION['token'])) $_SESSION['token'] = md5(uniqid(mt_rand(), true));

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


class filecrypt{

    var $_CHUNK_SIZE;

    function __construct(){
        $this->_CHUNK_SIZE = 100*1024; // 100Kb
    }

    public function encrypt($string, $key){
        $key = pack('H*', $key);
        if (extension_loaded('mcrypt') === true) return mcrypt_encrypt(MCRYPT_BLOWFISH, substr($key, 0, mcrypt_get_key_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB)), $string, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND));
        return false;
    }

    public function decrypt($string, $key){
        $key = pack('H*', $key);
        if (extension_loaded('mcrypt') === true) return mcrypt_decrypt(MCRYPT_BLOWFISH, substr($key, 0, mcrypt_get_key_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB)), $string, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND));
        return false;
    }

    public function encryptFileChunks($source, $destination, $key){
        return $this->cryptFileChunks($source, $destination, $key, 'encrypt');
    }

    public function decryptFileChunks($source, $destination, $key){
        return $this->cryptFileChunks($source, $destination, $key, 'decrypt');
    }

    private function cryptFileChunks($source, $destination, $key, $op){

        if($op != "encrypt" and $op != "decrypt") return false;

        $buffer = '';
        $inHandle = fopen($source, 'rb');
        $outHandle = fopen($destination, 'wb+');

        if ($inHandle === false) return false;
        if ($outHandle === false) return false;

        while(!feof($inHandle)){
            $buffer = fread($inHandle, $this->_CHUNK_SIZE);
            if($op == "encrypt") $buffer = $this->encrypt($buffer, $key);
            elseif($op == "decrypt") $buffer = $this->decrypt($buffer, $key);
            fwrite($outHandle, $buffer);
        }
        fclose($inHandle);
        fclose($outHandle);
        return true;
    }

    public function printFileChunks($source, $key){

        $buffer = '';
        $inHandle = fopen($source, 'rb');

        if ($inHandle === false) return false;

        while(!feof($inHandle)){
            $buffer = fread($inHandle, $this->_CHUNK_SIZE);
            $buffer = $this->decrypt($buffer, $key);
            echo $buffer;
        }
        return fclose($inHandle);
    }
}

?>