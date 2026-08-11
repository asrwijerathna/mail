<?php
	session_start();
	//echo "test1";
    if (isset($_POST['submit']))
        {     
    include ('dbconnect.php');
    //session_start();

	$u_name = $_POST['u_name'];
	$u_pass = $_POST['u_pass'];
     
	

    //$query = mysqli_query("SELECT user_name FROM user_tbl WHERE user_name='$u_name' and password='$u_pass'");
	
	$query = "SELECT user_name, user_sec_no, user_s_no FROM user_tbl WHERE user_name='$u_name' and password='$u_pass'";
	$result = mysqli_query($conn, $query);
	
	//echo $result;
     if (mysqli_num_rows($result) != 0)
    {
	
	$_SESSION["login_user"]=$u_name;
	
	
	//$query = "SELECT user_sec_no,user_s_no FROM user_tbl WHERE user_name='$u_name' and password='$u_pass'";
	//$result = mysqli_query($conn, $query);
	
	while($row=mysqli_fetch_assoc($result)){
	$_SESSION["user_sec_no"]=$row['user_sec_no'];
	$_SESSION["user_s_no"]=$row['user_s_no'];
	}
   
	 
	 session_write_close();
	 header("location:dashboard.php?u_name=$u_name");
	 exit();
	 
     }
      else
      {
     session_write_close();
     header("location:login_form.php?invalid=$u_name");
     exit();
    }
    }
    
?>