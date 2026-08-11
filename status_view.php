<?php include("includes/header.php"); ?>

<?php
// add dbconnection
include ('dbconnect.php');
$damid=$_GET['dam'];
$id=$_GET['id'];


//create query
$query = "SELECT * FROM action_tbl WHERE l_no='$id'";
$result = mysqli_query($conn, $query);


?>

<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>ලිපි සම්බන්ධයෙන් ගෙන ඇති ක්‍රියා මාර්ග සහ වර්ථමාන තත්ත්වය</h3>
    <div class="row" style="padding-top:20px; padding-bottom:20px">
    <!--<div class="col-sm-8">-->
    <table class="table">
    <thead>
    <tr>
    	<th>අනු අංකය</th>
        <th>ක්‍රියාකල දිනය</th>
        <th>ක්‍රියා මාර්ගය</th>
        <th>වර්ථමාන තත්ත්වය</th>
        <th>ලිපි අංකය</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	while($row=mysqli_fetch_assoc($result)){
	?>
    <tr>
    	<td><?php echo $row['index'];?></td>
        <td><?php echo $row['action_date'];?></td>
        <td><?php echo $row['action'];?></td>
        <td><?php echo $row['status'];?></td>
        <td><?php echo $row['l_no'];?></td>
<!--        <td>
        <a href="editform.php?id=<?php /*?><?php echo $row['index'];?><?php */?>" class="btn btn-success" role="button">E</a>
        </td>-->
        <td>
        <a onClick="javascript: return confirm('ඔබට මෙය ඩිලීට් කිරීමට අවශ්‍ය නම් OK ඔබන්න. නැතිනම් Cancel ඔබන්න.');" href="delete_status.php?id=<?php echo $row['index'];?>&dam=<?php echo $damid; ?>" class="btn btn-danger" role="button">D</a>
        </td>
    </tr>
	<?php } ?>
    </tbody>
    </table>
    
    
                    <?php

				
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
				                				
				
				if($_GET['dam']==2){
				?>
                <br /><a class="btn btn-info" role="button" href="mail_view.php
                <?php	
					//echo $_GET['dam'];
				?>
                ">ආපසු යන්න</a>
                <?php	
				}elseif($_GET['dam']==1){
				?>	
                 <br /><a class="btn btn-info" role="button" href="search.php?dam=1
                <?php
					echo $back_string;
				?>
                ">ආපසු යන්න</a>
                <?php	
					}				
				?>
    
    
    <!--</div>-->
    </div>
    </div>
</div>
   
<!--Date time picker-->
<script type="text/javascript" src="js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/dtpicker/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/dtpicker/locales/bootstrap-datetimepicker.uk.js" charset="UTF-8"></script>



<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	$('.form_date').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	$('.form_time').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>

      
<?php 
include("includes/footer.php"); 
?>