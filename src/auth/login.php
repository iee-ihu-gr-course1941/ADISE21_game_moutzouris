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
		//EDW GINETAI TO REGISTER SE PERIPTOSI POY DEN YPARXEI TO USERNAME
		$sql_u="SELECT * FROM users WHERE username='$username'";
		$res_u=mysqli_query($conn,$sql_u) or die(mysqli_error($conn));
		if(mysqli_num_rows($res_u) == 0){			
			$sql = "INSERT INTO users VALUES (default,'$username','$pass')";
			$result=mysqli_query($conn,$sql) or die (mysqli_error($conn));	
		}
		$sql = "SELECT * FROM users WHERE username='$username' AND password='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if ($row['username'] === $username && $row['password'] === $pass) {
				$_SESSION['username'] = $row['username'];
				$token = generateRandomString(20);
				$_SESSION['user_token'] = $token;
				$_SESSION['user_id'] = $row['id'];
				unset($_SESSION['game_session']);
				header("Location: ../pages/lobby.php");
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
