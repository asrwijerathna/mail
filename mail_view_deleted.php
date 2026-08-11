<?php include("includes/header.php"); ?>
<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
<?php
$damid=10;

// add dbconnection
include ('dbconnect.php');
include('pagination/function.php');
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 5; //if you want to dispaly 10 records per page then you have to change here
$startpoint = ($page * $limit) - $limit;
$statement = "deleted_letters";




				if($_SESSION["login_user"]==true){	
				$u_name = $_SESSION["login_user"];
				$u_sec_no = $_SESSION["user_sec_no"];
				$u_s_no = $_SESSION["user_s_no"];
				$query1 = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
				$result1 = mysqli_query($conn, $query1);
				
			    while($row=mysqli_fetch_assoc($result1)){
					if($row['acc_type']=='A'){
						$query = "SELECT * FROM {$statement} ORDER BY recived_date DESC LIMIT {$startpoint} , {$limit}";
						$acc_type ='A';
					} elseif($row['acc_type']=='H'){
						$query = "SELECT * FROM {$statement} WHERE section_no='$u_sec_no' ORDER BY recived_date DESC LIMIT {$startpoint} , {$limit}";
						$acc_type ='H';
					} elseif($row['acc_type']=='N'){
						$query = "SELECT * FROM {$statement} WHERE section_no='$u_sec_no' AND subject_no='$u_s_no' ORDER BY recived_date DESC LIMIT {$startpoint} , {$limit}";
						$acc_type ='N';
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
<h3>
<?php
if($acc_type=='A'){
	echo "මකා දමා ඇති සියළු ලිපි මෙහි දැක්වේ.";
	}elseif($acc_type=='H'){
		echo "මකා දමා ඇති සියළු ලිපි මෙහි දැක්වේ.";
		}elseif($acc_type=='N'){
			echo "මකා දමා ඇති සියළු ලිපි මෙහි දැක්වේ.";
			}
?>
</h3>


    <div class="row" style="padding-top:20px; padding-bottom:20px">
    
    <table class="table">
    <thead>
    <tr>
    	<th>අනු අංකය</th>
        <th>ලැබුණු දිනය</th>
        <th>වෙලාව</th>
        <th>ලියුමේ දිනය</th>
        <th>එවන්නා</th>
        <th>ලිපි අංකය</th>
        <th>ලිපියේ මාතෘකාව</th>
        <th>අංශය</th>
        <th>විශය</th>
        <th>වර්ගය</th>
        <th>ලි.ප.</th>
        <th>තත්වය</th>
		<th>පරිශීලක</th>
        <th colspan="4">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	while($row=mysqli_fetch_assoc($result)){
		
		$l_id_no=$row['letter_no'];
		$query_get_status = "SELECT  `status` 
								FROM  `action_tbl` 
								WHERE l_no =$l_id_no
								ORDER BY  `index` DESC 
								LIMIT 1";
		$result_get_status = mysqli_query($conn, $query_get_status);
		if(mysqli_num_rows($result_get_status)!=0){
		while($row_action=mysqli_fetch_assoc($result_get_status)){
			$acton_tb_status=$row_action['status'];
			//echo $acton_tb_status;
		}
		}else{
			$acton_tb_status='NON';
			}
	?>
    <tr
    <?php
	
	
			if ($acton_tb_status=='NON'){
				echo " bgcolor='#FFAAAA'";
			}elseif($acton_tb_status==''){
				echo " bgcolor='#FFDDDD'";
			}elseif($acton_tb_status=='REC'){
				echo " bgcolor='#FFDDDD'";
			}elseif($acton_tb_status=='CLD'){
				echo " bgcolor='#DDFFDD'";
			}elseif($acton_tb_status=='WOK'){
				echo " bgcolor='#DDDDFF'";
			}
			
?>
    >
    	<td><?php echo $l_id_no;?></td>
        <td><?php echo $row['recived_date'];?></td>
        <td><?php echo $row['recived_time'];?></td>
        <td><?php echo $row['letter_date'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['sender'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['sender_no'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['title'];?></td>
        <td><?php echo $row['section_no'];?></td>
        <td><?php echo $row['subject_no'];?></td>
        <td><?php echo $row['type'];?></td>
        <td><?php echo $row['reg_no'];?></td>
        <td><?php //echo $row['status'];
				echo $acton_tb_status;
			
		?>
		</td>
		<td><?php echo $row['user_name'];?></td>

<?php 
$page1 = pagination($statement,$limit,$page);
$page_no = $page;
?>
<td>
        <!-- Button trigger modal -->
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal<?php echo $l_id_no;?>">V</button>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal<?php echo $l_id_no;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">අංක <?php echo $l_id_no;?> ලිපිය සම්බන්ධයෙන් ගෙන ඇති ක්‍රියාමාර්ග</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                <div class="modal-body">
                 <?php include("includes/status_table.php"); ?>                
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
              </div>
            </div>
          </div>
        </div>  
</td>


		
    </tr>
	<?php } ?>
    </tbody>
    </table>
    
<?php
echo "<div id='pagingg' class='container bg-light'>";
if(isset($page1)){
echo $page1;
}
echo "</div>";
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