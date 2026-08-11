<?php
include('dbconnect.php');

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Safety check: Prevent deleting yourself? (Optional but good practice)
    // session_start();
    // if($_SESSION['user_id'] == $id) { ... }
    
    $sql = "DELETE FROM user_tbl WHERE user_no=$id";
    if(mysqli_query($conn, $sql)) {
        header("Location: user_create.php?msg=User deleted successfully");
    } else {
        header("Location: user_create.php?error=Error deleting user: " . mysqli_error($conn));
    }
} else {
    header("Location: user_create.php");
}
?>
