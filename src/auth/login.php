<?php
session_start();
include "../db/db_conn.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

	function validate($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$username = validate($_POST['username']);
	$pass = validate($_POST['password']);

	if (empty($username)) {
		header("Location: ../index.php?error=Το όνομα χρήστη είναι απαραίτητο");
		exit();
	} else if (empty($pass)) {
		header("Location: ../index.php?error=Ο κωδικός είναι απαραίτητος");
		exit();
	} else {
		$sql = "SELECT * FROM users WHERE username='$username' AND password='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if ($row['username'] === $username && $row['password'] === $pass) {
				$_SESSION['username'] = $row['username'];
				$token = generateRandomString(20);
				$_SESSION['id'] = $token;
				addUserToSession($username, $row['id'], $token, $conn);
				header("Location: ../lobby.php");
				exit();
			} else {
				header("Location: ../index.php?error=Λάθος όνομα χρήστη ή κωδικός");
				exit();
			}
		} else {
			header("Location: ../index.php?error=Λάθος όνομα χρήστη ή κωδικός");
			exit();
		}
	}
} else {
	header("Location: ../index.php");
	exit();
}

function generateRandomString($length = 20)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function addUserToSession($username, $uid, $token, $conn)
{
	$sql = "INSERT INTO game_session values (default,'$username','$uid','$token')";
	$result = mysqli_query($conn, $sql);
	echo $result;
}
