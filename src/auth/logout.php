<?php
include "../db/db_conn.php";

global $conn;

function logout()
{
    session_start();
    global $conn;
    if (isset($_SESSION['session_id'])) {
        $sql = "DELETE FROM game_session WHERE user_token='{$_SESSION['user_token']}'";
        mysqli_query($conn, $sql);

        $sql = "UPDATE game_status set status='aborted' WHERE session_id='{$_SESSION['session_id']}'";
        mysqli_query($conn, $sql);
    }
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

logout();