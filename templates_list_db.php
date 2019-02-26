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
$table = 'sms_templates';

// Table's primary key
$primaryKey = 'id';
$columns = array(
   array( 'db' => 'id',  'dt' => 0 ),
    array( 'db' => 'Username',   'dt' => 1 ),
    array( 'db' => 'template',   'dt' => 2 )
  
);

// SQL server connection information
$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $servername
 );
 


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* If you just want to use the basic configuration for DataTables with PHP
* server-side, there is no need to edit below this line.
*/

require( 'ssp.class.php' );
//firter the result set by username
$value=$_SESSION['Username'];

//we use the complex query
echo json_encode(
   SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns , null , "Username='$value'" )
);

?>