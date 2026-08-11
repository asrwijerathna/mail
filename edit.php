<?php
include ('dbconnect.php');

$id = $_POST['id'];
$lrecive_date = $_POST['l_recive_date'];
$lrecive_time = $_POST['l_time'];
$ldate = $_POST['l_date'];
$lno = $_POST['l_no'];
$lsender = $_POST['sender'];
$ltitle = $_POST['l_title'];
$secno = $_POST['sec_no'];
$subno = $_POST['sub_no'];
$ltype = $_POST['l_type'];
$rno = $_POST['r_no'];
$limpo = $_POST['l_impo'];
//$rtime = date("h:i");

//echo $lrecive_date, $ldate;

//create query
$query = "UPDATE letter SET letter_no='$id', recived_date='$lrecive_date', recived_time='$lrecive_time', letter_date='$ldate', sender_no='$lno', sender='$lsender', title='$ltitle', section_no='$secno', subject_no='$subno', type='$ltype', reg_no='$rno', important='$limpo' WHERE letter_no='$id'";

if(mysqli_query($conn, $query)){
	header("location:mail_view.php");
	}
	else {
	echo "error my sql " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
?>