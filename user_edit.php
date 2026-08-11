<?php include("includes/header.php"); ?>
<?php
include('dbconnect.php');

$id = 0;
// Fetch user data
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM user_tbl WHERE user_no=$id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    if(!$user) {
        echo "<script>alert('User not found'); window.location='user_create.php';</script>";
        exit;
    }
}

// Handle Update
if(isset($_POST['update_user'])) {
    $u_id = intval($_POST['user_id']);
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $acc_type = $_POST['u_type'];
    $sec_no = intval($_POST['sec_no']);
    $sub_no = intval($_POST['sub_no']);
    
    // Password update logic (only if provided)
    $pass_sql = "";
    if(!empty($_POST['u_pass'])) {
        $u_pass = mysqli_real_escape_string($conn, $_POST['u_pass']);
        $pass_sql = ", password='$u_pass'"; // assuming plain text as per login_validation.php check earlier, or add hashing if system uses it. 
        // Based on login_form.php earlier, no hashing visible. Keeping plain text for compatibility.
    }
    
    $sql = "UPDATE user_tbl SET user_name='$u_name', acc_type='$acc_type', user_sec_no='$sec_no', user_s_no='$sub_no' $pass_sql WHERE user_no=$u_id";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>window.location='user_create.php?msg=User updated successfully';</script>";
    } else {
        $error = "Error updating user: " . mysqli_error($conn);
    }
}
?>

<div class="container" style="padding: 20px; margin-top: 20px;">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3><i class="fas fa-edit"></i> Edit User</h3>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <input type="hidden" name="user_id" value="<?php echo $user['user_no']; ?>">
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" name="u_name" class="form-control" value="<?php echo $user['user_name']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="u_pass" class="form-control" placeholder="Leave blank to keep current password">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <select name="u_type" class="form-control">
                            <option value="N" <?php if($user['acc_type']=='N') echo 'selected'; ?>>Normal</option>
                            <option value="H" <?php if($user['acc_type']=='H') echo 'selected'; ?>>Section Head</option>
                            <option value="A" <?php if($user['acc_type']=='A') echo 'selected'; ?>>Administrator</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Section No</label>
                    <div class="col-sm-10">
                        <input type="number" name="sec_no" class="form-control" value="<?php echo $user['user_sec_no']; ?>">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Subject No</label>
                    <div class="col-sm-10">
                        <input type="number" name="sub_no" class="form-control" value="<?php echo $user['user_s_no']; ?>">
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" name="update_user" class="btn btn-success"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="user_create.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
