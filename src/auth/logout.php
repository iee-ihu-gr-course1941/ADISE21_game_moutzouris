<?php
include "../db/db_conn.php";
session_start();

if (isset($_SESSION['id'])) {
    $token = $_SESSION['id'];
    $sql = "DELETE FROM game_session WHERE userToken='$token'";
    $result = mysqli_query($conn, $sql);
}

session_unset();
session_destroy();
header("Location: ../index.php");
