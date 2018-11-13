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
 * 
 * check if teachers table exist / if not create it
 * 
 */

$table='students';
if ($result = $mysqli->query("SHOW TABLES LIKE '".$table."'")) {
    if($result->num_rows == 1) {
        echo "<br>Ο πίνακας <b>students</b> υπάρχει ήδη και δεν αλλοιώθηκε.";
      
    }

     else 
     {

$sql = "CREATE TABLE IF NOT EXISTS students (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
StudentId INT(10) NOT NULL UNIQUE,
RegistrationNumber INT(10)  NOT NULL,
FirstName  VARCHAR(70) ,
LastName VARCHAR(70) ,
MotherFirstName VARCHAR(70),
FatherFirstName VARCHAR(70) ,
BirthDate  DATE ,
Telephone1 VARCHAR(20) ,
LevelName VARCHAR(20) 
) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci";
if ($mysqli->query($sql) === TRUE) {
    echo "<br/>";
    echo "<b>Ο πίνακας students δημιουργήθηκε επιτυχώς</b>";
   
} else {
    echo "<br/>";
    echo "Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
    echo "Πιθανά ο πίνακας υπάρχει ήδη. ";
    echo "<br/>";
}
     }
    }


    else {
        echo "<br> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;
    
    
    }



/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


    $sql= "SELECT max(RegistrationNumber) as max from $dbname.students";
    $row = $mysqli->query($sql)->fetch_array();
    $RegistrationNumber = intval($row['max'])+1 ;
    

    $sql= "SELECT max(StudentId) as max from $dbname.students";
    $row = $mysqli->query($sql)->fetch_array();
    $StudentId =intval( $row['max'])+1;


  $FirstName = $_POST['FirstName'];
  $LastName = $_POST['LastName'];
  $FatherFirstName = $_POST['FatherFirstName'];
  $MotherFirstName = $_POST['MotherFirstName'];
  $BirthDate = $_POST['BirthDate'];
  $Telephone1 = $_POST['Telephone1'];
  $LevelName = $_POST['LevelName'];

$stmt = $mysqli->prepare("INSERT INTO students (StudentId, RegistrationNumber,FirstName,LastName,MotherFirstName,FatherFirstName,BirthDate,Telephone1, LevelName)
               VALUES(?,?,?,?,?,?,? ,?,?)");

$stmt->bind_param("iisssssis",$StudentId, $RegistrationNumber,$FirstName, $LastName, $MotherFirstName,  $FatherFirstName , $BirthDate ,$Telephone1, $LevelName);

$stmt->execute();

printf(" Έγινε εισαγωγή %d εγγραφής. Εισήχθη ο μαθητής %s %s.\n", $stmt->affected_rows , $LastName , $FirstName );

/* close statement and connection */
$stmt->close();
$mysqli->close();

?> 