<?php
include '../db/db_conn.php';
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    if (isset($_SESSION['game_status']) && $_SESSION['game_status'] == 'started') {
        header('Location: ./board.php');
        exit();
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lobby</title>
        <link rel="stylesheet" href="../styles/main.css">
        <link rel="stylesheet" href="../styles/lobby.css">
        <link rel="icon" type="image/x-icon" href="../../assets/king.png">

    </head>

    <body>
        <div class="header">
            <div class='user-session'>
                <h3 class="current-username">Χρήστης: <?php echo $_SESSION['username']; ?></h3>
                <button onclick="location.href = '../auth/logout.php';" id="logout-button" class="logout-button">Αποσύνδεση</button>
            </div>
        </div>
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
        <button id="start-game" class='disabled' onclick="startGame()" disabled>
            Έναρξη
        </button>
    </body>
    <script src="../scripts/lobby.js"></script>

    </html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
include('./footer.php');
?>