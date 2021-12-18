<?php

$host = "localhost";
$username = "root";
$password = "";
$db_name = "moutzouris";

$mysqli = new mysqli($host, $user, $pass, $db, null, '/home/staff/it154486/mysql/run/mysql.sock');
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" .
		$mysqli->connect_errno . ") " . $mysqli->connect_error;
} ?>


// $conn = mysqli_connect($sname, $username, $password, $db_name);

// if (!$conn) {
// echo "Connection failed!";
// }