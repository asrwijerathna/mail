<?php

    //create query for fetch status data
	$query_count = "SELECT COUNT(*) as 'num_count' FROM action_tbl WHERE l_no='$l_id_no'";
	$result_count = mysqli_query($conn, $query_count);
	$row_count=mysqli_fetch_assoc($result_count);
	$total_count = $row_count['num_count'];
	
	
	if($row_count['num_count']>0){
?>

<table class="table">
    <thead>
    <tr>
        <th>අනු අංකය</th>
        <th>ක්‍රියාකල දිනය</th>
        <th>ක්‍රියා මාර්ගය</th>
        <th>වර්ථමාන තත්ත්වය</th>
        <th>ලිපි අංකය</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    
    <?php 

    $query_action = "SELECT * FROM action_tbl WHERE l_no='$l_id_no'";
    $result_action = mysqli_query($conn, $query_action);
    
    while($row=mysqli_fetch_assoc($result_action)){
    ?>
        <tr>
            <td><?php echo $row['index'];?></td>
            <td><?php echo $row['action_date'];?></td>
            <td><?php echo $row['action'];?></td>
            <td><?php echo $row['status'];?></td>
            <td><?php echo $row['l_no'];?></td>
            <td><a onClick="javascript: return confirm('ඔබට මෙය ඩිලීට් කිරීමට අවශ්‍ය නම් OK ඔබන්න. නැතිනම් Cancel ඔබන්න.');" href="delete_status.php?action_id=<?php echo $row['index'];?>&dam=<?php echo $damid; ?>
				 <?php if($damid==14){ ?>
                 &id=<?php echo $l_id_no.$back_lno.$back_recived_date.$back_letter_date.$back_sender_no.$back_sender.$back_title.$back_section_no.$back_subject_no.$back_type.$back_reg_no;?>
                 <?php } ?>            
            " class="btn btn-danger" role="button">D</a></td>
        </tr>

    <?php }
	?>	
     </tbody>
</table>	
    <?php
	}else{
		echo "ඉහත අංක දරණ ලිපිය සම්බන්ධයෙන් තොරතුරු ඇතුලත්කර නොමැත.";
		}
	
	 ?>
