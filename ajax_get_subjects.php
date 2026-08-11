<?php
session_start();
if(!isset($_SESSION["login_user"])){
    echo "<div class='alert alert-danger'>Unauthorized access.</div>";
    exit();
}

include('dbconnect.php');

$section_no = isset($_GET['section_no']) ? mysqli_real_escape_string($conn, $_GET['section_no']) : '';
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

// Role filter - we still need to restrict subjects if this is a Normal clerk who hacked the ajax call
$u_name = $_SESSION["login_user"];
$query_user = "SELECT acc_type, user_s_no FROM user_tbl WHERE user_name='$u_name'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);
$acc_type = $row_user['acc_type'];
$u_s_no = $row_user['user_s_no'];

$role_condition = "";
if($acc_type == 'N' && $u_s_no != '0') {
    $role_condition = " AND a.subject_no = '$u_s_no' ";
}

// Query subjects and letter counts for this section
$sql = "SELECT a.subject_no, COUNT(a.letter_no) as letter_count 
        FROM letter a
        WHERE a.section_no = '$section_no' $date_condition_letter $role_condition
        GROUP BY a.subject_no 
        ORDER BY a.subject_no ASC";

$result = mysqli_query($conn, $sql);

// Helper function to get counts for specific status
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

if($result && mysqli_num_rows($result) > 0) {
    echo '<div class="table-responsive">';
    echo '<table class="table table-hover table-bordered text-center">';
    echo '<thead class="thead-light">
            <tr>
                <th class="text-left">Subject Clerk / Subject No</th>
                <th>Total</th>
                <th><span class="badge badge-danger">NON</span></th>
                <th><span class="badge badge-info">REC</span></th>
                <th><span class="badge badge-warning">WOK</span></th>
                <th><span class="badge badge-success">CLD</span></th>
            </tr>
          </thead>';
    echo '<tbody>';
    
    while($row = mysqli_fetch_assoc($result)) {
        $subject_no = $row['subject_no'];
        $count = $row['letter_count'];
        
        // Find subject clerk name
        $clerk_name = "Subject " . $subject_no;
        $clerk_sql = "SELECT user_name FROM user_tbl WHERE user_sec_no = '$section_no' AND user_s_no = '$subject_no' AND acc_type='N' LIMIT 1";
        $clerk_res = mysqli_query($conn, $clerk_sql);
        if($clerk_res && mysqli_num_rows($clerk_res) > 0) {
            $clerk_row = mysqli_fetch_assoc($clerk_res);
            $clerk_name = $clerk_row['user_name'] . " (Sub: " . $subject_no . ")";
        }
        
        $c_non = getSubjectStatusCount($conn, 'NON', $date_condition_letter, $section_no, $subject_no);
        $c_rec = getSubjectStatusCount($conn, 'REC', $date_condition_letter, $section_no, $subject_no);
        $c_wok = getSubjectStatusCount($conn, 'WOK', $date_condition_letter, $section_no, $subject_no);
        $c_cld = getSubjectStatusCount($conn, 'CLD', $date_condition_letter, $section_no, $subject_no);
        
        echo "<tr>";
        echo "<td class='text-left align-middle'><i class='fas fa-user-circle text-secondary mr-2'></i> <strong>" . htmlspecialchars($clerk_name) . "</strong></td>";
        echo "<td class='align-middle font-weight-bold'>" . $count . "</td>";
        echo "<td class='align-middle text-danger font-weight-bold'>" . $c_non . "</td>";
        echo "<td class='align-middle text-info font-weight-bold'>" . $c_rec . "</td>";
        echo "<td class='align-middle text-warning font-weight-bold'>" . $c_wok . "</td>";
        echo "<td class='align-middle text-success font-weight-bold'>" . $c_cld . "</td>";
        echo "</tr>";
    }
    
    echo '</tbody></table></div>';
} else {
    echo "<div class='alert alert-info'>No subjects found for this section in the selected period.</div>";
}
?>
