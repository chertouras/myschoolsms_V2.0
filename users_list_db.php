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
$table = 'users';

// Table's primary key
$primaryKey = 'id';
$columns = array(
    array('db' => 'id', 'dt' => 0),
    array('db' => 'LastName', 'dt' => 1),
    array('db' => 'FirstName', 'dt' => 2),
    array('db' => 'FatherFirstName', 'dt' => 3),
    array('db' => 'telephone1', 'dt' => 4),
    array('db' => 'Access_level', 'dt' => 5),
    array('db' => 'Username', 'dt' => 6),
    array('db' => 'Passwd', 'dt' => 7),
    array('db' => 'email', 'dt' => 8),
    array('db' => 'quota', 'dt' => 9)

);

// SQL server connection information
$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db' => $dbname,
    'host' => $servername
);
 


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);

?>