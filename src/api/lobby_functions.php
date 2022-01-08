<?php
include('../db/db_conn.php');

function checkIfPlayerInSession()
{
    global $conn;
    if (isset($_SESSION['user_id'])) {

        //Check if player is already in a session
        $sql = "SELECT session_id FROM game_session WHERE user_id='{$_SESSION['user_id']}' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')";
        $result = mysqli_query($conn, $sql);
        $data = $result->fetch_assoc();

        //If is already in session, update the token in db
        if (isset($data['session_id'])) {
            $_SESSION['session_id'] = $data['session_id'];
            $sql = "UPDATE game_session SET user_token = '{$_SESSION['user_token']}' WHERE user_id='{$_SESSION['user_id']}'";
            mysqli_query($conn, $sql);
            checkGameInstance($_SESSION['session_id']);
        } else {

            //If is not in a session, add into one
            $available_session = getAvailableSession();
            if (isset($available_session)) {
                addUserToSession($available_session);
            }
        }
    }
}

//Check if there are any available sessions or create one
function getAvailableSession()
{
    global $conn;
    //Get all sessions with under 4 players
    $sql = "SELECT DISTINCT session_id, COUNT(*) as number_of_players FROM game_session WHERE session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended' OR status='started') GROUP BY session_id";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        if ($row['number_of_players'] < 4) {
            //Return the available session
            return $row['session_id'];
        }
    }


    //There are no availble sessions, so create a new session
    $sql = "SELECT max(id) as last_session_id FROM game_status";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    $current_session = $data['last_session_id'] + 1;

    //Add player to game_session table
    if (isset($_SESSION['user_id'])) {
        addUserToSession($current_session);
    }
}

function checkGameInstance($session_id)
{
    global $conn;
    //Check if instance of game is already initialized or initialize one
    $sql = "SELECT * FROM game_status WHERE session_id='$session_id' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended' OR status='started')";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();

    //If there is no instance, create a new one
    if (empty($data)) {
        $sql = "INSERT INTO game_status VALUES (default,'$session_id', default, 1, 1, '0', '0', default ,NOW(), default)";
        $result = mysqli_query($conn, $sql);
    }
}

function addUserToSession($session_id)
{
    global $conn;
    $sql = "INSERT INTO game_session values (default, $session_id,'{$_SESSION['username']}', '{$_SESSION['user_id']}', 1 ,'{$_SESSION['user_token']}', 0, 0)";
    mysqli_query($conn, $sql);
    $_SESSION['session_id'] = $session_id;
    checkGameInstance($session_id);
}

function getSessionPlayers()
{
    global $conn;
    $players = array();
    $sql = "SELECT * FROM game_session WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        $player = array("{$row['user_id']}" => "{$row['username']}");
        array_push($players, $player);
    }
    return $players;
}

function checkIfGameStarted()
{
    global $conn;
    $sql = "SELECT * FROM game_status WHERE session_id='{$_SESSION['session_id']}'";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    if (!empty($data)) {
        return $data['status'];
    } else {
        return 'null';
    }
}
