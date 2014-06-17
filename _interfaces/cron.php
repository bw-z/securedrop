<?php

include_once("../config.php");
include_once("../core.php");

$t = time();
//load expired files
$query = $db->prepare('SELECT accesskey, fileid FROM files WHERE expiry < ?');
$query->bind_param('s', $t);
$query->execute();

$query->bind_result($a, $b);

$files = array();

while ($query->fetch()) {
	array_push($files, array('key'=>$a, 'fileid'=>$b));
}

$query->close();

foreach ($files as $f) {
	
	$targetFile = $config['upload_location'] . $f['key'] . ".secureuploadfile";

	$query = $db->prepare('DELETE FROM files WHERE fileid = ?');
	$query->bind_param('s', $f['fileid']);
	$query->execute();
	
	unlink($targetFile);
}





?>