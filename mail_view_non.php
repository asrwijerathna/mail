<?php include("includes/header.php"); ?>
<link href="bootstrap/css/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
<?php
$damid=11;
// add dbconnection
include ('dbconnect.php');
include('pagination/function.php');
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
if(!in_array($limit, array(10, 25, 50, 100))){ $limit = 10; }
$startpoint = ($page * $limit) - $limit;
$statement = "letter";

$where_role = "";
if(isset($_SESSION["login_user"])){	
    $u_name = $_SESSION["login_user"];
    $u_sec_no = isset($_SESSION["user_sec_no"]) ? $_SESSION["user_sec_no"] : '';
    $u_s_no = isset($_SESSION["user_s_no"]) ? $_SESSION["user_s_no"] : '';
    $query1 = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
    $result1 = mysqli_query($conn, $query1);
    
    if($result1 && $row=mysqli_fetch_assoc($result1)){
        $acctype = $row['acc_type'];
        if($acctype=='H'){
            $where_role = " AND a.section_no = '$u_sec_no' ";
        } elseif($acctype=='N'){
            $where_role = " AND a.section_no = '$u_sec_no' AND a.subject_no = '$u_s_no' ";
        }
    }
}
if(!isset($acctype)) { $acctype = 'A'; }

$res_cnt = mysqli_query($conn, "SELECT COUNT(*) as total FROM letter a WHERE NOT EXISTS (SELECT 1 FROM action_tbl b WHERE b.l_no = a.letter_no) $where_role");
$total_records = ($res_cnt && $r = mysqli_fetch_assoc($res_cnt)) ? (int)$r['total'] : 0;

$query = "SELECT a.recived_date, a.letter_date, a.sender, a.sender_no, a.title, a.section_no, a.subject_no, a.type, a.reg_no, a.letter_no
        FROM letter a
        WHERE NOT EXISTS (SELECT 1 FROM action_tbl b WHERE b.l_no = a.letter_no)
        $where_role
        ORDER BY a.recived_date DESC, a.recived_time DESC 
        LIMIT {$startpoint} , {$limit}";

$result = mysqli_query($conn, $query);
$page1 = renderPagination($total_records, $limit, $page);
?>

<div class="container-fluid mt-4 mb-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 text-secondary">නව ලිපි <span class="badge badge-danger ml-2">NON</span></h3>
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
        <th>තත්වය</th>
        <th colspan="4">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	while($row=mysqli_fetch_assoc($result)){
		$l_id_no=$row['letter_no'];
	?>
    <tr class="table-danger">
    	<td><?php echo $row['letter_no'];?></td>
        <td><?php echo $row['recived_date'];?></td>
        <td><?php echo $row['letter_date'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['sender'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['sender_no'];?></td>
        <td style ="word-break:break-word;"><?php echo $row['title'];?></td>
        <td><?php echo $row['section_no'];?></td>
        <td><?php echo $row['subject_no'];?></td>
        <td><?php echo $row['type'];?></td>
        <td><?php echo $row['reg_no'];?></td>
        <td><span class="badge badge-pill badge-danger px-2 py-1">NON</span></td>

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
  var input = document.getElementById("tableSearch");
  var filter = input.value.toUpperCase();
  var table = document.getElementById("mailTable");
  var tr = table.getElementsByTagName("tr");
  for (var i = 1; i < tr.length; i++) {
    tr[i].style.display = "none";
    var td = tr[i].getElementsByTagName("td");
    for (var j = 0; j < td.length; j++) {
      if (td[j]) {
        var txt = td[j].textContent || td[j].innerText;
        if (txt.toUpperCase().indexOf(filter) > -1) { tr[i].style.display = ""; break; }
      }
    }
  }
}
</script>

<?php include("includes/footer.php"); ?>

<script type="text/javascript" src="js/dtpicker/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
</script>
