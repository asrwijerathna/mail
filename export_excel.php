<?php
error_reporting(0);
session_start();
include('dbconnect.php');

if(empty($_SESSION["login_user"])){
	header("location:login_form.php");
	exit();
}

// -----------------------------------------------------------------------------
// 1. Build Search Query
// -----------------------------------------------------------------------------
$where_clauses = array("letter_no > 0");

// Role-based access filter — use SESSION for sec/subject numbers, only acc_type from DB
$_u          = $_SESSION["login_user"];
$_u_sec_no   = isset($_SESSION['user_sec_no']) ? (int)$_SESSION['user_sec_no'] : 0;
$_u_s_no     = isset($_SESSION['user_s_no'])   ? (int)$_SESSION['user_s_no']   : 0;
$acc_type_sess = 'A';
$_res_u = mysqli_query($conn, "SELECT acc_type FROM user_tbl WHERE user_name='" . mysqli_real_escape_string($conn, $_u) . "'");
if ($_res_u && $_row_u = mysqli_fetch_assoc($_res_u)) {
    $acc_type_sess = $_row_u['acc_type'];
    if ($acc_type_sess == 'H') {
        $where_clauses[] = "section_no=" . $_u_sec_no;
    } elseif ($acc_type_sess == 'N') {
        $where_clauses[] = "section_no=" . $_u_sec_no;
        $where_clauses[] = "subject_no=" . $_u_s_no;
    }
}

if (!empty($_GET["lno"])) {
    $where_clauses[] = "letter_no=" . mysqli_real_escape_string($conn, $_GET["lno"]);
}
if (!empty($_GET["section_no"])) {
    $where_clauses[] = "section_no=" . mysqli_real_escape_string($conn, $_GET["section_no"]);
}
if (!empty($_GET["subject_no"])) {
    $where_clauses[] = "subject_no=" . mysqli_real_escape_string($conn, $_GET["subject_no"]);
}
if (!empty($_GET["type"])) {
    $where_clauses[] = "type='" . mysqli_real_escape_string($conn, $_GET["type"]) . "'";
}
if (!empty($_GET["important"])) {
    $where_clauses[] = "important='" . mysqli_real_escape_string($conn, $_GET["important"]) . "'";
}
if (!empty($_GET["reg_no"])) {
    $where_clauses[] = "reg_no=" . mysqli_real_escape_string($conn, $_GET["reg_no"]);
}
if (!empty($_GET["title"])) {
    $where_clauses[] = "(title LIKE '%" . mysqli_real_escape_string($conn, $_GET["title"]) . "%')";
}
if (!empty($_GET["sender"])) {
    $where_clauses[] = "(sender LIKE '%" . mysqli_real_escape_string($conn, $_GET["sender"]) . "%')";
}
if (!empty($_GET["sender_no"])) {
    $where_clauses[] = "(sender_no LIKE '%" . mysqli_real_escape_string($conn, $_GET["sender_no"]) . "%')";
}

// Date Logic: Received Date
if (!empty($_GET["recived_date"]) && !empty($_GET["recived_date2"])) {
    $d1 = mysqli_real_escape_string($conn, $_GET["recived_date"]);
    $d2 = mysqli_real_escape_string($conn, $_GET["recived_date2"]);
    $where_clauses[] = "(recived_date BETWEEN '$d1' AND '$d2')";
} elseif (!empty($_GET["recived_date"])) {
    $where_clauses[] = "recived_date='" . mysqli_real_escape_string($conn, $_GET["recived_date"]) . "'";
}

// Date Logic: Letter Date
if (!empty($_GET["letter_date"]) && !empty($_GET["letter_date2"])) {
    $d1 = mysqli_real_escape_string($conn, $_GET["letter_date"]);
    $d2 = mysqli_real_escape_string($conn, $_GET["letter_date2"]);
    $where_clauses[] = "(letter_date BETWEEN '$d1' AND '$d2')";
} elseif (!empty($_GET["letter_date"])) {
    $where_clauses[] = "letter_date='" . mysqli_real_escape_string($conn, $_GET["letter_date"]) . "'";
}

$where_sql = " WHERE " . implode(" AND ", $where_clauses);


// -----------------------------------------------------------------------------
// 2. Handle Excel Export
// -----------------------------------------------------------------------------
if (isset($_GET['export_excel'])) {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=letters_export_" . date('Y-m-d') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<thead>
          <tr>
            <th bgcolor="#CCCCCC">අනු අංකය</th>
            <th bgcolor="#CCCCCC">ලිපිය ලැබුණු දිනය</th>
            <th bgcolor="#CCCCCC">ලිපියේ සදහන් දිනය</th>
            <th bgcolor="#CCCCCC">ලිපිය එවන්නා</th>
            <th bgcolor="#CCCCCC">එවන්නාගේ අංකය</th>
            <th bgcolor="#CCCCCC">මාතෘකාව</th>
            <th bgcolor="#CCCCCC">අංශයේ අංකය</th>
            <th bgcolor="#CCCCCC">විෂය අංකය</th>
            <th bgcolor="#CCCCCC">ලැබුණු ආකාරය</th>
            <th bgcolor="#CCCCCC">ලිප. අං.</th>
            <th bgcolor="#CCCCCC">වැදගත්බව</th>
          </tr>
          </thead><tbody>';

    $sql = "SELECT * FROM letter $where_sql";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $color = "";
        $imp_text = "";
        
        switch ($row["important"]) {
            case 'i': $color = "bgcolor='#FF0000'"; $imp_text = "ඉතා හදිසි"; break;
            case 'r': $color = "bgcolor='#FFA500'"; $imp_text = "සිහි කැදවීම්"; break;
            case 'p': $color = "bgcolor='#e88eda'"; $imp_text = "මහජන පැමිණිලි"; break;
            case 'n': $imp_text = "සාමාන්‍ය"; break;
            default:  $imp_text = $row["important"];
        }

        echo "<tr $color>";
        echo "<td>" . $row["letter_no"] . "</td>";
        echo "<td>" . $row["recived_date"] . "</td>";
        echo "<td>" . $row["letter_date"] . "</td>";
        echo "<td>" . $row["sender"] . "</td>";
        echo "<td>" . $row["sender_no"] . "</td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["section_no"] . "</td>";
        echo "<td>" . $row["subject_no"] . "</td>";
        echo "<td>" . $row["type"] . "</td>";
        echo "<td>" . $row["reg_no"] . "</td>";
        echo "<td>" . $imp_text . "</td>";
        echo "</tr>";
    }
    echo '</tbody></table></body></html>';
    exit();
}

// -----------------------------------------------------------------------------
// 3. View Logic (Pagination & HTML)
// -----------------------------------------------------------------------------
include("includes/header.php");

// Pagination Setup
$limit = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Count Total
if (isset($_GET['is_searched']) || isset($_GET['export_excel'])) {
    $sql_count = "SELECT COUNT(*) as cnt FROM letter $where_sql";
    $res_count = mysqli_query($conn, $sql_count);
    $row_count = mysqli_fetch_assoc($res_count);
    $total_records = $row_count['cnt'];
    $total_pages = ceil($total_records / $limit);

    // Fetch Data
    $sql = "SELECT * FROM letter $where_sql LIMIT $offset, $limit";
    $result = mysqli_query($conn, $sql);
} else {
    $total_records = 0;
    $total_pages = 0;
    $result = null;
}

// Helper for pagination links (keep current filters)
function get_query_string($exclude = []) {
    $params = $_GET;
    foreach ($exclude as $key) {
        unset($params[$key]);
    }
    return http_build_query($params);
}
?>

<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">

    <!-- Filter Form -->
    <form id="form1" name="form1" method="GET" action="export_excel.php">
        <input type="hidden" name="is_searched" value="1" />
        
        <div class="form-group row">
            <label class="col-md-2">අනු අංකය</label>
            <input type="text" name="lno" class="form-control col-md-5" value="<?php echo isset($_GET['lno']) ? $_GET['lno'] : ''; ?>" />
        </div>

        <!-- Received Date Range -->
        <div class="form-group row">
            <label class="col-md-2">ලිපිය ලැබුණු දිනය 1</label>      
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd" style="padding-left:0; padding-right:0;">
                <input class="form-control" type="text" name="recived_date" value="<?php echo isset($_GET['recived_date']) ? $_GET['recived_date'] : ''; ?>" placeholder="YYYY-MM-DD" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2">ලිපිය ලැබුණු දිනය 2</label> 
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding-left:0; padding-right:0;">
                <input class="form-control" type="text" name="recived_date2" value="<?php echo isset($_GET['recived_date2']) ? $_GET['recived_date2'] : ''; ?>" placeholder="YYYY-MM-DD" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <!-- Letter Date Range -->
        <div class="form-group row">
            <label class="col-md-2">ලිපියේ දිනය 1</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd" style="padding-left:0; padding-right:0;">
                <input class="form-control" type="text" name="letter_date" value="<?php echo isset($_GET['letter_date']) ? $_GET['letter_date'] : ''; ?>" placeholder="YYYY-MM-DD" readonly >
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2">ලිපියේ දිනය 2</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd" style="padding-left:0; padding-right:0;">
                <input class="form-control" type="text" name="letter_date2" value="<?php echo isset($_GET['letter_date2']) ? $_GET['letter_date2'] : ''; ?>" placeholder="YYYY-MM-DD" readonly >
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2">මාතෘකාව</label>
            <input class="form-control col-md-5" type="text" name="title" value="<?php echo isset($_GET['title']) ? stripcslashes($_GET['title']) : ''; ?>" />
        </div>
        <div class="form-group row">
            <label class="col-md-2">එවන්නා</label>
            <input class="form-control col-md-5" type="text" name="sender" value="<?php echo isset($_GET['sender']) ? stripcslashes($_GET['sender']) : ''; ?>" />
        </div>
        <div class="form-group row">
            <label class="col-md-2">ලිපියේ අංකය</label>
            <input class="form-control col-md-5" type="text" name="sender_no" value="<?php echo isset($_GET['sender_no']) ? stripcslashes($_GET['sender_no']) : ''; ?>" />
        </div>

        <div class="form-group row">
            <label class="col-md-2">අංශයේ අංකය </label>
            <select name="section_no" class="form-control col-md-5" style="width:auto; display:inline-block;">
                <option value="">--</option>
                <?php 
                for($i=1; $i<=6; $i++){
                    $sel = (isset($_GET['section_no']) && $_GET['section_no'] == $i) ? 'selected' : '';
                    echo "<option value='$i' $sel>$i</option>";
                }
                ?>
            </select>                
        </div>

        <div class="form-group row">
            <label class="col-md-2">විෂය </label>
            <select name="subject_no" class="form-control col-md-5" style="width:auto; display:inline-block;">
                <option value="">--</option>
                <?php
                for($i=1; $i<=20; $i++){
                    // Handle the 21 typo from original if needed, but assuming standard numbers first
                    // Original had value='20'>21. I'll stick to logic i=i for simplicity unless user complains, OR fix it.
                    // Let's use standard loop.
                    $sel = (isset($_GET['subject_no']) && $_GET['subject_no'] == $i) ? 'selected' : '';
                    echo "<option value='$i' $sel>$i</option>";
                }
                ?>
            </select> 
        </div>

        <div class="form-group row">                
            <label class="col-md-2">ලැබුණු ආකාරය</label>
            <select name="type" class="form-control col-md-5" style="width:auto; display:inline-block;">
                <option value="">--</option>
                <option value="N" <?php if(isset($_GET['type']) && $_GET['type']=='N') echo 'selected'; ?>>සාමාන්‍ය</option>
                <option value="R" <?php if(isset($_GET['type']) && $_GET['type']=='R') echo 'selected'; ?>>ලියාපදිංචි</option>
                <option value="H" <?php if(isset($_GET['type']) && $_GET['type']=='H') echo 'selected'; ?>>අතින්</option>
                <option value="F" <?php if(isset($_GET['type']) && $_GET['type']=='F') echo 'selected'; ?>>ෆැක්ස්</option>
                <option value="E" <?php if(isset($_GET['type']) && $_GET['type']=='E') echo 'selected'; ?>>E-Mail</option>
            </select> 
        </div>

        <div class="form-group row">
            <label class="col-md-2">ලි.ප. අංකය</label>
            <input class="form-control col-md-5" type="text" name="reg_no" value="<?php echo isset($_GET['reg_no']) ? stripcslashes($_GET['reg_no']) : ''; ?>" />
        </div>

        <div class="form-group row">                
            <label class="col-md-2">වැදගත් බව</label>
            <select name="important" class="form-control col-md-5" style="width:auto; display:inline-block;">
                <option value="">--</option>
                <option value="n" <?php if(isset($_GET['important']) && $_GET['important']=='n') echo 'selected'; ?>>සාමාන්‍ය</option>
                <option value="i" <?php if(isset($_GET['important']) && $_GET['important']=='i') echo 'selected'; ?>>ඉතා හදිසි</option>
                <option value="r" <?php if(isset($_GET['important']) && $_GET['important']=='r') echo 'selected'; ?>>සිහිකැඳවීම්</option>
                <option value="p" <?php if(isset($_GET['important']) && $_GET['important']=='p') echo 'selected'; ?>>මහජන පැමිණිලි</option>
            </select> 
        </div>

        <br>
        <div class="form-group row">
            <div class="col-md-2"></div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-info" style="width: 120px;">Filter</button>
                <button type="submit" name="export_excel" value="1" class="btn btn-success" style="width: 150px; margin-left:10px;">Export to Excel</button>
                <a href="export_excel.php" class="btn btn-secondary" style="width: 120px; margin-left:10px;">Reset</a>
            </div>
        </div>
    </form>

    <br />
    
    <!-- Results Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="bg-light">
                    <th>අනු අංකය</th>
                    <th>ලිපිය ලැබුණු දිනය</th>
                    <th>ලිපියේ සදහන් දිනය</th>
                    <th>ලිපිය එවන්නා</th>
                    <th>එවන්නාගේ අංකය</th>
                    <th>මාතෘකාව</th>
                    <th>අංශයේ අංකය</th>
                    <th>විෂය අංකය</th>
                    <th>ලැබුණු ආකාරය</th>
                    <th>ලිප. අං.</th>
                    <th>වැදගත්බව</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $color = "";
                    if ($row["important"]=='i') $color = "style='background-color:#FF0000'";
                    elseif ($row["important"]=='r') $color = "style='background-color:#FFA500'";
                    elseif ($row["important"]=='p') $color = "style='background-color:#e88eda'";
                    
                    $imp_text = "";
                    if($row["important"]=='i') $imp_text = "ඉතා හදිසි";
                    else if ($row["important"]=='r') $imp_text = "සිහි කැදවීම්";
                    else if ($row["important"]=='n') $imp_text = "සාමාන්‍ය";
                    else $imp_text = "මහජන පැමිණිලි";
            ?>
                <tr <?php echo $color; ?>>
                    <td><?php echo $row["letter_no"]; ?></td>
                    <td><?php echo $row["recived_date"]; ?></td>
                    <td><?php echo $row["letter_date"]; ?></td>
                    <td><?php echo $row["sender"]; ?></td>
                    <td><?php echo $row["sender_no"]; ?></td>
                    <td><?php echo $row["title"]; ?></td>
                    <td><?php echo $row["section_no"]; ?></td>
                    <td><?php echo $row["subject_no"]; ?></td>
                    <td><?php echo $row["type"]; ?></td>
                    <td><?php echo $row["reg_no"]; ?></td>
                    <td><?php echo $imp_text; ?></td>
                </tr>
            <?php
                }
            } else {
                if(isset($_GET['is_searched'])){
                    echo '<tr><td colspan="11" class="text-center">No results found.</td></tr>';
                } else {
                    echo '<tr><td colspan="11" class="text-center">Please apply a filter to view letters.</td></tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php 
            $qs = get_query_string(['page']);
            $base_url = "export_excel.php?" . ($qs ? $qs . "&" : "");
            
            // Previous
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="'.$base_url.'page='.($page-1).'">Previous</a></li>';
            } else {
                echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
            }

            // Numbers (show max 10 links window)
            $start = max(1, $page - 4);
            $end = min($total_pages, $page + 5);
            
            for ($i = $start; $i <= $end; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo '<li class="page-item '.$active.'"><a class="page-link" href="'.$base_url.'page='.$i.'">'.$i.'</a></li>';
            }

            // Next
            if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="'.$base_url.'page='.($page+1).'">Next</a></li>';
            } else {
                echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
            }
            ?>
        </ul>
        <div class="text-center">Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_records); ?> of <?php echo $total_records; ?> entries</div>
    </nav>
    <?php endif; ?>

</div>

<!-- Date Time Picker JS -->
<!-- Note: jQuery already included in header or needs to be included here if header misses it. 
     Existing code had local jquery include. I will keep it to be safe. -->
<script type="text/javascript" src="js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/dtpicker/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/dtpicker/locales/bootstrap-datetimepicker.uk.js" charset="UTF-8"></script>

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

<?php 
include("includes/footer.php"); 
?>