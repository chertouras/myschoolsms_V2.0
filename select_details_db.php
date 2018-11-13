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

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $person = intval($_POST['person']);

} else {
    exit();
}

$query = "SELECT t3.RegistrationNumber  , t3.surname , t3.name, t3.timeSent, t3.message, t3.smsid, t3.remarks, t3.status  
    FROM smsmessages t3 WHERE t3.timeSent not in ( SELECT t1.timeSent FROM smsmessages t1 WHERE t1.timeSent = (SELECT MAX(t2.timeSent) FROM smsmessages t2 
WHERE t2.RegistrationNumber = t1.RegistrationNumber) and person =$person and t3.RegistrationNumber = t1.RegistrationNumber ) and person =$person and RegistrationNumber=$id
order by t3.timeSent DESC";
$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQL_ASSOC)) {
    $myArray[] = $row;
}
if (empty($myArray)) {
    $myArray = array('values' => null);

}
echo json_encode($myArray);
/* close statement and connection */

$mysqli->close();

?> 