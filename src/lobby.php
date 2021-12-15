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
        <link rel="stylesheet" href="./styles/lobby.css">
    </head>

    <body>
        <h1>Lobby</h1>
        <table id="lobby-table">
            <tr>
                <th>Νο. Παίκτη</th>
                <th>Username</th>
            </tr>
            <tr>
                <td>Alfreds Futterkiste</td>
                <td>Maria Anders</td>
                <td>Germany</td>
            </tr>
        </table>
    </body>

    <script>
        function addToTable(uid, username) {
            const tableRow = document.createElement('tr');
            const firstColumn = document.createElement('td');
            const secondColumn = document.createElement('td');
            firstColumn.innerText = uid;
            secondColumn.innerText = username;
            tableRow.appendChild(firstColumn);
            tableRow.appendChild(secondColumn);
            document.getElementById('lobby-table').appendChild(tableRow);
        }
        setInterval(() => {
            document.getElementById('lobby-table').innerHTML = `<tr>
                <th>Νο. Παίκτη</th>
                <th>Username</th>
            </tr>`;
            <?php
            $sql = "SELECT * FROM game_session";
            $result = mysqli_query($conn, $sql);
            while ($row = $result->fetch_assoc()) {
            ?>
                addToTable('<?php echo $row['uid'] ?>', '<?php echo $row['username'] ?>')
            <?php
            }
            ?>
        }, 5000)
    </script>

    </html>

<?php
} else {
    header("Location: index.php");
    exit();
}
?>