<?php

// RENAME THIS FILE TO config.php

// Details used to connect to the DB
$db_server = "localhost";
$db_username = "root";
$db_password = "root";
$db_name = "securedrop";

$config['requireHTTPS'] = false;

// Maximum sile in MB - ensure that this is the same or lower than your php.ini settings for
// memory_limit
// post_max_size
$config['max_file_size'] = 30;

// File expiry time in hours, after this ends the file will be removed
// by the cron job
$config['file_expiry'] = 7 * 24;

?>