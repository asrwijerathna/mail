<?php if(session_status() === PHP_SESSION_NONE){ session_start(); } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Mail Management System</title>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />

<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!--Date time picker-->
<!--<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">-->
<link href="bootstrap/css/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">




<!-- Custom styles for this template -->
<link href="css/logo-nav.css" rel="stylesheet">    
<link href="css/mail_local.css" rel="stylesheet" type="text/css" />
<link href="css/modern_theme.css" rel="stylesheet" type="text/css" />


</head>

<body>
 
    <!-- Top Navigation (Fixed) -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container-fluid">
        <?php
        // Fetch Institute Details
        // Assuming dbconnect.php is included later or needs to be included here? 
        // Header usually includes dbconnect inside the sidebar logic, but top nav is before sidebar.
        // Let's include dbconnect at the very top if not already? 
        // It's safer to just include it here locally or check correct scope.
        // Actually, dbconnect is included inside the Sidebar PHP block (line 58). 
        // THIS IS A SCOPE ISSUE. I should move include('dbconnect.php') to the top of body or header.
        // However, I can just do a quick connect or include here for now to be safe.
        // Better: Lets move the main include('dbconnect.php') to top of file or use it here.
        include_once('dbconnect.php'); // Use include_once to avoid strict re-redeclare errors 
        
        $inst_name = "Mail Management System";
        $inst_logo = "img/300x60.png";
        
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
        <a class="navbar-brand" href="#">
          <img src="<?php echo $inst_logo; ?>" height="30" alt="Logo" style="margin-right: 10px;">
          <span style="font-size: 1.2rem; vertical-align: middle;"><?php echo $inst_name; ?></span>
        </a>
        <div class="navbar-nav ml-auto">
             <span class="nav-item nav-link">Welcome
                <?php	
                if(isset($_SESSION["login_user"]) && $_SESSION["login_user"]==true){		  
                    echo " ".$_SESSION["login_user"];
                } else{
                    header("location:login_form.php");
                }
                ?>
            </span>
        </div>
      </div>
    </nav>

    <!-- Main Connectivity: Sidebar (+ Content) -->
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <?php
                include ('dbconnect.php');
                if(isset($_SESSION["login_user"]) && $_SESSION["login_user"]==true){	
                $u_name = $_SESSION["login_user"];
                
                // Use Session variables if available, otherwise default to empty
                $u_sec_no = isset($_SESSION["user_sec_no"]) ? $_SESSION["user_sec_no"] : '';
                $u_s_no = isset($_SESSION["user_s_no"]) ? $_SESSION["user_s_no"] : '';

                $query = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name'";
                $result = mysqli_query($conn, $query);
                
                while($row=mysqli_fetch_assoc($result)){
                    $acctype = $row['acc_type'];
                    
                    // Define Filter Clause based on User Type
                    $filter_clause = "";
                    $filter_clause_alias = ""; // For queries with joins (alias 'a')

                    if($acctype == 'N') {
                        $filter_clause = "AND section_no = '$u_sec_no' AND subject_no = '$u_s_no'";
                        $filter_clause_alias = "AND a.section_no = '$u_sec_no' AND a.subject_no = '$u_s_no'";
                    } elseif ($acctype == 'H') {
                        $filter_clause = "AND section_no = '$u_sec_no'";
                        $filter_clause_alias = "AND a.section_no = '$u_sec_no'";
                    }
                    

                
                // Display Menu
                if($acctype=='A'){
                ?>
                    <li class="nav-item">
                      <a class="nav-link" href="index.php"><i class="fas fa-home"></i> ලිපි ඇතුලත්කිරීම</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view.php"><i class="fas fa-envelope-open-text"></i> ලියුම්</a>
                    </li>
                   <li class="nav-item">
                      <a class="nav-link" href="mail_view_non.php"><i class="fas fa-plus-circle"></i> නව ලිපි (NON)</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_rec.php"><i class="fas fa-inbox"></i> භාරගත් ලිපි (REC)</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_wok.php"><i class="fas fa-tools"></i> වැඩ ආරම්භකල (WOK)</a>
                    </li>                   
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_cld.php"><i class="fas fa-check-double"></i> වැඩ අවසන් (CLD)</a>
                    </li>                    
                    <li class="nav-item">
                      <a class="nav-link" href="export_excel.php"><i class="fas fa-file-excel"></i> වාර්ථා</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" href="search.php"><i class="fas fa-search"></i> සෙවීම්</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                    <li class="nav-item mt-3">
                      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Settings</span>
                      </h6>
                      <a class="nav-link" href="institute_details.php"><i class="fas fa-building"></i> ආයතනයේ තොරතුරු</a>
                      <a class="nav-link" href="user_create.php"><i class="fas fa-user-plus"></i> පරිශීලක</a>
                    </li>
                    <?php
                }elseif($acctype=='N'){
                ?>

                    <li class="nav-item">
                      <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view.php"><i class="fas fa-envelope-open-text"></i> ලියුම්</a>
                    </li>
                   <li class="nav-item">
                      <a class="nav-link" href="mail_view_non.php"><i class="fas fa-plus-circle"></i> නව ලිපි (NON)</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_rec.php"><i class="fas fa-inbox"></i> භාරගත් ලිපි (REC)</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_wok.php"><i class="fas fa-tools"></i> වැඩ ආරම්භකල (WOK)</a>
                    </li>                   
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_cld.php"><i class="fas fa-check-double"></i> වැඩ අවසන් (CLD)</a>
                    </li>                       
                    <li class="nav-item">
                      <a class="nav-link" href="search.php"><i class="fas fa-search"></i> සෙවීම්</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                    <?php
                }elseif($acctype=='H'){	
                ?>

                    <li class="nav-item">
                      <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view.php"><i class="fas fa-envelope-open-text"></i> ලියුම්</a>
                    </li>
                   <li class="nav-item">
                      <a class="nav-link" href="mail_view_non.php"><i class="fas fa-plus-circle"></i> නව ලිපි (NON)</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_rec.php"><i class="fas fa-inbox"></i> භාරගත් ලිපි (REC)</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_wok.php"><i class="fas fa-tools"></i> වැඩ ආරම්භකල (WOK)</a>
                    </li>                   
                    <li class="nav-item">
                      <a class="nav-link" href="mail_view_cld.php"><i class="fas fa-check-double"></i> වැඩ අවසන් (CLD)</a>
                    </li>                       
                    <li class="nav-item">
                      <a class="nav-link" href="search.php"><i class="fas fa-search"></i> සෙවීම්</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>                
                <?php
                }							
                }
                }
            ?>
            </ul>
          </div>
        </nav>

        <!-- Main Content Wrapper -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">