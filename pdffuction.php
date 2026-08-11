<?php
class DBController {
//	private $host = "localhost";
//	private $user = "root";
//	private $password = "";
//	private $database = "blog_samples";
//	
//	function __construct() {
//		$conn = $this->connectDB();
//		if(!empty($conn)) {
//			$this->selectDB($conn);
//		}
//	}
//	
//	function connectDB() {
//		$conn = mysql_connect($this->host,$this->user,$this->password);
//		return $conn;
//	}
//	
//	function selectDB($conn) {
//		mysql_select_db($this->database,$conn);
//	}
	
	function runQuery($query) {
	include ('dbconnect.php');
		$result = mysqli_query($conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function numRows($query) {
		$result  = mysqli_query($conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}



?>
