# myschoolsms v.1.0

**GRE**: Η δεύτερη έκδοση της εφαρμογή ενημέρωσης των γονέων των μαθητών μέσω sms γραμμένη σε Javascript και PHP. Περιλαμβάνει αρκετές βελτιώσεις σε σχέση με την πρώτη έκδοση που συνοψίζονται στα εξής:

- Δυνατότητα δημιουργίας χρηστών ώστε να μπορούν ελεγχόμενα να κάνουν χρήση της πλατφόρμας
- Υποστήριξη quota χρηστών ώστε να τίθενται όρια στις αποστολές
- Group χρηστών με διαφορετικά δικαιώματα στην εφαρμογή
- Βελτιωμένες αναφορές με αποδοτικότερη χρήση του Datatables plugin

**Eng**: Second version of my CRUD application for sending and managing SMS messages through http. It now supports: 
- multiple users 
- user groups with different privileges 
- SMS user quotas
- better reports and integration with the Datatables plugin


## Περιγραφή

Η βασικές λειτουργίες της εφαρμογής περιγράφονται στο README της 
πρώτης έκδοσης [εδώ](https://github.com/chertouras/myschoolsms)

## Σημεία προσοχής:

Με την εισαγωγή της δυνατότητας υποστήριξης πολλών χρηστών και λόγω έλλειψης χρόνου για την δημιουργία ενός installer η εφαρμογή απαιτεί για την λειτουργία της της παρακάτω, περαιτέρω από την πρώτη έκδοση, ρυθμίσεις: 
-  Την εισαγωγή των παραμέτρων σύνδεσης στη βάση δεδομένων **και** στο αρχείο **index.html** 
``` 
24.    $servername = "xxxxxxxxxxx";
25.    $username_db = "xxxxxxxx";
26.    $password_db = "xxxxxxxxx";
27.    $dbname = 'persons_db';

```
- Tην εισαγωγή στο αρχείο   **admin_statistics.php** του κλειδιού του SMS παρόχου (tern.gr / easysms.gr) 

```
38. $url = 'https://easysms.gr/api/balance/get?key=xxxxxxxxxxxxxxxxxxxx&type=json';
```


- **Εμπρόθετα επιλέχθηκε (για λόγους εξοικείωσης των διαχειριστών της εφαρμογής και κυρίως λόγω της εσωτερικής χρήσης τους -localhost- στο σχολείο ) να μην τοποθετούνται τα password με τιμή hash στην βάση δεδομένων**. Με αυτό τον τρόπο ο διαχειριστής θα μπορεί απλά να υπενθυμίζει τα password χωρίς να χρειάζεται να δημιουργεί καινούργια. Όποιος ενδιαφέρεται θα μπορούσε να επέμβει στον κώδικα και να χρησιμοποιήσει μια συνάρτηση τύπου **md5()** στην PHP πριν την αποθήκευση και μια αντίστοιχη πριν το login για την σύγκρισή τους (σχετικά απλό).

- Δημιουργήθηκε ένα αρχείο **smscenter_fake.php** για ψευδείς αποστολές όπου μπορεί να χρησιμοποιηθεί για πειραματισμό με την εφαρμογή χωρίς κόστος αποστολής sms. Με τη χρήση του, παράγεται μια απλή απάντηση τύπου json που είναι ίδια με την απάντηση του κέντρου της easysms.gr χωρίς όμως να αποστέλλεται πραγματικά το μήνυμα. Για να το χρησιμοποιήσετε απλά μετονομάστε το πραγματικό smscenter.php σε κάτι άλλο και δώστε στο smscenter_fake.php το όνομα smscenter.php

# Ευχαριστίες

Θα ήθελα να ευχαριστήσω θερμά τον **κ.κ. Χαράλαμπο Κάββαλο** @kavvalosb που εργάζεται στην **Πυροσβεστική Υπηρεσία Ηρακλείου** τόσο για τον κόπο του να μελετήσει την εφαρμογή, να ανακαλύψει διάφορα bugs, να προτείνει επεκτάσεις  όσο και για την επιλογή του να την χρησιμοποιήσει στην Υπηρεσία του για την ενημέρωση των συναδέλφων του σε περίπτωση περιστατικού.

# Αποποίηση Ευθύνης

**H εφαρμογή είναι δωρεάν και χωρίς κανένα περιορισμό στην χρήση και τροποποίηση της. Έγινε ως προσφορά στο κοινωνικό σύνολο και στο όραμα της ηλεκτρονική δημοκρατίας. H χρήση της γίνεται με πλήρη την ευθύνη του κάθε χρήστη.**

## Δημιουργός : 
Κωνσταντίνος Χερτούρας , AWS CSAA

Διπλωματούχος ΗΜΜΥ Πολυτεχνείου Κρήτης MSc Imperial College , MSc AΠΘ
Εκπαιδευτικός ΠΕ19 , ΕΠΑΛ Ροδόπολης Σερρών
Ερωτήσεις - απορίες: chertour@sch.gr


## License
[GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

