<?php

// RENAME THIS FILE TO config.php

// Details used to connect to the DB
$db_server = "localhost";
$db_username = "root";
$db_password = "root";
$db_name = "securedrop";

$config['requireHTTPS'] = false;

// Maximum sile in MB - ensure that this is the same or lower than your php.ini settings for;
// memory_limit
// post_max_size
// upload_max_filesize
$config['max_file_size'] = 30;

// File expiry time in hours, after this ends the file will be removed
// by the cron job
$config['file_expiry'] = 7 * 24;

// Homepage for securedrop
$config['securedrop_home'] = "http://localhost:8888/securedrop";

// Upload location
// ensure you can write to this folder, and it should be proteced from indexes etc
// pref outside web root
$ds = DIRECTORY_SEPARATOR;
$config['upload_location'] = dirname( __FILE__ ) . $ds  . "uploads" . $ds; 


//encrypt files at rest
$config['encrypt'] = false;
//make sure you set the encryption key in the DB

//contact email address
$config['contact_email'] = "help@domain.com";

// accounts and login
$config['allow_accounts'] = true;
$config['auth_type'] = "local";
//                      local or adldap supported

//for adldap auth
$config['account_suffix'] = "@mydomain.local";
$config['base_dn'] = "DC=mydomain,DC=local";
$config['domain_controllers'] = array('dc01.mydomain.local');
//$config['use_ssl'] = false;
//$config['use_tls'] = true;



?>