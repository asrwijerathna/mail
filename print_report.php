<?php
session_start();
if(!isset($_SESSION["login_user"])){
    header("location:login_form.php");
    exit();
}

include('dbconnect.php');

$time_filter = isset($_GET['time_filter']) ? $_GET['time_filter'] : 'weekly';

// Time filter condition
$date_condition = "";
$period_label = "";
switch($time_filter) {
    case 'daily':
        $date_condition = " AND DATE(a.recived_date) = CURDATE() ";
        $period_label = "Daily Report — " . date('d F Y');
        break;
    case 'weekly':
        $date_condition = " AND a.recived_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ";
        $period_label = "Weekly Report — " . date('d F Y', strtotime('-7 days')) . " to " . date('d F Y');
        break;
    case 'monthly':
        $date_condition = " AND MONTH(a.recived_date) = MONTH(CURDATE()) AND YEAR(a.recived_date) = YEAR(CURDATE()) ";
        $period_label = "Monthly Report — " . date('F Y');
        break;
    case 'yearly':
        $date_condition = " AND YEAR(a.recived_date) = YEAR(CURDATE()) ";
        $period_label = "Yearly Report — " . date('Y');
        break;
    default:
        $date_condition = "";
        $period_label = "All Time Report";
        break;
}

// Role filter
$u_name = $_SESSION["login_user"];
$query_user = "SELECT acc_type, user_sec_no, user_s_no FROM user_tbl WHERE user_name='$u_name'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);
$acc_type = $row_user['acc_type'];
$u_sec_no = $row_user['user_sec_no'];
$u_s_no = $row_user['user_s_no'];

$role_condition = "";
if($acc_type == 'N' && $u_s_no != '0') {
    $role_condition = " AND a.section_no = '$u_sec_no' AND a.subject_no = '$u_s_no' ";
} elseif($acc_type == 'H' && $u_sec_no != '0') {
    $role_condition = " AND a.section_no = '$u_sec_no' ";
}

// Get institution name
$inst_name = "Department of Local Government (NWP)";
$sql_inst = "SELECT * FROM institute_details LIMIT 1";
$res_inst = mysqli_query($conn, $sql_inst);
if($res_inst && mysqli_num_rows($res_inst) > 0){
    $row_inst = mysqli_fetch_assoc($res_inst);
    if(!empty($row_inst['institute_name'])) $inst_name = $row_inst['institute_name'];
}

// Helper: get status count for a specific subject
function getCount($conn, $status, $date_cond, $role_cond, $sec_no, $sub_no) {
    if ($status == 'NON') {
        $sql = "SELECT COUNT(*) as c FROM letter a
                WHERE NOT EXISTS (SELECT 1 FROM action_tbl b WHERE b.l_no = a.letter_no)
                AND a.section_no = '$sec_no' AND a.subject_no = '$sub_no' $date_cond";
    } else {
        $sql = "SELECT COUNT(*) as c FROM letter a
                INNER JOIN action_tbl b ON b.l_no = a.letter_no
                INNER JOIN (SELECT l_no, MAX(`index`) as mx FROM action_tbl GROUP BY l_no) m
                    ON b.l_no = m.l_no AND b.`index` = m.mx
                WHERE b.status = '$status' AND a.section_no = '$sec_no' AND a.subject_no = '$sub_no' $date_cond";
    }
    $r = mysqli_query($conn, $sql);
    return ($r) ? (int)mysqli_fetch_assoc($r)['c'] : 0;
}

// Query: sections and subjects
$sql = "SELECT DISTINCT a.section_no, a.subject_no
        FROM letter a
        WHERE 1=1 $date_condition $role_condition
        ORDER BY a.section_no ASC, a.subject_no ASC";
$result = mysqli_query($conn, $sql);

// Build report data grouped by section
$report_data = [];
$section_totals = [];

if($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $sec = $row['section_no'];
        $sub = $row['subject_no'];

        $non = getCount($conn, 'NON', $date_condition, $role_condition, $sec, $sub);
        $rec = getCount($conn, 'REC', $date_condition, $role_condition, $sec, $sub);
        $wok = getCount($conn, 'WOK', $date_condition, $role_condition, $sec, $sub);
        $cld = getCount($conn, 'CLD', $date_condition, $role_condition, $sec, $sub);
        $total = $non + $rec + $wok + $cld;

        // Get clerk name
        $clerk_name = "Subject $sub";
        $cr = mysqli_query($conn, "SELECT user_name FROM user_tbl WHERE user_sec_no='$sec' AND user_s_no='$sub' AND acc_type='N' LIMIT 1");
        if($cr && mysqli_num_rows($cr) > 0) {
            $cdata = mysqli_fetch_assoc($cr);
            $clerk_name = $cdata['user_name'];
        }

        $report_data[$sec][] = compact('sub','clerk_name','non','rec','wok','cld','total');

        if(!isset($section_totals[$sec])) $section_totals[$sec] = ['non'=>0,'rec'=>0,'wok'=>0,'cld'=>0,'total'=>0];
        $section_totals[$sec]['non'] += $non;
        $section_totals[$sec]['rec'] += $rec;
        $section_totals[$sec]['wok'] += $wok;
        $section_totals[$sec]['cld'] += $cld;
        $section_totals[$sec]['total'] += $total;
    }
}

// Grand totals
$grand = ['non'=>0,'rec'=>0,'wok'=>0,'cld'=>0,'total'=>0];
foreach($section_totals as $st) {
    $grand['non']   += $st['non'];
    $grand['rec']   += $st['rec'];
    $grand['wok']   += $st['wok'];
    $grand['cld']   += $st['cld'];
    $grand['total'] += $st['total'];
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Report — <?php echo htmlspecialchars($period_label); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #1a202c;
            font-size: 13px;
        }

        .page-wrapper {
            max-width: 960px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        /* ── HEADER ── */
        .report-header {
            background: linear-gradient(135deg, #1a3c6e 0%, #2563a8 60%, #1d8cf8 100%);
            color: #fff;
            padding: 32px 40px 24px;
            position: relative;
            overflow: hidden;
        }
        .report-header::after {
            content: '';
            position: absolute;
            right: -40px; top: -40px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .report-header .logo-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
        }
        .report-header .logo-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .report-header h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.3;
        }
        .report-header .sub-title {
            font-size: 13px;
            opacity: 0.85;
            margin-top: 2px;
        }
        .report-meta {
            display: flex;
            gap: 30px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.2);
            font-size: 12px;
            opacity: 0.9;
        }
        .report-meta span i { margin-right: 5px; opacity: 0.8; }

        /* ── SUMMARY CARDS ── */
        .summary-section {
            background: #f7f9fc;
            padding: 20px 40px;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-cards {
            display: flex;
            gap: 14px;
        }
        .s-card {
            flex: 1;
            border-radius: 10px;
            padding: 14px 18px;
            text-align: center;
            color: #fff;
        }
        .s-card .s-num { font-size: 28px; font-weight: 700; line-height: 1; }
        .s-card .s-lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; opacity: 0.9; }
        .c-total { background: linear-gradient(135deg,#4a5568,#2d3748); }
        .c-non   { background: linear-gradient(135deg,#e53e3e,#c53030); }
        .c-rec   { background: linear-gradient(135deg,#3182ce,#2b6cb0); }
        .c-wok   { background: linear-gradient(135deg,#d69e2e,#b7791f); }
        .c-cld   { background: linear-gradient(135deg,#38a169,#276749); }

        /* ── BODY ── */
        .report-body {
            padding: 28px 40px 40px;
        }

        .section-block {
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }
        .section-title {
            background: linear-gradient(90deg, #1a3c6e, #2563a8);
            color: #fff;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title span.sec-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background: #edf2f7;
            color: #4a5568;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 9px 14px;
            border-bottom: 2px solid #cbd5e0;
            text-align: center;
        }
        thead th:first-child { text-align: left; }
        tbody tr { border-bottom: 1px solid #edf2f7; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f7fafc; }
        tbody td {
            padding: 9px 14px;
            font-size: 12px;
            text-align: center;
            color: #4a5568;
        }
        tbody td:first-child { text-align: left; color: #2d3748; font-weight: 500; }

        .val-total { font-weight: 700; color: #2d3748; }
        .val-non   { font-weight: 600; color: #c53030; }
        .val-rec   { font-weight: 600; color: #2b6cb0; }
        .val-wok   { font-weight: 600; color: #b7791f; }
        .val-cld   { font-weight: 600; color: #276749; }

        /* Section subtotal row */
        .subtotal-row td {
            background: #ebf4ff;
            font-weight: 700;
            color: #1a3c6e;
            border-top: 2px solid #bee3f8;
            font-size: 12px;
        }

        /* Grand total */
        .grand-total-block {
            margin-top: 24px;
            border: 2px solid #1a3c6e;
            border-radius: 10px;
            overflow: hidden;
        }
        .grand-total-block .grand-header {
            background: #1a3c6e;
            color: #fff;
            padding: 10px 18px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .grand-total-block table thead th {
            background: #e8eef7;
        }
        .grand-total-block table tbody td {
            font-weight: 700;
            font-size: 13px;
        }

        /* ── FOOTER ── */
        .report-footer {
            background: #f7f9fc;
            border-top: 1px solid #e2e8f0;
            padding: 14px 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #718096;
        }

        /* ── PRINT BUTTON (screen only) ── */
        .print-bar {
            text-align: center;
            padding: 16px;
            background: #ebf4ff;
            border-bottom: 1px solid #bee3f8;
        }
        .print-bar button {
            background: linear-gradient(135deg, #1a3c6e, #2563a8);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 28px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }
        .print-bar button:hover { opacity: 0.88; }

        /* ── PRINT MEDIA ── */
        @media print {
            body { background: #fff; }
            .page-wrapper { box-shadow: none; border-radius: 0; margin: 0; max-width: 100%; }
            .print-bar { display: none; }
            .section-block { break-inside: avoid; }
            .report-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .section-title  { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .s-card         { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .grand-total-block .grand-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .subtotal-row td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <!-- Print Button (hidden when printing) -->
    <div class="print-bar">
        <button onclick="window.print()"><i class="fas fa-print mr-2"></i> Print / Save as PDF</button>
    </div>

    <!-- Header -->
    <div class="report-header">
        <div class="logo-row">
            <div class="logo-icon"><i class="fas fa-envelope-open-text"></i></div>
            <div>
                <h1><?php echo htmlspecialchars($inst_name); ?></h1>
                <div class="sub-title">Mail Management System — Official Report</div>
            </div>
        </div>
        <div class="report-meta">
            <span><i class="fas fa-calendar-alt"></i><?php echo htmlspecialchars($period_label); ?></span>
            <span><i class="fas fa-clock"></i>Generated: <?php echo date('d F Y, h:i A'); ?></span>
            <span><i class="fas fa-user"></i>By: <?php echo htmlspecialchars($u_name); ?></span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-section">
        <div class="summary-cards">
            <div class="s-card c-total">
                <div class="s-num"><?php echo $grand['total']; ?></div>
                <div class="s-lbl">Total Letters</div>
            </div>
            <div class="s-card c-non">
                <div class="s-num"><?php echo $grand['non']; ?></div>
                <div class="s-lbl">NON (නව)</div>
            </div>
            <div class="s-card c-rec">
                <div class="s-num"><?php echo $grand['rec']; ?></div>
                <div class="s-lbl">REC (භාරගත්)</div>
            </div>
            <div class="s-card c-wok">
                <div class="s-num"><?php echo $grand['wok']; ?></div>
                <div class="s-lbl">WOK (ක්‍රියාත්මක)</div>
            </div>
            <div class="s-card c-cld">
                <div class="s-num"><?php echo $grand['cld']; ?></div>
                <div class="s-lbl">CLD (අවසන්)</div>
            </div>
        </div>
    </div>

    <!-- Report Body -->
    <div class="report-body">

        <?php if(empty($report_data)): ?>
            <div style="text-align:center; padding:40px; color:#718096;">
                <i class="fas fa-inbox" style="font-size:48px; margin-bottom:16px; display:block; opacity:0.3;"></i>
                No data available for the selected period.
            </div>
        <?php else: ?>
            <?php foreach($report_data as $sec_no => $subjects): ?>
            <div class="section-block">
                <div class="section-title">
                    <i class="fas fa-building"></i>
                    Section <?php echo htmlspecialchars($sec_no); ?>
                    <span class="sec-badge"><?php echo count($subjects); ?> Subject<?php echo count($subjects) > 1 ? 's' : ''; ?></span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th style="width:35%">Subject Clerk</th>
                            <th>Subject No</th>
                            <th>Total</th>
                            <th style="color:#c53030">NON</th>
                            <th style="color:#2b6cb0">REC</th>
                            <th style="color:#b7791f">WOK</th>
                            <th style="color:#276749">CLD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($subjects as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['clerk_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['sub']); ?></td>
                            <td class="val-total"><?php echo $s['total']; ?></td>
                            <td class="val-non"><?php echo $s['non']; ?></td>
                            <td class="val-rec"><?php echo $s['rec']; ?></td>
                            <td class="val-wok"><?php echo $s['wok']; ?></td>
                            <td class="val-cld"><?php echo $s['cld']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <!-- Section Sub-Total -->
                        <tr class="subtotal-row">
                            <td colspan="2"><i class="fas fa-sigma mr-1"></i> Section <?php echo htmlspecialchars($sec_no); ?> Sub-Total</td>
                            <td><?php echo $section_totals[$sec_no]['total']; ?></td>
                            <td><?php echo $section_totals[$sec_no]['non']; ?></td>
                            <td><?php echo $section_totals[$sec_no]['rec']; ?></td>
                            <td><?php echo $section_totals[$sec_no]['wok']; ?></td>
                            <td><?php echo $section_totals[$sec_no]['cld']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>

            <!-- Grand Total -->
            <div class="grand-total-block">
                <div class="grand-header"><i class="fas fa-table mr-2"></i>Grand Total — All Sections</div>
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Total Letters</th>
                            <th style="color:#c53030">NON</th>
                            <th style="color:#2b6cb0">REC</th>
                            <th style="color:#b7791f">WOK</th>
                            <th style="color:#276749">CLD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>All Sections Combined</td>
                            <td class="val-total"><?php echo $grand['total']; ?></td>
                            <td class="val-non"><?php echo $grand['non']; ?></td>
                            <td class="val-rec"><?php echo $grand['rec']; ?></td>
                            <td class="val-wok"><?php echo $grand['wok']; ?></td>
                            <td class="val-cld"><?php echo $grand['cld']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <div class="report-footer">
        <span><i class="fas fa-shield-alt mr-1"></i> Confidential — For Internal Use Only</span>
        <span><?php echo htmlspecialchars($inst_name); ?> &copy; <?php echo date('Y'); ?></span>
        <span>Page 1</span>
    </div>

</div>

</body>
</html>
<?php mysqli_close($conn); ?>
