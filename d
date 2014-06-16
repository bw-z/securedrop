<?php

	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 3000);

	include_once("config.php");
	include_once("core.php");
	
	validateHTTPS($config);
    
    $expl = explode("/",$_SERVER["REQUEST_URI"]);
    $accesskey = $expl[count($expl)-1];
    
     $query = $db->prepare('SELECT filename, encrypted FROM files WHERE accesskey = ?');
		$query->bind_param('s', $accesskey);
		$query->execute();
	    
	    $query->bind_result($a, $b);
	    while ($query->fetch()) {
	    	$filename = $a; 
	    	$encrypted = $b;
	    }
	    
		if (!isset($filename)) {
			sleep(2);
			header("Location: ../?badfile");
			die();
		}
	
		$targetFile = $config['upload_location'] . $accesskey . ".secureuploadfile";
		
		if ($encrypted) {
		
			$passphrase = $config['secret'];
			 
			$iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
			$key = substr(md5("\x2D\xFC\xD8".$passphrase, true) .
			md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
			$opts = array('iv'=>$iv, 'key'=>$key);
			 
			$fp = fopen($targetFile, 'rb');
			stream_filter_append($fp, 'mdecrypt.tripledes', STREAM_FILTER_READ, $opts);

			header("Content-disposition: attachment; filename=$filename");
	    	fpassthru($fp);
	    
			die();
		
		} else {
			header("Content-disposition: attachment; filename=$filename");
			echo file_get_contents($targetFile); 
			
		}
    
    
    
    ?>