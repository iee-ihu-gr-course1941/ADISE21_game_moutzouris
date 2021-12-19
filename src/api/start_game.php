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
    global $conn;
    $sql = "SELECT id, cardname FROM cards WHERE cardchar != 'back'";

    $result = mysqli_query($conn, $sql);

    $cards = $result->fetch_all();
    shuffle($cards);
    return $cards;
}

//Select all players from game_session and count them
function getSessionPlayers()
{
    global $conn;
    $sql = "SELECT COUNT(user_id) AS number_of_players FROM game_session WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    return $data['number_of_players'];
}

//Update turns of players in game_session
function arrangeTurns()
{
    global $conn;
    $sql = "SELECT user_id FROM game_session WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $player_turns = array();
    $i = 1;
    //Update player turns in game_session table
    while ($row = $result->fetch_assoc()) {
        array_push($player_turns, array($row['user_id'] => $i));
        $sql = "UPDATE game_session SET player_turn='$i' where user_id='{$row['user_id']}'";
        mysqli_query($conn, $sql);
        $i++;
    }
    return $player_turns;
}

//Deal shuffled cards to players an put them in current_cards table
function dealCards($shuffledCards, $player_turns)
{
    global $conn;
    //Clear previous cards
    $sql = "DELETE FROM current_cards WHERE session_id='{$_SESSION['session_id']}'";
    mysqli_query($conn, $sql);
    $number_of_players = count($player_turns);

    $i = 1;
    foreach ($shuffledCards as $key => $card) {
        $user_id = array_keys($player_turns[$i - 1])[0];
        $sql = "INSERT INTO current_cards values (default, '$card[0]', '$card[1]','$user_id', '$i','{$_SESSION['session_id']}')";
        mysqli_query($conn, $sql);
        if ($i >= $number_of_players) {
            $i = 1;
        } else {
            $i++;
        }
    }
}

//Change game status
function changeGameStatus($number_of_players)
{
    global $conn;
    $sql = "UPDATE game_status SET status = 'started', number_of_players=$number_of_players WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    if (!empty($result)) {
        $_SESSION['game_status'] = 'started';
    }
}

$shuffledCards = shuffleCards();
$number_of_players = getSessionPlayers();
$player_turns = arrangeTurns();
dealCards($shuffledCards, $player_turns);
changeGameStatus($number_of_players);

echo json_encode($shuffledCards);
