<?php 
  if(!isset($_SESSION)) 
  { 
      session_start(); 
 } 

if ($_SESSION['Access_level'] !='user' )
{
  session_unset();
  session_destroy();
  header('Location: index.php');
    exit();
}

$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
   
    session_unset();
    session_destroy();
   
}

if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn']!=1){
    header('Location: index.php');
    exit();
}
?>
<nav class="navbar navbar-default navbar-static-top" style="background-color: #e3f2fd;">
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
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SMS Admin Board<span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="sms_list_student.php">Αποστολή SMS σε Μαθητές</a></li>
            <li><a href="sms_list_teacher.php">Αποστολή SMS σε Εκπαιδευτικούς</a></li>
             <li><a href="sms_standalone.php">Αποστολή Μεμονωμένου/νων SMS </a></li>
                     
          </ul>
        </li> 
		   		  
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ημερολόγιο SMS<span class="caret"></span></a>
          <ul class="dropdown-menu">
         
          
             <li><a href="sms_log_per_user.php">Ημερολόγιο SMS χρήστη </a></li>
            
          </ul>
        </li> 

 <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Διαχείριση Χρήστη<span class="caret"></span></a>
          <ul class="dropdown-menu">
         
          
             <li><a href="user_details.php">Στοιχεία Χρήστη </a></li>
             <li><a href="template_list.php">Πρότυπα SMS Χρήστη </a></li>
          </ul>
        </li> 



        <li><a href="logout.php"> <span style="color:red">Έξοδος<span></a></li>
        <li><span style="color:green">Έχετε συνδεθεί ως <?php echo $_SESSION['Username'] ?><span></li >

      </ul>
     
     
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<style type="text/css">
body {
    background-color: #f5f5f5;
    /* padding-top: 110px;  */
}

</style>