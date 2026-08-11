<?php include("includes/header.php"); ?>
<?php
include('dbconnect.php');
include('pagination/function.php');
$damid = 14;

// ─── Role-based access ───────────────────────────────────────────────────────
$_u_name   = isset($_SESSION['login_user'])  ? $_SESSION['login_user']       : '';
$_u_sec_no = isset($_SESSION['user_sec_no']) ? (int)$_SESSION['user_sec_no'] : 0;
$_u_s_no   = isset($_SESSION['user_s_no'])   ? (int)$_SESSION['user_s_no']   : 0;
$acc_type  = 'A';
$role_where = '';
if ($_u_name) {
    $_res_role = mysqli_query($conn, "SELECT acc_type FROM user_tbl WHERE user_name='" . mysqli_real_escape_string($conn, $_u_name) . "'");
    if ($_res_role && $_row_role = mysqli_fetch_assoc($_res_role)) {
        $acc_type = $_row_role['acc_type'];
        if ($acc_type == 'H') $role_where = " AND a.section_no=$_u_sec_no";
        elseif ($acc_type == 'N') $role_where = " AND a.section_no=$_u_sec_no AND a.subject_no=$_u_s_no";
    }
}

// ─── GET params ──────────────────────────────────────────────────────────────
$fields = ['lno','recived_date_from','recived_date_to','letter_date_from','letter_date_to',
           'sender_no','sender','title','section_no','subject_no','reg_no'];
foreach ($fields as $f) { $$f = isset($_GET[$f]) ? trim($_GET[$f]) : ''; }

$type_filter      = isset($_GET['type'])      ? (array)$_GET['type']      : [];
$important_filter = isset($_GET['important']) ? (array)$_GET['important'] : [];

// ─── Build WHERE ─────────────────────────────────────────────────────────────
$has_search  = false;
$search_where = '';

if ($lno            != '') { $search_where .= " AND a.letter_no="       .(int)$lno; $has_search=true; }
if ($recived_date_from!='') { $search_where .= " AND a.recived_date >= '" .mysqli_real_escape_string($conn,$recived_date_from)."'"; $has_search=true; }
if ($recived_date_to  !='') { $search_where .= " AND a.recived_date <= '" .mysqli_real_escape_string($conn,$recived_date_to)."'";   $has_search=true; }
if ($letter_date_from !='') { $search_where .= " AND a.letter_date >= '"  .mysqli_real_escape_string($conn,$letter_date_from)."'";  $has_search=true; }
if ($letter_date_to   !='') { $search_where .= " AND a.letter_date <= '"  .mysqli_real_escape_string($conn,$letter_date_to)."'";    $has_search=true; }
if ($sender_no        !='') { $search_where .= " AND a.sender_no LIKE '%".mysqli_real_escape_string($conn,$sender_no)."%'";         $has_search=true; }
if ($sender           !='') { $search_where .= " AND a.sender LIKE '%"   .mysqli_real_escape_string($conn,$sender)."%'";            $has_search=true; }
if ($title            !='') { $search_where .= " AND a.title LIKE '%"    .mysqli_real_escape_string($conn,$title)."%'";             $has_search=true; }
if ($section_no       !='') { $search_where .= " AND a.section_no="      .(int)$section_no;                                         $has_search=true; }
if ($subject_no       !='') { $search_where .= " AND a.subject_no="      .(int)$subject_no;                                         $has_search=true; }
if ($reg_no           !='') { $search_where .= " AND a.reg_no="          .(int)$reg_no;                                             $has_search=true; }

if (!empty($type_filter)) {
    $safe_types = array_map(fn($t) => "'" . mysqli_real_escape_string($conn, $t) . "'", $type_filter);
    $search_where .= " AND a.type IN (" . implode(',', $safe_types) . ")";
    $has_search = true;
}
if (!empty($important_filter)) {
    $safe_imp = array_map(fn($i) => "'" . mysqli_real_escape_string($conn, $i) . "'", $important_filter);
    $search_where .= " AND a.important IN (" . implode(',', $safe_imp) . ")";
    $has_search = true;
}

// ─── CSV Export ──────────────────────────────────────────────────────────────
if (isset($_GET['export_csv']) && $has_search) {
    $base_sql_export = "FROM letter a
        LEFT JOIN (
            SELECT t1.l_no, t1.status FROM action_tbl t1
            INNER JOIN (SELECT l_no, MAX(`index`) as mx FROM action_tbl GROUP BY l_no) t2
            ON t1.l_no=t2.l_no AND t1.`index`=t2.mx
        ) max_b ON max_b.l_no = a.letter_no
        WHERE a.letter_no > 0 $role_where $search_where";

    $csv_res = mysqli_query($conn, "SELECT a.*, max_b.status as acton_tb_status $base_sql_export ORDER BY a.letter_no DESC");
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="letter_search_' . date('Ymd_His') . '.csv"');
    echo "\xEF\xBB\xBF"; // BOM for Excel UTF-8
    $out = fopen('php://output', 'w');
    fputcsv($out, ['#','Received Date','Letter Date','Sender','Sender No','Title','Section','Subject','Type','Reg No','Important','Status']);
    if ($csv_res) {
        while ($row = mysqli_fetch_assoc($csv_res)) {
            $status = $row['acton_tb_status'] ?: 'NON';
            $imp = strtolower($row['important']);
            $imp_label = 'සාමාන්‍ය';
            if ($imp == 'i') $imp_label = 'ඉතා හදිස්සි';
            elseif ($imp == 'r') $imp_label = 'සිහි කැඳවීම්';
            elseif ($imp == 'p') $imp_label = 'මහජන පැමිණිලි';
            
            fputcsv($out, [
                $row['letter_no'], $row['recived_date'], $row['letter_date'],
                $row['sender'], $row['sender_no'], $row['title'],
                $row['section_no'], $row['subject_no'], $row['type'],
                $row['reg_no'], $imp_label, $status
            ]);
        }
    }
    fclose($out);
    exit();
}

// ─── Pagination ──────────────────────────────────────────────────────────────
$limit      = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
if (!in_array($limit, [10,20,50,100])) $limit = 20;
$page       = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$startpoint = ($page - 1) * $limit;

$base_sql = "FROM letter a
    LEFT JOIN (
        SELECT t1.l_no, t1.status FROM action_tbl t1
        INNER JOIN (SELECT l_no, MAX(`index`) as mx FROM action_tbl GROUP BY l_no) t2
        ON t1.l_no=t2.l_no AND t1.`index`=t2.mx
    ) max_b ON max_b.l_no = a.letter_no
    WHERE a.letter_no > 0 $role_where $search_where";

$total_records = 0;
$result        = null;
if ($has_search) {
    $res_cnt       = mysqli_query($conn, "SELECT COUNT(*) as total $base_sql");
    $total_records = ($res_cnt && $r = mysqli_fetch_assoc($res_cnt)) ? (int)$r['total'] : 0;
    $result        = mysqli_query($conn, "SELECT a.*, max_b.status as acton_tb_status $base_sql ORDER BY a.letter_no DESC LIMIT $startpoint,$limit");
    $page1         = renderPagination($total_records, $limit, $page);
}

// Helper to keep checkbox state
function chkd($arr, $val) { return in_array($val, $arr) ? 'checked' : ''; }
?>

<!-- Custom styles -->
<style>
.search-card { border: none; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
.search-card .card-header { background: linear-gradient(135deg,#1a3c6e,#2563a8); border-radius: 14px 14px 0 0; padding: 16px 24px; }
.filter-group label.form-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
.filter-group .form-control { border-radius: 8px; border: 1.5px solid #e2e8f0; font-size: 13px; transition: border-color .2s; }
.filter-group .form-control:focus { border-color: #2563a8; box-shadow: 0 0 0 3px rgba(37,99,168,.1); }
.check-group { display: flex; flex-wrap: wrap; gap: 8px; }
.check-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
              border: 1.5px solid #e2e8f0; border-radius: 20px; cursor: pointer; font-size: 12px;
              transition: all .2s; background: #fff; user-select: none; }
.check-pill input { display: none; }
.check-pill:has(input:checked) { border-color: #2563a8; background: #eff6ff; color: #1e40af; font-weight: 600; }
.check-pill.imp:has(input:checked) { border-color: #dc2626; background: #fef2f2; color: #991b1b; }
.btn-search  { background: linear-gradient(135deg,#1a3c6e,#2563a8); color:#fff; border:none; border-radius:8px; padding: 10px 28px; font-weight:600; }
.btn-reset   { border-radius:8px; padding: 10px 20px; }
.btn-csv     { background: linear-gradient(135deg,#166534,#16a34a); color:#fff; border:none; border-radius:8px; padding: 8px 20px; font-size:13px; font-weight:600; }
.results-card { border:none; border-radius:14px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
.results-card .card-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 14px 14px 0 0; padding: 12px 20px; }
.table th { font-size:11px; text-transform:uppercase; letter-spacing:.6px; color:#64748b; font-weight:600; }
.status-badge { border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; }
.badge-NON { background:#fee2e2; color:#991b1b; }
.badge-REC { background:#dbeafe; color:#1e40af; }
.badge-WOK { background:#fef3c7; color:#92400e; }
.badge-CLD { background:#dcfce7; color:#166534; }
.date-range-group { display:flex; gap:8px; align-items:center; }
.date-range-group .form-control { flex:1; }
.date-range-sep { color:#94a3b8; font-size:12px; white-space:nowrap; }
</style>

<div class="container-fluid mt-4 mb-5">

    <!-- Search Form Card -->
    <div class="card search-card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-white"><i class="fas fa-search mr-2"></i>ලිපි සෙවීම</h5>
            <span class="text-white-50" style="font-size:12px;">Filter &amp; Search Letters</span>
        </div>
        <div class="card-body p-4">
            <form id="searchForm" action="search.php" method="GET">

                <div class="row filter-group">
                    <!-- Letter No -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">අනු අංකය</label>
                        <input type="number" name="lno" class="form-control" placeholder="e.g. 1234" value="<?php echo htmlspecialchars($lno); ?>">
                    </div>
                    <!-- Sender No -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ලිපියේ අංකය</label>
                        <input type="text" name="sender_no" class="form-control" placeholder="Letter reference no." value="<?php echo htmlspecialchars($sender_no); ?>">
                    </div>
                    <!-- Sender -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ලිපිය එවන්නා</label>
                        <input type="text" name="sender" class="form-control" placeholder="Sender name..." value="<?php echo htmlspecialchars($sender); ?>">
                    </div>
                    <!-- Title -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ලිපියේ මාතෘකාව</label>
                        <input type="text" name="title" class="form-control" placeholder="Title keywords..." value="<?php echo htmlspecialchars($title); ?>">
                    </div>
                </div>

                <div class="row filter-group">
                    <!-- Received Date Range -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-calendar-alt mr-1 text-primary"></i>ලැබුණ දිනය (සිට — දක්වා)</label>
                        <div class="date-range-group">
                            <input type="date" name="recived_date_from" class="form-control" value="<?php echo htmlspecialchars($recived_date_from); ?>">
                            <span class="date-range-sep">→</span>
                            <input type="date" name="recived_date_to" class="form-control" value="<?php echo htmlspecialchars($recived_date_to); ?>">
                        </div>
                    </div>
                    <!-- Letter Date Range -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-calendar-check mr-1 text-primary"></i>ලිපියේ දිනය (සිට — දක්වා)</label>
                        <div class="date-range-group">
                            <input type="date" name="letter_date_from" class="form-control" value="<?php echo htmlspecialchars($letter_date_from); ?>">
                            <span class="date-range-sep">→</span>
                            <input type="date" name="letter_date_to" class="form-control" value="<?php echo htmlspecialchars($letter_date_to); ?>">
                        </div>
                    </div>
                    <!-- Section & Subject -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">අංශ අංකය</label>
                        <input type="number" name="section_no" class="form-control" placeholder="Section no." value="<?php echo htmlspecialchars($section_no); ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">විෂය අංකය</label>
                        <input type="number" name="subject_no" class="form-control" placeholder="Subject no." value="<?php echo htmlspecialchars($subject_no); ?>">
                    </div>
                </div>

                <div class="row filter-group">
                    <!-- Letter Type -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-tags mr-1 text-primary"></i>ලිපි වර්ගය</label>
                        <div class="check-group">
                            <label class="check-pill">
                                <input type="checkbox" name="type[]" value="N" <?php echo chkd($type_filter,'N'); ?>>
                                <i class="fas fa-envelope"></i> සාමාන්‍ය (N)
                            </label>
                            <label class="check-pill">
                                <input type="checkbox" name="type[]" value="R" <?php echo chkd($type_filter,'R'); ?>>
                                <i class="fas fa-registered"></i> ලියාපදිංචි (R)
                            </label>
                            <label class="check-pill">
                                <input type="checkbox" name="type[]" value="H" <?php echo chkd($type_filter,'H'); ?>>
                                <i class="fas fa-hand-paper"></i> අතින් (H)
                            </label>
                            <label class="check-pill">
                                <input type="checkbox" name="type[]" value="F" <?php echo chkd($type_filter,'F'); ?>>
                                <i class="fas fa-fax"></i> ෆැක්ස් (F)
                            </label>
                            <label class="check-pill">
                                <input type="checkbox" name="type[]" value="E" <?php echo chkd($type_filter,'E'); ?>>
                                <i class="fas fa-at"></i> Email (E)
                            </label>
                        </div>
                    </div>
                    <!-- Importance -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="fas fa-star mr-1 text-warning"></i>වැදගත් බව</label>
                        <div class="check-group">
                            <label class="check-pill">
                                <input type="checkbox" name="important[]" value="n" <?php echo chkd($important_filter,'n'); ?>>
                                <i class="fas fa-minus-circle text-muted"></i> සාමාන්‍ය
                            </label>
                            <label class="check-pill imp">
                                <input type="checkbox" name="important[]" value="i" <?php echo chkd($important_filter,'i'); ?>>
                                <i class="fas fa-exclamation-circle text-danger"></i> ඉතා හදිස්සි
                            </label>
                            <label class="check-pill">
                                <input type="checkbox" name="important[]" value="r" <?php echo chkd($important_filter,'r'); ?>>
                                <i class="fas fa-bell text-warning"></i> සිහි කැඳවීම්
                            </label>
                            <label class="check-pill">
                                <input type="checkbox" name="important[]" value="p" <?php echo chkd($important_filter,'p'); ?>>
                                <i class="fas fa-users text-info"></i> මහජන පැමිණිලි
                            </label>
                        </div>
                    </div>
                    <!-- Reg No -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">ලියාපදිංචි අංකය</label>
                        <input type="number" name="reg_no" class="form-control" placeholder="Reg no." value="<?php echo htmlspecialchars($reg_no); ?>">
                    </div>
                    <!-- Items per page -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">ප්‍රතිඵල ගණන</label>
                        <select name="limit" class="form-control">
                            <?php foreach([10,20,50,100] as $l): ?>
                                <option value="<?php echo $l; ?>" <?php echo ($limit==$l)?'selected':''; ?>><?php echo $l; ?> per page</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex align-items-center gap-2 mt-2">
                    <button type="submit" class="btn btn-search mr-2">
                        <i class="fas fa-search mr-1"></i> සොයන්න
                    </button>
                    <a href="search.php" class="btn btn-outline-secondary btn-reset mr-2">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                    <?php if($has_search && $total_records > 0): ?>
                    <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>&export_csv=1"
                       class="btn btn-csv">
                        <i class="fas fa-file-csv mr-1"></i> CSV Export
                    </a>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>

    <!-- Results -->
    <?php if ($has_search): ?>
    <div class="card results-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="font-weight-bold">
                <i class="fas fa-list mr-1 text-primary"></i>
                ප්‍රතිඵල: <strong class="text-primary"><?php echo $total_records; ?></strong> records found
            </span>
            <?php if($total_records > 0): ?>
            <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>&export_csv=1" class="btn btn-csv btn-sm">
                <i class="fas fa-file-csv mr-1"></i> Export CSV
            </a>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="resultsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>ලැබුණු දිනය</th>
                            <th>ලිපි දිනය</th>
                            <th>එවන්නා</th>
                            <th>ලිපි අංකය</th>
                            <th>මාතෘකාව</th>
                            <th>අංශ</th>
                            <th>විෂය</th>
                            <th>වර්ගය</th>
                            <th>වැදගත්</th>
                            <th>තත්වය</th>
                            <th colspan="4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $l_id_no = $row['letter_no'];
                        $acton_tb_status = $row['acton_tb_status'] ?: 'NON';
                        $row_class = '';
                        if ($acton_tb_status=='NON') $row_class='table-danger';
                        elseif ($acton_tb_status=='REC') $row_class='table-info';
                        elseif ($acton_tb_status=='WOK') $row_class='table-warning';
                        elseif ($acton_tb_status=='CLD') $row_class='table-success';

                        $imp = strtolower($row['important']);
                        $imp_badge = '';
                        if ($imp == 'n' || empty($imp)) {
                            $imp_badge = '<span class="badge badge-light" style="font-size:10px;">සාමාන්‍ය</span>';
                        } elseif ($imp == 'i') {
                            $imp_badge = '<span class="badge badge-danger" style="font-size:10px;">⚠ ඉතා හදිස්සි</span>';
                        } elseif ($imp == 'r') {
                            $imp_badge = '<span class="badge badge-warning" style="font-size:10px;"><i class="fas fa-bell"></i> සිහි කැඳවීම්</span>';
                        } elseif ($imp == 'p') {
                            $imp_badge = '<span class="badge badge-info" style="font-size:10px;"><i class="fas fa-users"></i> මහජන පැමිණිලි</span>';
                        } else {
                            $imp_badge = '<span class="badge badge-light" style="font-size:10px;">'.htmlspecialchars($imp).'</span>';
                        }

                        $page_no = $page;
                        $page1_rendered = $page1 ?? '';
                        // back link params
                        $back_lno=$row['letter_no']; $back_recived_date=$row['recived_date']; $back_letter_date=$row['letter_date'];
                        $back_sender_no=$row['sender_no']; $back_sender=$row['sender']; $back_title=$row['title'];
                        $back_section_no=$row['section_no']; $back_subject_no=$row['subject_no']; $back_type=$row['type']; $back_reg_no=$row['reg_no'];
                    ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><strong><?php echo $l_id_no; ?></strong></td>
                        <td><?php echo $row['recived_date']; ?></td>
                        <td><?php echo $row['letter_date']; ?></td>
                        <td style="word-break:break-word; max-width:130px;"><?php echo htmlspecialchars($row['sender']); ?></td>
                        <td><?php echo htmlspecialchars($row['sender_no']); ?></td>
                        <td style="word-break:break-word; max-width:160px;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo $row['section_no']; ?></td>
                        <td><?php echo $row['subject_no']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $imp_badge; ?></td>
                        <td><span class="status-badge badge-<?php echo $acton_tb_status; ?>"><?php echo $acton_tb_status; ?></span></td>
                        <?php include('includes/buttons.php'); ?>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center p-5 text-muted">
                <i class="fas fa-search" style="font-size:48px;opacity:.2;display:block;margin-bottom:12px;"></i>
                කණගාටුයි. ඔබ සෙවූ තොරතුර දත්ත ගබඩාවේ නොමැත.
            </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($page1)): ?>
        <div class="card-footer bg-white">
            <?php echo $page1; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info border-0 rounded-3 d-flex align-items-center" style="border-left: 4px solid #2563a8 !important; border-radius:10px;">
        <i class="fas fa-info-circle mr-3 text-primary" style="font-size:22px;"></i>
        <span>කරුණාකර සෙවීම සඳහා අවශ්‍ය තොරතුරු ඇතුලත්කර <strong>සොයන්න</strong> ක්ලික් කරන්න.</span>
    </div>
    <?php endif; ?>

</div>

<?php include("includes/footer.php"); ?>
