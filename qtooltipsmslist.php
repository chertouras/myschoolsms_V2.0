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
// Check connection
if ($mysqli->connect_error) {
    die("Η σύνδεση απέτυχε: " . $mysqli->connect_error);
}





if (isset($_POST['person'])) {
    $person = ($_POST['person']);


    if (isset($_POST['id'])) {
        $id = ($_POST['id']);

        $query = "(SELECT * from smsmessages WHERE CAST(person AS UNSIGNED)=$person and CAST(RegistrationNumber AS UNSIGNED)=$id ORDER BY 
    timeSent DESC LIMIT 5) ORDER BY timeSent";


        if ($result = $mysqli->query($query)) {


            if (($result->num_rows) == 0) {
                echo "Δεν βρέθηκαν καταγεγραμένα SMS σε αυτό τον αριθμό μητρώου";
                exit();
            }

            echo '<table  border="1" cellpadding="13">
        <tr>
          <td>Α/Α</td>
         <td>Επίθετο</td>
          <td>Remarks</td>
          <td>TimeStamp</td>
          <td>Μήνυμα</td>
        </tr>';

            while ($row = $result->fetch_row()) {

                echo '<tr><td>';
                echo $row[1];
                echo '</td> <td>';
                echo $row[4];
                echo '</td> <td>';
                echo substr($row[7], 0, 7);
                echo '</td> <td>';
                echo $row[10];
                echo '</td> <td>';
                echo $row[11];

                echo '</td></tr>';

            }
            echo '</table>';
            /* free result set */
            $result->close();
        }


        $mysqli->close();

    } else {
        echo 'error';
    }
} else {
    echo 'error';
}

?>