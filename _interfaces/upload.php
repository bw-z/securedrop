<?php

include_once("../config.php");
include_once("../core.php");

validateHTTPS($config);

 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];        
    
    $filekey = genKey(20);
	$timestamp = time();
	$expiry = $timestamp + $config['file_expiry'] * 60 * 60;
    
    $query = $db->prepare("INSERT INTO files (	accesskey, 
    											filename, 
    											timestamp, 
    											userid,
    											expiry
    										) 
    											VALUES(?, ?, ?, ?, ?)");
	
	$query->bind_param('sssss', 
							$filekey, 
							$_FILES['file']['name'], 
							$timestamp, 
							$userid, 
							$expiry);
	$query->execute();
	
    $targetFile = $config['upload_location'] . $filekey . ".secureuploadfile"; 
    move_uploaded_file($tempFile, $targetFile); 
     
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

