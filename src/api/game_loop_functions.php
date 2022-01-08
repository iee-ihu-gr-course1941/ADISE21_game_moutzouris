<?php
include('../db/db_conn.php');

function getShuffledCards()
{
    global $conn;
    $sql = "SELECT card_id, card_name, player_id, player_turn, url FROM current_cards INNER JOIN cards ON cards.id=current_cards.card_id WHERE session_id='{$_SESSION['session_id']}' ORDER BY card_name";
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
    $sql = "SELECT status, player_turn, winner, loser, number_of_players, first_round, last_change, round_no FROM game_status WHERE session_id='{$_SESSION['session_id']}'";
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

function getUsernames()
{
    global $conn;
    $sql = "SELECT username, player_turn FROM game_session WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        array_push($data, array($row['player_turn'] => $row['username']));
    }
    return $data;
}

function getScoreboard()
{
    global $conn;
    $sql = "SELECT username, wins, loses from game_session WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);

    $scoreboard = array();

    while ($row = $result->fetch_assoc()) {
        array_push($scoreboard, array('username' => $row['username'], 'wins' => $row['wins'], 'loses' => $row['loses']));
    }

    return $scoreboard;
}

function getRemainingPlayers($cards_by_player)
{
    $remainingPlayers = array();

    foreach ($cards_by_player as $player_turn => $cards) {
        array_push($remainingPlayers, $player_turn);
    }

    sort($remainingPlayers);

    return $remainingPlayers;
}
