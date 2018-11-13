<?php
session_start();
$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    
    session_unset();
    session_destroy();
   
}

if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!=1){
    header('Location: index.php');
    exit();
}
if ( $_SESSION['Access_level']!='admin')
{
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}

include 'parameters.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("H σύνδεση απέτυχε.<br/>  Είστε σίγουροι ότι υπάρχει ή βάση δεδομένων ακόμα? <br/> Λεπτομέρειες σφάλματος: " . $conn->connect_error);
}

$sql = "DROP TABLE smsmessages";

if ($conn->query($sql) === TRUE) {
    echo "<br/>";
    echo "Ο πίνακας με τα στοιχεία των SMS διαγράφηκε";
    echo "<br/>";
} else {
    echo "<br/> Υπήρξε λάθος κατά τη διαγραφή.<br/> Ο πίνακας των SMS δεν υπάρχει.<br/>Λεπτομέρειες : " . $conn->error;
}

$conn->close();
?> 