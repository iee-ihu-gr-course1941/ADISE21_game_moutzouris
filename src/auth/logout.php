<?php
include "../db/db_conn2.php";
session_start();

if (isset($_SESSION['session_id'])) {
    $sql = "DELETE FROM game_session WHERE user_token='{$_SESSION['session_id']}'";
    mysqli_query($conn, $sql);
}

session_unset();
session_destroy();
header("Location: ../index.php");
