﻿<?php session_start(); ?>



<?php
// add dbconnection
include ('dbconnect.php');
//include('pagination/function.php');
//$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
//$limit = 5; //if you want to dispaly 10 records per page then you have to change here
//$startpoint = ($page * $limit) - $limit;
//$s_date= $_POST['s_date'];
if(isset($_POST['damid'])==14){
	$lno= $_POST['lno'];
	$recived_date= $_POST['recived_date'];
	$letter_date= $_POST['letter_date'];
	$sender_no= $_POST['sender_no'];
	$sender= $_POST['sender'];
	$title= $_POST['title'];
	$section_no= $_POST['section_no'];
	$subject_no= $_POST['subject_no'];
	$type= $_POST['type'];
	$reg_no= $_POST['reg_no'];
} else{
	$lno= '';
	$recived_date= $_POST['recived_date'];
	//$recived_date2= $_POST['recived_date2'];
	$letter_date= '';
	$sender_no= '';
	$sender= '';
	$title= '';
	$section_no= '';
	$subject_no= '';
	$type= '';
	$reg_no= '';
	}
	
//$rdate = $_GET['rdate'];
$statement = "letter";



				if($_SESSION["login_user"]==true){	
				$u_name = $_SESSION["login_user"];
				$u_sec_no = $_SESSION["user_sec_no"];
				$u_s_no = $_SESSION["user_s_no"];
				$query1 = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
				$result1 = mysqli_query($conn, $query1);
				
			    while($row=mysqli_fetch_assoc($result1)){
					if($row['acc_type']==('A'||'H'||'N')){
						
								if ($_REQUEST["lno"]<>'') {
									$search_lno = " AND letter_no=".mysql_real_escape_string($_REQUEST["lno"]);	
								} else {
									$search_lno='';
									}
																	
								if ($_REQUEST["recived_date"]<>'') {
									$search_recived_date = " AND recived_date='".mysql_real_escape_string($_REQUEST["recived_date"])."'";	
								} else {
									$search_recived_date='';
									}	
									
								if ($_REQUEST["letter_date"]<>'') {
									$search_letter_date = " AND letter_date='".mysql_real_escape_string($_REQUEST["letter_date"])."'";	
								} else {
									$search_letter_date='';
									}	
									
								if ($_REQUEST["sender_no"]<>'') {
									$search_sender_no = " AND (sender_no LIKE '%".mysql_real_escape_string($_REQUEST["sender_no"])."%')";	
								} else {
									$search_sender_no='';
									}	
								
								if ($_REQUEST["sender"]<>'') {
									$search_sender = " AND (sender LIKE '%".mysql_real_escape_string($_REQUEST["sender"])."%')";	
								} else {
									$search_sender='';
									}
								
								if ($_REQUEST["title"]<>'') {
									$search_title = " AND (title LIKE '%".mysql_real_escape_string($_REQUEST["title"])."%')";
								} else {
									$search_title ='';
									}
								
								if ($_REQUEST["section_no"]<>'') {
									$search_section_no = " AND section_no=".mysql_real_escape_string($_REQUEST["section_no"]);	
								} else {
									$search_section_no='';
									}
								
								if ($_REQUEST["subject_no"]<>'') {
									$search_subject_no = " AND subject_no=".mysql_real_escape_string($_REQUEST["subject_no"]);	
								} else {
									$search_subject_no='';
									}
								
								if ($_REQUEST["type"]<>'') {
									$search_type = " AND type='".mysql_real_escape_string($_REQUEST["type"])."'";	
								} else {
									$search_type='';
									}
								if ($_REQUEST["reg_no"]<>'') {
									$search_reg_no = " AND reg_no=".mysql_real_escape_string($_REQUEST["reg_no"]);	
								} else {
									$search_reg_no='';
									}						
						
						$query = "SELECT * FROM {$statement} WHERE letter_no>0".$search_sender.$search_title.$search_lno.$search_recived_date.$search_letter_date.$search_sender_no.$search_section_no.$search_subject_no.$search_type.$search_reg_no;
						//$query = "SELECT * FROM {$statement} WHERE recived_date='$recived_date'";
						
					//} elseif($row['acc_type']==('N'||'H')){
					//	$query = "SELECT * FROM {$statement} WHERE section_no='$u_sec_no' AND subject_no='$u_s_no' AND recived_date='$recived_date'";
						}
				}
				}



//create query

$result = mysqli_query($conn, $query);

//if($result){
	//echo "Record Successfully Run the quary";
	//}
	//else {
	//echo "error my sql" . mysqli_error($conn);
	//}
?>

	<table bordered="1" bordercolor="#000000">
    <thead>
    <tr>
    	<th>අනු අංකය</th>
        <th>ලිපිය ලැබුණු දිනය</th>
        <th>ලැබුණු වෙලාව</th>
        <th>ලියුමේ දිනය</th>
        <th>එවන්නා</th>
        <th>ලිපි අංකය</th>
        <th>ලිපියේ මාතෘකාව</th>
        <th>අංශයේ අංකය</th>
        <th>විශය අංකය</th>
        <th>ලිපි වර්ගය</th>
        <th>ලියාපදිංචි අංකය</th>
        <th>අවසන් තත්වය</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	while($row=mysqli_fetch_assoc($result)){
	?>
    <tr>
    	<td><?php echo $row['letter_no'];?></td>
        <td><?php echo $row['recived_date'];?></td>
        <td><?php echo $row['recived_time'];?></td>
        <td><?php echo $row['letter_date'];?></td>
        <td><?php echo $row['sender'];?></td>
        <td><?php echo $row['sender_no'];?></td>
        <td><?php echo $row['title'];?></td>
        <td><?php echo $row['section_no'];?></td>
        <td><?php echo $row['subject_no'];?></td>
        <td><?php echo $row['type'];?></td>
        <td><?php echo $row['reg_no'];?></td>
        <td><?php echo $row['status'];?></td>
    </tr>
	<?php } ?>
    </tbody>
    </table>  
  
  
  
<?php  
  
  
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=' . date("Y-m-d") . '.xls');
  //echo $output;

?>