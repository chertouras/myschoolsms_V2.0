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
include 'parameters.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
     <script src='http://ksylvest.github.io/jquery-growl/javascripts/jquery.growl.js' type='text/javascript'></script> 
     <link href="http://ksylvest.github.io/jquery-growl/stylesheets/jquery.growl.css" rel="stylesheet" type="text/css">

 <style type="text/css">
  .glyphicon.spinning {
    animation: spin 1s infinite linear;
    -webkit-animation: spin2 1s infinite linear;
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg); }
    to { transform: scale(1) rotate(360deg); }
}

@-webkit-keyframes spin2 {
    from { -webkit-transform: rotate(0deg); }
    to { -webkit-transform: rotate(360deg); }
}
</style>


    <script type="text/javascript">
   
        $(document).ready(function() {
           $('#message').val('');
            var maxChars = $("#message");
            var max_length = maxChars.attr('maxlength');
            if (max_length > 0) {
                maxChars.bind('keyup', function(e) {
                    length = new Number(maxChars.val().length);
                    counter = max_length - length;
                    $("#smsNum_counter").text(counter);
                });
            }


            $('#clear').on('click', function() {
                    $('#message').val('');
                    $("#smsNum_counter").text('140');
                }
            );

         
      var wrapper = $(".input_fields_wrap"); //
       $('#addfield').on('click', function() {
          $(wrapper)
          .append('<div><input type="text" title="Παρακαλώ εισάγετε έναν έγκυρο 10-ψηφιο αριθμό τηλεφώνου" oninvalid="setCustomValidity(\'Παρακαλώ εισάγετε έναν έγκυρο 10-ψηφιο αριθμό τηλεφώνου\')" placeholder="Αριθμός Τηλεφώνου 69xxxxxxxx" class="form-control" pattern="[0-9]{10}" required   name="telNumber[]" oninput="setCustomValidity(\'\')"/><a href="#" class="remove_field"> <i class="fa fa-times-circle-o fa-lg" style="margin-bottom:10px"></i>Αφαίρεση</a></div>'); 
         });
  
      $(wrapper).on("click",".remove_field", function(e){ //
        e.preventDefault(); $(this).parent('div').remove(); 
    });        
          
            $('#frm').on('submit', function(e) {
                e.preventDefault();
                
                var textareaData= $('#message').val();
                var mobNums = $('input[name^=telNumber]').map(function(idx, elem) {
    return $(elem).val();
  }).get();

                  var result= $.each( mobNums ,  function ( key, value  ) {
          
                $( "#apostoli" ).addClass('disabled');
                $('#spanloading').addClass('glyphicon-refresh spinning');
                  $.ajax({
                    dataType: "json",
                    url: "smscenter.php",
                    method: "POST",
                    data: {to:value , message:textareaData },
                    cache: false,
                       success:function(result){
                         var status = parseInt(result["status"]);
              
                          if (status==1) {
                                  mymessage = "Το μύνημα στάλθηκε στον αριθμό " + value + ' με επιτυχία';
                                  $.growl.notice({ message:mymessage });
                                           }
                          else {  
                                  mymessage = "Η αποστολή στον αριθμό " + value + ' Απέτυχε';
                                 $.growl.error({ message:mymessage });
                                }              
                }
            }).done(function(results){
                     $.ajax({
                
                         url: "sms_log_insert_db.php",
                          method: "POST",
                          data: {Username:'<?php echo $_SESSION['Username'] ?>',Telephone1:value , person:'3', name:'Μεμονωμένη', surname:'Μεμονωμένη' ,RegistrationNumber:'Μεμονωμένη', message:textareaData , results:results},
                           cache: false,
                             success:function(result){
                                $('#spanloading').removeClass('glyphicon-refresh spinning');
                                $( "#apostoli" ).removeClass('disabled')   ;
                          }
                             });
                             });
                             });
                             });
                             });  //document ready

</script>
</head>

<body>
<?php
if ($_SESSION['Access_level'] === 'user') {
    include 'navigation_user.php';
} elseif ($_SESSION['Access_level'] === 'moderator') {
    include 'navigation_moderator.php';

} elseif ($_SESSION['Access_level'] === 'admin') {
    include 'navigation_admin.php';

} else {

    include 'navigation_user.php';
}

?>

<br>
<h3>
     Αποστολή SMS σε μεμονωμένους παραλήπτες
 </h3>
 <br>
 <div style='border: 1px solid #000; display:inline-block ; background-color:yellow; word-wrap: break-word;'>
 <span style='background-color:yellow'><em>Οδηγίες:</em></span>
<strong> Εισάγεται τον αριθμό του χρήστη στον οποίο θέλετε να στείλετε SMS. <br>
 Πατώντας το κουμπί <em>Προσθήκη Τηλεφώνου</em> μπορείτε να προσθέσετε παραλήπτες.</strong></div>

     <form id="frm" action="#" method="GET" role="form" style="margin-top:20px" >
    <div class="form-group row">
      <div class="input_fields_wrap col-xs-4">  
      <button type='button' class='add btn btn-primary' id='addfield'>
      <span class='glyphicon glyphicon-plus'></span>Προσθήκη Τηλεφώνου</button>
      <input type="text" class="form-control" pattern="[0-9]{10}" required oninvalid="setCustomValidity('Παρακαλώ εισάγετε έναν έγκυρο 10-ψηφιο αριθμό τηλεφώνου')"  oninput="setCustomValidity('')" title="Παρακαλώ εισάγετε έναν έγκυρο 10-ψηφιο αριθμό τηλεφώνου"  name="telNumber[]" style='margin-bottom:20px;margin-top:20px' placeholder="Αριθμός Τηλεφώνου 69xxxxxxxx">
      </div> 
     </div>
   
    <div class="form-group row">
    <div class="col-xs-8">  
    <textarea class="form-control" id="message" name="message" rows="4" cols="50" maxlength="140" required></textarea>
            <br>
            <br> Υπολοιπόμενοι Χαρακτήρες: <span id="smsNum_counter">140</span><br>
            <br>
      </div> </div>
    <button id="clear" type="reset" class='btn btn-danger'>Καθαρισμός</button>
    <button type="submit"  class='btn btn-success'>Αποστολή</button>
  </form>

  
</body>
</html>