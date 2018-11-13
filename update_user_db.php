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
$mysqli = new mysqli($servername, $username, $password, $dbname);
$mysqli->set_charset('utf8');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
} else {
    die('Δεν στάλθηκαν όλα τα απαραίτητα στοιχεία για την ενημέρωση της εγγραφής');

}
$FirstName = trim($_POST['FirstName']);
$LastName = trim($_POST['LastName']);
$FatherFirstName = trim($_POST['FatherFirstName']);
$Telephone1 = trim($_POST['telephone']);
$Access_level = trim($_POST['Access_level']);
$Username = trim($_POST['Username']);
$Passwd = trim($_POST['Password']);
$email = trim($_POST['email']);
$quota = trim($_POST['quota']);



$sql = "UPDATE  users SET email= '$email' , Passwd='$Passwd' , FirstName='$FirstName' , LastName = '$LastName' , FatherFirstName='$FatherFirstName',
 Access_level='$Access_level',Telephone1 ='$Telephone1' , Username='$Username' , quota='$quota' WHERE id='$id'";

if ($mysqli->query($sql) === true) {
    echo "Επιτυχής ενημέρωση";
} else {
    echo "Error updating record: " . $mysqli->error;
}

$mysqli->close();
?> 