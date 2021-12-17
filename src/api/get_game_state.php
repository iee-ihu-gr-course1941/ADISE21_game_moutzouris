<?php
session_start();
include('../db/db_conn.php');

// Headers
header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

global $conn;

function getGameState()
{
    global $conn;
    $sql = "SELECT * FROM game_status WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    //Check if there is a game instance
    if(isset($data['status'])){
        return $data;
    }
}

function getNumberOfPlayers()
{
    global $conn;
    $sql = "SELECT COUNT(user_id) as number_of_players FROM game_session WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    return $data['number_of_players'];
}
