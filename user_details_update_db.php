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

$level = array("admin", "moderator", "user");
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



//Who is logged in ?
 $Username = $_SESSION['Username'];


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  

if (!empty($_POST['Access_level']))
{

 $Access_level = trim($_POST['Access_level']);

}

if (!empty($_POST['quota']))
{

 $quota = trim($_POST['quota']);

}

//if Access_level and Username is posted do not accept the db insert
  if ( isset($Access_level) || isset($quota))
{
    echo 'Προσπαθείτε να κάνετε εισαγωγή παραμέτρων που δεν επιτρέπεται' ;
    
    exit(); 

}

$id = trim($_POST['id']);
  $FirstName = trim($_POST['FirstName']);
  $LastName = trim($_POST['LastName']);
  $FatherFirstName = trim($_POST['FatherFirstName']);
  $Telephone1 = trim($_POST['Telephone1']);

  
  $Passwd =trim( $_POST['Password']);

  $email = trim($_POST['email']);


 

$stmt = $mysqli->prepare("Update users  SET FirstName = ?,LastName=?,FatherFirstName=?,Telephone1=?, Passwd=? , 
                  email=? where Username=? and id = ?");

$stmt->bind_param("sssssssi",$FirstName, $LastName, 
                      $FatherFirstName , $Telephone1,$Passwd, $email,$Username , $id);


$status = $stmt->execute();
/* BK: always check whether the execute() succeeded */
if ($status === false) {
  trigger_error($stmt->error, E_USER_ERROR);
}
printf("<br/> Έγινε ενημέρωση %d εγγραφής.<br/>Ενημερώθηκε ο χρήστης %s %s.\n", $stmt->affected_rows , $LastName , $FirstName );
/* close statement and connection */
$stmt->close();
$mysqli->close();

?> 