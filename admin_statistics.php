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

$level = array("admin", "moderator");
if (!in_array($_SESSION['Access_level'], $level ))
{
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}
include 'parameters.php';
$mysqli = new mysqli($servername, $username, $password , $dbname);
$mysqli->set_charset('utf8');

/**
 * 
 * Θα πρέπει να εισαχθεί το κλειδί που δώθηκε κατά την αγορά του πακέτου SMS
 * 
 * 
 * 
 * 
 * 
*/
$url = 'https://easysms.gr/api/balance/get?key=xxxxxxxxxxxxxxxxxxxx&type=json';

/**
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */





$obj = file_get_contents($url);
$json= json_decode($obj);

$Username = $_SESSION['Username'];

$sql_user = <<<SQL
    SELECT quota , Firstname , Lastname , Access_level , email
    FROM `users` WHERE Username='$Username'
SQL;

if(!$result = $mysqli->query($sql_user)){
    die('There was an error running the query [' . $mysqli->error . ']');
}


$sql_users_sum = <<<SQL
    SELECT SUM(quota) users_total_quota
    FROM `users` WHERE Access_level='user'
SQL;


if(!$result_users_sum = $mysqli->query($sql_users_sum)){
    die('There was an error running the query [' . $mysqli->error . ']');
}
$sql_moderators_sum = <<<SQL
    SELECT SUM(quota)  as moderators_total_quota
    FROM `users` WHERE Access_level='moderator'
    
SQL;

if(!$result_moderators_sum = $mysqli->query($sql_moderators_sum)){
    die('There was an error running the query [' . $mysqli->error . ']');
}
$row_user = $result->fetch_assoc();
$row_users_sum = $result_users_sum->fetch_assoc();
$row_moderators_sum = $result_moderators_sum->fetch_assoc();
$json->Lastname=$row_user['Lastname'];
$json->Firstname=$row_user['Firstname'];
$json->Access_level=$row_user['Access_level'];
$json->email=$row_user['email'];
$json->quota=$row_user['quota'];
$json->quota_users=$row_users_sum['users_total_quota'];
$json->quota_moderators=$row_moderators_sum['moderators_total_quota'];


echo ( json_encode($json));



  ?>