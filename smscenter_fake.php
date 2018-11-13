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

$key = 'xxxxxxxxxxx';
$message = $_POST['message'];
$to = $_POST['to'];
$type = 'json';
$originator = 'EPALRODO';

include 'parameters.php';
$mysqli = new mysqli($servername, $username, $password, $dbname);
$mysqli->set_charset('utf8');
	// Check connection
if ($mysqli->connect_error) {
	die("Το SMS δεν σταλθηκε. Παρακαλώ επικοινωνήστε με το διαχειριστή   : " . $mysqli->connect_error);
}



function sendSMS($key, $to, $message, $originator, $type)
{
	$myObj = new stdClass();
	$myObj->status = "1";
	$myObj->id = "39116149";
	$myObj->balance = "5";
	$myObj->mcc = "202";
	$myObj->mnc = "01";
	$myObj->remarks = "Success: Accepted for delivery";
	$myObj->error = "0";
	$response = json_encode($myObj);
	return $response;
}

$Username = $_SESSION['Username'];
$sql = "Select quota from users  where Username='$Username' ";


if ($result = $mysqli->query($sql)) {
	$row_user = $result->fetch_assoc();
	if ($row_user["quota"] > 0) {
		$response = sendSMS($key, $to, $message, $originator, $type);
		$response_decoded = json_decode($response, true);

		$Username = $_SESSION['Username'];
		$sql = "UPDATE  users SET quota=quota-1  where Username='$Username' ";

		if ($mysqli->query($sql) === true) {
			$response_decoded["sql_result"] = "1";

		} else {
			$response_decoded["sql_result"] = "0";
		}

		$mysqli->close();
		$response = json_encode($response_decoded);
		echo $response;
		exit;

	} else {


		$myObj = new stdClass();
		$myObj->status = "0";
		$myObj->id = "00000000";
		$myObj->balance = "0";
		$myObj->mcc = "0000";
		$myObj->mnc = "00";
		$myObj->remarks = "Δεν υπάρχει επαρκές υπόλοιπο SMS. Παρακαλώ επικοινωνήστε με τον διαχειριστή";
		$myObj->error = "0";
		$response = json_encode($myObj);
		$mysqli->close();
		echo $response;
		exit();
	}

} else {

	die("Το SMS δεν σταλθηκε. Παρακαλώ επικοινωνήστε με το διαχειριστή sms_center.php error");
	exit;

}

$mysqli->close();

?>