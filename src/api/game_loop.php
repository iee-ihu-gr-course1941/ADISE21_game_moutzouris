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
    $sql = "SELECT card_id, card_name, player_id, player_turn, url FROM current_cards INNER JOIN cards ON cards.id=current_cards.card_id WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $cards = array();
    while ($card = $result->fetch_assoc()) {
        array_push($cards, $card);
    }
    return $cards;
}

function splitCardsByUser($shuffled_cards)
{
    $cards_by_user = array();
    foreach ($shuffled_cards as $value) {
        $cards_by_user[$value['player_turn']][] = $value;
    }
    return $cards_by_user;
}

function getGameState()
{
    global $conn;
    $sql = "SELECT status, player_turn, winner, loser, number_of_players, first_round, last_change FROM game_status WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result->fetch_assoc();
}

function getPlayerTurn()
{
    global $conn;
    $sql = "SELECT player_turn FROM game_session WHERE session_id='{$_SESSION['session_id']}' AND user_token='{$_SESSION['user_token']}'";
    $result = mysqli_query($conn, $sql);
    return $result->fetch_assoc()['player_turn'];
}

$shuffled_cards = getShuffledCards();
$cards_by_user = splitCardsByUser($shuffled_cards);
$game_state = getGameState();
$player_turn = getPlayerTurn();

$response = array('game_state' => array_merge(array('my_turn' => $player_turn), $game_state), 'player_cards' => $cards_by_user);

echo json_encode($response);
