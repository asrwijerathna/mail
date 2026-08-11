<?php include("includes/header.php"); ?>
<?php
// Secure code: Check if user is logged in
if(!isset($_SESSION["login_user"])){
    header("location:login_form.php");
    exit();
}

include ('dbconnect.php');

$time_filter = isset($_GET['time_filter']) ? $_GET['time_filter'] : 'weekly';

$date_condition_letter = "";
if($time_filter == 'daily'){
    $date_condition_letter = " AND DATE(a.recived_date) = CURDATE() ";
} elseif($time_filter == 'weekly'){
    $date_condition_letter = " AND a.recived_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ";
} elseif($time_filter == 'monthly'){
    $date_condition_letter = " AND MONTH(a.recived_date) = MONTH(CURDATE()) AND YEAR(a.recived_date) = YEAR(CURDATE()) ";
} elseif($time_filter == 'yearly'){
    $date_condition_letter = " AND YEAR(a.recived_date) = YEAR(CURDATE()) ";
}

$u_name = $_SESSION["login_user"];
$query_user = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);
$acc_type = $row_user['acc_type'];

$u_sec_no = (isset($_SESSION["user_sec_no"])) ? $_SESSION["user_sec_no"] : '';
$u_s_no = (isset($_SESSION["user_s_no"])) ? $_SESSION["user_s_no"] : '';

$role_condition_letter = "";
if($acc_type == 'A' || ($u_sec_no == '0' && $u_s_no == '0')){
    // Admin or Section=0 & Subject=0: See the entire office (no filter)
    $role_condition_letter = "";
} elseif($acc_type == 'H' || $u_s_no == '0'){
    // Section Head or Subject=0: See their specific section
    $role_condition_letter = " AND a.section_no = '$u_sec_no' ";
} else {
    // Normal Subject Clerk: See only their specific subject
    $role_condition_letter = " AND a.section_no = '$u_sec_no' AND a.subject_no = '$u_s_no' ";
}

// Top widgets counts (Filtered by time and role)
$sql_non = "SELECT COUNT(*) as count FROM letter a WHERE a.letter_no NOT IN (SELECT l_no FROM action_tbl) $date_condition_letter $role_condition_letter";
$res_non = mysqli_query($conn, $sql_non);
$count_non = ($res_non) ? mysqli_fetch_assoc($res_non)['count'] : 0;

function getDashboardStatusCount($conn, $status, $date_cond, $role_cond) {
    $sql = "SELECT COUNT(*) as count FROM letter a
            INNER JOIN action_tbl b ON b.l_no = a.letter_no
            INNER JOIN (
                SELECT l_no, MAX(`index`) as max_index
                FROM action_tbl
                GROUP BY l_no
            ) max_b ON b.l_no = max_b.l_no AND b.`index` = max_b.max_index
            WHERE b.status = '$status' $date_cond $role_cond";
    $res = mysqli_query($conn, $sql);
    return ($res) ? mysqli_fetch_assoc($res)['count'] : 0;
}

$count_rec = getDashboardStatusCount($conn, 'REC', $date_condition_letter, $role_condition_letter);
$count_wok = getDashboardStatusCount($conn, 'WOK', $date_condition_letter, $role_condition_letter);
$count_cld = getDashboardStatusCount($conn, 'CLD', $date_condition_letter, $role_condition_letter);

// Section Group Query
$query_combined = "
    SELECT a.section_no, COUNT(*) as count 
    FROM letter a
    WHERE 1=1 $date_condition_letter $role_condition_letter
    GROUP BY a.section_no 
    ORDER BY a.section_no ASC
";
$result_combined = mysqli_query($conn, $query_combined);
?>

<div class="container main-container" style="padding-top: 40px; padding-bottom: 40px;">
    
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-4 mb-4 border-bottom">
        <h1 class="h2">Dashboard Overview</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="?time_filter=daily" class="btn btn-sm <?php echo ($time_filter == 'daily') ? 'btn-primary' : 'btn-outline-primary'; ?>">Daily</a>
                <a href="?time_filter=weekly" class="btn btn-sm <?php echo ($time_filter == 'weekly') ? 'btn-primary' : 'btn-outline-primary'; ?>">Weekly</a>
                <a href="?time_filter=monthly" class="btn btn-sm <?php echo ($time_filter == 'monthly') ? 'btn-primary' : 'btn-outline-primary'; ?>">Monthly</a>
                <a href="?time_filter=yearly" class="btn btn-sm <?php echo ($time_filter == 'yearly') ? 'btn-primary' : 'btn-outline-primary'; ?>">Yearly</a>
                <a href="?time_filter=all" class="btn btn-sm <?php echo ($time_filter == 'all') ? 'btn-primary' : 'btn-outline-primary'; ?>">All Time</a>
            </div>
            <a href="print_report.php?time_filter=<?php echo urlencode($time_filter); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-file-pdf mr-1"></i> Print Report
            </a>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="row mb-5">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-red" onclick="window.location.href='mail_view_non.php'" style="cursor: pointer;">
                <h3><?php echo $count_non; ?></h3>
                <div class="label">නව ලිපි (NON)</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-orange" onclick="window.location.href='mail_view_rec.php'" style="cursor: pointer;">
                <h3><?php echo $count_rec; ?></h3>
                <div class="label">භාරගත් (REC)</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-blue" onclick="window.location.href='mail_view_wok.php'" style="cursor: pointer;">
                <h3><?php echo $count_wok; ?></h3>
                <div class="label">වැඩ ආරම්භකල (WOK)</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card bg-green" onclick="window.location.href='mail_view_cld.php'" style="cursor: pointer;">
                <h3><?php echo $count_cld; ?></h3>
                <div class="label">වැඩ අවසන් (CLD)</div>
            </div>
        </div>
    </div>

    <!-- Sections Overview -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <strong><i class="fas fa-building"></i> Office Sections (Click for Details)</strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Section No</th>
                                    <th class="text-right">Total Letters</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($result_combined && mysqli_num_rows($result_combined) > 0){
                                    while($row = mysqli_fetch_assoc($result_combined)){
                                        $sec = htmlspecialchars($row['section_no']);
                                        echo "<tr style='cursor: pointer;' onclick='loadSubjects(\"$sec\", \"$time_filter\")'>";
                                        echo "<td class='align-middle'><strong>Section " . $sec . "</strong></td>";
                                        echo "<td class='text-right align-middle'><span class='badge badge-primary' style='font-size: 0.9em; padding: 8px 12px;'>" . $row['count'] . "</span></td>";
                                        echo "<td class='text-center align-middle'><button class='btn btn-sm btn-outline-info'><i class='fas fa-search-plus'></i> View Subjects</button></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center text-muted p-4'>No data available for the selected period.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Modal Shell -->
<div class="modal fade" id="dynamicModal" tabindex="-1" role="dialog" aria-labelledby="dynamicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="dynamicModalLabel">Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="dynamicModalBody">
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading details...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="modalBackButton" class="btn btn-secondary d-none" onclick="goBackToSubjects()">Back to Subjects</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
let currentSection = "";
let currentTimeFilter = "";

function loadSubjects(sectionNo, timeFilter) {
    currentSection = sectionNo;
    currentTimeFilter = timeFilter;
    
    $('#dynamicModalLabel').text("Section " + sectionNo + " - Subjects");
    $('#modalBackButton').addClass('d-none');
    $('#dynamicModalBody').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading subjects...</p></div>');
    
    if (!$('#dynamicModal').hasClass('show')) {
        $('#dynamicModal').modal('show');
    }
    
    $.ajax({
        url: 'ajax_get_subjects.php',
        type: 'GET',
        data: { section_no: sectionNo, time_filter: timeFilter },
        success: function(response) {
            $('#dynamicModalBody').html(response);
        },
        error: function() {
            $('#dynamicModalBody').html('<div class="alert alert-danger">Error loading subjects. Please try again.</div>');
        }
    });
}

function loadLetters(sectionNo, subjectNo) {
    $('#dynamicModalLabel').text("Section " + sectionNo + " - Subject " + subjectNo + " Letters");
    $('#modalBackButton').removeClass('d-none');
    $('#dynamicModalBody').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading letters...</p></div>');
    
    $.ajax({
        url: 'ajax_get_letters.php',
        type: 'GET',
        data: { section_no: sectionNo, subject_no: subjectNo, time_filter: currentTimeFilter },
        success: function(response) {
            $('#dynamicModalBody').html(response);
        },
        error: function() {
            $('#dynamicModalBody').html('<div class="alert alert-danger">Error loading letters. Please try again.</div>');
        }
    });
}

function goBackToSubjects() {
    loadSubjects(currentSection, currentTimeFilter);
}
</script>

<?php include("includes/footer.php"); ?>
