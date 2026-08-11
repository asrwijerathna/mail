<?php include("includes/header.php"); ?>
<?php
// add dbconnection
include ('dbconnect.php');

// Fetch all users ordered by section number
$query = "SELECT * FROM user_tbl ORDER BY user_sec_no ASC, user_no DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container bg-light" style="padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h3 class="mb-4 text-primary"><i class="fas fa-user-plus"></i> නව පරිශීලක (User Management)</h3>

    <!-- Feedback Message -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_GET['msg']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif(isset($_GET['error'])): ?>
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_GET['error']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- User Creation Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Add New User
        </div>
        <div class="card-body">
            <form role="form" action="user_insert.php" method="post">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>පරිශීලක නාමය (Username)</label>
                        <input type="text" name="u_name" class="form-control" required />
                    </div>
                    <div class="form-group col-md-4">
                        <label>මුර පදය (Password)</label>
                        <input type="password" name="u_pass" class="form-control" required />
                    </div>
                     <div class="form-group col-md-4">
                        <label>පරිශීලක වර්ගය (Type)</label>
                        <select name="u_type" class="form-control">
                            <option value="N">Normal (Subject Clerk)</option>
                            <option value="H">Section Head</option>
                            <option value="A">Administrator</option>
                        </select>
                    </div>
                </div>
                 <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>අංශ අංකය (Section No)</label>
                        <input type="number" name="sec_no" class="form-control" />
                    </div>
                    <div class="form-group col-md-4">
                        <label>විශය අංකය (Subject No)</label>
                        <input type="number" name="sub_no" class="form-control" />
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> ඇතුලත් කරන්න (Create)</button>
            </form>
        </div>
    </div>

    <!-- User List Table -->
    <h4 class="mb-3 text-secondary">Existing Users</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Type</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $current_section = null;
                while($row = mysqli_fetch_assoc($result)): 
                    if($current_section !== $row['user_sec_no']):
                        $current_section = $row['user_sec_no'];
                        $sec_display = empty($current_section) ? "Admins / No Section" : "අංශය (Section) - " . $current_section;
                ?>
                <tr class="table-secondary font-weight-bold">
                    <td colspan="6" class="text-center text-dark"><i class="fas fa-users"></i> <?php echo $sec_display; ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td><?php echo $row['user_no']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td>
                        <?php 
                        switch($row['acc_type']) {
                            case 'A': echo '<span class="badge badge-danger">Admin</span>'; break;
                            case 'H': echo '<span class="badge badge-warning">Head</span>'; break;
                            case 'N': echo '<span class="badge badge-info">Normal</span>'; break;
                            default: echo $row['acc_type'];
                        }
                        ?>
                    </td>
                    <td><?php echo $row['user_sec_no']; ?></td>
                    <td><?php echo $row['user_s_no']; ?></td>
                    <td>
                        <a href="user_edit.php?id=<?php echo $row['user_no']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                        <a href="user_delete.php?id=<?php echo $row['user_no']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include("includes/footer.php"); ?>