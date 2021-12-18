<?php
session_start();
include('../db/db_conn.php');

// Headers
header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

global $conn;

function getShuffledCards()
{
    global $conn;
    $sql = "SELECT card_id, card_name, player_id FROM current_cards WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $cards = array();
    while ($card = $result->fetch_assoc()) {
        array_push($cards, $card);
    }
    return $cards;
}

function splitCardsByUser($shuffledCards)
{
    $cards_by_user = array();
    foreach ($shuffledCards as $value) {
        $cards_by_user[$value['player_id']][] = $value;
    }
    return $cards_by_user;
}

function getGameState()
{
    global $conn;
    $sql = "SELECT status, user_turn, number_of_players, last_change FROM game_status WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result->fetch_assoc();
}

$shuffledCards = getShuffledCards();
$cards_by_user = splitCardsByUser($shuffledCards);
$game_state = getGameState();

$response = array('cards' => $cards_by_user, 'game_state' => $game_state);

echo json_encode($response);
