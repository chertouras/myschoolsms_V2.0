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

$level = array("admin");
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
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet"/>
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
		
		.field_set {
			border-color: black;
			border-style: solid;
		}
	</style>

	<script type="text/javascript">
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
						$.ajax( {
							url: "deletedbsms.php",
							cache: false,
							success: function ( result ) {
								$( "#delresult" ).html( '' ).css( 'display', 'block' );
								$( "#delresult" ).append( result );//.fadeOut( 7000 );
							}

							,
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

			$( '#createdb' ).click( function () {

				$.ajax( {
					url: "sms_log_creation.php",
					cache: false,
					success: function ( result ) {
						$( "#createresult" ).html( '' ).css( 'display', 'block' );
						$( "#createresult" ).append( result );//.fadeOut( 7000 );
					}

					,
					error: function () {
						alert( 'Error occurs!' );
					}


				} );
			} );





		} );
	</script>
</head>

<body>

       <div id="dialog-confirm" title="Επιβεβαίωση Διαγραφής?">
		<span id="loadingUI"></span>
		<p id="insidedialog">
			<span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> Πρόκειτε να διαγράψετε τον πίνακα SMS. Είστε σίγουρος/ή?
		</p>
	</div>
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
	<br>
	<br>
	<div class="container">
		<h2 class="text-center bg-info">Σελίδα διαχείρισης αρχείου SMS</h2>
	</div>
	<br>
	<div class="container">
		<div class="panel panel-primary"><br>
			<div class="panel-body">
				<h3 class="text-on-pannel text-primary"><strong> Δημιουργία πίνακα εγγραφών SMS </strong></h3>

				<p style="margin-top: 10px ; margin-left: 20px">
					<button class="btn-primary btn-lg" id="createdb">Δημιουργία του πίνακα απεσταλμένων SMS</button>
					<span id='createresult' style="margin-left:10px"></span>
				</p>
			</div>
		</div>
	</div>
	<br><br>

	<div class="container">
		<div class="panel panel-primary"><br>
			<div class="panel-body">
				<h3 class="text-on-pannel text-primary"><strong> Διαγραφή πίνακα εγγραφών SMS </strong></h3>

				<p style="margin-top: 10px ; margin-left: 20px"> 
					<button class="btn-danger btn-lg" id="deletedb">Διαγραφή του πίνακα απεσταλμένων SMS</button>
					<span id='delresult' style="margin-left:10px"></span> </p>
			</div>
		</div>
	</div>
</body>
</html>