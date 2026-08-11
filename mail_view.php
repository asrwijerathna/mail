<?php include("includes/header.php"); ?>
<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
<?php
$damid=10;

// add dbconnection
include ('dbconnect.php');
include('pagination/function.php');
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
if(!in_array($limit, array(10, 25, 50, 100))){ $limit = 10; }
$startpoint = ($page * $limit) - $limit;
$statement = "letter";




$where = "";
if(isset($_SESSION["login_user"])){	
    $u_name = $_SESSION["login_user"];
    $u_sec_no = isset($_SESSION["user_sec_no"]) ? $_SESSION["user_sec_no"] : '';
    $u_s_no = isset($_SESSION["user_s_no"]) ? $_SESSION["user_s_no"] : '';
    $query1 = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
    $result1 = mysqli_query($conn, $query1);
    
    if($result1 && $row=mysqli_fetch_assoc($result1)){
        $acc_type = $row['acc_type'];
        if($acc_type=='H'){
            $where = " WHERE a.section_no='$u_sec_no' ";
        } elseif($acc_type=='N'){
            $where = " WHERE a.section_no='$u_sec_no' AND a.subject_no='$u_s_no' ";
        }
    }
}
if(!isset($acc_type)) { $acc_type = 'A'; }

$res_cnt = mysqli_query($conn, "SELECT COUNT(*) as total FROM letter a $where");
$total_records = ($res_cnt && $r = mysqli_fetch_assoc($res_cnt)) ? (int)$r['total'] : 0;

$query = "SELECT a.*, (SELECT status FROM action_tbl b WHERE b.l_no = a.letter_no ORDER BY b.`index` DESC LIMIT 1) as acton_tb_status FROM {$statement} a $where ORDER BY a.recived_date DESC, a.recived_time DESC LIMIT {$startpoint} , {$limit}";

$result = mysqli_query($conn, $query);
$page1 = renderPagination($total_records, $limit, $page);
?>


<div class="container-fluid mt-4 mb-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 text-secondary">
    <?php
    if($acc_type=='A'){
        echo "ආයතනයට ලැබී ඇති සියළුම ලිපි මෙහි දැක්වේ.";
        }elseif($acc_type=='H'){
            echo "ඔබගේ අංශයට ලැබී ඇති සියළුම ලිපි මෙහි දැක්වේ.";
            }elseif($acc_type=='N'){
                echo "ඔබට ලැබී ඇති සියළුම ලිපි මෙහි දැක්වේ.";
                }
    ?>
    </h3>
    <div class="form-inline">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            </div>
            <input type="text" id="tableSearch" class="form-control" placeholder="Search current page..." onkeyup="filterTable()">
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0 table-responsive">
    <table class="table table-hover table-striped mb-0" id="mailTable">
    <thead class="thead-light">
    <tr>
    	<th>අනු අංකය</th>
        <th>ලිපිය ලැබුණු දිනය</th>
        
        <th>ලියුමේ දිනය</th>
        <th>එවන්නා</th>
        <th>ලිපි අංකය</th>
        <th>ලිපියේ මාතෘකාව</th>
        <th>අංශය</th>
        <th>විශය</th>
        <th>වර්ගය</th>
        <th>ලි.ප.</th>
        <th>අවසන් තත්වය</th>
        <th colspan="4">Action</th>
    </tr>
    </thead>
    <tbody>
	<?php 
	while($row=mysqli_fetch_assoc($result)){
		
		$l_id_no=$row['letter_no'];
		$acton_tb_status = $row['acton_tb_status'] ? $row['acton_tb_status'] : 'NON';
        
        $badge_class = 'badge-secondary';
        $row_class = '';
        if($acton_tb_status == 'NON') { $badge_class = 'badge-danger'; $row_class = 'table-danger'; }
        if($acton_tb_status == 'REC') { $badge_class = 'badge-info'; $row_class = 'table-info'; }
        if($acton_tb_status == 'WOK') { $badge_class = 'badge-warning'; $row_class = 'table-warning'; }
        if($acton_tb_status == 'CLD') { $badge_class = 'badge-success'; $row_class = 'table-success'; }
	?>
    <tr class="<?php echo $row_class; ?>">
    	<td><?php echo $l_id_no;?></td>
        <td><?php echo $row['recived_date'];?></td>
        
        <td><?php echo $row['letter_date'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['sender'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['sender_no'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['title'];?></td>
        <td><?php echo $row['section_no'];?></td>
        <td><?php echo $row['subject_no'];?></td>
        <td><?php echo $row['type'];?></td>
        <td><?php echo $row['reg_no'];?></td>
        <td><span class="badge badge-pill <?php echo $badge_class; ?> px-2 py-1"><?php echo $acton_tb_status; ?></span></td>

<?php 
$page_no = $page;
include("includes/buttons.php"); 

?>

    </tr>
	<?php } ?>
    </tbody>
    </table>
    </div>
</div>
    
<?php
echo "<div id='pagingg' class='mt-4'>";
if(isset($page1)){
echo $page1;
}
echo "</div>";
?>
</div>

<script>
function filterTable() {
  var input, filter, table, tr, td, i, j, txtValue;
  input = document.getElementById("tableSearch");
  filter = input.value.toUpperCase();
  table = document.getElementById("mailTable");
  tr = table.getElementsByTagName("tr");
  
  for (i = 1; i < tr.length; i++) {
    tr[i].style.display = "none";
    td = tr[i].getElementsByTagName("td");
    for (j = 0; j < td.length; j++) {
      if (td[j]) {
        txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
          break;
        }
      }
    }
  }
}
</script>





  
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