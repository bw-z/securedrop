<?php

include_once("../../config.php");
include_once("../../core.php");

validateHTTPS($config);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3000);

 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];        
    
    $filekey = genKey(20);
	$timestamp = time();
	$expiry = $timestamp + $config['file_expiry'] * 60 * 60;
	
	if ($config['encrypt'] && $_SESSION['encrypt'] == "on") {
		$encrypt = 1;
	} else {
		$encrypt = 0;
	}
    
    $query = $db->prepare("INSERT INTO files (	accesskey, 
    											filename, 
    											timestamp, 
    											userid,
    											expiry,
    											encrypted
    										) 
    											VALUES(?, ?, ?, ?, ?, ?)");
	
	$query->bind_param('ssssss', 
							$filekey, 
							$_FILES['file']['name'], 
							$timestamp, 
							$userid, 
							$expiry,
							$encrypt);
	$query->execute();
	
	$targetFile = $config['upload_location'] . $filekey . ".secureuploadfile";
    
   
			if ($config['encrypt'] && $_SESSION['encrypt'] == "on") {
				 
				$passphrase = $config['secret'];
				
				
				$filecrypt = new filecrypt();
				
				 $filecrypt->encryptFileChunks($_FILES['file']['tmp_name'], $targetFile, $passphrase);
				 
				 //$filecrypt->decryptFileChunks('encrypted.gif', 'decrypted.gif', $key);
				
				/* 
				$iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
				$key = substr(md5("\x2D\xFC\xD8".$passphrase, true) .
				md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
				$opts = array('iv'=>$iv, 'key'=>$key);
				 
				$fp = fopen($targetFile, 'wb');
				stream_filter_append($fp, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts);
				fwrite($fp, file_get_contents($_FILES['file']['tmp_name']));
				 
				fclose($fp);
				*/
			
			} else {
				 
				move_uploaded_file($tempFile, $targetFile); 
			} 		
     
}


function genKey($l) {
		$alfa = "23456789qwertyuiopasdfghjkzxcvbnm";
	    $room = "";
	    for($i = 0; $i < $l; $i++) {
	      	$room .= $alfa[rand(0, strlen($alfa)-1)];
	    }
	    return $room;
    }
    
    
?> 

