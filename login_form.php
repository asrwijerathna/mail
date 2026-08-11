<?php
// login_form.php
include('dbconnect.php');

// Fetch Institute Details
$inst_name = "Mail Management System";
$inst_logo = "img/300x60.png";
$curr_year = date("Y");

if(isset($conn)) {
    $sql_inst = "SELECT * FROM institute_details LIMIT 1";
    $res_inst = mysqli_query($conn, $sql_inst);
    if($res_inst && mysqli_num_rows($res_inst) > 0){
        $row_inst = mysqli_fetch_assoc($res_inst);
        $inst_name = $row_inst['institute_name'];
        $inst_logo = $row_inst['logo_path'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $inst_name; ?> - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .logo-container {
            margin-bottom: 25px;
        }
        
        .logo-container img {
            max-width: 250px;
            height: auto;
            max-height: 80px;
            object-fit: contain;
        }
        
        .institute-name {
            font-size: 1.25rem;
            color: #2d3436;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .form-control {
            border-radius: 50px;
            padding: 12px 25px;
            background-color: #f1f2f6;
            border: none;
            margin-bottom: 20px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }
        
        .btn-login {
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(45deg, #4a90e2, #00cec9);
            border: none;
            width: 100%;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }
        
        .btn-login:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
        }
        
        .alert-custom {
            border-radius: 10px;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }
        
        .footer-text {
            margin-top: 30px;
            font-size: 0.85rem;
            color: #b2bec3;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-container">
            <img src="<?php echo $inst_logo; ?>" alt="Institute Logo">
        </div>
        
        <h3 class="institute-name"><?php echo $inst_name; ?></h3>
        
        <?php if(isset($_GET['invalid'])): ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <i class="fas fa-exclamation-circle"></i> 
                <?php echo "Invalid Username or Password for " . htmlspecialchars($_GET['invalid']); ?>
            </div>
        <?php else: ?>
             <div class="alert alert-info alert-custom" role="alert">
                <i class="fas fa-lock"></i> Please login to your account.
            </div>
        <?php endif; ?>
        
        <form role="form" action="login_validation.php" method="post" name="mail_login" onsubmit="return validateForm()">
            <div class="input-group mb-3">
                <input type="text" name="u_name" class="form-control" placeholder="පරිශීලක නාමය (User Name)" required />
            </div>
            
            <div class="input-group mb-3">
                <input type="password" name="u_pass" class="form-control" placeholder="මුර පදය (Password)" required />
            </div>
            
            <button type="submit" name="submit" class="btn btn-login">
                ඇතුලත් කරන්න <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>
        
        <div class="footer-text">
            &copy; <?php echo $curr_year; ?> Mail Management System
        </div>
    </div>

<script>    
    function validateForm() {
        var x = document.forms["mail_login"]["u_name"].value;
        var y = document.forms["mail_login"]["u_pass"].value;
    
        if (x == "") {
            alert("කරුණාකර ඔබේ පරිෂීලක නාමය ඇතුලත් කරන්න");
            return false;
        }
        
        if (y == "") {
            alert("කරුණාකර ඔබේ මුර පදය ඇතුලත් කරන්න");
            return false;
        }

        return true;
    }
</script> 

</body>
</html>