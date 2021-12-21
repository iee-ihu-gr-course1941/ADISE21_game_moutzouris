<?php
session_start();
include('../db/db_conn.php');
global $conn;

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
// $input = json_decode(file_get_contents('php://input'), true);
// if ($input == null) {
//     $input = [];
// }
// if (isset($_SERVER['HTTP_X_TOKEN'])) {
//     $input['token'] = $_SERVER['HTTP_X_TOKEN'];
// } else {
//     $input['token'] = '';
// }

switch ($r = array_shift($request)) {
    case 'board':
        switch ($b = array_shift($request)) {
            case '':
            case null:
                // handle_board($method, $input);
                break;
            case 'swap-card':
                if (swap_card($request[0], $request[1], $request[2])) {
                    echo json_encode(array('status' => '200'));
                } else {
                    echo json_encode(array('status' => '404'));
                }
                break;
            case 'end-turn':
                if (end_turn($request[0])) {
                    echo json_encode(array('status' => '200'));
                } else {
                    echo json_encode(array('status' => '404'));
                }
                break;
            case 'discard-cards':
                checkIfPlayerFinished($request[0]);

                if (discard_cards($request[0], $request[1], $request[2])) {
                    checkGameEnded();
                    echo json_encode(array('status' => '200'));
                } else {
                    echo json_encode(array('status' => '404'));
                }
                break;
            default:
                header("HTTP/1.1 404 Not Found");
                break;
        }
        break;
    case 'status':
        if (sizeof($request) == 0) {
            // handle_status($method);
        } else {
            header("HTTP/1.1 404 Not Found");
        }
        break;
    case 'players':
        // handle_player($method, $request, $input);
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit;
}

function swap_card($from_player, $to_player, $card_id)
{
    global $conn;
    $sql = "UPDATE current_cards SET player_turn='$to_player' WHERE card_id='$card_id' AND player_turn='$from_player' AND session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function end_turn($next_player)
{
    global $conn;
    $sql = "UPDATE game_status set player_turn='$next_player', first_round=0, last_change=NOW()";
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
    $sql = "UPDATE game_status set last_change=NOW()";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function checkIfPlayerFinished($player_turn)
{
    global $conn;
    $sql = "SELECT * from current_cards WHERE player_turn='$player_turn' AND session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_all();
    if (count($data) == 2) {
        $winnerExists = checkIfWinnerExists();
        if (!$winnerExists) {
            $sql = "UPDATE game_status SET winner='$player_turn', last_change=NOW() WHERE session_id='{$_SESSION['session_id']}'";
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
            $sql = "UPDATE game_status SET loser='$player_turn' WHERE session_id='{$_SESSION['session_id']}'";
            $result = mysqli_query($conn, $sql);
            endGame();
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
