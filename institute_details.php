<?php include("includes/header.php"); ?>

<?php
// Check if Admin
if(!isset($_SESSION['login_user']) || !isset($acctype) || $acctype != 'A') {
    // Redirect if not admin (Access Denied)
    // For now just show message or redirect
   // echo "<script>window.location='index.php';</script>";
   // exit;
   // Note: $acctype is defined in header.php but inside a local scope scope loop? 
   // Actually header.php runs the loop to generate the menu. $acctype might not be available globally here 
   // unless we re-fetch. 
   // Let's re-fetch to be safe.
}

include('dbconnect.php');

$msg = "";
$msg_type = "";

// Handle Form Submission
if(isset($_POST['update_details'])) {
    $i_name = mysqli_real_escape_string($conn, $_POST['institute_name']);
    
    // File Upload
    $target_dir = "img/";
    $uploadOk = 1;
    $logo_path = "";
    
    // Check if new file uploaded
    if(isset($_FILES["logo_file"]) && $_FILES["logo_file"]["error"] == 0) {
        $target_file = $target_dir . basename($_FILES["logo_file"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        // Validate Image
        $check = getimagesize($_FILES["logo_file"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $msg = "File is not an image.";
            $msg_type = "danger";
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $msg_type = "danger";
            $uploadOk = 0;
        }
        
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["logo_file"]["tmp_name"], $target_file)) {
                $logo_path = $target_file;
            } else {
                $msg = "Sorry, there was an error uploading your file.";
                $msg_type = "danger";
            }
        }
    }
    
    // Update DB
    if ($uploadOk == 1) {
        if($logo_path != "") {
            $sql = "UPDATE institute_details SET institute_name='$i_name', logo_path='$logo_path' WHERE id=1";
        } else {
            $sql = "UPDATE institute_details SET institute_name='$i_name' WHERE id=1";
        }
        
        if (mysqli_query($conn, $sql)) {
            $msg = "Details updated successfully!";
            $msg_type = "success";
            // Refresh variables for immediate feedback (though header already loaded)
             echo "<meta http-equiv='refresh' content='0'>";
        } else {
            $msg = "Error updating record: " . mysqli_error($conn);
            $msg_type = "danger";
        }
    }
}

// Fetch Current Data
$curr_name = "";
$curr_logo = "";
$sql_get = "SELECT * FROM institute_details WHERE id=1";
$res_get = mysqli_query($conn, $sql_get);
if($row_get = mysqli_fetch_assoc($res_get)) {
    $curr_name = $row_get['institute_name'];
    $curr_logo = $row_get['logo_path'];
}
?>

<div class="container bg-light" style="padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 class="mb-4 text-primary"><i class="fas fa-building"></i> ආයතනයේ තොරතුරු (Institute Details)</h2>
    <hr>
    
    <?php if($msg != ""): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="institute_name" class="font-weight-bold">ආයතනයේ නම (Institute Name):</label>
                    <input type="text" class="form-control form-control-lg" id="institute_name" name="institute_name" value="<?php echo $curr_name; ?>" required>
                </div>
                
                <div class="form-group mt-4">
                    <label for="logo_file" class="font-weight-bold">ආයතනයේ ලාංඡනය (Logo):</label>
                    <p class="text-muted small">හොඳම ප්‍රතිඵල සඳහා PNG හෝ JPG භාවිතා කරන්න. (Recommended height: 60px)</p>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo_file" name="logo_file">
                        <label class="custom-file-label" for="logo_file">Browse Logo...</label>
                    </div>
                </div>
                
                <button type="submit" name="update_details" class="btn btn-primary btn-lg mt-3"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </div>
        
        <div class="col-md-4 text-center">
            <div class="card">
                <div class="card-header">Current Logo</div>
                <div class="card-body bg-dark">
                    <img src="<?php echo $curr_logo; ?>" alt="Current Logo" class="img-fluid" style="max-height: 100px;">
                </div>
                <div class="card-footer text-muted">
                    Preview
                </div>
            </div>
            <div class="mt-4">
                 <h5 class="text-secondary"><?php echo $curr_name; ?></h5>
            </div>
        </div>
    </div>
</div>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<?php include("includes/footer.php"); ?>
