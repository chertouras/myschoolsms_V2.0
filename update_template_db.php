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
$level = array("admin", "moderator","user");
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
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


if (isset($_POST['id'])) {
    $id = intval ($_POST['id']);
  }

  
  $Username=$_SESSION['Username'];
  $template = $_POST['template'];

$sql = "UPDATE  sms_templates SET template='$template' WHERE id=$id";

if ($mysqli->query($sql) === TRUE) {
    echo "Record update successfully";
} else {
    echo "Error updating record: " . $mysqli->error;
}

$mysqli->close();
?> 