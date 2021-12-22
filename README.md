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
