<?php
include ('dbconnect.php');

$u_name = $_POST['u_name'];
$sec_no = $_POST['sec_no'];
$sub_no = $_POST['sub_no'];
$u_type = $_POST['u_type'];
$u_pass = $_POST['u_pass'];


//create query
$query = "INSERT INTO user_tbl (user_name, user_sec_no, user_s_no,acc_type, password) VALUES ('{$u_name}', '{$sec_no}', '{$sub_no}', '{$u_type}', '{$u_pass}')";

if(mysqli_query($conn, $query)){
	header("location:user_create.php?idcheck=1&u_name=$u_name&sub_no=$sub_no&u_type=$u_type");
	}
	else {
	echo "error my sql" . mysqli_error($conn);
	}
	
	mysqli_close($conn);
?>