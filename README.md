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

Στο αρχειο ```schema.sql```, Στην βάση με όνομα moutzouris φτιάχνουνε πινάκες: 


Ο Πίνακας users:

|Attribute  |  Description   |  Values |
|-----------|----------------|---------|
|id         | Primary key    | int     |	
|username   | Ονομα	     | varchar |
|password   | Κωδικός        | varchar |

Ο πίνακας game_session περιέχει την κατάσταση του παίκτη:

|Attribute   |  Description  			 |  Values	         |
|------------|-----------------------------------|---------	         |
|id          | Primary key   			 | int  AUTO_INCREMENT   |	
|session_id  | Στο session id που βρίσκεται      | int	  	         |
|username    | Ονομα του παίκτη	     	         | varchar	         |
|user_id     | To ID του Παίκτη  		 | int    	         |
|player_turn | τρέχων σειρά του παίκτη   	 |'1', '2', '3', '4'     |
|user_token  | Primary key    			 | varchar               |	
|wins        | Νικες	     			 | int 	  	         |
|loses       | Ητες           			 | int                   |

Ο πίνακας cards περιέχει όλες τις κάρτες 

|Attribute   |  Description  			 |  Values	    |
|------------|-----------------------------------|---------	    |
|id          | Primary key   			 | int   	    |	
|cardname    | Αριθμος κάρτας    		 | varchar	    |
|cardchar    | Ομάδα κάρτας      	         | varchar	    |
|url         | Εικόνα κάρτας			 | url    	    |


Ο πίνακας current_cards περιέχει τα primary id των καρτών σε ένα τρέχων game_session:


|Attribute    |  Description  				    Values	  		 |
|-------------|-------------------------------------------|----------------------------- |
|id           | Primary key   				  | int  AUTO_INCREMENT 	 |	
|card_in      | id της καρτας		       		  | int	   		         |
|card_name    | Αριθμός της κάρτας      	          | varchar	   		 |
|player_id    | Τρέχων παίκτης που την έχει		  | int  	   		 |
|player_turn  | Σειρά του παίκτη που έχει την κάρτα       | ('1', '2', '3', '4')	 |
|session_id   | Το session το οποίο είναι ενεργο	  | int  	   		 |


Ο πίνακας game_status αποθηκεύει την κατάσταση του παιχνιδιού:




Στο αρχείο ```db_upass.php```, βρίσκεται το username της βάσης και ο κωδικός.
```
<?php
	$DB_PASS = 'sakasaka';
	$DB_USER = 'root';
?>
```

Στο αρχείο ```db_conn.php```, κάνει την σύνδεση της απομακρυσμένης βάσης με τα παραπάνω στοιχειά. Σε περίπτωση που τα στοιχειά δεν αντιστοιχούνται κανονικά θα εμφανίσει ένα error. Η εντολή ```$conn = new mysqli($host, $user, $pass, $db, null, '/home/student/it/2015/it154486/mysql/run/mysql.sock');``` κάνει την σύνδεση της βάσης με τα παρακάτω στοιχειά.

# Σχεδίαση της Login Σελίδας

Η κύρια σελίδα αποτελείται από δυο βασικά αρχεία. ```Το index.php``` και ```login.php``` 

Στo αρχείο ```index.php``` περιέχει την διεπαφή του χρήστη, ο χρήστης βάζει το username και το password.

Στο αρχείο ```login.php``` περιέχει τον κώδικα για την διαδικασία του login, στην πραγματικότητα κάνει τον έλεγχο των χρηστών.

* Στην εντολή ```if (isset($_POST['username']) && isset($_POST['password'])) ``` παιρνει το username και password απο τo form στο index.php.

* Στην εντολή  ``` if (empty($username))``` ελέγχει αν πληκτρολογήθηκε username και αντίστοιχα password, αλλιώς βγάζει error ότι κάτι λείπει.

* Στην συνέχεια της εντολής if, ελέγχει  με ένα sql ερώτημα ```$sql_u="SELECT * FROM users WHERE username='$username'";``` αν υπάρχει το username. Αν δεν υπάρχει,γίνεται αυτόματα insert στον πίνακα users με το password που δόθηκε. 

* Στην συνέχεια της εντολής if, ελέγχει με ένα sql ερώτημα ``` $sql = "SELECT * FROM users WHERE username='$username' AND password='$pass'"; ``` αν υπάρχει το username και password στην βάση να επιστρέψει ενα αποτέλεσμα ```$result = mysqli_query($conn, $sql);``` και στην εντολή ```if (mysqli_num_rows($result) === 1) ``` κάνει την εισόδο στο lobby.

* Αν υπάρχει το username και το Password είναι διαφορετικό, τότε η εντολή ```$result = mysqli_query($conn, $sql);``` επιστρέφει 0 και δεν μπαίνει στην if.


# Σχεδίαση του Lobby

Το lobby αποτελείτε από τα δυο αρχεία με όνομα ```lobby.php``` (ένα για interface και ένα για κώδικα) και ένα αρχείο ```lobby.js```.

* Mε την συνάρτηση ```getAvailableSession()``` φτιάχνει ένα session αν δεν υπάρχει ήδη. Όταν υπάρχει session βάζει μέχρι 4 παίκτες και αν έχει γεμίσει, τότε φτιάχνει ένα καινούργιο session.

* Με το ερώτημα ```$sql = "SELECT DISTINCT session_id, COUNT(*) as number_of_players FROM game_session WHERE session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended') GROUP BY session_id";``` επιστρέφει σε μια μεταβλητή των αριθμό των παικτών από τον πινάκα game_session οι οποίοι δεν βρίσκονται σε session.

* Αν το session έχει γεμίσει και είναι πάνω από 4 παίκτες τότε με το ερώτημα ```$sql = "SELECT max(id) as last_session_id FROM game_status";``` φτιάχνει ένα νέο session με id του προηγούμενου session +1. ``` $current_session = $data['last_session_id'] + 1; ```
* Οι παίκτες μπαίνουν σε νέο session με την συνάρτηση ``` function addUserToSession($session_id) ```

* Η Συνάρτηση ``` checkIfPlayerInSession() ``` ελέγχει αν ο χρήστης έχει session αλλιώς καλεί την ``` function addUserToSession() ``` 
* Με το ερώτημα ```  $sql = "SELECT session_id FROM game_session WHERE user_id='{$_SESSION['user_id']}' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')";``` ελέγχει το id του user αν βρίσκεται σε κάποιον πινάκα του game_session  όπου το πεδίο status του πίνακα game_status δεν βρίσκεται σε κατάσταση aborted ή ended. 

* Αν δεν έχει session τότε καλεί την  ``` getAvailableSession(); ``` για να βρει διαθέσιμο session, επιστρέφει ένα διαθέσιμο session_id με λιγότερα από τέσσερις παίκτες.
* Καλεί την ``` function addUserToSession() ``` για να τον προσθέσει. 

* Η συνάρτηση ```checkGameInstance()``` φτιάχνει την κατάσταση του παιχνιδιού 
*  Ελέγχει το session ενός παιχνιδιού έχει ξεκινήσει με το ερώτημα ``` $sql = "SELECT * FROM game_status WHERE session_id='$session_id' AND session_id NOT IN (SELECT session_id FROM game_status WHERE status='aborted' OR status='ended')"; ```
* Αν το session δεν έχει κάποια κατάσταση, τότε φτιάχνει μια κατάσταση  με το ερώτημα ```$sql = "INSERT INTO game_status VALUES (default, default, 1, 1, 0, NOW(), '$session_id')"; ```


 
# Σχεδίαση του παιχνιδιού

Οι κύριες υλοποιήσεις συναρτήσεων για το παιχνίδι πρωτού ξεκινήσει, βρίσκονται στο αρχείο ```game_functions.php```

* Η συνάρτηση ```getShuffledCards() ``` με το ερώτημα ``` $sql = "SELECT id, cardname FROM cards WHERE cardchar != 'back'";``` ανακατεύει τις κάρτες οι οποίες αντιστοιχούν στο συγκεκριμένο ενεργό session.

* Η συνάρτηση ```dealCards($shuffledCards, $player_turns)``` Μοιράζει τις ανακατεμένες κάρτες στους παίκτες που βρίσκονται σε ένα game_session όλοι μαζί με το ερώτημα ```$sql = "INSERT INTO current_cards values (default, '$card[0]', '$card[1]','$user_id', '$i','{$_SESSION['session_id']}')"; ``` 

* Ο καθορισμός της σειράς του παιχνιδιού ανάμεσα στους παίκτες πραγματοποιείτε με την συνάρτηση ```arrangeTurns()``` με την εντολή ```$sql = "SELECT user_id FROM game_session WHERE session_id='{$_SESSION['session_id']}'";```

* Η συνάρτηση ```changeGameStatus($number_of_players)```  κάνει update το τρέχων game_status σε κατάσταση ```started``` του παιχνιδιού για να ξεκινήσει, με την εντολή ``` $sql = "UPDATE game_status SET status = 'started', number_of_players=$number_of_players,last_change=NOW() WHERE session_id='{$_SESSION['session_id']}'";```

Οι συναρτήσεις που βρίσκονται στο αρχείο ```game_loop_function.php ``` , υλοποιούν ενέργειες για τα τρέχων ενεργά παιχνίδια ```(δηλαδή game_status, game_status=’started’)  ``` 

* Η συνάρτηση ``` getPlayerTurn()``` παίρνει ο παίκτης την σειρά του, η εντολή που επιτυγχάνεται  ``` $sql = "SELECT player_turn FROM game_session WHERE session_id='{$_SESSION['session_id']}' AND user_token='{$_SESSION['user_token']}'";```

* Η συνάρτηση ```getRemainingPlayers($cards_by_player) ``` ελέγχει κάθε φορά αν ένας παίκτης βγήκε από το παιχνίδι (δηλαδή κέρδισε) με την εντολή ```foreach ($cards_by_player as $player_turn=> $cards) {array_push($remainingPlayers, $player_turn);}```

