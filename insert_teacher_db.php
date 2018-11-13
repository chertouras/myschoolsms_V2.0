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

$table='teachers';
if ($result = $mysqli->query("SHOW TABLES LIKE '".$table."'")) {
    if($result->num_rows == 1) {
        echo "<br>Ο πίνακας teachers υπάρχει ήδη και δεν αλλοιώθηκε.";
    }

     else 
     {
$sql = "CREATE TABLE IF NOT EXISTS teachers (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
am VARCHAR(10) ,
afm VARCHAR(10) UNIQUE NOT NULL,
LastName   VARCHAR(70) ,
FirstName VARCHAR(70) ,
FatherFirstName VARCHAR(70) ,
sxesi_ergasias  VARCHAR(70) ,
sxesi_topothetisis  VARCHAR(70) ,
telephone  VARCHAR(12) DEFAULT '6900000000'  
) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci";
if ($mysqli->query($sql) === TRUE) {
    echo "<br/>  <b>Ο πίνακας teachers δημιουργήθηκε επιτυχώς.</b><br/>";
} else {
    echo "<br/> Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
    echo "<br/> Πιθανά ο πίνακας υπάρχει ήδη. ";
}
}
}
else {
    echo "<br/> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;


}

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

  $am = $_POST['am'];
  $afm = $_POST['afm'];
  $FirstName = $_POST['FirstName'];
  $LastName = $_POST['LastName'];
  $FatherFirstName = $_POST['FatherFirstName'];
  $sxesi = $_POST['sxesi'];
  $telephone = $_POST['telephone'];
  $sxesitop = $_POST['sxesitop'];

$stmt = $mysqli->prepare("INSERT INTO teachers (am, afm,FirstName,LastName,FatherFirstName,sxesi_ergasias,sxesi_topothetisis,telephone)
               VALUES(?,?,?,?,?,?,? ,?)");

$stmt->bind_param("iisssssi",$am, $afm,$FirstName, $LastName, $FatherFirstName , $sxesi ,$sxesitop, $telephone);

$stmt->execute();

printf(" Έγινε εισαγωγή %d εγγραφής. Εισήχθη ο εκπαιδευτικός %s %s.\n", $stmt->affected_rows , $LastName , $FirstName );

/* close statement and connection */
$stmt->close();
$mysqli->close();
?> 