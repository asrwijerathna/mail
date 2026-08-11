<?php

include ('dbconnect.php');


if(isset($_GET['damid'])){
$damid = $_GET['damid'];
}


if(isset($_GET['page_no'])){
if($_GET['page_no']>1){
	$page_no = $_GET['page_no'];
	} else {
		$page_no = 1;
		}
}		
$action_date = mysqli_real_escape_string($conn, $_POST['action_date']);
$action = mysqli_real_escape_string($conn, $_POST['action']);
$a_type = mysqli_real_escape_string($conn, $_POST['a_type']);
$l_no = mysqli_real_escape_string($conn, $_POST['l_no']);

//$rtime = date("h:i");

//echo $lrecive_date, $ldate;

//create query
$query = "INSERT INTO action_tbl (action, action_date, status, l_no) VALUES ('{$action}', '{$action_date}', '{$a_type}', '{$l_no}')";

if(mysqli_query($conn, $query)){
	$query2 = "UPDATE letter SET status='$a_type' WHERE letter_no='$l_no'";
	if(mysqli_query($conn, $query2)){
		if($damid==10){
			header("location:mail_view.php?page=$page_no");
			} elseif($damid==11){
				header("location:mail_view_non.php?page=$page_no");
				} elseif($damid==12){
					header("location:mail_view_rec.php?page=$page_no");
					} elseif($damid==13){
						header("location:mail_view_wok.php?page=$page_no");
						} elseif($damid==15){
							header("location:mail_view_cld.php?page=$page_no");
						} elseif($damid==14){
							
							
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
							} 
		
		
		
	}else {
		echo "error my sql" . mysqli_error($conn);
	}
}else {
	echo "error my sql" . mysqli_error($conn);
	}

	
	mysqli_close($conn);
?>