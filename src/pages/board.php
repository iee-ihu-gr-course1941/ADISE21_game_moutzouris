<?php
include '../db/db_conn.php';
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
?>
     <!DOCTYPE html>
     <html>

     <head>
          <title>Μουτζούρης</title>
          <link rel="stylesheet" type="text/css" href="../styles/main.css">
          <link rel="stylesheet" type="text/css" href="../styles/board.css">
          <link rel="icon" type="image/x-icon" href="../../assets/king.png">
     </head>

     <body>
          <main class="wrapper">
               <div class="header">
                    <div class='player-turn-container'>
                         <div>
                              <h2>Σειρά παίκτη: &nbsp;</h2>
                              <h2 id="current-turn">1</h2>
                         </div>
                         <div>
                              <h3>Η σειρά μου: &nbsp;</h3>
                              <h3 id="player-turn">1</h3>
                         </div>
                    </div>
                    <div id="scoreboard">

                    </div>
                    <div class='user-session'>
                         <h3 class="current-username">Παίκτης: <?php echo $_SESSION['username']; ?></h3>
                         <button onclick="location.href = '../auth/logout.php';" id="logout-button" class="logout-button">Αποσύνδεση</button>
                    </div>
               </div>
               <div id="opponent-cards">

               </div>
               <div class="user-cards-row" id="my-cards">

               </div>
          </main>
          <div id='countdown'>
               <h2>Το παιχνίδι συνεχίζει σε</h2>
               <h1 id='countdown-seconds'>5 δευτερόλεπτα</h1>
          </div>

          <div id="game-end-overlay">
               <h1>Το παιχνίδι τελείωσε</h1>
               <div class="actions">
                    <button onclick='continueSession()'>Έναρξη νέου παιχνιδιού</button>
                    <button onclick="location.href = '../auth/logout.php'">Λήξη παιχνιδιού</button>
               </div>
          </div>

     </body>
     <script>
          let serverState = {
               player_turn: undefined,
               my_turn: undefined,
               user_id: undefined,
               number_of_players: undefined,
               winner: undefined,
               loser: undefined,
               last_change: undefined,
               status: undefined,
               remainingPlayers: [],
               round_no: 0
          };


          let clientState = {
               selectedCards: [],
               roundEnabled: false,
               winnerShown: false,
               winner: undefined,
               loserShown: false,
               loser: undefined,
               usernames: {},
               clientInitialized: false,
               previousRoundNumber: 0
          };
     </script>

     <script src="../scripts/render_board.js"> </script>
     <script src="../scripts/board.js"> </script>

     </html>
<?php
} else {
     header("Location: ../index.php");
     exit();
}
?>