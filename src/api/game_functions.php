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
                    echo json_encode(array('status' => 'Successfully swapped'));
                } else {
                    echo json_encode(array('status' => 'Something went wrong'));
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
    $sql = "UPDATE current_cards SET player_id='$to_player' WHERE card_id='$card_id' AND player_id='$from_player' AND session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// function endTurn(){
//     global $conn;
//     $sql = 
// }
