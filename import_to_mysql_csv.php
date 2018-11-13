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



$_GET = array_map("trim", $_GET);
$_GET = array_map('stripslashes', $_GET);
$filtered_post_get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
if (isset($_GET['method'])) {
    $method = $_GET['method'];
} else {

    exit();
}


$mysqli = new mysqli($servername, $username, $password);
$mysqli->set_charset('utf8');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (!$mysqli->select_db($dbname)) {
    $sql = 'CREATE DATABASE' . $dbname . 'CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci';
    $result = $mysqli->query($sql);
    if ($result) {
        echo "H βάση δεδομένων $dbname δημιουργήθηκε επιτυχώς\n";
    } else {
        echo 'Σφάλμα κατά την δημιουργία της βάση δεδομένων: ' . $mysqli->error . "\n";
        echo '<br/> Η βάση δεδομένων δεν δημιουργήθηκε';
    }
};

$mysqli->select_db($dbname);
$table = 'teachers';
if ($result = $mysqli->query("SHOW TABLES LIKE '" . $table . "'")) {
    if ($result->num_rows == 1) {
        echo "<br/>Ο πίνακας teachers υπάρχει ήδη και δεν αλλοιώθηκε.<br/>";
    } else {
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
        if ($mysqli->query($sql) === true) {
            echo "<br/>  <b>Ο πίνακας teachers δημιουργήθηκε επιτυχώς.</b><br/>";
        } else {
            echo "<br/> Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
            echo "<br/> Πιθανά ο πίνακας υπάρχει ήδη. ";
        }
    }
} else {
    echo "<br/> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;
}
$mysqli->select_db($dbname);



if (isset($_FILES['csvfile']) && ($_FILES['csvfile']['error'] == UPLOAD_ERR_OK)) {
    $data = $_FILES['csvfile']['tmp_name'];
    $data = str_replace("\\", "\\\\", $data);

    /************************************************ */
    /************************************************ */
   /* CHECK SOMEHOW THE VALIDITY OF CSV*/

    $row = 1;
    $expectedColumnNum = 12;
    if (($handle = fopen($data, "r")) !== false) {
        while (($datacsv = fgetcsv($handle, 1000, ";")) !== false) {
        //  var_dump($data);
            if (count($datacsv) != $expectedColumnNum) {
                echo " <br>Το αρχείο CSV που δοκιμάσατε να εισάγετε δεν περιέχει τον αριθμό στηλών που περιέχει το <br>
            αρχείο που εξάγει το myschool";
                exit();
            }
        // Επόμενος έλεγχος για υλοποίηση
        // if (!DateTime::createFromFormat('Y-m-d', $data[1])) {
        //     echo "To CSV στην στήλη ... δεν περιέχει έγκυρη ημερομηνία ";
        //     exit();
        // }
        }
        fclose($handle);
    }
    /************************************************ */
    /************************************************ */
    /************************************************ */

    if ($method == '2') {
        $file_data = file_get_contents($data);
  
  //BE CAREFULL - ASSUMING WINDOWS
        $utf8_file_data = iconv(mb_detect_encoding($file_data, mb_detect_order(), true), "UTF-8", $file_data);

        file_put_contents($data, $utf8_file_data);
    }
    /************************************************ */
    /************************************************ */
    /************************************************ */
    /************************************************ */
    $sql = "LOAD DATA LOCAL INFILE '" . $data . "'  
INTO TABLE `teachers` CHARACTER SET utf8 FIELDS TERMINATED BY ';'  
LINES TERMINATED BY '\r\n' IGNORE 1 LINES 
(am, afm, LastName, FirstName, FatherFirstName, sxesi_ergasias, sxesi_topothetisis );";

    $result = $mysqli->query($sql);
    if (!$result) {
        die('<br> Δεν έγινε μεταφόρτωση: ' . $mysqli->error . '<br> Σίγουρα είναι το αρχείο ήδη UTF-8?');
    } else {
        echo ("<br>H εισαγωγή των εγγραφών έγινε επιτυχώς");
    }
} else {
    echo "<br>To αρχείο δεν μπόρεσε να μεταφορτωθεί.";
    echo "<br>Ελέξτε εαν έχετε επιλέξει αρχείο για μεταφόρτωση.";
    echo "<br> Παρακαλώ ξαναδοκιμάστε";
    exit();
}
exit();

?> 