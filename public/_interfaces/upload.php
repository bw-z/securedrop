<?php

include_once("../../config.php");
include_once("../../core.php");

validateHTTPS($config);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3000);

 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];
    
    $filesize = 0;
    //verify file upload size
    $filesize = $_FILES['file']['size'];
    
    
    
    if ($filesize > $config['max_file_size'] * 1000000) {
	    header("Location: ../?uploaderror");
	    die();
    }
    
    
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
    											encrypted,
    											filesize
    										) 
    											VALUES(?, ?, ?, ?, ?, ?, ?)");
	
	$query->bind_param('sssssss', 
							$filekey, 
							$_FILES['file']['name'], 
							$timestamp, 
							$userid, 
							$expiry,
							$encrypt,
							$filesize);
	$query->execute();
	
	$targetFile = $config['upload_location'] . $filekey . ".secureuploadfile";
    
   
			if ($config['encrypt'] && $_SESSION['encrypt'] == "on") {
				 
				$passphrase = $config['secret'];
				
				
				$filecrypt = new filecrypt();
				
				 $filecrypt->encryptFileChunks($_FILES['file']['tmp_name'], $targetFile, $passphrase);
			
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

