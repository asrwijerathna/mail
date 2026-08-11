<?php include("includes/header.php"); ?>



<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>ලිපි පිළිබඳ සෙවීම් කිරීම</h3>
    
    
<div class="alert alert-info" role="alert">    
<?php
// add dbconnection
include ('dbconnect.php');

$s_date = $_POST['s_date'];
$e_date = $_POST['e_date'];

//create query
$query = "SELECT * FROM letter WHERE recived_date BETWEEN '$s_date' AND '$e_date'";

$result = mysqli_query($conn, $query);

if($result){
	echo $s_date . " දින දී ඇතුලත්කර ඇති ලිපි පහත දැක්වේ.";
	}
	else {
	echo "error my sql" . mysqli_error($conn);
	}
?>    
</div>    

<div class="row" style="padding-top:20px; padding-bottom:20px">
    <!--<div class="col-sm-8">-->
    <table class="table">
    <thead>
    <tr>
    	<th>අනු අංකය</th>
        <th>ලිපිය ලැබුණු දිනය</th>
        <th>ලැබුණු වෙලාව</th>
        <th>ලියුමේ දිනය</th>
        <th>ලිපි අංකය</th>
        <th>ලිපියේ මාතෘකාව</th>
        <th>අංශයේ අංකය</th>
        <th>විශය අංකය</th>
        <th>ලිපි වර්ගය</th>
        <th>ලියාපදිංචි අංකය</th>
        <th colspan="4">Action</th>
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
        <td><?php echo $row['sender_no'];?></td>
        <td><?php echo $row['title'];?></td>
        <td><?php echo $row['section_no'];?></td>
        <td><?php echo $row['subject_no'];?></td>
        <td><?php echo $row['type'];?></td>
        <td><?php echo $row['reg_no'];?></td>
        <td>
        <a href="editform.php?id=<?php echo $row['letter_no'];?>" class="btn btn-success" role="button">E</a>
        </td>
        <td>
        <a href="delete.php?id=<?php echo $row['letter_no'];?>" class="btn btn-danger" role="button">D</a>
        </td>
        <td>
        <a href="status_form.php?id=<?php echo $row['letter_no'];?>" class="btn btn-info" role="button" >S</a>
        </td>
        <td>
        <a href="status_view.php?id=<?php echo $row['letter_no'];?>" class="btn btn-secondary" role="button" >V</a>
        </td>
    </tr>
	<?php } ?>
    </tbody>
    </table>
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