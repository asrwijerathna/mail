<?php
include('dbconnect.php');

echo "<h2>1. Database Connection</h2>";
if($conn) {
    echo "<p style='color:green'>✅ Connected successfully to DB: <b>mail</b></p>";
} else {
    echo "<p style='color:red'>❌ Connection FAILED: " . mysqli_connect_error() . "</p>";
    exit;
}

echo "<h2>2. user_tbl - All Users</h2>";
$q = "SELECT user_no, user_name, user_sec_no, user_s_no, acc_type, password FROM user_tbl";
$r = mysqli_query($conn, $q);
if($r && mysqli_num_rows($r) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>user_no</th><th>user_name</th><th>user_sec_no</th><th>user_s_no</th><th>acc_type</th><th>password (raw)</th></tr>";
    while($row = mysqli_fetch_assoc($r)) {
        echo "<tr>";
        echo "<td>" . $row['user_no'] . "</td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . $row['user_sec_no'] . "</td>";
        echo "<td>" . $row['user_s_no'] . "</td>";
        echo "<td>" . $row['acc_type'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>❌ No users found in user_tbl! Table is empty.</p>";
    echo "<p>Error: " . mysqli_error($conn) . "</p>";
}

echo "<h2>3. Test Login Query</h2>";
$test_user = "admin";
$test_pass = "admin";
$q2 = "SELECT user_name, user_sec_no, user_s_no FROM user_tbl WHERE user_name='$test_user' and password='$test_pass'";
echo "<p>Query: <code>" . htmlspecialchars($q2) . "</code></p>";
$r2 = mysqli_query($conn, $q2);
if($r2 && mysqli_num_rows($r2) > 0) {
    echo "<p style='color:green'>✅ Login WOULD SUCCEED with admin/admin</p>";
} else {
    echo "<p style='color:red'>❌ Login FAILS with admin/admin - rows returned: " . ($r2 ? mysqli_num_rows($r2) : 'query failed') . "</p>";
}

echo "<h2>4. institute_details table</h2>";
$q3 = "SELECT * FROM institute_details LIMIT 1";
$r3 = mysqli_query($conn, $q3);
if($r3 && mysqli_num_rows($r3) > 0) {
    $row3 = mysqli_fetch_assoc($r3);
    echo "<p style='color:green'>✅ institute_details OK: " . $row3['institute_name'] . "</p>";
} else {
    echo "<p style='color:orange'>⚠️ institute_details empty or missing. Error: " . mysqli_error($conn) . "</p>";
}
?>
