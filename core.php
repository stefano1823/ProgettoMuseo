<?php
session_start();
$db_hostname = 'localhost';
$db_username = 'onlinemuseum';
$db_password = '';
$db_name = 'my_onlinemuseum';
include_once __DIR__ . '/libs/csrf/csrfprotector.php'; 
csrfProtector::init();
mysql_select_db($db_name, mysql_pconnect($db_hostname, $db_username, $db_password));
mysql_query('CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(16) NOT NULL, password VARCHAR(32) NOT NULL, email VARCHAR(60) NOT NULL);');
function clear($var) {
	return addslashes(htmlspecialchars(trim($var)));
}
