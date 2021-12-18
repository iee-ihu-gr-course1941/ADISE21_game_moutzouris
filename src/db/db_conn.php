<?php

$host = "localhost";
$username = "root";
$password = "";
$db_name = "moutzouris";

$conn = new mysqli($host, $user, $pass, $db, null, '/home/student/it/2015/it154486/mysql/run/mysql.sock');
if ($conn->connect_errno) {
	echo "Failed to connect to MySQL: (" .
		$conn->connect_errno . ") " . $conn->connect_error;
} ?>
