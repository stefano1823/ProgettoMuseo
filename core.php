<?php
session_start();
$db_hostname = 'localhost';
$db_username = 'onlinemuseum';
$db_password = '';
$db_name = 'my_onlinemuseum';

mysql_select_db($db_name, mysql_pconnect($db_hostname, $db_username, $db_password));
mysql_query('CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(16) NOT NULL, password VARCHAR(32) NOT NULL, email VARCHAR(60) NOT NULL, reg_ip VARCHAR(20), last_ip VARCHAR(20), reg_date INT NOT NULL, last_login INT)');
function clear($var) {
	return addslashes(htmlspecialchars(trim($var)));
}
