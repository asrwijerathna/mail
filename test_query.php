<?php
include('dbconnect.php');

$sql_slow = "SELECT COUNT(*) as count FROM letter a
INNER JOIN (
    SELECT l_no, status 
    FROM action_tbl t1
    WHERE `index` = (SELECT MAX(`index`) FROM action_tbl t2 WHERE t2.l_no = t1.l_no)
) b ON b.l_no = a.letter_no 
WHERE b.status = 'REC'";

$sql_fast = "SELECT COUNT(*) as count FROM letter a
INNER JOIN action_tbl b ON b.l_no = a.letter_no
INNER JOIN (
    SELECT l_no, MAX(`index`) as max_index
    FROM action_tbl
    GROUP BY l_no
) max_b ON b.l_no = max_b.l_no AND b.`index` = max_b.max_index
WHERE b.status = 'REC'";

echo "Testing slow query...\n";
$s = microtime(true);
$r = mysqli_query($conn, $sql_slow);
echo "Time: " . (microtime(true) - $s) . " seconds\n";

echo "Testing fast query...\n";
$s = microtime(true);
$r = mysqli_query($conn, $sql_fast);
echo "Time: " . (microtime(true) - $s) . " seconds\n";
?>
