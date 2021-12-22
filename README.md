# ADISE21_game_moutzouris
Παιχνίδι Χαρτιών - Μουτζούρης

# Συντάκτες

* Παναγιώτης Μαλλιότας
* Μιλτιάδης Παπαμαυρουδής
* Σταύρος Κούλας

# Τεχνολογίες

* HTML
* JavaScript
* CSS
* MySQL
* PHP

# Περιγραφή

Στην αρχική σελίδα της εφαρμογής υπάρχει ένα login, o χρήστης εισάγει τα διαπιστευτήρια του Username και Password  ή φτιάχνει ένα καινούργιο λογαριασμό και έπειτα μπαίνει στην επόμενη φάση που είναι το lobby. Σε δεύτερη φάση ο χρήστης περιμένει να μπει τουλάχιστον ένας χρήστης ώστε να ξεκινήσει το παιχνίδι, με χωρητικότητα τεσσάρων ατόμων. Σε περίπτωση που εισαχθούν στην εφαρμογή πάνω από τέσσερις χρήστες, ο χρήστης μπαίνει σε ένα διαφορετικό lobby και περιμένει νέους χρήστες να εισαχθούν. Κατά την εκκίνηση του παιχνιδιού ο κάθε παίκτης παίρνει μια κρυφή κάρτα μόνο από τον αντίπαλο  του αριστερά και αν έχει δυο κάρτες ίδιου αριθμού, τις αφαιρεί από το παιχνίδι. Αν τελειώσουν τα φύλλα του παίκτη, βγαίνει από το παιχνίδι σαν νικητής και το παιχνίδι συνεχίζεται για τους υπόλοιπους παίκτες. Χαμένος στο παιχνίδι αυτός που μένει με τον Ρήγα στο χέρι και κρατείτε ένα Score. 

# Demo

Το demo μπορείτε να το βρείτε στην σελίδα: https://users.iee.ihu.gr/~it154486/ADISE21_game_moutzouris/src/index.php 

# Σχεδίαση της βάσης 

Στο αρχειο schema.sql, Στην βάση με όνομα moutzouris φτιάχνουνε πινάκες: 

Ο Πίνακας users περιεχέι username,password και ένα id. Επίσης φαίνεται η λογική του insert. Το default πεδίο παίρνει την ιδιότητα του auto_increment.
```
CREATE DATABASE moutzouris;
```

```
CREATE TABLE users (
    `id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (id)
);

```
```
INSERT INTO `users` VALUES (default, 'panos', 'abc');
INSERT INTO `users` VALUES (default, 'miltos', 'abc');
INSERT INTO `users` VALUES (default, 'stavros', 'abc');
```

Ο πίνακας game_session περιέχει την κατάσταση του παίκτη. Όπως την σειρά του, το primary id του από τον πίνακα users, σε πιο session παιχνιδιού είναι και to token του.   
```
CREATE TABLE game_session (
    `id` int NOT NULL AUTO_INCREMENT,
    `session_id` int,
    `username` varchar(255) NOT NULL,
    `user_id` int NOT NULL,
    `player_turn` enum('1', '2', '3', '4'),
    `user_token` varchar(20) NOT NULL,
    PRIMARY KEY (id)
);
```

Ο πίνακας cards περιέχει όλες τις κάρτες με ένα id, ένα όνομα , μια κλάση και μια εικόνα σε ενα url.
```
CREATE TABLE cards (
    `id` int NOT NULL,
    `cardname` varchar(255) NOT NULL,
    `cardchar` varchar(255) NOT NULL,
    `url` varchar(2000) NOT NULL,
    PRIMARY KEY(id)
);
```
```
INSERT INTO
    cards
VALUES
    (
        1,
        'ace',
        'clubs',
        'https://upload.wikimedia.org/wikipedia/commons/3/36/Playing_card_club_A.svg'
    );
```

Ο πίνακας game_status αποθηκεύει την κατάσταση του παιχνιδιού δηλαδή κατάσταση των παικτών, σειρά παικτών,αριθμό παικτών, τους νικητές και το session που βρίσκονται οι παίκτες. 
```
CREATE TABLE game_status (
    `id` int NOT NULL AUTO_INCREMENT,
    `status` enum(
        'initialized',
        'started',
        'ended',
        'aborted'
    ) NOT NULL DEFAULT 'initialized',
    `player_turn` int NOT NULL DEFAULT 1,
    `number_of_players` int NOT NULL,
    `winner` int DEFAULT NULL,
    `last_change` timestamp DEFAULT NOW(),
    `session_id` int not null,
    PRIMARY KEY (id)
);
```

Ο πίνακας current_cards περιέχει το primary id των καρτών από τον πίνακα cards, όνομα καρτών, το primary id των χρηστών, την σειρά και το session id από τον πίνακα game_session το primary id του. 
```
CREATE TABLE current_cards (
    `id` int NOT NULL AUTO_INCREMENT,
    `card_id` int NOT NULL,
    `card_name` varchar(255) NOT NULL,
    `player_id` int NOT NULL,
    `player_turn` enum('1', '2', '3', '4'),
    `session_id` int NOT NULL,
    PRIMARY KEY(id)
);
```

Στο αρχείο db_upass.php, βρίσκεται το username της βάσης και ο κωδικός.
```
<?php
	$DB_PASS = 'sakasaka';
	$DB_USER = 'root';
?>
```

Στο αρχείο db_conn.php, κάνει την σύνδεση της απομακρυσμένης βάσης με τα παραπάνω στοιχειά. Σε περίπτωση που τα στοιχειά δεν αντιστοιχούνται κανονικά θα εμφανίσει ένα error. Η εντολή ```$conn = new mysqli($host, $user, $pass, $db, null, '/home/student/it/2015/it154486/mysql/run/mysql.sock');``` κάνει την σύνδεση της βάσης με τα παρακάτω στοιχειά.


```
<?php
$host = 'localhost';
$db = 'moutzouris';
require_once "db_upass.php";

$user = $DB_USER;
$pass = $DB_PASS;

if (gethostname() == 'users.iee.ihu.gr') {
    $conn = new mysqli($host, $user, $pass, $db, null, '/home/student/it/2015/it154486/mysql/run/mysql.sock');
} else {
    $conn = new mysqli($host, $user, '', $db);
}

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" .
        $conn->connect_errno . ") " . $conn->connect_error;
}
```

# Σχεδίαση της Login Σελίδας

Η κύρια σελίδα αποτελείται από δυο βασικά αρχεία. Το index.php και login.php 

Στo αρχείο index.php περιέχει την διεπαφή του χρήστη, ο χρήστης βάζει το username και το password.

```
<body>	
		<form action="auth/login.php" method="post">
			<h2>Είσοδος</h2>
			<?php if (isset($_GET['error'])) { ?>
				<p class="error"><?php echo $_GET['error']; ?></p>
			<?php } ?>
			<label>Όνομα Χρήστη</label>
			<input type="text" name="username" placeholder="User Name"><br>

			<label>Κωδικός</label>
			<input type="password" name="password" placeholder="Password"><br>

			<button type="submit">Είσοδος</button>
		</form>
	</body>
```

Στο αρχείο login.php περιέχει τον κώδικα για την διαδικασία του login, στην πραγματικότητα κάνει τον έλεγχο των χρηστών.

* Στην εντολή ```if (isset($_POST['username']) && isset($_POST['password'])) ``` παιρνει το username και password απο τo form στο index.php.

```
include "../db/db_conn.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

	function validate($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$username = validate($_POST['username']);
	$pass = validate($_POST['password']);

```
* Στην εντολή  ``` if (empty($username))``` ελέγχει αν πληκτρολογήθηκε username και αντίστοιχα password, αλλιώς βγάζει error ότι κάτι λείπει.
* Στην συνέχεια της εντολής if, ελέγχει  με ένα sql ερώτημα ```$sql_u="SELECT * FROM users WHERE username='$username'";``` αν υπάρχει το username. Αν δεν υπάρχει,γίνεται αυτόματα insert στον πίνακα users με το password που δόθηκε. 
* Στην συνέχεια της εντολής if, ελέγχει με ένα sql ερώτημα ``` $sql = "SELECT * FROM users WHERE username='$username' AND password='$pass'"; ``` αν υπάρχει το username και password στην βάση να επιστρέψει ενα αποτέλεσμα ```$result = mysqli_query($conn, $sql);``` και στην εντολή ```if (mysqli_num_rows($result) === 1) ``` κάνει την εισόδο στο lobby.
* Αν υπάρχει το username και το Password είναι διαφορετικό, τότε η εντολή ```$result = mysqli_query($conn, $sql);``` επιστρέφει 0 και δεν μπαίνει στην if.

```
if (empty($username)) {
		header("Location: ../index.php?error=Το όνομα χρήστη είναι απαραίτητο");
		exit();
	} else if (empty($pass)) {
		header("Location: ../index.php?error=Ο κωδικός είναι απαραίτητος");
		exit();
	} else {
		//EDW GINETAI TO REGISTER SE PERIPTOSI POY DEN YPARXEI TO USERNAME
		$sql_u="SELECT * FROM users WHERE username='$username'";
		$res_u=mysqli_query($conn,$sql_u) or die(mysqli_error($conn));
		if(mysqli_num_rows($res_u) == 0){			
			$sql = "INSERT INTO users VALUES (default,'$username','$pass')";
			$result=mysqli_query($conn,$sql) or die (mysqli_error($conn));	
		}
		$sql = "SELECT * FROM users WHERE username='$username' AND password='$pass'";

		$result = mysqli_query($conn, $sql);
		//EDW GINETAI H EISODOS STO LOBBY AN YPARXEI TO USERNAME KAI TO PASSWORD 
		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if ($row['username'] === $username && $row['password'] === $pass) {
				$_SESSION['username'] = $row['username'];
				$token = generateRandomString(20);
				$_SESSION['user_token'] = $token;
				$_SESSION['user_id'] = $row['id'];
				unset($_SESSION['game_session']);
				header("Location: ../pages/lobby.php");
				exit();
			} else {
				header("Location: ../index.php?error=Λάθος όνομα χρήστη ή κωδικός");
				exit();
			}
		} else {
			header("Location: ../index.php?error=Λάθος όνομα χρήστη ή κωδικός");
			exit();
		}
	}
} else {
	header("Location: ../index.php");
	exit();
}
```

# Σχεδίαση του Lobby

Το lobby αποτελείτε από τα δυο αρχεία με όνομα lobby.php (ένα για interface και ένα για κώδικα) και ένα αρχείο lobby.js.

Mε την συνάρτηση  getAvailableSession() φτιάχνει ένα session αν δεν υπάρχει ήδη. Όταν υπάρχει session βάζει μέχρι 4 παίκτες και αν έχει γεμίσει, τότε φτιάχνει ένα καινούργιο session.

* Με το ερώτημα ```$sql = "SELECT DISTINCT session_id, COUNT(*) as number_of_players FROM game_session WHERE session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended') GROUP BY session_id";``` επιστρέφει σε μια μεταβλητή των αριθμό των παικτών από τον πινάκα game_session οι οποίοι δεν βρίσκονται σε session.
```
function getAvailableSession()
{
    global $conn;
    //Get all sessions with under 4 players
    $sql = "SELECT DISTINCT session_id, COUNT(*) as number_of_players FROM game_session WHERE session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended') GROUP BY session_id";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        if ($row['number_of_players'] < 4) {
            //Return the available session
            return $row['session_id'];
        }
    }
```
* Αν το session έχει γεμίσει και είναι πάνω από 4 παίκτες τότε με το ερώτημα ```$sql = "SELECT max(id) as last_session_id FROM game_status";``` φτιάχνει ένα νέο session με id του προηγούμενου session +1. ``` $current_session = $data['last_session_id'] + 1; ```
* Οι παίκτες μπαίνουν σε νέο session με την συνάρτηση ``` function addUserToSession($session_id) ```

```
$sql = "SELECT max(id) as last_session_id FROM game_status";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    $current_session = $data['last_session_id'] + 1;

    //Add player to game_session table
    if (isset($_SESSION['user_id'])) {
        addUserToSession($current_session);
    }
```

* Η Συνάρτηση ``` checkIfPlayerInSession() ``` ελέγχει αν ο χρήστης έχει session αλλιώς καλεί την ``` function addUserToSession() ``` 
* Με το ερώτημα ```  $sql = "SELECT session_id FROM game_session WHERE user_id='{$_SESSION['user_id']}' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')";``` ελέγχει το id του user αν βρίσκεται σε κάποιον πινάκα του game_session  όπου το πεδίο status του πίνακα game_status δεν βρίσκεται σε κατάσταση aborted ή ended. 


```
function checkIfPlayerInSession()
{
    global $conn;
    if (isset($_SESSION['user_id'])) {

        //Check if player is already in a session
        $sql = "SELECT session_id FROM game_session WHERE user_id='{$_SESSION['user_id']}' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')";
        $result = mysqli_query($conn, $sql);
        $data = $result->fetch_assoc();
```
* Αν δεν έχει session τότε καλεί την  ``` getAvailableSession(); ``` για να βρει διαθέσιμο session, επιστρέφει ένα διαθέσιμο session_id με λιγότερα από τέσσερις παίκτες.
* Καλεί την ``` function addUserToSession() ``` για να τον προσθέσει. 
```
        } else {

            //If is not in a session, add into one
            $available_session = getAvailableSession();
            if (isset($available_session)) {
                addUserToSession($available_session);
            }
        }
    }
}
```
* Η συνάρτηση ``` function addUserToSession() ``` 
```
function addUserToSession($session_id)
{
    global $conn;
    $sql = "INSERT INTO game_session values (default, $session_id,'{$_SESSION['username']}', '{$_SESSION['user_id']}', 1 ,'{$_SESSION['user_token']}')";
    mysqli_query($conn, $sql);
    $_SESSION['session_id'] = $session_id;
    checkGameInstance($session_id);
}
```
* Η συνάρτηση ```checkGameInstance()``` φτιάχνει την κατάσταση του παιχνιδιού 
*  Ελέγχει το session ενός παιχνιδιού έχει ξεκινήσει με το ερώτημα ``` $sql = "SELECT * FROM game_status WHERE session_id='$session_id' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')"; ```
* Αν το session δεν έχει κάποια κατάσταση, τότε φτιάχνει μια κατάσταση  με το ερώτημα ```$sql = "INSERT INTO game_status VALUES (default, default, 1, 1, 0, NOW(), '$session_id')"; ```

``` 
function checkGameInstance($session_id)
{
    global $conn;
    //Check if instance of game is already initialized or initialize one
    $sql = "SELECT * FROM game_status WHERE session_id='$session_id' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();

    //If there is no instance, create a new one
    if (empty($data)) {
        $sql = "INSERT INTO game_status VALUES (default, default, 1, 1, 0, NOW(), '$session_id')";
        $result = mysqli_query($conn, $sql);
    }
}
```
 
# Σχεδίαση του παιχνιδιού


