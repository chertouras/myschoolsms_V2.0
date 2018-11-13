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

if ($_SESSION['Access_level'] == 'user' || $_SESSION['Access_level'] == 'moderator') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/responsive/2.2.0/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.bootstrap.min.css"/>
    <link type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet"/>
    <link type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>

    <style type="text/css">

        td.classqtip {
            background-color: whitesmoke !important;
            font-weight: bold;
            text-align: center;
        }

        form {
            display: table;
        }

        p {
            display: table-row;
        }

        label {
            display: table-cell;
        }

        input {
            display: table-cell;
        }

        label.error {
            float: none;
            color: red;
            font-size: 75%;
            display: block;
        }

        .myClass.ui-dialog input {
            font-size: .8em;
            margin: 3px;
        }

        .myClass.ui-dialog select {
            font-size: .8em;
            margin: 3px;
        }

        tfoot input {
            width: 70%;
            padding: 3px;
            box-sizing: border-box;
        }

        th.dt-center, td.dt-center {
            text-align: center;
        }

        .ui-tooltip,
        .qtip {
            max-width: 374px !important;
        }

        .new-tip-color td {
            padding: 5px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {

            var table = $('#users').DataTable({
                dom: 'Bfrtip',
                buttons: ['print',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        title: 'Λίστα Χρηστών'
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": 'users_list_db.php',
                responsive: true,
                "columnDefs": [
                    {
                        "targets": [0],
                        "searchable": false,
                        'visible': false,
                        "className": 'usefull'
                    },
                    {
                        "className": "classqtip",
                        "targets": [0, 1, 2, 3, 4]
                    },
                    {
                        "targets": [-2],
                        "data": null,
                        "defaultContent": "<button type='button' class='edit btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>&nbsp;</button>"
                    }, {
                        "targets": [-1],
                        "data": null,
                        "defaultContent": "<button type='button' class='delete btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span>&nbsp;</button>"
                    }, {
                        "className": "dt-center",
                        "targets": "_all"
                    }],
                "order": [
                    [3, "asc"],
                    [1, "asc"]
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

            new $.fn.dataTable.FixedHeader(table);

            $('#users tfoot th:lt(6)').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Αναζήτηση..." />');
            });

            table.columns().every(function () {
                var that = this;

                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });


            $("#dialog-confirm").dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: "auto",
                responsive: true,
                modal: true,
                buttons: {
                    "delbutton": {

                        id: 'delbutton',
                        text: 'Διαγραφή',

                        click: function () {
                            value = ($("#dialog-confirm").data('id'));


                            $.ajax({
                                url: "delete_user_db.php",
                                method: "POST",
                                data: {
                                    id: value
                                },
                                cache: false,
                                beforeSend: function () {
                                    // setting a timeout
                                    $('#delbutton').html('<img src="loader.gif" alt="Παρακαλώ Περιμένετε..." /> Παρακαλώ περιμένετε...');


                                },
                                success: function (result) {
                               
                                $("#deleteResult").html(result);
                                $("#deleteResultModal").modal('show');
                                    
                                    table.ajax.reload();
                                    $('#delbutton').html('Διαγραφή');

                                }
                            });
                            $(this).dialog("close");
                        }
                    },
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                }
            });

            $("#dialog-edit").dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: "auto",
                modal: true,
                responsive: true,
                dialogClass: 'myClass',
                open: function () {
                    var rowPassed = ($("#dialog-edit").data('data'));
                    
                
                    
                    $("#id").val(rowPassed[0]);
                    $("#LastName").val(rowPassed[1]);
                    $("#FirstName").val(rowPassed[2]);
                    $("#FatherFirstName").val(rowPassed[3]);
                    $("#Username").val(rowPassed[6]);
                    $("#Password").val(rowPassed[7]);
                    $("#email").val(rowPassed[8]);
                    $("#Access_level").val(rowPassed[5]).change();
                    $("#quota").val(rowPassed[9]);
                    $("#telephone").val(rowPassed[4]);
                },
                buttons: {
                    "updbutton": {

                        id: 'updbutton',
                        text: 'Ενημέρωση',

                        click: function () {
                            if ($('#modalformedit').valid()) {
                                $.ajax({
                                    url: "update_user_db.php",
                                    method: "POST",
                                    data: $('#modalformedit').serialize(),
                                    context: this,
                                    cache: false,
                                    beforeSend: function () {
                                        // setting a timeout
                                        $('#updbutton').html('<img src="loader.gif" alt="Παρακαλώ Περιμένετε..." /> Παρακαλώ περιμένετε...');

                                    },
                                    success: function (result) {
                                        table.ajax.reload();
                                        $('#updbutton').html('Ενημέρωση');
                                        $(this).dialog("close");
                                    }
                                });
                            }
                        }
                    },
                    Ακύρωση: function () {
                        $(this).dialog("close");
                    }
                }
            });

            $('#dialog-add').dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: "auto",
                modal: true,
                dialogClass: 'myClass',
                title: 'Προσθήκη Εγγραφής...',
                close: function (event, ui) {
                    $("#modalformadd").trigger('reset');
                    validator.resetForm();
                },
                buttons: {
                    "savebutton": {

                        id: 'savebutton',
                        text: 'Αποθήκευση',

                        click: function () {
                            if ($('#modalformadd').valid()) {
                                $.ajax({
                                    url: "insert_user_db.php",
                                    method: "POST",
                                    data: $('#modalformadd').serialize(),
                                    context: this, //most important
                                    cache: false,
                                    beforeSend: function () {
                                        // setting a timeout
                                        $('#savebutton').html('<img src="loader.gif" alt="Παρακαλώ Περιμένετε..." /> Παρακαλώ περιμένετε...');

                                    },

                                    success: function (result) {
                                                      
                                        if (result === '0')
                  
                                              {
                                            alert ('O χρήστης ήδη υπάρχει. Επίλέξτε κάποιο άλλο Username');
                                            $('#savebutton').html('Αποθήκευση');
                                            return;  
                                                  }
                                        $("#modalformadd").trigger('reset');
                                        table.ajax.reload();
                                        $('#savebutton').html('Αποθήκευση');
                                        $(this).dialog("close");
                                    }
                                })
                            }
                        }
                    },
                    Ακύρωση: function () {
                        $(this).dialog("close");
                        $("#modalformadd").trigger('reset');
                        validator.resetForm();
                    }
                }
            });

            $('#users').on('click', '.delete', function (e) {
                var table = $(this).closest('table').DataTable();
                if (!jQuery.isEmptyObject(table.row($(this).parents('tr')).data())) {
                    var data = table.row($(this).parents('tr')).data();
                } else {
                    var data = table.row((this)).data();
                }
                $('#tableVal').text(' ' + data[1] + ' ' + data[2] + ' ?');
                $('#dialog-confirm').data('id', data[0]).dialog('open');
            });

            $('#users').on('click', '.edit', function (e) {
                var table = $(this).closest('table').DataTable();
                if (!jQuery.isEmptyObject(table.row($(this).parents('tr')).data())) {
                    var data = table.row($(this).parents('tr')).data();
                } else {
                    var data = table.row((this)).data();
                }
                $('#dialog-edit').data('data', data).dialog('open');
            });




            var validator = $('#modalformadd').validate({
                rules: {
                 
                   
                    FirstName: {
                        required: true,
                        minlength: 3
                    },
                    LastName: {
                        required: true,
                        minlength: 3
                    },
                    FatherFirstName: {
                        required: true,
                        minlength: 3
                    }, 
                    Access_level: {
                        required: true,
                        
                    },
                    Telephone1: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },
                    Username: {
                        required: true,
                        minlength: 3
                    },
                    Password: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    quota: {
                        required: true
                    }

                },
                messages: {
                    
                    FirstName: "Παρακαλώ εισάγετε το ονομά σας",
                    LastName: "Παρακαλώ εισάγετε το επιθετό σας",
                    FatherFirstName: "Παρακαλώ εισάγετε το πατρωνυμο σας",
                    Telephone1: "Παρακαλώ εισάγετε το τηλέφωνό σας",
                    Username: "Παρακαλώ εισάγετε το username σας",
                    Password: "Παρακαλώ εισάγετε το password σας",
                    email: "Παρακαλώ εισάγετε το email σας",
                    Access_level: "Παρακαλώ επιλέξτε από την λίστα επιλογών",
                    quota: "Παρακαλώ εισάγετε δικαίωμα SMS"
                }
            });

            var validatoredit = $('#modalformedit').validate({
                rules: {
                    FirstName: {
                        required: true,
                        minlength: 3
                    },
                    LastName: {
                        required: true,
                        minlength: 3
                    },
                    FatherFirstName: {
                        required: true,
                        minlength: 3
                    }, 
                    Access_level: {
                        required: true,
                        
                    },
                    telephone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },
                    Username: {
                        required: true,
                        minlength: 3
                    },
                    Password: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    quota: {
                        required: true
                    }

                },
                messages: {
                    FirstName: "Παρακαλώ εισάγετε το ονομά σας",
                    LastName: "Παρακαλώ εισάγετε το επιθετό σας",
                    FatherFirstName: "Παρακαλώ εισάγετε το πατρωνυμο σας",
                    telephone: "Παρακαλώ εισάγετε το τηλέφωνό σας",
                    Username: "Παρακαλώ εισάγετε το username σας",
                    Password: "Παρακαλώ εισάγετε το password σας",
                    email: "Παρακαλώ εισάγετε το email σας",
                    Access_level: "Παρακαλώ επιλέξτε από την λίστα επιλογών",
                    quota: "Παρακαλώ εισάγετε δικαίωμα SMS"

                }
            });
            $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
                var checked = $('.filter').is(':checked');

                if (checked && aData[7] == 3) {
                    return true;
                }

                return false;
            });
            var oTable = $('#users').dataTable();
            $('.filter').on("click", function (e) {

                oTable.fnDraw();
            });


            $('#add_user').click(function () {
                $('#dialog-add').dialog('open');
                return false;

            });

        });
       
    </script>
</head>

<body>


<?php

if ($_SESSION["Access_level"] === 'admin') {
    include 'navigation_admin.php';
} else {

    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();

}

?>


<h3>
    Διαχείριση πίνακα Χρηστών
</h3>


<br><span style='background-color:yellow'><em>Οδηγίες:</em></span>
<strong> Με την χρήση των κουμπιών <span class='glyphicon glyphicon-edit'></span> και <span
            class='glyphicon glyphicon-trash'></span>
    μπορείτε να Επεξεργαστείτε ή να Διαγράψετε εγγραφές.</strong><br>
<br>
<br>
<table id='users' class="table table-striped table-bordered nowrap" width="100%" cellspacing="0">
    <thead>

    <tr>
        <th>id</th>
        <th>Επίθετο</th>
        <th>Όνομα</th>
        <th>Πατρώνυμο</th>
        <th>Τηλέφωνο</th>
        <th>Επίπεδο Πρόσβασης</th>
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th>Δικαίωμα SMS</th>
        <th data-b-sortable="false"></th>
        <th data-b-sortable="false"></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
         <th>id</th>
        <th>Επίθετο</th>
        <th>Όνομα</th>
        <th>Πατρώνυμο</th>
        <th>Τηλέφωνο</th>
        <th>Επίπεδο Πρόσβασης</th>
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th>Δικαίωμα SMS</th>
        <th data-b-sortable="false"></th>
        <th data-b-sortable="false"></th>
    </tr>
    </tfoot>


</table>

<div id="dialog-confirm" title="Επιβεβαίωση Διαγραφής?" style="display: none;">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> Είστε σίγουρος για την
        διαγραφή του χρήστη
        <span id="tableVal"></span>
    </p>
</div>

<div id="dialog-edit" title="Ενημέρωση στοιχείων χρήστη" style="display: none;">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
    </p>

    <form id="modalformedit" action="#">
        <input type="hidden" name="id" id='id'>
       
       
        <p>
            <label for="FirstName">Όνομα : </label>
            <input type="text" name="FirstName" id="FirstName" size="35"/>
        </p>
        <p>
            <label for="LastName">Επίθετο: </label>
            <input type="text" name="LastName" id="LastName" size="35"/>
        </p>
        <p>
            <label for="FatherFirstName">Πατρώνυμο: </label>
            <input type="text" name="FatherFirstName" id="FatherFirstName" size="35"/>
        </p>

          <p>
            <label for="telephone">Τηλέφωνο: </label>
            <input type="text" pattern="\d*" name="telephone" id="telephone" size="35"/>
        </p>
        <p>
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" size="35"/>
        </p>

        <p>
            <label for="Username">Username: </label>
            <input type="text" name="Username" id="Username" size="35"/>
        </p>
        <p>
            <label for="Password">Password: </label>
            <input type="text" name="Password" id="Password" size="35"/>
        </p> 
      
        

        <p>
            <label for="Access_level">Access_level: </label>
            <select name="Access_level" id="Access_level">
                <option value="user">User</option>
                <option value="moderator">Moderator</option>
                <option value="admin">Admin</option>
            </select>

        </p>

        <p>
            <label for="quota">Quota: </label>
            <input type="text" name="quota" id="quota" size="35"/>
        </p>
        

    </form>


</div>


<button type="button" class="btn btn-success" id='add_user'><span class='glyphicon glyphicon-plus'>&nbsp;</span>Προσθήκη
    εγγραφής
</button>


<div id="dialog-add" title="Προσθήκη στοιχείων χρήστη" style="display: none;">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
    </p>

    <form id="modalformadd">
    <p>
            <label for="FirstName">Όνομα : </label>
            <input type="text" name="FirstName" id="FirstName" size="35"/>
        </p>
        <p>
            <label for="LastName">Επίθετο: </label>
            <input type="text" name="LastName" id="LastName" size="35"/>
        </p>
        <p>
            <label for="FatherFirstName">Πατρώνυμο: </label>
            <input type="text" name="FatherFirstName" id="FatherFirstName" size="35"/>
        </p>

        <p>
            <label for="Telephone1">Τηλέφωνο: </label>
            <input type="text" pattern="\d*" name="Telephone1" id="Telephone1" size="35"/>
        </p>
        <p>
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" size="35"/>
        </p>
          <p>
            <label for="Username">Username: </label>
            <input type="text" name="Username" id="Username" size="35"/>
        </p>
        <p>
            <label for="Password">Password: </label>
            <input type="text" name="Password" id="Password" size="35"/>
        </p>
        

        <p>
            <label for="Access_level">Access_level: </label>
            <select name="Access_level" id="Access_level">
                <option value="user">User</option>
                <option value="moderator">Moderator</option>
                <option value="admin">Admin</option>
            </select>

        </p>
        <p>
            <label for="quota">Quota: </label>
            <input type="text" name="quota" id="quota" size="35"/>
        </p>
      
    
       
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteResultModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" id="myModalLabel"> Μήνυμα Συστήματος</h4>
       </div>
       <div class="modal-body" id="deleteResult" style="overflow-x: scroll;">
          
       </div>
    </div>
   </div>
 </div>


</body>
</html>