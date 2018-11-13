<?php 
session_start(); 
$now=time(); 
if (isset($_SESSION[ 'discard_after']) && $now> $_SESSION['discard_after']) 
{ session_unset(); 
	session_destroy(); 
} 
if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!=1){
	 header('Location: index.php'); exit(); 
	 } 


     ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/yadcf/0.9.1/jquery.dataTables.yadcf.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.bootstrap.min.css" />
    <link type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" />
    <link type="text/css" href=" https://cdnjs.cloudflare.com/ajax/libs/yadcf/0.9.1/jquery.dataTables.yadcf.css" rel="stylesheet" />
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
    font-weight: bold; text-align: center;
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
        .yadcf-filter {
            width: 70px;
        }
        tfoot input {
            width: 70%;
            padding: 3px;
            box-sizing: border-box;
        }
        th.dt-center,
        td.dt-center {
            text-align: center;
        }
        .ui-tooltip,
        .qtip {
            max-width: 370px !important;
        }
        .new-tip-color td {
            padding: 5px;
        }





    </style>


    <script type="text/javascript">
        $(document).ready(function () {

            var table = $('#students').DataTable({
                dom: 'Bfrtip',
				buttons: ['print' ,
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4', 
				   		title:'Λίστα Μαθητών'					
                    }
				],
               
                "processing": true,
                "serverSide": true,
                "order": [
                    [9, "asc"],
                    [3, "asc"]
                ],
                "ajax": 'students_list_db.php',
                responsive: true,

                "columnDefs": [

                    { "className": "classqtip", "targets": [0,1,2,3,4 ] 
                    },
                    {
                        "className": "dt-center",
                        "targets": "_all"
                    }, 
                    {
                        "targets": [0, 1],
                        "searchable": false,
                        'visible': false,
                        "className": 'usefull'
                    }, {
                        "targets": [-2],
                        "data": null,
                        "defaultContent": "<button type='button' class='edit btn btn-xs btn-primary'><span class='glyphicon glyphicon-edit'></span>&nbsp;</button>"
                    }, {
                        "targets": [-1],
                        "data": null,
                        "defaultContent": "<button type='button' <?php if ($_SESSION['Access_level']!='admin'){  echo 'disabled title=\"Απαιτούνται δικαιώματα διαχειριστή\"';}  ?> class='delete btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span>&nbsp;</button>"
                    }
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
                    },
                    'sEmptyTable': "No records available - Got it?",
                }

            });
            yadcf.init(table, [{
                    column_number: 0
                },

                {
                    column_number: 3,
                    filter_type: "text",
                    filter_default_label: "Εισάγετε..."
                },

                {
                    column_number: 7,
                    filter_type: "text",
                    filter_default_label: "Εισάγετε..."
                }, {
                    column_number: 8,
                    filter_type: "text",
                    filter_default_label: "Εισάγετε..."
                }, {
                    column_number: 9,
                    filter_type: "text",
                    filter_default_label: "Εισάγετε..."
                }
            ], 'footer');


            new $.fn.dataTable.FixedHeader(table);

            $("#dialog-confirm").dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: "auto",
                modal: true,
                buttons: {
                    "delbutton": {

                        id:'delbutton',
                        text :'Διαγραφή',

                      click:  function () {

                            value = ($("#dialog-confirm").data('id'));

                            $.ajax({
                                url: "delete_student_db.php",
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
                                    table.ajax.reload();
                                    $('#delbutton').html('Διαγραφή');

                                }
                            });
                          $(this).dialog("close");
                        } },
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
                dialogClass: 'myClass',
                open: function () {
                    var rowPassed = ($("#dialog-edit").data('data'));
                    $("#RegistrationNumber").val(rowPassed[2]);
                    $("#FirstName").val(rowPassed[4]);
                    $("#LastName").val(rowPassed[3]);
                    $("#FatherFirstName").val(rowPassed[5]);
                    $("#MotherFirstName").val(rowPassed[6]);
                    $("#BirthDate").val(rowPassed[7]);
                    $("#Telephone1").val(rowPassed[8]);
                    $("#LevelName").val(rowPassed[9]);
                },
                buttons: {

                    "updbutton": {

                        id:'updbutton',
                        text :'Ενημέρωση',

                        click:  function () {
                            if ($('#modalformedit').valid()) {
                                $.ajax({

                                    url: "update_student_db.php",
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
                        }},
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
                title: 'Προσθήκη Εγγραφής',
                close: function (event, ui) {
                    $("#modalformadd").trigger('reset');
                    validator.resetForm();
                },
                buttons: {
                "savebutton": {

                    id:'savebutton',
                        text :'Αποθήκευση',

                        click:  function () {
                        if ($('#modalformadd').valid()) {
                            $.ajax({

                                url: "insert_student_db.php",
                                method: "POST",
                                data: $('#modalformadd').serialize(),
                                context: this, //most important
                                cache: false,
                                beforeSend: function () {
                                    // setting a timeout
                                    $('#savebutton').html('<img src="loader.gif" alt="Παρακαλώ Περιμένετε..." /> Παρακαλώ περιμένετε...');

                                },

                                success: function (result) {

                                    $("#modalformadd").trigger('reset');
                                    table.ajax.reload();
                                    $('#savebutton').html('Αποθήκευση');
                                    $(this).dialog("close");

                                }
                            })
                        }


                    }},
                    Ακύρωση: function () {
                        $(this).dialog("close");
                        $("#modalformadd").trigger('reset');
                        validator.resetForm();
                    }
                }

            });

            $('#students').on('click', '.delete', function (e) {

                var table = $(this).closest('table').DataTable();

                if (!jQuery.isEmptyObject(table.row($(this).parents('tr')).data())) {
                    var data = table.row($(this).parents('tr')).data();
                } else {
                    var data = table.row((this)).data();
                }
                $('#tableVal').text(' ' + data[3] + ' ' + data[4] + '?');
                $('#dialog-confirm').data('id', data[2]).dialog('open');
            });



            $('#students').on('mouseenter', 'tbody tr ', function (event) {

                var data = table.row((this)).data();
                var $id_row = data[2];
                var $surname = data[3];
                var $name = data[4];
                
                $(this).find('.classqtip').qtip({
                    overwrite: false,
                    style: 'new-tip-color',
                    content: {
                        text: '<img src="loader.gif" alt="Παρακαλώ Περιμένετε..." /> Παρακαλώ περιμένετε...',
                        ajax: {
                            url: 'qtooltipsmslist.php',
                            type: 'POST',

                            data: {
                                id: $id_row,
                                person: '1'
                            }
                        }, //ajax

                        title: {
                            text: 'Τελευταία 5 απεσταλμένα SMS για τον/την ' + $surname + ' ' + $name,
                            button: true

                        },
                    }, //content

                    position: {
                        my: 'bottom center',
                        at: 'top center',
                        target: $('td:eq(2)', this),
                        viewport: $('#students'),
                        adjust: {
                            screen: true
                        }
                    },
                    show: {
                        event: event.type,
                        ready: true,solo: true
                    },
                    hide: {
                        fixed: true
                    }
                }, event);

            });




            $('#students').on('click', '.edit', function (e) {
                var table = $(this).closest('table').DataTable();

                if (!jQuery.isEmptyObject(table.row($(this).parents('tr')).data())) {
                    var data = table.row($(this).parents('tr')).data();
                } else {
                    var data = table.row((this)).data();
                }

                $('#dialog-edit').data('data', data).dialog('open');


            });


            jQuery(function ($) {
                $.datepicker.regional['el'] = {
                    clearText: 'Σβήσιμο',
                    clearStatus: 'Σβήσιμο της επιλεγμένης ημερομηνίας',
                    closeText: 'Κλείσιμο',
                    closeStatus: 'Κλείσιμο χωρίς αλλαγή',
                    prevText: 'Προηγούμενος',
                    prevStatus: 'Επισκόπηση προηγούμενου μήνα',
                    prevBigText: '&#x3c;&#x3c;',
                    prevBigStatus: '',
                    nextText: 'Επόμενος',
                    nextStatus: 'Επισκόπηση επόμενου μήνα',
                    nextBigText: '&#x3e;&#x3e;',
                    nextBigStatus: '',
                    currentText: 'Τρέχων Μήνας',
                    currentStatus: 'Επισκόπηση τρέχοντος μήνα',
                    monthNames: ['Ιανουάριος', 'Φεβρουάριος', 'Μάρτιος', 'Απρίλιος', 'Μάιος', 'Ιούνιος',
                        'Ιούλιος', 'Αύγουστος', 'Σεπτέμβριος', 'Οκτώβριος', 'Νοέμβριος', 'Δεκέμβριος'
                    ],
                    monthNamesShort: ['Ιαν', 'Φεβ', 'Μαρ', 'Απρ', 'Μαι', 'Ιουν',
                        'Ιουλ', 'Αυγ', 'Σεπ', 'Οκτ', 'Νοε', 'Δεκ'
                    ],
                    monthStatus: 'Επισκόπηση άλλου μήνα',
                    yearStatus: 'Επισκόπηση άλλου έτους',
                    weekHeader: 'Εβδ',
                    weekStatus: '',
                    dayNames: ['Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο'],
                    dayNamesShort: ['Κυρ', 'Δευ', 'Τρι', 'Τετ', 'Πεμ', 'Παρ', 'Σαβ'],
                    dayNamesMin: ['Κυ', 'Δε', 'Τρ', 'Τε', 'Πε', 'Πα', 'Σα'],
                    dayStatus: 'Ανάθεση ως πρώτη μέρα της εβδομάδος: DD',
                    dateStatus: 'Επιλογή DD d MM',
                    dateFormat: 'dd-mm-yy',
                    firstDay: 1,
                    initStatus: 'Επιλέξτε μια ημερομηνία',
                    isRTL: false
                };
                $.datepicker.setDefaults($.datepicker.regional['el']);
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
                    MotherFirstName: {
                        required: true,
                        minlength: 3
                    },
                    BirthDate: {},
                    Telephone1: {
                        required: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    LevelName: {
                        required: true,
                        maxlength: 1
                    },
                },
                messages: {
                    FirstName: "Παρακαλώ εισάγετε το όνομά σας",
                    LastName: "Παρακαλώ εισάγετε το επίθετό σας",
                    FatherFirstName: "Παρακαλώ εισάγετε το πατρώνυμο σας",
                    MotherFirstName: "Παρακαλώ εισάγετε το μητρώνυμο σας",
                    Telephone1: "Παρακαλώ εισάγετε το κινητό τηλεφωνό σας",
                    LevelName: "Παρακαλώ εισάγετε την ταξη του μαθητή",
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
                    MotherFirstName: {
                        required: true,
                        minlength: 3
                    },
                    BirthDate: {},
                    Telephone1: {
                        required: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    LevelName: {
                        required: true,
                        maxlength: 1
                    },
                },
                messages: {
                    FirstName: "Παρακαλώ εισάγετε το ονομά σας",
                    LastName: "Παρακαλώ εισάγετε το επιθετό σας",
                    FatherFirstName: "Παρακαλώ εισάγετε το πατρώνυμο σας",
                    MotherFirstName: "Παρακαλώ εισάγετε το μητρώνυμο σας",
                    BirthDate: "Παρακαλώ εισάγετε την ημερομηνια γεννησης (μμ/ηη/εεεε) σας",
                    Telephone1: "Παρακαλώ εισάγετε το τηλεφωνό σας",
                    LevelName: "Παρακαλώ εισάγετε την ταξη σας",
                }
            });

            $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
                var checked = $('.filter').is(':checked');

                if (checked && aData[7] == 3) {
                    return true;
                }

                return false;
            });
            var oTable = $('#students').dataTable();
            $('.filter').on("click", function (e) {

                oTable.fnDraw();
            });



            $('#add_student').click(function () {
                $('#dialog-add').dialog('open');
                return false;

            });

        });
        $(function () {

            $("#BirthDateadd").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: "-100:+0"
            });
            $("#BirthDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: "-100:+0"
            });
        });
    </script>
</head>

<body>


    <?php if ($_SESSION["Access_level"]==='moderator')
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

    <h3>
    Διαχείριση εγγραφών μαθητών 
    </h3>

    <br>
    <span style='background-color:yellow'><em>Οδηγίες:</em></span>
    <strong> Με την χρήση των κουμπιών <span class='glyphicon glyphicon-edit'></span> και <span class='glyphicon glyphicon-trash'></span>
 μπορείτε να Επεξεργαστείτε ή να Διαγράψετε εγγραφές.</strong><br>
 <strong>Στις πρώτες 3 στήλες με το πέρασμα του ποντικιού απο πάνω τους εμφανίζεται tooltip με πληροφορίες σχετικές με τα sms του μαθητή
 </strong>
    <br>
    <br>

    <table id='students' class="table table-striped table-bordered" width="100%" cellspacing='0'>
        <thead>
            <tr>
                <th>id</th>
                <th>StudentId</th>
                <th>ΑΜ </th>
                <th>Επίθετο</th>
                <th>Όνομα</th>
                <th>Πατρώνυμο</th>
                <th>Μητρώνυμο</th>
                <th>Ημ. Γέννησης</th>
                <th>Τηλέφωνο</th>
                <th>Τάξη</th>
                <th data-b-sortable="false"></th>
                <th data-b-sortable="false"></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>StudentId</th>
                <th>ΑΜ </th>
                <th></th>
                <th>Όνομα</th>
                <th>Πατρώνυμο</th>
                <th>Μητρώνυμο</th>
                <th></th>
                <th></th>
                <th></th>

                <th data-b-sortable="false"></th>
                <th data-b-sortable="false"></th>
            </tr>
        </tfoot>


    </table>

    <div id="dialog-confirm" title="Επιβεβαίωση Διαγραφής?" style="display: none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> Είστε σίγουρος για την διαγραφή του μαθητή
            <span id="tableVal"></span>
        </p>
    </div>

    <div id="dialog-edit" title="Ενημέρωση στοιχείων μαθητή" style="display: none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
        </p>

        <form id="modalformedit" action="#">

            <p>
                <label for="RegistrationNumber">Αριθμός Μητρώου: </label>
                <input type="text" name="RegistrationNumber" id="RegistrationNumber" readonly="readonly" style="background-color:cyan" />
            </p>

            <p>
                <label for="LastName">Επίθετο: </label>
                <input type="text" name="LastName" id="LastName" size="35" />
            </p>
            <p>
                <label for="FirstName">Όνομα: </label>
                <input type="text" name="FirstName" id="FirstName" size="35" />
            </p>
            <p>
                <label for="FatherFirstName">Πατρώνυμο: </label>
                <input type="text" name="FatherFirstName" id="FatherFirstName" size="35" />
            </p>
            <p>
                <label for="MotherFirstName">Μητρώνυμο: </label>
                <input type="text" name="MotherFirstName" id="MotherFirstName" size="35" />
            </p>

            <p>
                <label for="BirthDate">Ημ. Γέννησης: </label>
                <input type="text" name="BirthDate" id="BirthDate" />
            </p>
            <p>
                <label for="Telephone1">Τηλέφωνο: </label>
                <input type="text" pattern="\d*" name="Telephone1" id="Telephone1" size="35" />
            </p>
            <p>
                <label for="LevelName">Τάξη: </label>
                <input type="text" name="LevelName" id="LevelName" size="5" />
            </p>

        </form>


    </div>

  
    <button type="button" class="btn btn-success" id='add_student'><span class='glyphicon glyphicon-plus'>&nbsp;</span>Προσθήκη εγγραφής</button>
   
    <div id="dialog-add" title="Προσθήκη στοιχείων μαθητή" style="display: none;">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
        </p>

        <form id="modalformadd">

            <p>
                <label for="RegistrationNumberadd">Αριθμός Μητρώου: </label>
                <input type="text" name="RegistrationNumber" id="RegistrationNumberadd" readonly="readonly" style="background-color:#FF4800" value="Θα δοθεί αυτόματα" /> </p>

            <p>
                <label for="LastNameadd">Επίθετο: </label>
                <input type="text" name="LastName" id="LastNameadd" size="35" />
            </p>
            <p>
                <label for="FirstNameadd">Όνομα : </label>
                <input type="text" name="FirstName" id="FirstNameadd" size="35" />
            </p>
            <p>
                <label for="FatherFirstNameadd">Πατρώνυμο: </label>
                <input type="text" name="FatherFirstName" id="FatherFirstNameadd" size="35" />
            </p>
            <p>
                <label for="MotherFirstNameadd">Μητρώνυμο: </label>
                <input type="text" name="MotherFirstName" id="MotherFirstNameadd" size="35" />
            </p>

            <p>
                <label for="BirthDateadd">Ημ. Γέννησης: </label>
                <input type="text" name="BirthDate" id="BirthDateadd" />
            </p>
            <p>
                <label for="Telephone1add">Τηλέφωνο: </label>
                <input type="text" pattern="\d*" name="Telephone1" id="Telephone1add" size="35" />
            </p>
            <p>
                <label for="LevelNameadd">Τάξη: </label>
                <input type="text" name="LevelName" id="LevelNameadd" size="5" />
            </p>

        </form>


    </div>
</body>

</html>