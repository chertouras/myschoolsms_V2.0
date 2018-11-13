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

/**
 * 
 * 
 * check if teachers table exist / if not create it
 * 
 */

$table = 'users';
if ($result = $mysqli->query("SHOW TABLES LIKE '" . $table . "'")) {
    if ($result->num_rows != 1) {

        $sql = "CREATE TABLE `users` (
    `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    `FirstName` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `LastName` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `FatherFirstName` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `Telephone1` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `Access_level` enum('user','moderator','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
    `Username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `Passwd` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `email` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `quota` int(6) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        if ($mysqli->query($sql) === true) {
            echo "<br/>";
            echo "<b>Ο πίνακας $table δημιουργήθηκε επιτυχώς</b><br\>";

        } else {
            echo "<br/>";
            echo "Σφάλμα κατά την δημιουργία του πίνακα " . $mysqli->error;
            echo "Πιθανά ο πίνακας υπάρχει ήδη. ";
            echo "<br/>";
        }
    }//if
} else {
    echo "<br> Σφάλμα κατά τον έλεγχο ύπαρξης/δημιουργίας του πίνακα " . $mysqli->error;

}

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (!isset($_POST['Username']) || empty($_POST['Username'])) {
    echo '0';
    exit();
} else {
    $username = trim($_POST['Username']);
}
$query = "SELECT * FROM users WHERE username = ? ";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if (($stmt->num_rows) > 0) {
    echo '0';
    $stmt->close();
    exit();
}
$stmt->close();
/* close statement */

$FirstName = trim($_POST['FirstName']);
$LastName = trim($_POST['LastName']);
$FatherFirstName = trim($_POST['FatherFirstName']);
$Telephone1 = trim($_POST['Telephone1']);
$Access_level = trim($_POST['Access_level']);
$Username = trim($_POST['Username']);
$Passwd = trim($_POST['Password']);
$email = trim($_POST['email']);
$quota = trim($_POST['quota']);



$stmt = $mysqli->prepare("INSERT INTO users
                 (FirstName,LastName,FatherFirstName,Telephone1, Access_level, Username , Passwd , email , quota)
                  VALUES(?,?,?,?,?,?,?,?,?)");

$stmt->bind_param(
    "sssssssss",
    $FirstName,
    $LastName,
    $FatherFirstName,
    $Telephone1,
    $Access_level,
    $Username,
    $Passwd,
    $email,
    $quota
);
$stmt->execute();
printf("<br/> Έγινε εισαγωγή %d εγγραφής.<br/>Εισήχθη ο χρήστης %s %s.\n", $stmt->affected_rows, $LastName, $FirstName);
/* close statement and connection */
$stmt->close();
$mysqli->close();

?> 