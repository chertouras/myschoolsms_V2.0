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
$mysqli = new mysqli($servername, $username, $password , $dbname);
$mysqli->set_charset('utf8');
$sql = <<<SQL
    SELECT id , RegistrationNumber , FirstName , LastName , FatherFirstName , BirthDate , Telephone1 , LevelName
    FROM `students`
SQL;

if(!$result = $mysqli->query($sql)){
    die('Υπήρξε κάποιο πρόβλημα στο σύστημα της βάσης δεδομένων [' . $mysqli->error . ']');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

   
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/responsive.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
      
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
     <link type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" /> 
     <link type="text/css" href="https://cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet" /> 
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
     <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.bootstrap.min.css"/>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
     <script src='http://ksylvest.github.io/jquery-growl/javascripts/jquery.growl.js' type='text/javascript'></script> 
    <link href="http://ksylvest.github.io/jquery-growl/stylesheets/jquery.growl.css" rel="stylesheet" type="text/css">

    <style type="text/css">
      
  th.dt-center, td.dt-center { text-align: center; }
        div.dt-buttons {
float: right;
margin-left:10px;

}
#sms_names tr { display: none }
#sms_names tr:nth-child(-n+3) { display: block }

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

.warning{
    background-color: #787878 !important;
}
    </style>



    <script type="text/javascript">
   var thetable = {};
        $(document).ready(function() {
           $('#message').val('');
            $('#divform').hide();
            $('#togglebutton').hide();
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







            var templatesTable= $('#templatesTable').DataTable({
          "processing": true,
            "serverSide": true,
            "ajax":{url:'templates_list_db.php',"cache":false},
            responsive: true,
                "columnDefs": [
                    {
                        "targets": [0],
                        "searchable": false
                     
                    },
                    {
                        "targets": [-1],
                        "data": null,
                        "defaultContent": "<button type='button' class='use btn btn-xs btn-primary'>Χρήση&nbsp;</button>"
                    }, 
                     {
                        "className": "dt-center",
                        "targets": "_all"
                    }],
                "order": [
                    [1, "asc"],
                    [0, "asc"]
                ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Δεν υπάρχουν δεδομένα στον πίνακα",
                    "sInfo": "Εμφανίζονται _START_ έως _END_ από _TOTAL_ εγγραφές",
                    "sInfoEmpty": "Εμφανίζονται 0 έως 0 από 0 εγγραφές",
                    "sInfoFiltered": "(φιλτραρισμένες από _MAX_ συνολικά εγγραφές)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Δείξε _MENU_ εγγραφές",
                    "sLoadingRecords": "Φόρτωση...",
                    "sProcessing": "Επεξεργασία...",
                    "sSearch": "Αναζήτηση:",
                    "sSearchPlaceholder": "Αναζήτηση",
                    "sThousands": ".",
                    "sUrl": "",
                    "sZeroRecords": "Δεν βρέθηκαν εγγραφές που να ταιριάζουν",
                    "oPaginate": {
                        "sFirst": "Πρώτη",
                        "sPrevious": "Προηγούμενη",
                        "sNext": "Επόμενη",
                        "sLast": "Τελευταία"
                    },
                    "oAria": {
                        "sSortAscending": ": ενεργοποιήστε για αύξουσα ταξινόμηση της στήλης",
                        "sSortDescending": ": ενεργοποιήστε για φθίνουσα ταξινόμηση της στήλης"
                    },
                    'select': {
                        'rows': "%d επιλεγμένες στύλες"
                    }
                }

            });






            var table = $('#students').DataTable({
              "dom": '<"#buttonDiv"B>lfrtip',
                rowId: 'id',
                buttons: [
                'selectAll',
                'selectNone' ],
               select: true,
               responsive: true,
              'columnDefs': [{
                        targets: [0 ],"visible": false,
                        "searchable": false
                    },{ "className": "dt-center", "targets": "_all" },
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [[7, 'asc'],
                    [2, 'asc']
                ] ,
                language:  {
    "sDecimal":           ",",
    "sEmptyTable":        "Δεν υπάρχουν δεδομένα στον πίνακα",
    "sInfo":              "Εμφανίζονται _START_ έως _END_ από _TOTAL_ εγγραφές",
    "sInfoEmpty":         "Εμφανίζονται 0 έως 0 από 0 εγγραφές",
    "sInfoFiltered":      "(φιλτραρισμένες από _MAX_ συνολικά εγγραφές)",
    "sInfoPostFix":       "",
    "sInfoThousands":     ".",
    "sLengthMenu":        "Δείξε _MENU_ εγγραφές",
    "sLoadingRecords":    "Φόρτωση...",
    "sProcessing":        "Επεξεργασία...",
    "sSearch":            "Αναζήτηση:",
    "sSearchPlaceholder": "Αναζήτηση",
    "sThousands":         ".",
    "sUrl":               "",
    "sZeroRecords":       "Δεν βρέθηκαν εγγραφές που να ταιριάζουν",
    "oPaginate": {
        "sFirst":    "Πρώτη",
        "sPrevious": "Προηγούμενη",
        "sNext":     "Επόμενη",
        "sLast":     "Τελευταία"
    },
    "oAria": {
        "sSortAscending":  ": ενεργοποιήστε για αύξουσα ταξινόμηση της στήλης",
        "sSortDescending": ": ενεργοποιήστε για φθίνουσα ταξινόμηση της στήλης"
    },
    'select': {
            'rows': "%d επιλεγμένες στύλες"
        }
} ,


initComplete: function () {
            
       
            this.api().columns([2,3,4,5,6,7]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search($(this).val())
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }      
 });
  
 
 thetable.ref=table;
        
 table.button(0).text('Επιλογή Όλων');  
  
  table.button(1).text('Αποεπιλογή Όλων');  

   table
        .on( 'select', function ( e, dt, type, indexes ) {
            var rows_selected = dt.rows( { selected: true } ).count();
            var rowData = table.rows( indexes ).data().toArray();
           
            $('#sms_names').text('');
            if ((rows_selected) >= 1) {
                $('#divform').show();

            } else {
                $('#divform').hide();
            }
 
            if ((rows_selected) > 4) {
               
                $('#togglebutton').show();
               
            } 
            else {
                $('#togglebutton').hide();
            }
         
            printSelectedRows();
          $('#sms_names').find('tr:gt(3)').toggle();
        
        } ) //.on()

        .on( 'deselect', function ( e, dt, type, indexes ) {
            
            var rows_selected = dt.rows( { selected: true } ).count();
            var rowData = table.rows( indexes ).data().toArray();
            $('#sms_names').text('');
            if ((rows_selected) >= 1) {
                $('#divform').show();

            } else {
                $('#divform').hide();
            }


            if ((rows_selected) <= 4) {
                $('#togglebutton').hide();

            } else {
                $('#togglebutton').show();
            }
            printSelectedRows();
            $('#sms_names').find('tr:gt(3)').toggle();

            
           
        } );




    
 
            $("#togglebutton").on("click", function() {
    $('#sms_names').find('tr:gt(3)').toggle();
    if($(this).text() == 'Απόκρυψη')
       {
           $(this).text('Εμφάνιση περισσότερων');
       }
       else
       {
           $(this).text('Απόκρυψη');
       }
           });
    
    
    
    
         
          
            $('#frm').on('submit', function(e) {
                e.preventDefault();
                
                var textareaData= $('#message').val();
                var rows_selected= table.rows( { selected: true } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                var rowData = this.data();
                
                $( "#apostoli" ).addClass('disabled');
                $('#spanloading').addClass('glyphicon-refresh spinning');
                  $.ajax({
                dataType: "json",
                url: "smscenter.php",
                method: "POST",
                data: {to:rowData[6] , message:textareaData },
                cache: false,
                success:function(result){
                   var myselector="#smsno"+rowIdx;
                   var status = parseInt(result["status"]);
                   var sql_result = parseInt(result["sql_result"]);
                   $("#sms_names").find(myselector).closest('tr').append('<td><b>Αναφορά:</b>'+ 
                              (status==1 ? 'Το μήνυμα στάλθηκε <img src="ok.png">': 'H αποστολή απέτυχε <img src="notok.png"></td>')).append('<td>Log from the mobile network: '+result["remarks"] + '</td>');
                 
                              if (sql_result==1) {
                    mymessage = "Έγινε αφαίρεση 1 δικαιώματος sms από το λογαριασμό σας"    ;
                    $.growl.notice({ message:mymessage });
                }
                else {  
                    mymessage = 'Απέτυχε η ενημέρωση των δικαιωματων sms';

                    $.growl.error({ message:mymessage });

                }
               
               
               
               
                            }
            }).done(function(results){
                    $.ajax({
                
                         url: "sms_log_insert_db.php",
                         method: "POST",
                         data: {Username:'<?php echo $_SESSION['Username'] ?>', Telephone1:rowData[6] , person:'1', name:rowData[3], surname:rowData[2] ,RegistrationNumber:rowData[1], message:textareaData , results:results},
                          cache: false,
                             success:function(result){
                                $('#spanloading').removeClass('glyphicon-refresh spinning');
                                $( "#apostoli" ).removeClass('disabled')   ;            }
                            });
                                         });
       });
    });

       
       
        function printSelectedRows() {
           
          var rows_selected= table.rows( { selected: true } ).every( function ( rowIdx, tableLoop, rowLoop ) {
          var rowData = this.data();
   
           $('#sms_names')   
        .append('<tr  style="border: 1px solid black; display : table-row;"><td style=" border: 1px solid black;font-size: 85%;font-weight: bold; " id="smsno'+rowIdx+'">'+rowData[2] + ' ' + rowData[3] + ' ' 
                + ' </td><td  style=" color: blue; border: 1px solid black;">'+rowData[6]+' </td><td  style=" color: blue; border: 1px solid black;">')
        .append('</td></tr>');
   
         });
     
           
           };
 





 
 $('#saveTemplate').on("click", function (){
    var textareaData= $('#message').val();
    if (textareaData.length===0 )
       {
       
        
        $('#emptySMS').modal('show')

       }

       else {
    //exit();
    //check if val is null
    mymessage = 'Γίνεται αποθήκευση του μυνήματος ως προτύπου...';
    $.growl.error({title: "Αποθήκευση σε εξέλιξη", message:mymessage });
    $.ajax({
                
                url: "insert_template_db.php",
                method: "POST",
                data: {template:textareaData},
                 cache: false,
                    success:function(result){
                        $.growl.error({title: "Μήνυμα συστήματος", message:'Επιτυχής Αποθήκευση' });          }
                   });

       }

 })

 $('#useTemplate').on("click", function (){
$('#templatesToUse').on('shown.bs.modal', function (e) {
    templatesTable.ajax.reload();
      $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
}).modal('show');


$('#templatesTable').on('click', '.use', function (e) {
                templatesTable.ajax.reload();
                console.log(templatesTable);
                var table = $(this).closest('table').DataTable();
                
                if (!jQuery.isEmptyObject(table.row($(this).parents('tr')).data())) {
                    var data = table.row($(this).parents('tr')).data();
                } else {
                    var data = table.row((this)).data();
                }
                $('#message').val(data[2]);


                length = new Number(maxChars.val().length);
                    counter = max_length - length;
                    $("#smsNum_counter").text(counter);
            });






 });
/*   */
 
 });  //document ready

</script>
</head>

<body>
<?php
if ($_SESSION["Access_level"]==='moderator')
{include 'navigation_moderator.php';}
elseif  ($_SESSION["Access_level"]==='admin')
{include 'navigation_admin.php';}
elseif  ($_SESSION["Access_level"]==='user')
{include 'navigation_user.php';}


else {

  session_unset();
  session_destroy();
  header('Location: index.php');
  exit();

}

?>
<br> 
 <h3>
     Αποστολή SMS σε μαθητές 
 </h3>
 <br>
 <div style='border: 1px solid #000; display:inline-block ; background-color:yellow'>
 <span style='background-color:yellow'><em>Οδηγίες:</em></span>
<strong> Επιλέγετε από τον πίνακα των μαθητών κάνοντας click <span class='glyphicon glyphicon-hand-up'></span> 
πάνω στην αντίστοιχη γραμμή. <br>
Η γραμμή υπερτονίζεται με <span style='background-color:#B0BED9'>γκρι χρώμα</span> και τοποθετείται στη λίστα με τους επιλεγμένους παραλήπτες 
κάτω από τον πίνακα. <br>Μπορείτε να επιλέξετε το σύνολο των εγγραφών από το κουμπί "Επιλογή Όλων" και να 
αποστείλετε μαζικά <span class="fa-stack">
  <i class="fa fa-comment fa-stack-2x"></i>
  <i class="fa fa-stack-1x fa-stack-text fa-inverse">SMS </i>
</span> σε όλους</strong></div>
 <br>
 <br>
   <div id="buttonDiv"><br></div>
   <br>
<?php




echo "<table id='students' class='table table-striped table-bordered' width='100%' cellspacing='0'>
<thead>
<tr>
    <th>id</th>
    <th>A/A</th> 
    <th>Επίθετο</th>
    <th>Όνομα</th>
    <th>Πατρώνυμο</th>
    <th>Ημ. Γέννησης</th>
    <th>Τηλέφωνο</th>
    <th>Τάξη</th>
    </tr> </thead> <tfoot>
    <tr>
      <th>id</th>
      <th>A/A</th>
      <th>Επίθετο</th>
       <th>Όνομα</th>
      <th>Πατρώνυμο</th>
     <th>Ημ. Γέννησης</th>
      <th>Τηλέφωνο</th>
      <th>Τάξη</th>
     
    </tr>
</tfoot><tbody>";



while($row = $result->fetch_assoc())
{
echo "<tr>";
echo "<td>" . $row['id'] . "</td>";
echo "<td>" . $row['RegistrationNumber'] . "</td>";
echo "<td>" . $row['LastName'] . "</td>";
echo "<td>" . $row['FirstName'] . "</td>";
echo "<td>" . $row['FatherFirstName'] . "</td>";
echo "<td>" . $row['BirthDate'] . "</td>";
echo "<td>" . $row['Telephone1'] . "</td>";
echo "<td>" . $row['LevelName'] . "</td>";
echo "</tr>";
}
echo "</tbody></table>";
$Username=$_SESSION['Username'];
$sql = <<<SQL
    SELECT quota from users  where Username='$Username'
SQL;

if(!$result = $mysqli->query($sql)){
    die('Υπήρξε κάποιο πρόβλημα στο σύστημα της βάσης δεδομένων [' . $mysqli->error . ']');
}
$row = $result->fetch_assoc();
if (intval($row['quota']) < 1){


 
 echo 
 "
  <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
    <div class=\"modal-dialog\">
    
      <!-- Modal content-->
      <div class=\"modal-content\">
        <div class=\"modal-header\">
          <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
          <h4 class=\"modal-title\">Προσοχή!</h4>
        </div>
        <div class=\"modal-body\">
          <p>Δεν έχετε υπολοιπόμενα sms για αποστολή! Θα πρέπει να επικοινωνήσετε με τον διαχειριστή
          για να σας εγκρίνει νέο μερίδιο sms! Μπορείτε μόνο να δείτε τα τηλέφωνα χωρίς να 
          τα επιλέξετε!</p>
        </div>
        <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 
 <script type='text/javascript'>
     $(document).ready(function(){  
         $('#myModal').modal('show'); 
    
        thetable.ref.select.style('api');
        var cells = thetable.ref.cells().nodes();
        $(cells).addClass('warning');     
      
    });
 </script>";
 }
?>   
    <br>
    <br>
    <table style='table-layout: fixed; border-collapse: collapse;' id="sms_names"></table>
    <br>
 
    <button id='togglebutton' class='btn btn-primary'>Εμφάνιση περισσότερων</button>
    <br>
    <br>
    <div id="divform" style='margin-bottom:20px;display:inline-block;'>
        <form id="frm" action="#" method="GET">
            <textarea id="message" name="message" rows="4" cols="50" maxlength="140" required></textarea>
            <div style="display:inline-block">
            <button id="saveTemplate"  type="button" class="btn btn-danger" style="display:block;  width: 160px !important;">Αποθήκευση προτύπου</button>
            <button  id="useTemplate"  type="button" class="btn btn-info" style="display:block; width: 160px !important;">Χρήση προτύπου</button>
            </div>  
            
            <br> Υπολοιπόμενοι Χαρακτήρες: <span id="smsNum_counter">140</span><br>
            <br>
            <button id="clear" type="reset" class='btn btn-danger'>Καθαρισμός</button>
            <button id="apostoli" class='btn btn-success' type="submit"> 
            <span id="spanloading" class="glyphicon"></span> Αποστολή</button>
        </form>

       
    </div> 
    <div style='display:inline-block;'></div>








    <div class="modal fade" id="emptySMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Μήνυμα Συστήματος</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      Δεν έχετε γράψει περιεχόμενο για να έχει νόημα η αποθήκευση.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
        
      </div>
    </div>
  </div>
</div>



<!--    

Modal to hold the templates

-->

<div class="modal fade" id="templatesToUse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Πρότυπα Χρήστη</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table id='templatesTable' class='table table-striped table-bordered' width='100%' cellspacing='0'>
<thead>
<tr>
    <th>id</th>
    <th>Όνομα Χρήστη</th>
    <th>Πρότυπο</th>
    <th data-b-sortable="false"></th>
    </tr> </thead> <tbody>
</tbody></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
        
      </div>
    </div>
  </div>
</div>

</body>
</html> 