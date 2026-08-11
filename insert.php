<?php
date_default_timezone_set('Asia/Colombo');

include ('dbconnect.php');

$l_id = $_POST['l_id'];
$lrecive_date = $_POST['l_recive_date'];
$lrecive_time = date("h:i:sa");
$ldate = $_POST['l_date'];
$lsender = $_POST['sender'];
$lno = $_POST['l_no'];
$ltitle = $_POST['l_title'];
$secno = $_POST['sec_no'];
$subno = $_POST['sub_no'];
$ltype = isset($_POST['l_type']) ? $_POST['l_type'] : '';
$rno = $_POST['r_no'];
$limpo = isset($_POST['l_impo']) ? $_POST['l_impo'] : '';

//create query
$query = "INSERT INTO letter (letter_no, recived_date, recived_time,letter_date, sender, sender_no, title, section_no, subject_no, type, reg_no, important) VALUES ('{$l_id}', '{$lrecive_date}', '{$lrecive_time}', '{$ldate}', '{$lsender}', '{$lno}', '{$ltitle}', '{$secno}', '{$subno}', '{$ltype}', '{$rno}', '{$limpo}')";

if(mysqli_query($conn, $query)){
	header("location:index.php?idcheck=good&l_id=".urlencode($l_id)."&rdate=".urlencode($lrecive_date)."&ltime=".urlencode($lrecive_time)."&lsender=".urlencode($lsender)."&ltitle=".urlencode($ltitle));
}
else {
	if (mysqli_errno($conn) == 1062) {
		$params = http_build_query(array(
			'idcheck' => 'bad',
			'l_id' => $l_id,
			'l_recive_date' => $lrecive_date,
			'l_date' => $ldate,
			'sender' => $lsender,
			'l_no' => $lno,
			'l_title' => $ltitle,
			'sec_no' => $secno,
			'sub_no' => $subno,
			'l_type' => $ltype,
			'r_no' => $rno,
			'l_impo' => $limpo
		));
		header("location:index.php?" . $params);
	} else {
		header("location:index.php?idcheck=bad_unknown");
	}
}

mysqli_close($conn);
?>