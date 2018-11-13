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

$level = array("admin", "moderator");
if (!in_array($_SESSION['Access_level'], $level ))
{
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}
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
        jQuery(function ($) {
          $.datepicker.regional['el'] = {
          clearText: 'Σβήσιμο', clearStatus: 'Σβήσιμο της επιλεγμένης ημερομηνίας',
          closeText: 'Κλείσιμο', closeStatus: 'Κλείσιμο χωρίς αλλαγή',
          prevText: 'Προηγούμενος', prevStatus: 'Επισκόπηση προηγούμενου μήνα',
          prevBigText: '&#x3c;&#x3c;', prevBigStatus: '',
          nextText: 'Επόμενος', nextStatus: 'Επισκόπηση επόμενου μήνα',
          nextBigText: '&#x3e;&#x3e;', nextBigStatus: '',
          currentText: 'Τρέχων Μήνας', currentStatus: 'Επισκόπηση τρέχοντος μήνα',
          monthNames: ['Ιανουάριος', 'Φεβρουάριος', 'Μάρτιος', 'Απρίλιος', 'Μάιος', 'Ιούνιος',
            'Ιούλιος', 'Αύγουστος', 'Σεπτέμβριος', 'Οκτώβριος', 'Νοέμβριος', 'Δεκέμβριος'],
          monthNamesShort: ['Ιαν', 'Φεβ', 'Μαρ', 'Απρ', 'Μαι', 'Ιουν',
            'Ιουλ', 'Αυγ', 'Σεπ', 'Οκτ', 'Νοε', 'Δεκ'],
          monthStatus: 'Επισκόπηση άλλου μήνα', yearStatus: 'Επισκόπηση άλλου έτους',
          weekHeader: 'Εβδ', weekStatus: '',
          dayNames: ['Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο'],
          dayNamesShort: ['Κυρ', 'Δευ', 'Τρι', 'Τετ', 'Πεμ', 'Παρ', 'Σαβ'],
          dayNamesMin: ['Κυ', 'Δε', 'Τρ', 'Τε', 'Πε', 'Πα', 'Σα'],
          dayStatus: 'Ανάθεση ως πρώτη μέρα της εβδομάδος: DD', dateStatus: 'Επιλογή DD d MM',
          dateFormat: 'dd-mm-yy', firstDay: 1,
          initStatus: 'Επιλέξτε μια ημερομηνία', isRTL: false
        };
        $.datepicker.setDefaults($.datepicker.regional['el']);
      });

            var validator = $('#modalformadd').validate({
                rules: {
                    FirstName: { required: true, minlength: 3 },
                    LastName: { required: true, minlength: 3 },
                    FatherFirstName: { required: true, minlength: 3 },
                    MotherFirstName: { required: true, minlength: 3 },
                    BirthDate: {},
                    Telephone1: { required: true, minlength: 10, maxlength: 10 },
                    LevelName: { required: true, maxlength: 1 },
                },
                messages: {
                    FirstName: "Παρακαλώ εισάγετε ένα όνομα ",
                    LastName: "Παρακαλώ εισάγετε ένα επίθετο",
                    FatherFirstName: "Παρακαλώ εισάγετε το πατρώνυμο ",
                    MotherFirstName: "Παρακαλώ εισάγετε το μητρώνυμο ",
                    Telephone1: "Παρακαλώ εισάγετε το τηλέφωνο ",
                    LevelName: "Παρακαλώ εισάγετε την τάξη  (A | B | Γ)",
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

                url: "insert_student_db.php",
                method: "POST",
                data:  $( this ).serialize() ,
                context: this,  //most important
                cache: false,
                success: function (result) {
              
                $("#modalformadd").trigger('reset');
                 validator.resetForm();

                }
              }).done(function(result) {
                 $("#spinner").css("display", "none"); 
                 $("#formadd").css("display", "block");
                     $("#resultajax").html(result).fadeOut(5000, function() {
                 $(this).html("").show(); //reset the label after fadeout
        });
                		  
			  }); 
          }
             });
 }); //document ready

$(function () {
  $("#BirthDate").datepicker({ dateFormat: 'yy-mm-dd',  changeMonth: true,
            changeYear: true,
            showButtonPanel: true,yearRange: "-100:+0" });
    });
</script>
</head>

<body>
<?php

if ($_SESSION["Access_level"]==='moderator')
{include 'navigation_moderator.php';}
elseif  ($_SESSION["Access_level"]==='admin')
{include 'navigation_admin.php';}
else {

  session_unset();
  session_destroy();
  header('Location: index.php');
  exit();

}


?>
<br>
<div style='border: 1px solid #000; display:inline-block ; background-color:yellow; word-wrap: break-word;'>
<span style='background-color:yellow'><em>Οδηγίες:</em></span>
    <strong>Με την παρακάτω φόρμα, μπορείτε να εισάγετε μαθητές
      που δεν υπάρχουν στο αρχείο XML του MySchool. <br>
      Πληκτρολογήστε το σύνολο των στοιχείων τους και πατήστε το κουμπί Υποβολή 
      ώστε <br>να γίνει εισαγωγή του μαθητή στη βάση δεδομέων της εφαρμογής.
      <br> Το σύστημα περιλαμβάνει έλεγχο λαθών ώστε να συμπληρώνονται όλα
    τα στοιχεία της φόρμας.    </strong>
    <br>
  </div>


<div id="formadd">
<form class="well form-horizontal" action="#" method="post"  id="modalformadd">
<fieldset>
<legend>Εισαγωγή Μαθητή</legend>

<div class="form-group">
  <label class="col-md-4 control-label">Αριθμός Εισαγωγής:</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
 
	   <input type="text" id="RegistrationNumber" name="RegistrationNumber" class="form-control" readonly="readonly" placeholder="Θα δοθεί αυτόματα" />
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" >Όνομα:</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="FirstName" name="FirstName" placeholder="Όνομα" class="form-control"  type="text">
    </div>
  </div>
</div>
	

<div class="form-group">
  <label class="col-md-4 control-label" >Επίθετο:</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="LastName" name="LastName" placeholder="Επίθετο" class="form-control"  type="text">
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Τάξη:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-blackboard"></i></span>
  <input id="LevelName" name="LevelName" placeholder="Τάξη Α|Β|Γ" class="form-control"  type="text">
    </div>
  </div>
</div>

       
<div class="form-group">
  <label class="col-md-4 control-label">Τηλέφωνο:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
  <input id="Telephone1" name="Telephone1" pattern="\d*" placeholder="6972999999" class="form-control" type="text">
    </div>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label">Πατρώνυμο:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="FatherFirstName" name="FatherFirstName" placeholder="Πατρώνυμο" class="form-control" type="text">
    </div>
  </div>
</div>
 
<div class="form-group">
  <label class="col-md-4 control-label">Μητρώνυμο:</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input id="MotherFirstName" name="MotherFirstName" placeholder="Μητρώνυμο" class="form-control"  type="text">
    </div>
  </div>
</div>

<div class="form-group"> 
  <label class="col-md-4 control-label">Ημ. Γέννησης:</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
  <input  id="BirthDate" name="BirthDate" placeholder="Ημ.Γέννησης" class="form-control"  type="text">
  </div>
</div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <input type="submit" name='Submit' value='Υποβολή' class="btn btn-warning" > </input>
	<br>
 <span id="resultajax" ></span>
	</div>
	 
</div>

</fieldset> 
</form>
</div>
    
	
<div id="spinner" style="display:none">Αποθήκευση σε εκτέλεση ...</div>
</body>

</html>