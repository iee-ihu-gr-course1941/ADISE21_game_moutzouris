<?php
session_start();
include './db/db_conn.php';
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lobby</title>
        <link rel="stylesheet" href="./styles/main.css">
        <link rel="stylesheet" href="./styles/lobby.css">

    </head>

    <body>
        <h1>Lobby</h1>
        <h2>Δημιουργία παιχνιδιού...</h2>
        <div class="table-container">
            <table id="lobby-table">
                <tr>
                    <th>Νο. Παίκτη</th>
                    <th>Username</th>
                </tr>
            </table>
        </div>
        <p id='game-check'>Χρειάζονται τουλάχιστον 2 παίκτες για να ξεκινήσει το παιχνίδι</p>
        <button id="start-game" class='disabled' disabled>
            Έναρξη
        </button>
    </body>
    <script src="./scripts/lobby.js"></script>

    </html>

<?php
} else {
    header("Location: index.php");
    exit();
}
include('./footer.php');
?>