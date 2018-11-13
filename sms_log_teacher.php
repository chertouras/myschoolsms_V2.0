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
$mysqli = new mysqli($servername, $username, $password, $dbname);
$mysqli->set_charset('utf8');


$sql = <<<SQL
    SELECT id , Username, RegistrationNumber, Telephone1 , name , surname , smsid , remarks , status , message , timeSent
    FROM `smsmessages` WHERE person=2
SQL;

if (!$result = $mysqli->query($sql)) {
    die('Κάποιο σφάλμα προέκυψε. Λεπτομέρειες: [' . $mysqli->error . ']');
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

   
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/responsive.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
   <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.bootstrap.min.css"/>
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>


   <style type="text/css">
   th.dt-center, td.dt-center { text-align: center; }
   </style>
  
  
    <script type="text/javascript">
           $(document).ready(function() {
           var table = $('#teachers').DataTable({
                responsive: true,
               dom: 'Bfrtip',
				buttons: ['print' ,
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4', 
				   		title:'Απεσταλμένα SMS Εκπαιδευτικών'					}
				],
             
              'columnDefs': [{
                        targets: [0 ],"visible": false,
                        "searchable": false
                    },{ "className": "dt-center", "targets": "_all" },
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [
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
}     
 });

         
 });  //document ready

</script>
</head>

<body>
<?php
if ($_SESSION["Access_level"] === 'moderator') {
    include 'navigation_moderator.php';
} elseif ($_SESSION["Access_level"] === 'admin') {
    include 'navigation_admin.php';
} else {

    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}

?>
 <br> 
 <h3>
     Στοιχεία αποστολής SMS εκπαιδευτικών
 </h3>

   <div id="buttonDiv"><br></div>
   <br>



<?php

echo "<table id='teachers' class='table table-striped table-bordered' width='100%' cellspacing='0'>
<thead>
<tr>
    <th>id</th>
    <th>ΑΦΜ</th>
    <th>Username Αποστολέα</th>
    <th>Επίθετο</th>
    <th>Όνομα</th>
    <th>Τηλέφωνο</th>  
    <th>Ημ.Αποστολής</th>  
    <th>Μήνυμα</th> 
     <th>status</th>
      <th>Remarks</th>
      <th>SMSid</th>
   
    </tr> </thead> <tfoot>
    <tr>
    <th>id</th>
    <th>ΑΦΜ</th>
    <th>Username Αποστολέα</th>
    <th>Επίθετο</th>
    <th>Όνομα</th>
    <th>Τηλέφωνο</th>  
    <th>Ημ.Αποστολής</th>  
    <th>Μήνυμα</th> 
     <th>status</th>
      <th>Remarks</th>
      <th>SMSid</th>
     
    </tr>
</tfoot><tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['RegistrationNumber'] . "</td>";
    echo "<td>" . $row['Username'] . "</td>";
    echo "<td>" . $row['surname'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['Telephone1'] . "</td>";
    echo "<td>" . $row['timeSent'] . "</td>";
    echo "<td>" . $row['message'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "<td>" . $row['remarks'] . "</td>";
    echo "<td>" . $row['smsid'] . "</td>";

    echo "</tr>";
}
echo "</tbody></table>";
?>   
      
</body>
</html>