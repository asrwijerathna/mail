<?php //include("includes/header.php"); ?>

<?php
// add dbconnection
include ('dbconnect.php');
include ('pdffuction.php');

//create query
$query = "SELECT COUNT(*) FROM letter";
$result = mysqli_query($conn, $query);


$query = "SELECT COUNT(*) FROM letter WHERE status='NON'";
$result = mysqli_query($conn, $query);


if($result){
	$row = mysqli_fetch_row($result);
	echo "Total Letters : " . $row[0];
	
	$row = mysqli_fetch_row($result);
	echo "Total Letters : " . $row[0];
	}
?>




<?php 
mysqli_close($conn);
?>