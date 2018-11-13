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
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['id'])) {
    $id = intval ($_POST['id']);
  }

// sql to delete a record
$sql = "DELETE FROM students WHERE RegistrationNumber=$id";

if ($conn->query($sql) === TRUE) {
    echo "Η διαγραφή έγινε με επιτυχία";
} else {
    echo "Η διαγραφή ΔΕΝ έγινε. Σφάλμα : " . $conn->error;
}

$conn->close();
?> 