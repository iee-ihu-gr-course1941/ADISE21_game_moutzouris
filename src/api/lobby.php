<?php
include('../db/db_conn.php');

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$players = array();

$sql = "SELECT * FROM game_session";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    $player = array("{$row['uid']}" => "{$row['username']}");
    array_push($players, $player);
}

echo json_encode($players);
