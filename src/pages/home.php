<?php
include '../db/db_conn.php';
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
?>
     <!DOCTYPE html>
     <html>

     <head>
          <title>Μουτζούρης</title>
          <link rel="stylesheet" type="text/css" href="../styles/main.css">
          <link rel="stylesheet" type="text/css" href="../styles/home_style.css">
          <script src="../scripts/script_home.js" defer> </script>
     </head>

     <body>
          <main class="wrapper">
               <div class="header">
                    <div class='user-session'>
                         <h3 class="current-username">Χρήστης: <?php echo $_SESSION['username']; ?></h3>
                         <button onclick="location.href = '../auth/logout.php';" id="logout-button" class="logout-button">Αποσύνδεση</button>
                    </div>
               </div>
               <div class="oponent-cards">

               </div>
               <div class="user-cards-row" id="my-cards">

               </div>
          </main>

     </body>

     </html>
<?php
} else {
     header("Location: ../index.php");
     exit();
}
?>