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

if (isset($_GET['person'])) {

    $person = intval($_GET['person']);

} else {
    exit();
}


$table = <<<EOT
(SELECT t1.*
FROM smsmessages t1
WHERE t1.timeSent = (SELECT MAX(t2.timeSent)
				 FROM smsmessages t2
                 WHERE t2.RegistrationNumber = t1.RegistrationNumber) and person =$person)
                 temp 
EOT;
// Table's primary key
$primaryKey = 'id';

$columns = array(
    array(
        'db' => 'id',
        'dt' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ),
    array('db' => 'id', 'dt' => 'id'),
    array('db' => 'RegistrationNumber', 'dt' => 'RegistrationNumber'),
    array('db' => 'Telephone1', 'dt' => 'Telephone1'),
    array('db' => 'name', 'dt' => 'name'),
    array('db' => 'surname', 'dt' => 'surname'),
    array('db' => 'error', 'dt' => 'error'),
    array('db' => 'smsid', 'dt' => 'smsid'),
    array('db' => 'remarks', 'dt' => 'remarks'),
    array('db' => 'timeSent', 'dt' => 'timeSent'),
    array('db' => 'message', 'dt' => 'message'),
    array('db' => 'status', 'dt' => 'status')

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

require('ssp.class2.php');

echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, null)
);

?>