<?php
session_start();
include './db_conn.php';
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {

?>
     <!DOCTYPE html>
     <html>

     <head>
          <title>HOME</title>
          <link rel="stylesheet" type="text/css" href="home_style.css">

          <script>

          </script>
     </head>



     <body>

          <button onclick="addCard();" class="add_button" id="add_button">Add card test</button>

          <div class="clearfix">
               <?php
               $sqlQuery = "SELECT * FROM Kartes";
               $result = $conn->query($sqlQuery);
               if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                         // echo "id: " . $row["id"] . " - CardName: " . $row["cardname"] . " " . $row["cardchar"] . "URL: " . $row["url"] . "<br>";
                         echo '<div class="img-container">';
                         echo '<img src="' . $row["url"] . '" alt="Assos">';
                         echo '</div>';
                    }
               } else {
                    echo "0 results";
               }
               ?>
          </div>


          <h3 class="user_who">Χρήστης: <?php echo $_SESSION['name']; ?></h3>
          <button onclick="location.href = 'logout.php';" id="logout_button" class="logout_button">Logout</button>

     </body>
     <script>
          console.log(<?php $result ?>);
     </script>

     </html>

<?php
} else {
     header("Location: index.php");
     exit();
}
?>