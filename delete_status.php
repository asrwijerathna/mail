<?php
$loc=$_GET['dam'];
$action_id=$_GET['action_id'];
include('dbconnect.php');


//create query
 
$query = "DELETE FROM  `action_tbl` WHERE  `index` ='$action_id'";

  
if(mysqli_query($conn, $query)){
		if($loc==10){
		header("location:mail_view.php");
		}elseif($loc==11){
			header("location:mail_view_no_action.php");
			}elseif($loc==12){
				header("location:mail_view_pending.php");
				}elseif($loc==13){
					header("location:mail_view_closed.php");
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
	
	mysqli_close($conn);
?>