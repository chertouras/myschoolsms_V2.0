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

/**
 * 
 * 
 * check if table exist / if not create it
 * 
 */

$table='sms_templates';
if ($result = $mysqli->query("SHOW TABLES LIKE '".$table."'")) {
    if($result->num_rows == 1) {
        echo "<br>Ο πίνακας <b>$table</b> υπάρχει ήδη και δεν αλλοιώθηκε.";
      
    }

     else 
     {

$sql = "CREATE TABLE IF NOT EXISTS $table (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
template VARCHAR(140) ,
Username VARCHAR(20) NOT NULL ,
FOREIGN KEY  (Username) REFERENCES users(Username)
) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci";

if ($mysqli->query($sql) === TRUE) {
    echo "<br/>";
    echo "<b>Ο πίνακας $table δημιουργήθηκε επιτυχώς</b>";
   
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





$Username = $_SESSION['Username'];
  
$template=$_POST['template'];
$stmt = $mysqli->prepare("INSERT INTO $table (Username, template)
               VALUES(?,?)");

$stmt->bind_param("ss",$Username, $template);

$stmt->execute();

printf(" Έγινε εισαγωγή %d εγγραφής. Αποθηκεύτηκε το template %s.\n", $stmt->affected_rows ,$template  );

/* close statement and connection */
$stmt->close();
$mysqli->close();

?> 