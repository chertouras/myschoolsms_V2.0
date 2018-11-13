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
include 'parameters.php';
$table = 'smsmessages';

// Table's primary key
$primaryKey = 'id';


$columns = array(
    array(
        'db' => 'id',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_'.$d;
        }
    ),
    array( 'db' => 'id',  'dt' =>'id' ),
    array( 'db' => 'RegistrationNumber',  'dt' => 'RegistrationNumber' ),
   array( 'db' => 'Telephone1',  'dt' => 'Telephone1' ),
   array( 'db' => 'name',   'dt' => 'name'),
   array( 'db' => 'surname',   'dt' =>  'surname'),
   array( 'db' => 'error',   'dt' => 'error' ),
   array( 'db' => 'smsid',   'dt' => 'smsid' ),
   array( 'db' => 'remarks',   'dt' =>'remarks' ),
   array( 'db' => 'timeSent',   'dt' => 'timeSent' ),
   array( 'db' => 'message',     'dt' =>'message' )
  
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

echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>