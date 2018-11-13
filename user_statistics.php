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

$level = array("user");
if (!in_array($_SESSION['Access_level'], $level)) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}

include 'parameters.php';
$mysqli = new mysqli($servername, $username, $password, $dbname);
$mysqli->set_charset('utf8');


$json = (object)[];
$Username = $_SESSION['Username'];

$sql = <<<SQL
    SELECT quota , Firstname , Lastname , Access_level , email
    FROM `users` WHERE Username='$Username'
SQL;

if (!$result = $mysqli->query($sql)) {
    die('There was an error running the query [' . $mysqli->error . ']');
}

$row = $result->fetch_assoc();

$json->Lastname = $row['Lastname'];
$json->Firstname = $row['Firstname'];
$json->Access_level = $row['Access_level'];
$json->email = $row['email'];
$json->quota = $row['quota'];

echo (json_encode($json));

?>