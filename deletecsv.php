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
    die("H σύνδεση απέτυχε. Είστε σίγουροι ότι υπάρχει ή βάση δεδομένων ακόμα? Λεπτομέρειες σφάλματος: " . $conn->connect_error);
}

$sql = "DROP TABLE teachers";

if ($conn->query($sql) === TRUE) {
    echo "<br/>O πίνακας teachers διαγράφηκε";
} else {
    
    echo "<br/>O πίνακας teachers ΔΕΝ διαγράφηκε.<br/> Είστε σίγουρος/ή ότι υπάρχει?";
    echo "<br/>";

echo "Error: " . $conn->error;
}
$conn->close();
?> 