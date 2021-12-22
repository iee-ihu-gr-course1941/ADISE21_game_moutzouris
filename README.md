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
INSERT INTO  `users` VALUES (default, 'panos', 'abc');
INSERT INTO `users` VALUES (default, 'miltos', 'abc');
```

Ο Πίνακας users περιεχέι username,password και ένα id. Επίσης φαίνεται η λογική του insert. Το default πεδίο παίρνει την ιδιότητα του auto_increment.  
  
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
Ο πίνακας game_session περιέχει την κατάσταση του παίκτη. Όπως την σειρά του, το primary id του από τον πίνακα users, σε πιο session παιχνιδιού είναι και to token του. 

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
Ο πίνακας cards περιέχει όλες τις κάρτες με ένα id, ένα όνομα , μια κλάση και μια εικόνα σε ενα url.


