<?php
if(session_status() === PHP_SESSION_NONE){ session_start(); }
include('dbconnect.php');

header('Content-Type: application/json; charset=utf-8');

$response = array('exists' => false, 'message' => '');

if (isset($_GET['l_id']) && trim($_GET['l_id']) !== '') {
    $l_id = mysqli_real_escape_string($conn, trim($_GET['l_id']));
    $query = "SELECT letter_no FROM letter WHERE letter_no = '$l_id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $response['exists'] = true;
        $response['message'] = "අනු අංකය '$l_id' දැනටමත් පද්ධතියේ පවතී.";
    } else {
        $response['exists'] = false;
        $response['message'] = "මෙම අනු අංකය ලබා ගත හැක.";
    }
}

echo json_encode($response);
exit();
?>
