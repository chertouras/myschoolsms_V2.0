<?php
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != 1) {
    header('Location: index.php');
    exit();
}


$servername = "xxxxxxxxx";
$username = "xxxxxxxx";
$password = "xxxxxxxx";
$dbname = 'persons_db';

?>