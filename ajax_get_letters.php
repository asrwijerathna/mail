<?php
session_start();
if(!isset($_SESSION["login_user"])){
    echo "<div class='alert alert-danger'>Unauthorized access.</div>";
    exit();
}

include('dbconnect.php');

$section_no = isset($_GET['section_no']) ? mysqli_real_escape_string($conn, $_GET['section_no']) : '';
$subject_no = isset($_GET['subject_no']) ? mysqli_real_escape_string($conn, $_GET['subject_no']) : '';
$time_filter = isset($_GET['time_filter']) ? $_GET['time_filter'] : 'weekly';

// Apply time filter
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

// Role filter
$u_name = $_SESSION["login_user"];
$query_user = "SELECT acc_type, user_sec_no, user_s_no FROM user_tbl WHERE user_name='$u_name'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);
$acc_type = $row_user['acc_type'];
$u_sec_no = $row_user['user_sec_no'];
$u_s_no = $row_user['user_s_no'];

// Basic security check to ensure they can't view letters outside their scope
if($acc_type == 'N' && ($u_sec_no != $section_no || $u_s_no != $subject_no)) {
    echo "<div class='alert alert-danger'>You do not have permission to view letters for this subject.</div>";
    exit();
} elseif($acc_type == 'H' && $u_sec_no != $section_no) {
    echo "<div class='alert alert-danger'>You do not have permission to view letters for this section.</div>";
    exit();
}

// Query counts for NON, REC, WOK, CLD for this specific subject
function getSubjectStatusCount($conn, $status, $date_cond, $sec_no, $sub_no) {
    if ($status == 'NON') {
        $sql = "SELECT COUNT(*) as count FROM letter a 
                WHERE a.letter_no NOT IN (SELECT l_no FROM action_tbl) 
                AND a.section_no = '$sec_no' AND a.subject_no = '$sub_no' $date_cond";
    } else {
        $sql = "SELECT COUNT(*) as count FROM letter a
                INNER JOIN action_tbl b ON b.l_no = a.letter_no
                INNER JOIN (
                    SELECT l_no, MAX(`index`) as max_index
                    FROM action_tbl
                    GROUP BY l_no
                ) max_b ON b.l_no = max_b.l_no AND b.`index` = max_b.max_index
                WHERE b.status = '$status' AND a.section_no = '$sec_no' AND a.subject_no = '$sub_no' $date_cond";
    }
    $res = mysqli_query($conn, $sql);
    return ($res) ? mysqli_fetch_assoc($res)['count'] : 0;
}

$c_non = getSubjectStatusCount($conn, 'NON', $date_condition_letter, $section_no, $subject_no);
$c_rec = getSubjectStatusCount($conn, 'REC', $date_condition_letter, $section_no, $subject_no);
$c_wok = getSubjectStatusCount($conn, 'WOK', $date_condition_letter, $section_no, $subject_no);
$c_cld = getSubjectStatusCount($conn, 'CLD', $date_condition_letter, $section_no, $subject_no);

echo '<div class="row text-center mt-3 mb-4">';

// NON Card
echo '
<div class="col-md-3 col-6 mb-3">
    <div class="card bg-danger text-white shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body py-4">
            <h1 class="display-4 font-weight-bold mb-2">' . $c_non . '</h1>
            <h6 class="text-uppercase mb-0 font-weight-bold" style="letter-spacing: 1px;"><i class="fas fa-inbox mr-1"></i> නව ලිපි (NON)</h6>
        </div>
    </div>
</div>';

// REC Card
echo '
<div class="col-md-3 col-6 mb-3">
    <div class="card bg-info text-white shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body py-4">
            <h1 class="display-4 font-weight-bold mb-2">' . $c_rec . '</h1>
            <h6 class="text-uppercase mb-0 font-weight-bold" style="letter-spacing: 1px;"><i class="fas fa-check-circle mr-1"></i> භාරගත් (REC)</h6>
        </div>
    </div>
</div>';

// WOK Card
echo '
<div class="col-md-3 col-6 mb-3">
    <div class="card bg-warning text-dark shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body py-4">
            <h1 class="display-4 font-weight-bold mb-2">' . $c_wok . '</h1>
            <h6 class="text-uppercase mb-0 font-weight-bold" style="letter-spacing: 1px;"><i class="fas fa-spinner mr-1"></i> වැඩ ආරම්භකල (WOK)</h6>
        </div>
    </div>
</div>';

// CLD Card
echo '
<div class="col-md-3 col-6 mb-3">
    <div class="card bg-success text-white shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body py-4">
            <h1 class="display-4 font-weight-bold mb-2">' . $c_cld . '</h1>
            <h6 class="text-uppercase mb-0 font-weight-bold" style="letter-spacing: 1px;"><i class="fas fa-check-double mr-1"></i> වැඩ අවසන් (CLD)</h6>
        </div>
    </div>
</div>';

echo '</div>';
?>
