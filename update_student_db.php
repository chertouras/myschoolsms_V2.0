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


if (isset($_POST['RegistrationNumber'])) {
    $id = intval($_POST['RegistrationNumber']);
}


$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$FatherFirstName = $_POST['FatherFirstName'];
$MotherFirstName = $_POST['MotherFirstName'];
$BirthDate = $_POST['BirthDate'];
$Telephone1 = $_POST['Telephone1'];
$LevelName = $_POST['LevelName'];

$sql = "UPDATE  students SET FirstName='$FirstName' , LastName = '$LastName' , FatherFirstName='$FatherFirstName',
 MotherFirstName='$MotherFirstName',BirthDate ='$BirthDate' , Telephone1='$Telephone1' , LevelName='$LevelName' WHERE RegistrationNumber=$id";

if ($mysqli->query($sql) === true) {
    echo "Record update successfully";
} else {
    echo "Error updating record: " . $mysqli->error;
}

$mysqli->close();
?> 