<?php
session_start();
include('../db/db_conn.php');
include('./board_functions.php');
include('./game_functions.php');
include('./lobby_functions.php');
include('./game_loop_functions.php');

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
    case 'lobby':
        checkIfPlayerInSession();
        $players = getSessionPlayers();
        $status = checkIfGameStarted();

        echo json_encode(array(
            'players' => $players,
            'game_status' => $status
        ));
        break;
    case 'start-game':
        
        $shuffledCards = shuffleCards();
        $number_of_players = getSessionPlayersNames();
        $player_turns = arrangeTurns();
        dealCards($shuffledCards, $player_turns);
        changeGameStatus($number_of_players);

        echo json_encode($shuffledCards);
        break;
    case 'game-status':

        $shuffled_cards = getShuffledCards();
        $cards_by_user = splitCardsByUser($shuffled_cards);
        $game_state = getGameState();
        $player_turn = getPlayerTurn();
        $usernames = getUsernames();
        $scoreboard = getScoreboard();
        $remainingPlayers = getRemainingPlayers($cards_by_user);

        $response = array('game_state' => array_merge(array('my_turn' => $player_turn, 'remainingPlayers' => $remainingPlayers), $game_state), 'player_cards' => $cards_by_user, 'usernames' => $usernames, 'scoreboard' => $scoreboard);

        echo json_encode($response);

        break;

    case 'continue-session':
        if (prepareNextGame()) {
            echo json_encode(array('status' => '200'));
        } else {
            echo json_encode(array('status' => '404'));
        }
        break;
    case 'status':
        if (sizeof($request) == 0) {
            handleStatus($method);
        } else {
            header("HTTP/1.1 404 Not Found");
        }
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit;
}

function handleStatus($status){
    if ($status){
        echo json_encode(array('status' => '404','message' => 'Something went wrong'));
    }
}