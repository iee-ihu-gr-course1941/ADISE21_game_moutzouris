<?php
include('../db/db_conn.php');

function swap_card($from_player, $to_player, $card_id)
{
    global $conn;
    $sql = "UPDATE current_cards SET player_turn='$to_player' WHERE card_id='$card_id' AND player_turn='$from_player' AND session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    checkIfPlayerFinished($from_player);
    return $result;
}

function end_turn($next_player)
{
    global $conn;
    $sql = "UPDATE game_status set player_turn=$next_player, first_round=0, round_no = round_no + 1, last_change=NOW() WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result;
}


function discard_cards($player_turn, $card_id_1, $card_id_2)
{
    global $conn;
    $sql = "SELECT player_turn FROM game_session WHERE user_token='{$_SESSION['user_token']}'";
    $result = mysqli_query($conn, $sql);
    if (isset($result)) {
        $pt = $result->fetch_assoc()['player_turn'];
        if ($player_turn == $pt) {
            $sql = "DELETE FROM current_cards WHERE session_id='{$_SESSION['session_id']}' AND player_turn='$player_turn' AND card_id='$card_id_1'";
            $result = mysqli_query($conn, $sql);
            $sql = "DELETE FROM current_cards WHERE session_id='{$_SESSION['session_id']}' AND player_turn='$player_turn' AND card_id='$card_id_2'";
            $result = mysqli_query($conn, $sql);
            updateLastChange();
            return $result;
        }
    }
    return false;
}

function updateLastChange()
{
    global $conn;
    $sql = "UPDATE game_status set first_round=0, last_change=NOW() WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function checkIfPlayerFinished($player_turn)
{
    global $conn;
    $sql = "SELECT * from current_cards WHERE player_turn='$player_turn' AND session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);

    $same = false;
    if (mysqli_num_rows($result) == 2) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($rows, $row);
        }
        if ($rows[0]['card_name'] == $rows[1]['card_name']) {
            $sql = "DELETE FROM current_cards WHERE player_turn='$player_turn' AND session_id='{$_SESSION['session_id']}'";
            mysqli_query($conn, $sql);
            $same = true;
        }
    }

    if ((mysqli_num_rows($result) == 2 && $same) || mysqli_num_rows($result) == 0) {
        $winnerExists = checkIfWinnerExists();
        if (!$winnerExists) {
            $sql = "UPDATE game_status SET winner='$player_turn', last_change=NOW() WHERE session_id='{$_SESSION['session_id']}'";
            mysqli_query($conn, $sql);
            $sql = "UPDATE game_session SET wins = wins + 1 WHERE player_turn='$player_turn' AND session_id='{$_SESSION['session_id']}'";
            mysqli_query($conn, $sql);
        }
        return true;
    } else {
        return false;
    }
}

function checkIfWinnerExists()
{
    global $conn;
    $sql = "SELECT winner FROM game_status WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $winner = $result->fetch_assoc()['winner'];
    if ($winner != '0') {
        return true;
    } else {
        return false;
    }
}

function checkGameEnded()
{
    global $conn;
    $sql = "SELECT player_turn, card_name FROM current_cards WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $cards_by_user = array();
    while ($row = $result->fetch_assoc()) {
        $cards_by_user[$row['player_turn']][] = $row['card_name'];
    }
    //Split cards by user
    foreach ($cards_by_user as $player_turn => $cards) {
        //If one user has one king then he lost
        if (count($cards) == 1 && $cards[0] == 'king') {
            $winner = checkIfWinnerExists();
            if ($winner) {
                $sql = "UPDATE game_status SET loser='$player_turn' WHERE session_id='{$_SESSION['session_id']}'";
                $result = mysqli_query($conn, $sql);
                $sql = "UPDATE game_session SET loses= loses + 1 WHERE player_turn='$player_turn' AND session_id='{$_SESSION['session_id']}'";
                mysqli_query($conn, $sql);
                endGame();
            }
        }
    }
    //Update last change in game_status
    updateLastChange();
}

function endGame()
{
    global $conn;
    $sql = "UPDATE game_status SET status='ended' WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result;
}
