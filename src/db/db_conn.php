<?php

$host = "localhost";
$username = "root";
$password = "000000";
$db_name = "moutzouris";

$mysqli = new mysqli($host, $user, $pass, $db, null, '/home/student/it/2015/it154486/mysql/run/mysql.sock');
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" .
		$mysqli->connect_errno . ") " . $mysqli->connect_error;
} ?>
