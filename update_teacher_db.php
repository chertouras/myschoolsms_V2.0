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
    die('Δεν στάλθηκε όλα τα απαραίτητα στοιχεία για την ενημέρωση της εγγραφής');

}


$am = ($_POST['am']);
$afm = ($_POST['afm']);
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$FatherFirstName = $_POST['FatherFirstName'];
$sxesi_ergasias = $_POST['sxesi_ergasias'];
$telephone = ($_POST['telephone']);
$sxesi_topothetisis = $_POST['sxesi_topothetisis'];

$sql = "UPDATE  teachers SET am= '$am' , afm='$afm' , FirstName='$FirstName' , LastName = '$LastName' , FatherFirstName='$FatherFirstName',
 sxesi_ergasias='$sxesi_ergasias',telephone ='$telephone' , sxesi_topothetisis='$sxesi_topothetisis' WHERE id='$id'";

if ($mysqli->query($sql) === true) {
    echo "Επιτυχής ενημέρωση";
} else {
    echo "Error updating record: " . $mysqli->error;
}

$mysqli->close();
?> 