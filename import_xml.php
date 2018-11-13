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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
		.text-on-pannel {
			background: #fff none repeat scroll 0 0;
			height: auto;
			margin-left: 20px;
			padding: 3px 5px;
			position: absolute;
			margin-top: -47px;
			border: 1px solid #337ab7;
			border-radius: 8px;
		}
		
		.panel {
			/* for text on pannel */
			margin-top: 27px !important;
		}
		
		.panel-body {
			padding-top: 10px !important;
		}
		
		.contentcsv {
  background-color: coral; 
  text-align:justify ; 
}
.contentcsvutf8 {
  background-color: lightskyblue;text-align:justify ; 
}
	
	</style>

	<script type="text/javascript">
		function ajaxSuccess() {
			
			$( "#addresult" ).html( '' ).append( this.responseText );
			$( "#buttonform" ).removeAttr( 'disabled' );
			$( "#deletedb" ).removeAttr( 'disabled' );
		
		}

		function AJAXSubmit( oFormElement ) {
			$( "#addresult" ).css( 'display', 'block' );
			$( "#buttonform" ).attr( 'disabled', 'disabled' );
			$( "#deletedb" ).attr( 'disabled', 'disabled' );
			$( "#addresult" ).html( 'Παρακαλώ περιμένετε <img src="ajax-loader.gif">' );
			if ( !oFormElement.action ) {
				return;

			}
			var oReq = new XMLHttpRequest();
			oReq.onload = ajaxSuccess;
			if ( oFormElement.method.toLowerCase() === "post" ) {
				oReq.open( "post", oFormElement.action );
				oReq.send( new FormData( oFormElement ) );
			} else {
				var oField, sFieldType, nFile, sSearch = "";
				for ( var nItem = 0; nItem < oFormElement.elements.length; nItem++ ) {
					oField = oFormElement.elements[ nItem ];
					if ( !oField.hasAttribute( "name" ) ) {
						continue;
					}
					sFieldType = oField.nodeName.toUpperCase() === "INPUT" ?
						oField.getAttribute( "type" ).toUpperCase() : "TEXT";
					if ( sFieldType === "FILE" ) {
						for ( nFile = 0; nFile < oField.files.length; sSearch += "&" + escape( oField.name ) + "=" + escape( oField.files[ nFile++ ].name ) );
					} else if ( ( sFieldType !== "RADIO" && sFieldType !== "CHECKBOX" ) || oField.checked ) {
						sSearch += "&" + escape( oField.name ) + "=" + escape( oField.value );
					}
				}
				oReq.open( "get", oFormElement.action.replace( /(?:\?.*)?$/, sSearch.replace( /^&/, "?" ) ), true );
				oReq.send( null );
			}
		}

		$( document ).ready( function () {
			var files;
			
			
			
			$( "#dialog-confirm" ).dialog( {
				autoOpen: false,
				resizable: false,
				height: "auto",
				width: 400,
				modal: true,
				buttons: {
					"Διαγραφή": function () {
						$( "#buttonform" ).attr( 'disabled', 'disabled' );
						$( "#deletedb" ).attr( 'disabled', 'disabled' );
						$( "#delresult" ).html( 'Παρακαλώ περιμένετε <img src="ajax-loader.gif">' );
								
						$.ajax( {
							url: "deletedb.php",
							cache: false,
							success: function ( result ) {
								$( "#buttonform" ).removeAttr( 'disabled' );
			                    $( "#deletedb" ).removeAttr( 'disabled' );
								$( "#delresult" ).html( '' ).css( 'display', 'block' );
								$( "#delresult" ).append( result );
							},
							error: function () {
								alert( 'Error occurs!' );
							}
						} );

						$( this ).dialog( "close" );
					},
					Cancel: function () {
						$( this ).dialog( "close" );
					}
				}
			} );


			$( '#deletedb' ).click( function () {
				$( '#dialog-confirm' ).dialog( 'open' );
				return false;
			} );

		} );
	</script>
</head>

<body>
	
<div id="helpimport" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Σχετικά με τη σελίδα διαχείρισης αρχείων δεδομένων μαθητών από το MySchool</h4>
      </div>
      <div class="modal-body" >
        <p style="font-size:13px;">	<div class="contentcsvutf8">
			<b>Επιλογή Μεταφόρτωση Αρχείων:</b> Το MySchool επιτρέπει την εξαγωγή δεδομένων 
			των μαθητών σε μορφή XML. To αρχείο XML δεν περιλαμβάνει το σύνολο των πεδίων
			που υπάρχουν στις φόρμες διαχείρισης του MySchool. Το πεδίο που χρησιμοποιήθηκε 
			για την εισαγωγή του κινητού τηλεφώνου είναι 
			αυτό του σταθερού τηλεφώνου του Μαθητή (Telephone1) στο οποίο πρέπει είτε
			να τοποθετείται το κινητό τηλέφωνο του κηδεμόνα είτε να γίνεται εκ των υστέρων 
			edit της εγγραφής μετά την εισαγωγή στην βάση δεδομένων από την επιλογή "Διαχείριση Εγγραφών - 
			Διαχείριση Μαθητών". Έχοντας τα προηγούμενα υπόψη μας μπορούμε να επιλέξουμε το αρχείο XML που
			εξάγαγαμε από το MySchool.
			<br>
			<b> Κουμπί Εισαγωγή Αρχείου XML:</b> Με το πάτημα του κουμπιού <em> Εισαγωγή Αρχείου XML </em>
			εισάγονται στη βάση δεδομένων και στον πίνακα students, οι εγγραφές που υπάρχουν στο αρχείο XML. 
			Πριν την εισαγωγή και αν ο πίνακας students δεν υπάρχει αυτός δημιουργείται. 
			<br><b>Ταυτόχρονα δημιουργείται και ο πίνακας της MySQL με το όνομα <em>smsmessages</em> που 
			θα φιλοξενεί τα στοιχεία των sms που αποστέλλονται μέσω της εφαρμογής αν δεν υπάρχει ήδη.</b>
			<br> Εάν οι πίνακες ήδη υπάρχουν δεν θα αλλοιωθούν ενώ εάν επιλέξω για 
			μεταφόρτωση διαφορετικά xml αρχεία, τότε θα εισάγονται μόνο εγγραφές που 
			έχουν διαφορετικό StudentId που αποτελεί παράμετρο του συστήματος MySchool με μοναδικά 
			χαρακτηριστικά.

        </p>
      </div><div class="contentcsv">
<p style="font-size:13px;">
			<b>Επιλογή Διαγραφή του πίνακα Μαθητών:</b>ΠΡΟΣΟΧΗ: Με το πάτημα του κουμπιού και αφού 
			ζητηθεί επιβεβαίωση θα ΔΙΑΓΡΑΦΕΙ ο πίνακας των μαθητών. Δεν υπάρχει τρόπος 
			επαναφοράς. Σε μια τέτοια περίπτωση μόνο νέα εισαγωγή xml αρχείου θα δημιουργήσει
			εγγραφές.</p></div>
      </div>
     </div>
  </div>

</div>

	<div id="dialog-confirm" title="Επιβεβαίωση Διαγραφής?">
		<span id="loadingUI"></span>
		<p id="insidedialog">

			<span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> Πρόκειτε να διαγράψετε τον πίνακα των μαθητών. Είστε σίγουρος?

		</p>
	</div>
	<?php
include 'navigation_admin.php';
?>
	<br>
	<br>
	<br>

	<div class="container">
		<h2 class="text-center bg-info">Σελίδα διαχείρισης αρχείων δεδομένων μαθητών από το myschool</h2>
	</div>



	<div class="container">
		<h3 class="text-center bg-info">Εισαγωγή αρχείου XML myschool</h3> <a href="#" data-toggle="modal" data-target="#helpimport" id="instructions">Οδηγίες</a>
	</div>
	<br><br>


	<form action="import_xml_to_mysql.php" method="post" enctype="multipart/form-data" onsubmit="AJAXSubmit(this); return false;">
		<div class="container">
			<div class="panel panel-primary"><br>
				<div class="panel-body">
					<h3 class="text-on-pannel text-primary"><strong> Μεταφόρτωση αρχείων </strong></h3>

					<p style="margin-top: 10px ; margin-left: 20px">
						Επιλέξτε το XML αρχείο για μεταφόρτωση:
						<input type="file" id="xmlfile" name="xmlfile" accept=".xml" class="btn-default">
					</p>
					<p style="margin-top: 10px ; margin-left: 20px">

						<button class="btn-primary btn-lg" id="buttonform" type='submit'>
							Εισαγωγή αρχείου XML
						</button>
						<span id='addresult' style="margin-left:10px"></span>
					</p>
				</div>
			</div>
		</div>
	</form>
	<br>
	<div class="container">
		<div class="panel panel-primary"><br>
			<div class="panel-body">
				<h3 class="text-on-pannel text-primary"><strong>Διαγραφή πίνακα μαθητών </strong></h3>

				<p style="margin-top: 10px ; margin-left: 20px"> 
					<button class="btn-danger btn-lg" id="deletedb">Διαγραφή του πίνακα μαθητών</button>
					<span id='delresult' style="margin-left:10px"></span> </p>
			</div>
		</div>
	</div>
</body>

</html>