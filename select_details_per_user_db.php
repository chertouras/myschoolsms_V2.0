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


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (isset($_POST['Username'])) {
    $Username = (($_POST['Username']));


} else {
    exit();
}

$query_ini = "SET @row_number = 0";
$result = $mysqli->query($query_ini);
$query = " SELECT (@row_number:=@row_number + 1) AS num, Username, surname , name, timeSent, message, 
    smsid, remarks, status  
    FROM smsmessages  WHERE  Username='" . $Username . "' order by timeSent DESC";
$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $myArray[] = $row;

}
if (empty($myArray)) {
    $myArray = array('values' => null);

}
echo json_encode($myArray);
/* close statement and connection */

$mysqli->close();

?> 