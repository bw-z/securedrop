<?php

	
	include_once("../../config.php");
	include_once("../../core.php");
	
	validateHTTPS($config);

    
	// Function which displays the upload time in a user friendly format
	function textDate($d, $tense = "past") {
		$t = time();
		if ($tense == "past") {
			$diff = $t - $d;
			$in = "";
			$ago = "ago";	
		} else {
			$diff = $d - $t;
			$in = "in ";
			$ago = "";	
		}
		
		if ($diff < 60) {
			return $in . "moments $ago";
		} 
		if ($diff < 60 * 60) {
			return $in . intval($diff / 60) . " mins $ago (" . date("h:i a", $d) . ")";
		} 
		if ($diff < 60 * 60 * 60) {
			return $in . intval($diff / 60 / 60) . " hours $ago (" . date("h:i a", $d) . ")";
		} 
		return date("Y-m-d h:i a", $d);
	}
    
    $query = $db->prepare('SELECT accesskey, filename, timestamp, fileid, expiry, encrypted FROM files WHERE userid = ? ORDER BY timestamp DESC');
		$query->bind_param('s', $userid);
		$query->execute();
		
		$files = array();
	    
	    $query->bind_result($a, $b, $c, $d, $e, $f);
	    while ($query->fetch()) {
	    	
	    	$file['fileid'] = $d;
	    	$file['filename'] = htmlspecialchars($b); 
	    	$file['accesskey'] = $a; 
	    	$file['timestamp'] = $c;
	    	$file['textDate'] = textDate($c);
			$file['expires'] = textDate($e, "present");
			$file['encrypted'] = $f;
	    	 
	    	array_push($files, $file);
	    	
	    }

echo json_encode($files);

?>