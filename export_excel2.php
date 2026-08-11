<?php include("includes/header.php"); ?>


<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>Mail Management System</h3>

<div class="alert alert-info" role="alert">
 <?php
 	//echo $_GET['idcheck'];
 	if(isset($_GET['idcheck'])){
		$rdate = $_GET['rdate'];
		$ltime = $_GET['ltime'];
		echo "සාර්ථකව ඇතුලත්කරන ලද තොරතුරු : " . $rdate . "," . $ltime;
	} elseif(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		echo "Item " . $delete . " sucsessfully deleted.";
	} else {
		echo " වාර්තාව අවශ්‍ය දිනය ඇතුලත්කර සොයන්න ක්ලික් කරන්න.";
	}  
	?>
</div>


        <div class="row">

    <div class="col-sm-12">


        <form role="form" action="export_excel.php" method="GET" >   
                 <div class="form-group row">       	
                <label for="dtp_input2" class="col-md-2" >ආරම්භක දිනය ඇතුලත් කරන්න : </label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="s_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                
                 </div>
                 
                <label for="dtp_input2" class="col-md-2" >අවසාන දිනය ඇතුලත් කරන්න : </label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="e_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                 
                 </div>
                 <div class="controls">
				<input type="hidden" id="dtp_input2" value="" />
                <button type="submit" class="btn btn-info btn-block ">Submit</button>
	         	</div>
            </div>
        		
        </form>





<?php
// add dbconnection
include ('dbconnect.php');

if(isset($_GET['s_date'])&&isset($_GET['e_date'])){
$s_date= $_GET['s_date'];
$e_date= $_GET['e_date'];
$statement = "letter";



	

				if($_SESSION["login_user"]==true){	
				$u_name = $_SESSION["login_user"];
				$u_sec_no = $_SESSION["user_sec_no"];
				$u_s_no = $_SESSION["user_s_no"];
				$query1 = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
				$result1 = mysqli_query($conn, $query1);
				
			    while($row=mysqli_fetch_assoc($result1)){
					if($row['acc_type']=='A'){
						$query = "SELECT * FROM {$statement} WHERE recived_date BETWEEN '$s_date' AND '$e_date'";
						
					} elseif($row['acc_type']==('N'||'H')){
						$query = "SELECT * FROM {$statement} WHERE section_no='$u_sec_no' AND subject_no='$u_s_no' AND recived_date BETWEEN '$s_date' AND '$e_date'";
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

<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>Mail Management System</h3>
    <div class="row" style="padding-top:20px; padding-bottom:20px">
    <!--<div class="col-sm-8">-->
    <table class="table">
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
  
<form method="POST" action="export.php?damid=15">		
            <input type="hidden" name="lno" class="form-control col-md-5" value="<?php //echo $lno;?>"/>
            <input type="hidden" name="recived_date" class="form-control col-md-5" value="<?php echo $s_date;?>"/>
            <input type="hidden" name="recived_date2" class="form-control col-md-5" value="<?php echo $e_date;?>"/>
            <input type="hidden" name="letter_date" class="form-control col-md-5" value="<?php //echo $letter_date;?>"/>
            <input type="hidden" name="sender_no" class="form-control col-md-5" value="<?php //echo $sender_no;?>"/>
            <input type="hidden" name="sender" class="form-control col-md-5" value="<?php //echo $sender;?>"/>
            <input type="hidden" name="title" class="form-control col-md-5" value="<?php //echo $title;?>"/>
            <input type="hidden" name="section_no" class="form-control col-md-5" value="<?php //echo $section_no;?>"/>
            <input type="hidden" name="subject_no" class="form-control col-md-5" value="<?php //echo $subject_no;?>"/>
            <input type="hidden" name="type" class="form-control col-md-5" value="<?php //echo $ttype;?>"/>
            <input type="hidden" name="reg_no" class="form-control col-md-5" value="<?php //echo $reg_no;?>"/>
            
			<input type="submit" name="export" class="btn btn-info btn-block" value="Export to Excel" />
</form>
  
  
    <?php
//echo "<div id='pagingg' class='container bg-light'>";
//echo pagination($statement,$limit,$page);
//echo "</div>";

}
else{
?>


   

    


<?php } ?>
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