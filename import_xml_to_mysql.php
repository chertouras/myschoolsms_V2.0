<?php
session_start();
$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {

    session_unset();
    session_destroy();

}

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != 1) {
    header('Location: index.php');
    exit();
}

$level = array("admin", "moderator");
if (!in_array($_SESSION['Access_level'], $level)) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}


include 'parameters.php';
$mysqli = new mysqli($servername, $username, $password);
$mysqli->set_charset('utf8');


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (!$mysqli->select_db($dbname)) {

    $sql = "CREATE DATABASE $dbname  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci";
    $result = $mysqli->query($sql);
    if ($result) {

    } else {
        echo '<br/>Σφάλμα κατά την δημιουργία της βάσης δεδομένων: ' . mysql_error() . "\n";
        echo '<br/>Η βάση δεδομένων πιθανά θα υπάρχει';
    }
};

$mysqli->select_db($dbname);


$table = 'students';
if ($result = $mysqli->query("SHOW TABLES LIKE '" . $table . "'")) {
    if ($result->num_rows == 1) {
        echo "<br/>Ο πίνακας <b>students</b> υπάρχει ήδη και δεν αλλοιώθηκε.";

    } else {

        $sql = "CREATE TABLE IF NOT EXISTS students (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
StudentId INT(10) NOT NULL UNIQUE,
RegistrationNumber INT(10)  NOT NULL,
FirstName  VARCHAR(70) ,
LastName VARCHAR(70) ,
MotherFirstName VARCHAR(70),
FatherFirstName VARCHAR(70) ,
BirthDate  DATE ,
Telephone1 VARCHAR(20) ,
LevelName VARCHAR(20) 
) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci";
        if ($mysqli->query($sql) === true) {
            echo "<br/>";
            echo "<b>Ο πίνακας students δημιουργήθηκε επιτυχώς</b>";

        } else {
            echo "<br/>";
            echo "Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
            echo "Πιθανά ο πίνακας υπάρχει ήδη. ";
            echo "<br/>";
        }
    }
} else {
    echo "<br/> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;


}

$table = 'smsmessages';

if ($result = $mysqli->query("SHOW TABLES LIKE '" . $table . "'")) {
    if ($result->num_rows == 1) {
        echo "<br/>Ο πίνακας <b>smsmessages</b> υπάρχει ήδη και δεν αλλοιώθηκε";

    } else {

        /********************************************************************* */
        $sql = "CREATE TABLE IF NOT EXISTS smsmessages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    RegistrationNumber  VARCHAR(10)  NOT NULL,
    Telephone1 VARCHAR(20) ,
    name varchar(20),
    surname varchar(40),
    error int(2),
    smsid varchar(20),
    remarks varchar (40),
    status int(2) ,
    person int(2),
    timeSent  DATETIME DEFAULT CURRENT_TIMESTAMP,
    message varchar(200),
    Username VARCHAR(20) NOT NULL ,
    INDEX usr_name (Username) ,
    FOREIGN KEY  (Username) REFERENCES users(Username)
    ) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci";
        if ($mysqli->query($sql) === true) {
            echo "<br/>";
            echo "<b>Ο πίνακας smsmeggages δημιουργήθηκε επιτυχώς</b>";
            echo "<br/>";
        } else {
            echo "<br/>";
            echo "Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
            echo "Πιθανά ο πίνακας υπάρχει ήδη. ";
            echo "<br/>";
        }
    }
    /********************************************************************** */

    if (isset($_FILES['xmlfile']) && ($_FILES['xmlfile']['error'] == UPLOAD_ERR_OK)) {
        $xml = simplexml_load_file($_FILES['xmlfile']['tmp_name']);

    } else {
        echo "<br/>To αρχείο XML με τα στοιχεία των μαθητών δεν μπόρεσε να μεταφορτωθεί. ";
        echo "<br/>Παρακαλώ ελέγξτε εάν το έχετε επιλέξει.";
        echo "<br/>Παρακαλώ ξαναδοκιμάστε";
        exit();

    }
} else {
    echo "<br> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;


}


$nodes = $xml->xpath('//Students');
$students = $nodes[0];

$errors = 0;
$atleastone = 0;
foreach ($students as $student) {




    $insert_row = $mysqli->query("INSERT INTO students (StudentId, RegistrationNumber,FirstName,LastName,MotherFirstName,FatherFirstName,BirthDate,Telephone1, LevelName)
               VALUES($student->StudentId, $student->RegistrationNumber,'$student->FirstName', '$student->LastName' ,	
  '$student->MotherFirstName','$student->FatherFirstName','$student->BirthDate','$student->Telephone1' , '$student->LevelName' )");


    if ($insert_row) {

        $atleastone++;
    } else {

        $errors++;
    }

}

if ($errors == 0) {
    echo ("H εισαγωγή του συνόλου των εγγραφών έγινε επιτυχώς");
} else {

    if ($atleastone == 0) {
        echo ("<br> Κάποιο σφάλμα προέκυψε κατά την εισαγωγή.");
        echo ("<br> Μήπως δοκιμάσατε να εισάγετε το ίδιο αρχείο μαθητών?");
    } else {
        echo ("<br>Εισήχθησαν " . $atleastone . " εγγραφές. ");
        echo ("<br>ΔΕΝ εισήχθησαν " . $errors . " εγγραφές που πιθανά ήταν διπλές. ");

    }


}




?> 