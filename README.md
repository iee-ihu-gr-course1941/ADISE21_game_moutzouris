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

Στην βάση με όνομα moutzouris φτιάχνουνε πινάκες: 


CREATE TABLE users (
    `id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (id)
);




  
  
