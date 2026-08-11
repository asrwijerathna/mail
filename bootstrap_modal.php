<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Mail Management System</title>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />


<!--Date time picker-->
<!--<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">-->




<!-- Custom styles for this template -->
<!--<link href="css/logo-nav.css" rel="stylesheet">    
<link href="css/mail_local.css" rel="stylesheet" type="text/css" />-->


</head>

<body>
 
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
            <a class="nav-link">Welcome
              <?php	
			  if($_SESSION["login_user"]==true){		  
			  echo " ".$_SESSION["login_user"];
			  } else{
			  header("location:login_form.php");
			  }
			  ?>
              </a>
            </li>
            
            
            <?php
				include ('dbconnect.php');
				if($_SESSION["login_user"]==true){	
				$u_name = $_SESSION["login_user"];
				$query = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
				$result = mysqli_query($conn, $query);
				
			    while($row=mysqli_fetch_assoc($result)){
				$acctype = $row['acc_type'];
				
				if($row['acc_type']=='A'){
				?>
					<li class="nav-item">
					  <a class="nav-link" href="index.php">මුල් පිටුව
						<span class="sr-only">(current)</span>
					  </a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="mail_view.php">ලියුම්</a>
					</li>
 					<li class="nav-item">
					  <a class="nav-link" href="mail_view_no_action.php">ක්‍රියාකර නොමැති</a>
					</li>                    
                    <li class="nav-item">
					  <a class="nav-link" href="mail_view_pending.php">ක්‍රියාකාරී</a>
					</li>                   
                    <li class="nav-item">
					  <a class="nav-link" href="mail_view_closed.php">වැඩ අවසන්</a>
					</li>                    
					<li class="nav-item">
					  <a class="nav-link" href="export_excel.php">වාර්ථා</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="user_create.php">පරිශීලක</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="search.php">සෙවීම්</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="logout.php">Logout</a>
					</li>
                    <?php
				}elseif($row['acc_type']=='N'){
				?>
					<li class="nav-item">
					  <a class="nav-link" href="index.php">මුල් පිටුව
						<span class="sr-only">(current)</span>
					  </a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="mail_view.php">ලියුම්</a>
					</li>
 					<li class="nav-item">
					  <a class="nav-link" href="mail_view_no_action.php">ක්‍රියාකර නොමැති</a>
					</li> 
                    <li class="nav-item">
					  <a class="nav-link" href="mail_view_pending.php">ක්‍රියාකාරී</a>
					</li>
                    <li class="nav-item">
					  <a class="nav-link" href="mail_view_closed.php">වැඩ අවසන්</a>
					</li>                       
					<li class="nav-item">
					  <a class="nav-link" href="search.php">සෙවීම්</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="logout.php">Logout</a>
					</li>
                    <?php
				}elseif($row['acc_type']=='H'){	
				?>
					<li class="nav-item">
					  <a class="nav-link" href="index.php">මුල් පිටුව
						<span class="sr-only">(current)</span>
					  </a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="mail_view.php">ලියුම්</a>
					</li>
 					<li class="nav-item">
					  <a class="nav-link" href="mail_view_no_action.php">ක්‍රියාකර නොමැති</a>
					</li>                     
                    <li class="nav-item">
					  <a class="nav-link" href="mail_view_pending.php">ක්‍රියාකාරී</a>
					</li>
                    <li class="nav-item">
					  <a class="nav-link" href="mail_view_closed.php">වැඩ අවසන්</a>
					</li>                       
					<li class="nav-item">
					  <a class="nav-link" href="search.php">සෙවීම්</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="logout.php">Logout</a>
					</li>                
				<?php
				}							
				}
				}
			?>
            
            
            

            
            
          </ul>
        </div>
      </div>
    </nav>





<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                        <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Header1</th>
                            <th>Header2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>data1</td>
                            <td>data2</td>
                        </tr>
                    </tbody>
                </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php 
include("includes/footer.php"); 
?>