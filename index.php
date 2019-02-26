<?php
$_GET = array_map("trim", $_GET);
$_GET=array_map('stripslashes', $_GET) ;
$filtered_post_get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
if (isset($_GET['message'])){
    $crd=$_GET['message'];
        if ($crd=='invalid')
            {
                 $crd="Παρακαλώ εισάγετε έγκυρα στοιχεία εισόδου";
            }
                            }
        else
            {
            $crd='';  
            }
$_POST = array_map("trim", $_POST);
$_POST=array_map('stripslashes', $_POST) ;
$filtered_post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

$username = $password = $userError = $passError = '';
if (isset($filtered_post['form-username']) && isset($filtered_post['form-password'])) {
    $username = $filtered_post['form-username'];
    $pass_word = $filtered_post['form-password'];
    
//connect to users database to identify who is logging in...
//var_dump($password);
$servername="localhost";
$username_db="xxxx";
$password_db="xxxxxx";
$dbname='persons_db';

$mysqli = new mysqli($servername, $username_db, $password_db , $dbname);
$mysqli->set_charset('utf8');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
  

$query= "SELECT Username , Passwd , Access_level FROM users WHERE Username = ?  AND  Passwd=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $username , $pass_word);
$stmt->execute();


$stmt->bind_result($Username, $Passwd, $Access_level );
$stmt->store_result();

if ( $stmt->num_rows == 1)
{
    $result =  $stmt->fetch();
    
    session_start();
        $_SESSION['loggedIn'] = true;
        $_SESSION['Username'] = $Username;
        $_SESSION['Access_level'] = $Access_level;
        $_SESSION['discard_after'] = time()+ 3600	;
         header('Location: main.php');
         exit();
}

else 


{

 header('Location: index.php?message=invalid');
        exit();
}


   }
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ΕΠΑΛ Ροδόπολης SMS Center</title>
        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">


     
       

<style>

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}

 

</style>


    </head>

    <body>







        <!-- Top content -->
        <div class="top-content">
      	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>ΕΠΑΛ Ροδόπολης </strong> SMS Center</h1>
                            <div class="description">
                            	<p>
	                            	Καλώς ήρθατε στην ιστοσελίδα αποστολής SMS του  
	                            	<a href="http://epal-rodop.ser.sch.gr"><strong>ΕΠΑΛ Ροδόπολης</strong></a>
                            	</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Είσοδος στο σύστημα</h3>
                            		<p>Παρακαλώ πληκτρολογήστε τα στοιχεία εισόδου:</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-key"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
			                    <form role="form" action="?" method="post" class="login-form">
			                    	<div class="form-group">
			                    		<label class="sr-only" for="form-username">Username</label>
			                        	<input type="text" name="form-username" placeholder="Username..." class="form-username form-control" id="form-username">
			                            <span style="color:red"><?php echo $crd;?></span>
                                    </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-password">Password</label>
			                        	<input type="password" name="form-password" placeholder="Password..." class="form-password form-control" id="form-password">
                                        <span style="color:red"><?php echo $crd;?></span>
                                    </div>
			                        <button type="submit" class="btn">Είσοδος</button>
			                    </form>
                 <span style="margin:5px"></span>

<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" id="myModalLabel">  Μήνυμα Συστήματος   </h4>
       </div>
       <div class="modal-body" id="getMessage" style="overflow-x: scroll;">
          
       </div>
    </div>
   </div>
 </div>


<span id="helper">
<div name="passwordResetModal" class="modal fade" id="modalSubscriptionForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Υπενθύμιση Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
      <form id="passwordResetForm" method="post">
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="number" id="form3" class="form-control validate" name="Telephone1" required>
          <label data-error="wrong" data-success="right" for="form3">Το τηλέφωνό σας ... </label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="form2" class="form-control validate" name="email" required>
          <label data-error="wrong" data-success="right" for="form2">Το email σας...</label>
        </div>
     
      </div>
      <div class="modal-footer d-flex justify-content-center">
<button class="btn btn-indigo" id='submitButton' type="submit">Αποστολή </button>
</form>
      </div>
    </div>
  </div>
</div>

<div class="text-center">
  <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalSubscriptionForm">Ξέχασα το Password μου...</a>
</div>
                            
          </span>                  
                            
                            
                            
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
            
        </div>



  






        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->


        <div id="footer">

		<p style="background-color: #FFFF00;font:14px Georgia,Times New Roman, Times, serif;">Δημιουργία Πληροφοριακού Συστήματος &amp; Διαχείριση :
     <span style="color:#009;  font:14px Georgia,Times New Roman, Times, serif;"><span style="color:#cf4d3f;font-size:80%">&#9632;</span>Χερτούρας</span>
     <span style="color:#009; font:14px Georgia,Times New Roman, Times, serif;">Κωνσταντίνος</span>
     <span style="color:#009;  font:14px Georgia,Times New Roman, Times, serif;"><span style="color:#cf4d3f;font-size:80%">&#9632;</span> Καθηγητής Πληροφορικής</span>
     <span style="color:#009;  font:14px Georgia,Times New Roman, Times, serif;"><span style="color:#F00; font-size:80%">&#9632;</span>chertour at sch.gr</span>
<span style="color:#009;  font:14px Georgia,Times New Roman, Times, serif;"><span style="color:#006400; font-size:80%">&#9632;</span><a href="http://users.otenet.gr/~chertour">users.otenet.gr/~chertour</a></span>
    <span style="color:#009;  font:14px Georgia,Times New Roman, Times, serif;"><span style="color:#006400; font-size:80%">&#9632;</span><a href="http://github.com/chertouras">github.com/chertouras</a></span>   
<!-- End Footer --></p>

	</div>
   
    <script type="text/javascript">



$(document).ready(function(){ 
  
  var mymodal = $("[name='passwordResetModal']");
 
    $('#passwordResetForm').submit(function(e){
      e.preventDefault();
   

   mymodal.find("#submitButton").html('<img src="ajax-loader.gif"> &nbsp; &nbsp; Παρακαλώ περιμένετε</img>').prop('disabled',true);
      $.post('passwordReminder.php', 
         $('#passwordResetForm').serialize(), 
       
         
         function(data, status, xhr){

         mymodal.find('form').trigger('reset');
    
        mymodal.find("#submitButton").html("Αποστολή").prop('disabled',false);
        mymodal.modal('hide');
        $("#getMessage").html(data);
        $("#messageModal").modal('show');
       
         });
     
});

});


        </script>

   
    </body>

</html>