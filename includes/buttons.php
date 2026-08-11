
        <?php 
		if($acctype=='A'){
		?>	 
                <td>
                <a href="editform.php?id=<?php echo $l_id_no;?>" class="btn btn-success" role="button">E</a>
                </td>
                <td>
                <a onClick="javascript: return confirm('ඔබට මෙය ඩිලීට් කිරීමට අවශ්‍ය නම් OK ඔබන්න. නැතිනම් Cancel ඔබන්න.');" href="delete.php?delete_id=<?php echo $l_id_no;?>&dam=<?php echo $damid; ?>
				 <?php if($damid==14){ ?>
                     &id=<?php echo $l_id_no.@$back_lno.@$back_recived_date.@$back_letter_date.@$back_sender_no.@$back_sender.@$back_title.@$back_section_no.@$back_subject_no.@$back_type.@$back_reg_no;?>
                     <?php } ?>                
                " class="btn btn-danger" role="button">D</a>
                </td>
        <?php	
			} elseif($acctype=='H'){
		?>
                <td>
                <a href="editform.php?id=<?php echo $l_id_no;?>" class="btn btn-success" role="button">E</a>
                </td>
        
        <?php				
			}
		?>

        <td>
        <!-- S Button - triggers Status Entry Modal -->
		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#statusModal<?php echo $l_id_no;?>">S</button>
        </td>
        <td>
        <!-- V Button - triggers View Status Modal -->
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#viewModal<?php echo $l_id_no;?>">V</button>
        
        <!-- Modals must be inside a td to prevent browser from ejecting them outside the table -->
        <!-- S Modal - Status Entry Form (placed outside table via JS) -->
        <div class="modal fade mail-modal" id="statusModal<?php echo $l_id_no;?>" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel<?php echo $l_id_no;?>" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel<?php echo $l_id_no;?>">අංක <?php echo $l_id_no;?> ලිපිය සම්බන්ධයෙන් ගෙන ඇති ක්‍රියාමාර්ග ඇතුලත් කරන්න</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php include("includes/status_form_modal.php"); ?>                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- V Modal - View Status Table (placed outside table via JS) -->
        <div class="modal fade mail-modal" id="viewModal<?php echo $l_id_no;?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel<?php echo $l_id_no;?>" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel<?php echo $l_id_no;?>">අංක <?php echo $l_id_no;?> ලිපිය සම්බන්ධයෙන් ගෙන ඇති ක්‍රියාමාර්ග</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php include("includes/status_table.php"); ?>                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        
        </td>

