<?php session_start(); ?>
<?php
$loc=$_GET['dam'];
$delete_id=$_GET['delete_id'];
include('dbconnect.php');

//create select query
$query_delete_letter = "SELECT * FROM letter WHERE letter_no='$delete_id' LIMIT 1";
$result_delete_letter = mysqli_query($conn, $query_delete_letter);
$row1=mysqli_fetch_assoc($result_delete_letter);
	$letter_no = $row1['letter_no'];
	$recived_date = $row1['recived_date'];
	$recived_time = $row1['recived_time'];
	$letter_date = $row1['letter_date'];
	$sender = $row1['sender'];
	$sender_no = $row1['sender_no'];
	$title = $row1['title'];
	$section_no = $row1['section_no'];
	$subject_no = $row1['subject_no'];
	$type = $row1['type'];
	$reg_no = $row1['reg_no'];
	

$u_name = $_SESSION["login_user"];
$query_letter_delete_user = "SELECT user_name,password FROM user_tbl WHERE user_name='$u_name' LIMIT 1";	
$result_letter_delete_user = mysqli_query($conn, $query_letter_delete_user);	
$row2=mysqli_fetch_assoc($result_letter_delete_user);	
$user_name = $row2['user_name'];
$password = $row2['password'];

$query_letter_delete_insert = "INSERT INTO deleted_letters (letter_no, recived_date, recived_time,letter_date, sender, sender_no, title, section_no, subject_no, type, reg_no, user_name, password) VALUES ('{$letter_no}', '{$recived_date}', '{$recived_time}', '{$letter_date}', '{$sender}', '{$sender_no}', '{$title}', '{$section_no}', '{$subject_no}', '{$type}', '{$reg_no}', '{$user_name}', '{$password}')";

if(mysqli_query($conn, $query_letter_delete_insert)){

//create delete query
$query = "DELETE FROM letter WHERE letter_no='$delete_id'";


if(mysqli_query($conn, $query)){
	
	if($loc==10){
		header("location:mail_view.php");
		}elseif($loc==11){
			header("location:mail_view_non.php");
			}elseif($loc==12){
				header("location:mail_view_rec.php");
				}elseif($loc==13){
					header("location:mail_view_wok.php");
					}elseif($loc==15){
						header("location:mail_view_cld.php");
					}elseif($loc==14){
						
						
				$id=$_GET['id']; 
								
				if(isset($_GET['back_lno'])){
					$back_lno="&back_lno=".$_GET['back_lno'];
					}else{
						$back_lno="";
						}
				if(isset($_GET['back_recived_date'])){
					$back_recived_date="&back_recived_date=".$_GET['back_recived_date'];
					}else{
						$back_recived_date="";
						}	
				if(isset($_GET['back_letter_date'])){
					$back_letter_date="&back_letter_date=".$_GET['back_letter_date'];
					}else{
						$back_letter_date="";
						}					
				if(isset($_GET['back_sender_no'])){
					$back_sender_no="&back_sender_no=".$_GET['back_sender_no'];
					}else{
						$back_sender_no="";
						}					
				if(isset($_GET['back_sender'])){
					$back_sender="&back_sender=".$_GET['back_sender'];
					}else{
						$back_sender="";
						}							
				if(isset($_GET['back_title'])){
					$back_title="&back_title=".$_GET['back_title'];
					}else{
						$back_title="";
						}				
				if(isset($_GET['back_section_no'])){
					$back_section_no="&back_section_no=".$_GET['back_section_no'];
					}else{
						$back_section_no="";
						}										
				if(isset($_GET['back_subject_no'])){
					$back_subject_no="&back_subject_no=".$_GET['back_subject_no'];
					}else{
						$back_subject_no="";
						}	
				if(isset($_GET['back_type'])){
					$back_type="&back_type=".$_GET['back_type'];					
					}else{
						$back_type="";
						}	
				if(isset($_GET['back_reg_no'])){
					$back_reg_no="&back_reg_no=".$_GET['back_reg_no'];
					}else{
						$back_reg_no="";
						}	
					
				$back_string = $back_lno.$back_recived_date.$back_letter_date.$back_sender_no.$back_sender.$back_title.$back_section_no.$back_subject_no.$back_type.$back_reg_no;						
		
							
							header("location:search.php?dam=1".$back_string);						

						}else{
							header("location:mail_view.php");
							}	
	}
	else {
	echo "error my sql " . mysqli_error($conn);
	}
}	
	mysqli_close($conn);
?>