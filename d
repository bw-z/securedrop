<?php

	ini_set('memory_limit', '-1');

	include_once("config.php");
	include_once("core.php");
	
	validateHTTPS($config);
    
    $expl = explode("/",$_SERVER["REQUEST_URI"]);
    $accesskey = $expl[count($expl)-1];
    
    $targetFile =  $config['upload_location'] . $accesskey;
    
     $query = $db->prepare('SELECT filename FROM files WHERE accesskey = ?');
		$query->bind_param('s', $accesskey);
		$query->execute();
		
	    
	    $query->bind_result($a);
	    while ($query->fetch()) {
	    	
	    	$filename = $a; 
	    	
	    }
    
    header("Content-disposition: attachment; filename=$filename");
    readfile($targetFile);
    
    die();
    
    
    
    ?>