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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	
<style type="text/css">
strong {
    color:red;
}
		
	</style>	
	<title>ΕΠΑΛ Ροδόπολης - SMS Center</title>
	<script type="text/javascript">

$(document).ready(function(){

$( "#addresult" ).html( 'Παρακαλώ περιμένετε <img src="ajax-loader.gif">' );
$.ajax( {
							
							url: '<?php if ($_SESSION['Access_level'] == 'admin' || $_SESSION['Access_level'] == 'moderator') {
													echo "admin_statistics.php";
												} else {
													echo "user_statistics.php";

												}
												?>',
							cache: false,
							dataType:"json",
							success: function ( data ) {
								$( "#addresult" ).html( '' ); 
													
							$( "#addresult" ).append('<h3><strong>&nbsp;Στοιχεία χρήστη:<?php echo $_SESSION['Username']; ?></strong></h3> <br>'); 
							$.each(data, function(key, value) {
								
								if (key=='status') {key='Επιτυχής σύνδεση στο SMS Center:';}
								else 
								if (key=='balance') {key='<strong >Συνολικό υπόλοιπο SMS παρόχου:</strong>';} 
								else
								if (key=='remarks') {key='Παρατηρήσεις κέντρου SMS:';} 
								else
								if (key=='quota') {key='<strong><abbr>Υπόλοιπο SMS χρήστη:</abbr></strong>';} 
								else
								if (key=='Lastname') {key='Επίθετο:';}
								else 
								if (key=='Firstname') {key='Όνομα:';} 
								else
								if (key=='Access_level') {key='Επίπεδο διαβάθμισης:';} 
								else
								if (key=='quota_users') {key='<strong >Συνολικά μυνήματα επιτρεπόμενα σε users:</strong >';}
								else 
								if (key=='quota_moderators') {key='<strong >Συνολικά μυνήματα επιτρεπόμενα σε moderators:</strong>';} 
								else
								{key=key+':';} 
								$( "#addresult" ).append('<h5>&nbsp;&nbsp;'+'&nbsp;&nbsp;'+key+' ' + value+'</h5>' ); });
							},
							error: function () {
								alert( 'Error occurs!' );
							}
						} );
						});
	
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

	session_unset();
	session_destroy();
	header('Location: index.php');
	exit();
}

?>
<?php 
echo '<div style="display: inline-block; background-color:#7DCEA0; font:13pt Georgia,Times New Roman, Times, serif;">';
echo 'Εχετε συνδεθεί ως <strong>' . $_SESSION['Username'] . '</strong> με δικαιώματα πρόσβασης <strong>' . $_SESSION['Access_level'] . '</strong>';
echo '</div> <br\>'
?>
<br><br>
<h3><em>Καλώς ήρθατε στο σύστημα διαχείρισης και αποστολής SMS σε μαθητές και
εκπαιδευτικούς του ΕΠΑΛ Ροδόπολης.<br>
Παρακαλούμε συμμορφωθείτε με τους παρακάτω όρους καλής λειτουργίας:</em></h3>
<div style="display: inline-block; background-color:lightgray; font:13pt Georgia,Times New Roman, Times, serif;">
<ul>
<li>Μην χρησιμοποιείτε το σύστημα από κοινόχρηστους υπολογιστές καθώς μπορεί να διαφύγουν οι
κωδικοί ασφαλείας.</li>
<li>Μην μοιράζεστε τους κωδικούς πρόσβασης με τρίτους.</li>
<li>Το σύνολο των μηνυμάτων καταγράφεται σε log και φυλάσσεται σε βάση δεδομένων.</li>
<li>To σύστημα χρησιμοποιεί το sms gateway της εταιρίας TERN (www.easysms.gr) όπου και
αυτοί με την σειρά τους καταγράφουν τα μηνύματα. </li>
<li>Να είστε ΠΑΝΤΑ ΣΙΓΟΥΡΟΙ ότι έχετε την συναίνεση του παραλήπτη για αποστολή sms.</li>
<li>Το σύστημα είναι πλήρως προσβάσιμο από κινητές συσκευές</li>
</ul></div>
<br><br>
<div id='addresult' style="margin-left:10px;display: inline-block; background-color:lightgray; "></div>
<br>
<div style="margin-top:150px;display: inline-block;background-color:lightblue; ">
Δημιουργία - διαχείριση - συντήρηση: Κ.Χερτούρας - chertour at sch.gr - <a href="http://www.github.com/chertouras">github.com/chertouras</a>

</div>
</body>
<html>