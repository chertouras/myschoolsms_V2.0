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

    $sql = "CREATE DATABASE $dbname CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci";
    $result = $mysqli->query($sql);
    if ($result) {

    } else {
        echo 'Σφάλμα κατά την δημιουργία της βάσης δεδομένων: ' . mysql_error() . "\n";
        echo 'Η βάση δεδομένων πιθανά θα υπάρχει';
    }
};

$mysqli->select_db($dbname);


$table = 'smsmessages';
if ($result = $mysqli->query("SHOW TABLES LIKE '" . $table . "'")) {
    if ($result->num_rows == 1) {
        echo "<br>Ο πίνακας smsmessages υπάρχει ήδη και δεν αλλοιώθηκε.";
    } else {


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
            echo "<b>Ο πίνακας smsmessages δημιουργήθηκε επιτυχώς</b>";

        } else {
            echo "<br/>";
            echo "Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
            echo "<br/>Πιθανά ο πίνακας υπάρχει ήδη. ";
            echo "<br/>";
        }

    }
} else {
    echo "<br> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;


}
?>