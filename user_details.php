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

$Username = $_SESSION['Username'];

$sql = <<<SQL
    SELECT id , Username, Telephone1 , FirstName , LastName , FatherFirstName , Access_level , Passwd , email , quota
    FROM `users` WHERE Username='$Username'
SQL;

if(!$result = $mysqli->query($sql)){
    die('Κάποιο σφάλμα προέκυψε. Λεπτομέρειες: [' . $mysqli->error . ']');
}

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Εισαγωγή Εγγραφής</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <link type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
	
    <script type="text/javascript">
        $(document).ready(function () {
        
            var validator = $('#modalformadd').validate({
                rules: {
                    FirstName: { required: true, minlength: 3 },
                    LastName: { required: true, minlength: 3 },
                    FatherFirstName: { required: true, minlength: 3 },
                    Telephone1: { required: true, minlength: 10, maxlength: 10 },
                    Access_level: { required: true},
                    Username: { required: true},
                    Password: { required: true},
                    email: { required: true},
                    quota: { required: true},

                },
                messages: {
                    FirstName: "Παρακαλώ εισάγετε ένα όνομα ",
                    LastName: "Παρακαλώ εισάγετε ένα επίθετο",
                    FatherFirstName: "Παρακαλώ εισάγετε το πατρώνυμο ",
                    Telephone1: "Παρακαλώ εισάγετε το τηλέφωνο ",
                    Access_level: "Παρακαλώ επιλέξτε επίπεδο πρόσβασης",
                    Username: "Παρακαλώ εισάγετε ένα όνομα χρήστη (στα Αγγλικά)... ",
                    Password: "Παρακαλώ εισάγετε ενα κωδικό πρόσβασης...",
                    email: "Παρακαλώ εισάγετε email...",
                    quota:"Παρακαλώ εισάγετε το δικαίωμα για sms..."
                },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
				
		 $('#modalformadd').submit(function (event) {

event.preventDefault();

if ($('#modalformadd').valid()) {
    $("#formadd").css("display", "none");
    $("#spinner").css("display", "block");

              $.ajax({

                url: "user_details_update_db.php",
                method: "POST",
                data:  $('input:not([readonly="readonly"])').serialize()+'&id='+ $('#RegistrationNumber').val() ,
                context: this,  //most important
                cache: false,
                success: function (result) {
                
                
                 if (result === '0')
                  
                  {
                   alert ('O χρήστης ήδη υπάρχει. Επιλέξτε κάποιο άλλο Username');
                   return;  
                  }
                $("#modalformadd").trigger('reset');
                 validator.resetForm();

                }
              }).done(function(result) {
               
               result=result+'<br> H σελιδα θα ανανεωθεί αυτόματα...'
                $("#spinner").css("display", "none"); 
                 $("#formadd").css("display", "block");
                     $("#resultajax").html(result).fadeOut(6000, function() {
                 $(this).html("").show(); //reset the label after fadeout
                 setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 100); 
        });
                		  
			  }); 
          }
             });
 }); //document ready
 $(document).ready(function(){

$('[data-toggle="tooltip"]').tooltip();   

});
</script>
</head>

<body>
<?php
if ($_SESSION["Access_level"]==='moderator')
{include 'navigation_moderator.php';}
elseif  ($_SESSION["Access_level"]==='admin')
{include 'navigation_admin.php';}
elseif ($_SESSION["Access_level"]==='user')
{include 'navigation_user.php';}
else

{

  session_unset();
  session_destroy();
  header('Location: index.php');
  exit();

}

?>
<br>
<div style='border: 1px solid #000; display:inline-block ; background-color:yellow; word-wrap: break-word;'>
<span style='background-color:yellow'><em>Οδηγίες:</em></span>
    <strong>Με την παρακάτω φόρμα, μπορείτε να τροποποιείσετε μέρος των στοιχείων σας. <br>
     </strong>
    <br>
  </div>


<div id="formadd">
<form class="well form-horizontal" action="#" method="post"  id="modalformadd">
<fieldset>
<legend>Στοιχεία Χρήστη</legend>

<div class="form-group">
  <label class="col-md-4 control-label">Αριθμός Εισαγωγής:</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
 
	   <input data-toggle="tooltip" title="Δυνατότητα αλλαγής μόνο απο τον διαχειριστή..." type="text" id="RegistrationNumber" name="RegistrationNumber" class="form-control" readonly="readonly" value=<?php echo $row["id"];   ?>>
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" >Όνομα:</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="FirstName" name="FirstName" placeholder="Όνομα" class="form-control"  type="text" value=<?php echo $row["FirstName"];   ?>>
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" >Επίθετο:</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="LastName" name="LastName" placeholder="Επίθετο" class="form-control"  type="text" value=<?php echo $row["LastName"];   ?>> 
    </div>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label">Πατρώνυμο:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="FatherFirstName" name="FatherFirstName" placeholder="Πατρώνυμο" class="form-control" type="text" value=<?php echo $row["FatherFirstName"];   ?>>
    </div>
  </div>
</div>  


<div class="form-group">
  <label class="col-md-4 control-label">Τηλέφωνο:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
  <input id="Telephone1" name="Telephone1"  class="form-control" type="text" value=<?php echo $row["Telephone1"];   ?> >
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Διαβάθμιση:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock  "></i></span>
        <input data-toggle="tooltip" title="Δυνατότητα αλλαγής μόνο απο τον διαχειριστή..."  id="Access_level" name="Access_level"  class="form-control" readonly="readonly" value=<?php echo $row["Access_level"];   ?> >
 
        
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label">Email:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
  <input id="email" name="email" placeholder="user@example.com" class="form-control" type="email" value=<?php echo $row["email"];   ?>>
    </div>
  </div>
</div>


 
<div class="form-group">
  <label class="col-md-4 control-label">Username:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
  <input data-toggle="tooltip" title="Δυνατότητα αλλαγής μόνο απο τον διαχειριστή..."  id="Username" name="Username" readonly="readonly" class="form-control" type="text" value=<?php echo $row["Username"];   ?>>
 
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Password:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
  <input id="Password" name="Password" autocomplete="new-password" class="form-control" type="text" value=<?php echo $row["Passwd"];   ?> >
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Quota SMS:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-send"></i></span>
  <input data-toggle="tooltip" title="Δυνατότητα αλλαγής μόνο απο τον διαχειριστή..."  id="quota" name="quota" pattern="\d*" placeholder="Δικαίωμα SMS" readonly="readonly" class="form-control" type="text" value=<?php echo $row["quota"];   ?>> 
    </div>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <input type="submit" name='Submit' value='Υποβολή' class="btn btn-warning" > 

  <input type="reset" name='Reset' value='Καθαρισμός' class="btn btn-danger" > 

 <span id="resultajax" ></span>
	</div>
	 
</div>

</fieldset> 
</form>
</div>
    
	
<div id="spinner" style="display:none">Αποθήκευση σε εκτέλεση ...</div>
</body>

</html>