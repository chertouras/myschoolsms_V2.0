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
include 'parameters.php';
$mysqli = new mysqli($servername, $username, $password, $dbname);
$mysqli->set_charset('utf8');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Η σύνδεση απέτυχε: %s\n", mysqli_connect_error());
    exit();
}

$result = ($_POST['results']);
$Username = $_POST['Username'];
$RegistrationNumber = $_POST['RegistrationNumber'];
$Telephone1 = $_POST['Telephone1'];
$message = $_POST['message'];
$smsid = $result['id'];
$error = $result['error'];
$remarks = $result['remarks'];
$status = $result['status'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$person = $_POST['person'];


$stmt = $mysqli->prepare("INSERT INTO smsmessages (Username , RegistrationNumber, person, smsid , name,surname,error,Telephone1 , remarks, status ,message)
               VALUES(?,?,?,?,?,?,?,?,? ,? ,?)");

$stmt->bind_param("ssisssissis", $Username, $RegistrationNumber, $person, $smsid, $name, $surname, $error, $Telephone1, $remarks, $status, $message);

$stmt->execute();

printf(" Έγινε εισαγωγή %d εγγραφής. To sms με id %s \n", $stmt->affected_rows, $smsid);

/* close statement and connection */
$stmt->close();
$mysqli->close();
?> 