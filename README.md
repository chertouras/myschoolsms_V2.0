# myschoolsms v.2.1

**GRE**: Στην V.2.1 προστέθηκαν οι παρακάτω δυνατότητες: 

- Δυνατότητα υπενθύμισης του password στο email που βρίσκεται στο προφίλ του με την χρήση του **gmail**.
- Δυνατότητα αποθήκευσης, χρήσης και διαχείρισης προτύπων sms ώστε να μην είναι αναγκαία η συνεχείς πληκτρολόγηση.


**Eng**: In V.2.1 the following new features were added: 
- password reminder 
- SMS templates per users


## Περιγραφή

Οι βασικές λειτουργίες της εφαρμογής περιγράφονται στο README της 
πρώτης έκδοσης [εδώ](https://github.com/chertouras/myschoolsms) και στο README του
Master branch.

## Σημεία προσοχής:

Απαιτούνται οι περαιτέρω από την έκδοση V.2.0, ρυθμίσεις: 
-  Την εισαγωγή των παραμέτρων σύνδεσης στη βάση δεδομένων **και** στο αρχείο **passwordReminder.php** 
``` 
24.    $servername = "xxxxxxxxxxx";
25.    $username_db = "xxxxxxxx";
26.    $password_db = "xxxxxxxxx";
27.    $dbname = 'persons_db';

```
## Σημεία προσοχής για τη λειτουργία του E- mail:

Για να γίνει δυνατή η αποστολή email απαιτείται η ύπαρξη λογαριασμού Gmail μέσω του οποίου θα γίνει το smtp relay
Τα βασικα σημεία προσοχής είναι τα ακόλουθα:
- Download από το https://www.glob.com.au/sendmail/ του sendmail.zip 
- Unzip και τοποθέτηση στο xampp (θα πρέπει να έχουμε ως αποτέλεσμα κάτι όπως c:\xampp\sendmail)
- Εdit το sendmail.ini στο οποίο και τοποθετούμε τα στοιχεία του Gmail: 

```               
smtp_server=smtp.gmail.com
smtp_port=465
smtp_ssl=auto
auth_username=xxxxxxxxx@gmail.com
auth_password=xxxxxxxxxxxxxxxxx
```   


- Edit το php.ini στο c:\xampp\php\php.ini με τις εξής μετατροπές:
              [mail function]
```
; For Win32 only.
; http://php.net/smtp
SMTP=localhost
; http://php.net/smtp-port
smtp_port=25

; For Win32 only.
; http://php.net/sendmail-from
;sendmail_from = me@example.com

; For Unix only.  You may supply arguments as well (default: "sendmail -t -i").
; http://php.net/sendmail-path
sendmail_path ="C:\xampp\sendmail\sendmail.exe -t" 
```

# Ευχαριστίες

Θα ήθελα να ευχαριστήσω θερμά τον **κ.κ. Χαράλαμπο Κάββαλο** @kavvalosb που εργάζεται στην **Πυροσβεστική Υπηρεσία Ηρακλείου** τόσο για τον κόπο του να μελετήσει την εφαρμογή, να ανακαλύψει διάφορα bugs, να προτείνει επεκτάσεις, όσο και για την επιλογή του να την χρησιμοποιήσει στην Υπηρεσία του για την ενημέρωση των συναδέλφων του σε περιπτώσεις περιστατικών.

# Αποποίηση Ευθύνης

**H εφαρμογή είναι δωρεάν και χωρίς κανένα περιορισμό στην χρήση και τροποποίηση της. Έγινε ως προσφορά στο κοινωνικό σύνολο και στο όραμα της ηλεκτρονική δημοκρατίας. H χρήση της γίνεται με πλήρη την ευθύνη του κάθε χρήστη.**

## Δημιουργός : 
Κωνσταντίνος Χερτούρας , AWS CSAA

Διπλωματούχος ΗΜΜΥ Πολυτεχνείου Κρήτης MSc Imperial College , MSc AΠΘ
Εκπαιδευτικός ΠΕ19 , ΕΠΑΛ Ροδόπολης Σερρών
Ερωτήσεις - απορίες: chertour@sch.gr


## License
[GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

