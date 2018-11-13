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
if ( $_SESSION['Access_level']=='user' || $_SESSION['Access_level']=='moderator' )
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
							url: "deletecsv.php",
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

<body bgcolor='#D2B48C'>
	<div id="dialog-confirm" title="Επιβεβαίωση Διαγραφής?">
		<span id="loadingUI"></span>
		<p id="insidedialog">
			<span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> Πρόκειτε να διαγράψετε τον πίνακα με τα στοιχεία των εκπαιδευτικών. Είστε σίγουρος/ή?

		</p>
	</div>
	<?php
	include 'navigation_admin.php';
	?>
	<br>
	<br>


	<div class="container">
		<h2 class="text-center bg-info">Σελίδα διαχείρισης του αρχείου Εκπαιδευτικών από το myschool</h2>
	</div>

	<div class="container">
		<h3 class="text-center bg-info">Εισαγωγή αρχείου CSV myschool</h3><a href="#" data-toggle="modal" data-target="#helpimport" id="instructions">Οδηγίες</a>
	
	</div>
	<br><br>

	<div id="helpimport" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Σχετικά με τη σελίδα διαχείρισης εγγραφών των εκπαιδευτικών από το MySchool</h4>
      </div>
      <div class="modal-body" >
        <p style="font-size:13px;">
			
		<div class="contentcsvutf8">
		   <b>Επιλογή Μεταφόρτωση Αρχείων:</b> Το MySchool επιτρέπει την εξαγωγή των στοιχείων 
			των εκπαιδευτικών σε μορφή xls(x). Το αρχείο xls ως proprietary τύπος αρχείου 
			της Microsoft δεν είναι απευθείας διαχειρίσιμο από τις 
			ρουτίνες της PHP. Ως εκ τούτου, απαιτείται η μετατροπή του σε μορφή CSV που αποτελεί
			ένα προγραμματιστικα εύκολα διαχειρίσιμο text format. 
			Η μετατροπή του αρχείου .XLS, με την χρήση του EXCEL, σε .CSV, 
			δεν φροντίζει για την απευθείας μορφοποίηση των χαρακτήρων σε utf8 αλλά απαιτεί 
			συγκεκριμένες ρυθμίσεις κατά την αποθήκευση.
			<br><b>
			Για τη μεταφόρτωση του αρχείου CSV ακολουθούμε τα εξής βήματα:<br><br>
			Επιλογή αρχείου:</b> Επιλέγουμε το αρχείο CSV που θέλουμε να μεταφορτώσουμε.
			<b>ΠΡΟΣΟΧΗ: Ο έλεγχος εγκυρότητας του CSV αρχείου αφορά την ύπαρξη 12 στηλών 
				όπως αυτές δημιουργούνται στο αρχείο xls του MySchool.
					Θα πρέπει να είστε προσεκτικοί καθώς και άλλα text αρχεία μπορεί να 
				τύχουν της σπάνιας περίπτωσης να γίνει δυνατό να μεταφορτωθούν
				 (από τον χειριστή) εισάγοντας λάθος εγγραφές
				 στη βάση δεδομένων.</b></div>
			<br><div class="contentcsv">
			<b> Εισαγωγή αρχείου csv:</b> Με το πάτημα του κουμπιού <em> Εισαγωγή αρχείου CSV </em>
			αρχικά δημιουργείται ο πίνακας της MySQL με το όνομα <em>teachers</em> που 
			θα φιλοξενεί τα στοιχεία των εκπαιδευτικών από το MySchool.</b><br> Στην συνέχεια με 
			την χρήση δυνατοτήτων της MySQL γίνεται μαζική εισαγωγή των εγγραφών στον πίνακα από το
			αρχείο CSV.	<br><b>Προσοχή: To αρχείο CSV θα πρέπει να είναι ήδη σε μορφή UTF8 και
				όχι στην κωδικοποιήση ελληνικών των windows που συνήθως έχουν τα EXCEL αρχεία.</b><br>
			<br><b>Παρατήρηση: Δεν δημιουργείται ο πίνακας της MySQL με το όνομα <em>smsmessages</em> που 
			θα φιλοξενεί τα στοιχεία των sms που αποστέλλονται μέσω της εφαρμογής. Ο πίνακας αυτός δημιουργείται 
		    με την εισαγωγή των μαθητών μέσω XML ή από την επιλογή του μενού SMS Admin Board -> Διαχείριση πίνακα SMS</b>
			<br>Παρατηρήσεις: 1) Εάν οι πίνακες ήδη υπάρχουν δεν θα αλλοιωθούν	2) Η παράμετρος ΑΦΜ αποτελεί μοναδικό χαρακτηριστικό και είναι απαραίτητη η ύπαρξή του. 
	</div>
			<br><div class="contentcsvutf8">
			<b> Εισαγωγή αρχείου CSV με αυτόματη μετατροπή σε UTF8</b>
			 Με το πάτημα του κουμπιού <em> Εισαγωγή αρχείου CSV με αυτόματη μετατροπή σε UTF8</em>
			 αρχικά δημιουργείται ο πίνακας της MySQL με το όνομα <em>teachers</em> που 
			 θα φιλοξενεί τα στοιχεία των εκπαιδευτικών από το MySchool.</b><br> Στην συνέχεια κωδικοποιήση
			 μετατρέπεται μέσω αυτόματου εντοπισμού σε utf-8 και στην συνέχεια γίνεται εισαγωγή στην βάση 
			 δεδομένων. Επειδή υπάρχει η περίπτωση ο αυτόματος εντοπισμός κωδικοποίησης 
			 /*mb_detect_encoding(Αρχείο CSV, mb_detect_order(), true) */ μπορεί να μην λειτουργήσει
			 υπάρχει η περίπτωση να χρειάζεται η μετατροπή σε utf-8 μέσω τρίτου εργαλείου (π.χ. notepad++)
			<br><b>Παρατήρηση: Όμοια και εδώ δεν δημιουργείται ο πίνακας της mysql με το όνομα <em>smsmessages</em>. 
			<br>Παρατηρήσεις: 1) Εάν οι πίνακες ήδη υπάρχουν δεν θα αλλοιωθούν	2) Η παράμετρος ΑΦΜ αποτελεί μοναδικό χαρακτηριστικό και είναι απαραίτητη η ύπαρξή του.
</div>
        </p>
<p>
<p style="font-size:13px;">
			<b>Επιλογή Διαγραφή του πίνακα teachers:</b><br>ΠΡΟΣΟΧΗ: Με το πάτημα του κουμπιού και αφού 
			ζητηθεί επιβεβαίωση θα ΔΙΑΓΡΑΦΕΙ ο πίνακας των εκπαιδευτικών. Δεν υπάρχει τρόπος 
			επαναφοράς. Σε μια τέτοια περίπτωση μόνο νέα εισαγωγή του CSV αρχείου θα δημιουργήσει
			εγγραφές</p>
      </div>
    
    </div>

  </div>

</div>

	<form  method="post" enctype="multipart/form-data" onsubmit="AJAXSubmit(this); return false;">
		<div class="container">
			<div class="panel panel-primary"><br>
				<div class="panel-body">
					<h3 class="text-on-pannel text-primary"><strong> Μεταφόρτωση αρχείων </strong></h3>

					<p style="margin-top: 10px ; margin-left: 20px">
						Επιλέξτε το CSV αρχείο για μεταφόρτωση:
						<input type="file" id="csvfile" name="csvfile" accept=".csv" class="btn-default">
					</p>
					<p style="margin-top: 10px ; margin-left: 20px">

						<button class="btn-primary btn-lg" id="buttonform" type='submit' onclick='this.form.action="import_to_mysql_csv.php?method=1";'>
							Εισαγωγή αρχείου CSV
						</button>
						<button class="btn-primary btn-lg" id="buttonformutf8" type='submit' onclick='this.form.action="import_to_mysql_csv.php?method=2"'>
							Εισαγωγή αρχείου CSV με αυτόματη μετατροπή σε UTF8
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
				<h3 class="text-on-pannel text-primary"><strong>Διαγραφή πίνακα εκπαιδευτικών </strong></h3>
				<p style="margin-top: 10px ; margin-left: 20px">
					<button class="btn-danger btn-lg" id="deletedb">Διαγραφή του πίνακα teachers</button>
					<span id='delresult' style="margin-left:10px"></span> </p>
			</div>
		</div>
	</div>
	<br>
</body>
</html>