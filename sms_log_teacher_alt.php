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
    <style type="text/css">
      th.dt-center, td.dt-center {
        text-align: center;
      }
      td.details-control {
        background: url('details_open.png') no-repeat center center;
        cursor: pointer;
      }
      tr.shown td.details-control {
        background: url('details_close.png') no-repeat center center;
      }
    </style>
    <script type="text/javascript">
      $(document).ready(function() {
        var dt = $('#teachers').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": { "url": 'sms_list_alt_db.php',
          
            "data": {
        "person": 2
                       }
          },
          "columns": [
            {
              "class":          "details-control",
              "orderable":      false,
              "data":           null,
              "defaultContent": ""
            }
            ,
            {
              "data": "RegistrationNumber" }
            ,
            {
              "data": "surname" }
            ,
            {
              "data": "name" }
            ,
            {
              "data": "Telephone1" }
            ,
            {
              "data": "timeSent" }
            ,
            {
              "data": "message" }
            ,
            {
              "data": "smsid" }
            ,
            {
              "data": "remarks" }
            ,
          ],
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
            }
            ,
            "oAria": {
              "sSortAscending":  ": ενεργοποιήστε για αύξουσα ταξινόμηση της στήλης",
              "sSortDescending": ": ενεργοποιήστε για φθίνουσα ταξινόμηση της στήλης"
            }
            ,
            'select': {
              'rows': "%d επιλεγμένες στύλες"
            }
          }
        }
                                         );
        var detailRows = [];
        function format (callback, d ) {
          $.ajax({
            url: "select_details_db.php",
            method: "POST",
            dataType: "json",
            data: {
              id: d.RegistrationNumber , person:2
            }
            ,
            cache: false,
            complete: function (response) {
              var data = JSON.parse(response.responseText);
              var thead = '',  tbody = '';
              if (!( data[0] == undefined )){
                for (var key in data[0]) {
                  switch (key) {
                    case "RegistrationNumber":
                      processed_key ='AM';
                      break;
                    case "Telephone1":
                      processed_key =0;
                      break;
                    case "error":
                      processed_key =0;
                      break;
                    case "message":
                      processed_key ='Μήνυμα';
                      break;
                    case "name":
                      processed_key ='Όνομα';
                      break;
                    case "remarks":
                      processed_key ='Παρατηρήσεις';
                      break;
                    case "smsid":
                      processed_key ='Smsid';
                      break;
                    case "surname":
                      processed_key ='Επίθετο';
                      break;
                    case "status":
                      processed_key ='Κατάσταση';
                      break;
                    case "timeSent":
                      processed_key ='Ημ.Αποστολής';
                      break;
                    default:
                      processed_key=  "Πρόβλημα εμφανίστηκε";
                  }
                  if (processed_key != 0){
                    thead += '<th>' + processed_key + '</th>';
                  }
                }
                $.each(data, function (i, d) {
                  tbody += '<tr><td>' + d.RegistrationNumber + '</td><td>' + d.surname + '</td>\
<td>' + d.name + '</td><td>' + d.timeSent + '</td><td>' + d.message + '</td>\
<td>' + d.smsid + '</td><td>' + d.remarks + '</td><td>' + d.status + '</td>\
      </tr>';
                }
                      );
              }
              else
              {
                thead = '<th>  Αποτέλεσμα  </th>';
                tbody = '<tr><td> Δεν υπάρχουν άλλα μηνύματα στο αρχείο </tr></td>';
              }
              callback($('<table class="table table-bordered">' + thead + tbody + '</table>')).show();
            }
            ,  error: function () {
              alert(' there was an error!');
            }
          }
                );
        }
        $('#teachers tbody').on('click', 'td.details-control', function () {
          var tr = $(this).closest('tr');
          var row = dt.row( tr );
          if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
          }
          else {
            // Open this row
           
            format(row.child,row.data());
            tr.addClass('shown');
          }
        }
                               );
      }
                       );
      //document ready
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
      Στοιχεία αποστολής SMS Εκπαιδευτικών
    </h3>
    <div id="buttonDiv">
      <br>
    </div>
    <br>
    <table id='teachers' class='table table-striped table-bordered' width='100%' cellspacing='0'>
      <thead>
        <tr>
          <th>
          </th>
          <th>ΑΦΜ
          </th>
          <th>Επίθετο
          </th>
          <th>Όνομα
          </th>
          <th>Τηλέφωνο
          </th>  
          <th>Ημ.Αποστολής
          </th>  
          <th>Μήνυμα
          </th> 
          <th>SMSid
          </th>
          <th>Παρατηρήσεις
          </th>
        </tr> 
      </thead>
      <tfoot>
        <tr>
          <th>
          </th>
          <th>ΑΦΜ
          </th>
          <th>Επίθετο
          </th>
          <th>Όνομα
          </th>
          <th>Τηλέφωνο
          </th>  
          <th>Ημ.Αποστολής
          </th>  
          <th>Μήνυμα
          </th> 
          <th>SMSid
          </th>
          <th>Παρατηρήσεις
          </th>
        </tr>
      </tfoot> 
    </table>   
    <br>
    <br>
    <table style='table-layout: fixed; border-collapse: collapse; ' id="sms_names">
    </table>
    <br>
    <br>
  </body>
</html>
