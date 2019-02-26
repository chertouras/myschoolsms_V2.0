<?php
$servername="localhost";
$username="xxxx";
$password="xxxx";
$dbname='persons_db';
$mysqli = new mysqli($servername, $username, $password , $dbname);
$mysqli->set_charset('utf8');


//Who is logged in ?
 


$table='users';


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  
 if(!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['Telephone1']) || empty($_POST['Telephone1']))
{
   echo 'Παρακαλώ να εισάγεται τα στοιχεία που σας ζητούνται από την φόρμα υπενθύμισης.'; 
   exit();
}
else{
$email = trim($_POST['email']); 
$Telephone1=trim($_POST['Telephone1']); 
} 


//check if username or email already exists

if ($stmt = $mysqli->prepare("SELECT email , Passwd FROM users WHERE email=? and Telephone1=?")) {

    /* bind parameters for markers */
    $stmt->bind_param("si", $email ,$Telephone1 );

    /* execute query */
    $status = $stmt->execute();

    // $stmt->store_result();
    
     $rows = $stmt->num_rows;

   
/* BK: always check whether the execute() succeeded */
if ($status === false) {
  trigger_error($stmt->error, E_USER_ERROR);
}

    /* bind result variables */
    $stmt->bind_result($dbEmail,$Passwd);

    /* fetch value */
    $stmt->fetch();

   if ( $dbEmail!==$email  )
   { printf("Δεν βρέθηκε καμία διεύθυνση με στοιχεία: %s που να ταυτίζεται με τον αριθμό τηλεφώνου %s",  $email ,$Telephone1 ) ; 
    
} else 
{
  printf("Ζητήθηκε η αποστολή email στην διεύθυνση %s",  $dbEmail ," με το αποθηκευμένο password σας.");

    
      
   
    $from = "chertour@otenet.gr";
    $to = $dbEmail;
    $subject = "Αποστολή Password από την εφαρμογή αποστολής SMS";
     mb_internal_encoding('UTF-8');
    $encoded_subject = mb_encode_mimeheader($subject, 'UTF-8', 'B', "\r\n", strlen('Subject: '));
   
    $message = "Το αποθηκευμένο password σας είναι: " . $Passwd;
    $headers = "From:" . $from."\r\n";
    $headers.= "Content-Type: text/plain;charset=utf-8\r\n";
   


    if(mail($to,$encoded_subject,$message, $headers))
    {
        echo " <br> Έγινε επιτυχής αποστολή";
    } 
    else 
    {
        echo "  <br>  ΑΠΟΤΥΧΙΑ ΑΠΟΣΤΟΛΗΣ";
    }
    
    }//if else
    $stmt->close();
}
else 
{

    echo "MySQLi Failed. Please contact the system administrator"; 
    
}




$mysqli->close();

?> 