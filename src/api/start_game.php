<?php
session_start();
include('../db/db_conn.php');

// Headers
header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


//Select all cards and shuffle them
function shuffleCards()
{
    global $conn, $cardIds;
    $sql = "SELECT id FROM cards WHERE cardchar != 'back'";

    $result = mysqli_query($conn, $sql);

    $cardIds = $result->fetch_all();
    shuffle($cardIds);
}

//Select all players from game_session and count them
function countPlayers()
{
    global $conn, $number_of_players;
    $sql = "SELECT COUNT(uid) AS number_of_players FROM game_session";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    $number_of_players = $data['number_of_players'];
}

//Change game status
function changeGameStatus($status, $p_turn, $number_of_players)
{
    global $conn;
    $sql = "UPDATE game_status SET status = '$status' , p_turn=$p_turn,number_of_players=$number_of_players WHERE id = 1";
    mysqli_query($conn, $sql);
}

shuffleCards();
countPlayers();
changeGameStatus('started', 1, $number_of_players);
$_SESSION['game_status'] = 'started';


echo json_encode($cardIds);

// Instantiate DB & connect
// $database = new Database();
// $db = $database->connect();

// Instantiate blog post object
// $post = new Post($db);


// Get raw posted data
// $data = json_decode(file_get_contents("php://input"));

// $post->title = $data->title;
// $post->body = $data->body;
// $post->author = $data->author;
// $post->category_id = $data->category_id;

// // Create post
// if ($post->create()) {
//     echo json_encode(
//         array('message' => 'Post Created')
//     );
// } else {
//     echo json_encode(
//         array('message' => 'Post Not Created')
//     );
// }
