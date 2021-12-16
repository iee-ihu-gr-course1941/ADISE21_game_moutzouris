<?php
session_start();
include('../db/db_conn.php');

// Headers
header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$players = array();
$game_status = '';

$sql = "SELECT * FROM game_session";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    $player = array("{$row['uid']}" => "{$row['username']}");
    array_push($players, $player);
}

function checkGameStarted()
{
    global $conn,$game_status;
    $sql = "SELECT * FROM game_status WHERE id=1";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        $game_status = $row['status'];
    }
}

checkGameStarted();

echo json_encode(array(
    'players' => $players,
    'game_status' => $game_status
));
