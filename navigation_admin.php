<?php 
if (!isset($_SESSION)) {
  session_start();
}
$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {

  session_unset();
  session_destroy();

}

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != 1) {
  header('Location: index.php');
  exit();
}


if ($_SESSION['Access_level'] != 'admin') {
  session_unset();
  session_destroy();
  header('Location: index.php');
  exit();

}

?>
<nav class="navbar navbar-default navbar-fixed-top" style="background-color: #e3f2fd;">
  <div class="container-fluid">
 
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"></a>
    </div>

    
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
       
        <li><a href="main.php">Αρχική</a></li>
    
		   <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Διαχείριση Αρχείων MySchool <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="import_xml.php">Διαχείριση αρχείου XML MySchool</a></li>
            <li><a href="import_csv.php">Διαχείριση αρχείου CSV MySchool (Εκπαιδευτικών)</a></li>
           
          </ul>
        </li>
		  
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Διαχείριση Χρηστών Εφαρμογής <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="insert_user.php">Προσθήκη Χρηστών </a></li>
            <li><a href="users_list.php">Διαχείριση Χρηστών</a></li>
           
          </ul>
        </li>






		   <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Εισαγωγή Στοιχείων <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="insert_student_standalone.php">Μαθητών</a></li>
            <li><a href="insert_teacher_standalone.php">Εκπαιδευτικών</a></li>
           
          </ul>
        </li> 
		  <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Διαχείριση Εγγραφών<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="students_list.php">Διαχείριση Μαθητών</a></li>
            <li><a href="teachers_list.php">Διαχείριση Εκπαιδευτικών</a></li>
           
          </ul>
        </li>
		  
		    
		     <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SMS Admin Board<span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="sms_log_admin.php">Διαχείριση πίνακα SMS</a></li>
            <li><a href="sms_list_student.php">Αποστολή SMS σε Μαθητές</a></li>
            <li><a href="sms_list_teacher.php">Αποστολή SMS σε Εκπαιδευτικούς</a></li>
             <li><a href="sms_standalone.php">Αποστολή Μεμονωμένου/νων SMS </a></li>
            <li><a href="sms_log_student.php">Στατιστικά/Διαχείριση SMS Μαθητών - PDF</a></li>
            <li><a href="sms_log_student_alt.php">Στατιστικά/Διαχείριση SMS Μαθητών - Νεα έκδοση </a></li>
             <li><a href="sms_log_teacher.php">Στατιστικά/Διαχείριση SMS Εκπαιδευτικών - PDF</a></li>
             <li><a href="sms_log_teacher_alt.php">Στατιστικά/Διαχείριση SMS Εκπαιδευτικών - Νεα έκδοση </a></li>
             <li><a href="sms_log_standalone.php">Στατιστικά/Διαχείριση Μεμονωμένων SMS </a></li>
             <li><a href="sms_log_user_alt.php"><span style="color:red;">Στατιστικά SMS / Username </span></a></li>
          </ul>
        </li> 
		   		  
        <li><a href="logout.php"> <span style="color:red">Έξοδος<span></a></li>
        <li> <span style="color:green">Έχετε συνδεθεί ως <?php echo $_SESSION['Username'] ?><span></li>

      </ul>
     
     
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<style type="text/css">
body {
    background-color: #f5f5f5;
    padding-top: 70px; 
}
</style>