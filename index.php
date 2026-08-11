<?php
if(session_status() === PHP_SESSION_NONE){ session_start(); }
include 'dbconnect.php';

if(isset($_SESSION['login_user'])){
    $u_name_check_idx = $_SESSION['login_user'];
    $query_check_idx = "SELECT acc_type FROM user_tbl WHERE user_name='$u_name_check_idx'";
    $result_check_idx = mysqli_query($conn, $query_check_idx);
    if($result_check_idx && mysqli_num_rows($result_check_idx) > 0){
        $row_check_idx = mysqli_fetch_assoc($result_check_idx);
        $acctype_check_idx = $row_check_idx['acc_type'];
        
        if($acctype_check_idx == 'N' || $acctype_check_idx == 'H'){
            header("location:dashboard.php");
            exit();
        }
    }
}
include("includes/header.php"); 


// add dbconnection
include ('dbconnect.php');

// Removed unused heavy query

$val_lid = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_id'])) ? htmlspecialchars($_GET['l_id']) : '';
$val_recive_date = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_recive_date'])) ? htmlspecialchars($_GET['l_recive_date']) : '';
$val_date = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_date'])) ? htmlspecialchars($_GET['l_date']) : '';
$val_sender = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['sender'])) ? htmlspecialchars($_GET['sender']) : '';
$val_lno = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_no'])) ? htmlspecialchars($_GET['l_no']) : '';
$val_title = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_title'])) ? htmlspecialchars($_GET['l_title']) : '';
$val_secno = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['sec_no'])) ? htmlspecialchars($_GET['sec_no']) : '';
$val_subno = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['sub_no'])) ? htmlspecialchars($_GET['sub_no']) : '';
$val_ltype = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_type'])) ? htmlspecialchars($_GET['l_type']) : '';
$val_rno = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['r_no'])) ? htmlspecialchars($_GET['r_no']) : '';
$val_limpo = (isset($_GET['idcheck']) && $_GET['idcheck'] == 'bad' && isset($_GET['l_impo'])) ? htmlspecialchars($_GET['l_impo']) : '';
?>

  
  
<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>ලිපි කළමණාකරණ දත්ත පද්ධතිය v1.0</h3>

<?php
if(isset($_GET['idcheck'])){
	if($_GET['idcheck']=='good'){
		$lid = htmlspecialchars($_GET['l_id']);
		$rdate = htmlspecialchars($_GET['rdate']);
		$ltime = htmlspecialchars($_GET['ltime']);
		$ltitle = htmlspecialchars($_GET['ltitle']);
		$lsender = htmlspecialchars($_GET['lsender']);
		echo '<div class="alert alert-success" role="alert">';
		echo "✅ <strong>සාර්ථකයි!</strong> ලිපිය සාර්ථකව ඇතුලත් කරන ලදී. (අනු අංකය: " . $lid . ", ලැබුණු දිනය: " . $rdate . ", එවන්නා: " . $lsender . ")";
		echo '</div>';
	} elseif($_GET['idcheck']=='bad') {
		$lid = htmlspecialchars($_GET['l_id']);
		echo '<div class="alert alert-danger" role="alert" style="font-size:16px; font-weight:bold;">';
		echo "⚠️ <strong>කණගාටුයි! ලිපිය ඇතුලත් කල නොහැක.</strong> <u>" . $lid . "</u> අනු අංකයෙන් දැනටමත් ලිපියක් පද්ධතියට ඇතුලත් කර ඇති නිසා නව අංකයක් භාවිතා කර ලිපිය ඇතුලත් කරන්න.";
		echo '</div>';
	} elseif($_GET['idcheck']=='bad_unknown') {
		echo '<div class="alert alert-danger" role="alert">';
		echo "❌ ලිපිය ඇතුලත් කිරිමේදී දෝෂයක් සිදු විය. කරුණාකර නැවත උත්සාහ කරන්න.";
		echo '</div>';
	}
} elseif(isset($_GET['delete'])) {
		$delete = htmlspecialchars($_GET['delete']);
		echo '<div class="alert alert-warning" role="alert">Item ' . $delete . ' successfully deleted.</div>';
} else {
		echo '<div class="alert alert-info" role="alert">තොරතුරු ඇතුලත් කර ඇතුලත් කිරීමේ බටනය ක්ලික් කරන්න.</div>';
} 
?>

    <div class="row">

    <div class="col-sm-12">
<script>    
    var isDuplicateId = false;
    var checkTimer = null;

    function checkDuplicateId() {
        var lidInput = document.getElementById("l_id_input");
        var feedback = document.getElementById("l_id_feedback");
        if(!lidInput || !feedback) return;
        
        var val = lidInput.value.trim();
        if(val === ""){
            lidInput.style.borderColor = "";
            lidInput.style.boxShadow = "";
            feedback.style.display = "none";
            feedback.innerHTML = "";
            isDuplicateId = false;
            return;
        }
        
        clearTimeout(checkTimer);
        checkTimer = setTimeout(function(){
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "check_letter_no.php?l_id=" + encodeURIComponent(val), true);
            xhr.onreadystatechange = function(){
                if(xhr.readyState === 4 && xhr.status === 200){
                    try {
                        var res = JSON.parse(xhr.responseText);
                        feedback.style.display = "block";
                        if(res.exists){
                            isDuplicateId = true;
                            lidInput.style.borderColor = "#dc3545";
                            lidInput.style.boxShadow = "0 0 5px rgba(220,53,69,0.5)";
                            feedback.style.color = "#dc3545";
                            feedback.innerHTML = "❌ කණගාටුයි! අනු අංකය '" + val + "' දැනටමත් පද්ධතියේ පවතී. කරුණාකර වෙනත් අංකයක් භාවිතා කරන්න.";
                        } else {
                            isDuplicateId = false;
                            lidInput.style.borderColor = "#28a745";
                            lidInput.style.boxShadow = "0 0 5px rgba(40,167,69,0.5)";
                            feedback.style.color = "#28a745";
                            feedback.innerHTML = "✓ මෙම අනු අංකය ලබා ගත හැක.";
                        }
                    } catch(e){}
                }
            };
            xhr.send();
        }, 250);
    }

    function validateForm() {
        var x = document.forms["mail"]["l_id"].value;
        var y = document.forms["mail"]["l_recive_date"].value;
        var z = document.forms["mail"]["sender"].value;
        var a = document.forms["mail"]["l_title"].value;
        var b = document.forms["mail"]["sec_no"].value;
        var c = document.forms["mail"]["sub_no"].value;
        var d = document.forms["mail"]["l_type"].value;
        var e = document.forms["mail"]["r_no"].value;
        var f = document.forms["mail"]["l_impo"].value;

        if (x == "") {
            alert("අනු අංකය සම්පූර්ණ කර නොමැත");
            document.forms["mail"]["l_id"].focus();
            return false;
        }
        
        if (isDuplicateId) {
            alert("කණගාටුයි! අනු අංකය '" + x + "' දැනටමත් පද්ධතියේ පවතී. කරුණාකර වෙනත් අනු අංකයක් භාවිතා කරන්න.");
            document.forms["mail"]["l_id"].focus();
            return false;
        }
        
        if (y == "") {
            alert("ලියුම ලැබුණු දිනය සදහන් කර නොමැත");
            return false;
        }	
        if (z == "") {
            alert("ලියුම් එවූ අය සදහන් කර නොමැත");
            return false;
        }	
        if (a == "") {
            alert("ලියුමේ මාතෘකාව සදහන් කර නොමැත");
            return false;
        }
        if (b == "") {
            alert("අංශයේ අංකය සදහන් කර නොමැත");
            return false;
        }	
        if (c == "") {
            alert("විෂයේ අංකය සදහන් කර නොමැත");
            return false;
        }	
        if (d == "") {
            alert("ලිපියේ වර්ගය සඳහන්කර නොමැත");
            return false;
        }
        if (e == "") {
            alert("ලිපියේ ලියාපදිංචි අංකය සඳහන්කර නොමැත");
            return false;
        }	
        if (f == "") {
            alert("ලිපියේ වැදගත් ස්භාවය ඇතුලත්කර නොමැත");
            return false;
        }
    }
</script>    
        <form role="form" action="insert.php" method="post" name="mail" onsubmit="return validateForm()" >
        
        	<div class="form-group row">
                <label class="col-md-2">අනු අංකය</label>
                <input type="text" name="l_id" id="l_id_input" value="<?php echo $val_lid; ?>" class="form-control col-md-5" onkeyup="checkDuplicateId()" onblur="checkDuplicateId()" autocomplete="off" />
                <div class="col-md-5 offset-md-2" id="l_id_feedback" style="display:none; margin-top:5px; font-weight:bold;"></div>
            </div>
        
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >ලිපිය ලැබුණු දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="l_recive_date" value="<?php echo $val_recive_date; ?>" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
            
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >ලියුමේ දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="l_date" value="<?php echo $val_date; ?>" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>

            <div class="form-group row">
                <label class="col-md-2">ලිපිය එවන්නා</label>
                <input type="text" name="sender" value="<?php echo $val_sender; ?>" class="form-control col-md-5" />
            </div>
            
			<div class="form-group row">
                <label class="col-md-2">ලිපි අංකය</label>
                <input type="text" name="l_no" value="<?php echo $val_lno; ?>" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">ලිපියේ මාතෘකාව</label>
                <input type="text" name="l_title" value="<?php echo $val_title; ?>" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">අංශයේ අංකය</label>
                <input type="text" name="sec_no" value="<?php echo $val_secno; ?>" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">විශය අංකය</label>
                <input type="text" name="sub_no" value="<?php echo $val_subno; ?>" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row radio-pink-gap">
                <label class="col-md-2">ලිපි වර්ගය</label>

                <input name="l_type" type="radio" id="radio100" value="N" <?php if($val_ltype == 'N') echo 'checked'; ?> onclick="disableTextbox()">
                <label for="radio100" class="col-md-1">සාමාන්‍ය තැපැල්</label>                        
            
                <input name="l_type" type="radio" id="radio101" value="R" <?php if($val_ltype == 'R') echo 'checked'; ?> onclick="enableTextbox()">
                <label for="radio101" class="col-md-1">ලියාපදිංචි තැපැල්</label>                     
            
                <input name="l_type" type="radio" id="radio102" value="H" <?php if($val_ltype == 'H') echo 'checked'; ?> onclick="disableTextbox()">
                <label for="radio102" class="col-md-1">අතින් භාරදුන්</label>
                
               	<input name="l_type" type="radio" id="radio103" value="F" <?php if($val_ltype == 'F') echo 'checked'; ?> onclick="disableTextbox()">
                <label for="radio103" class="col-md-1">Fax</label>
                
                <input name="l_type" type="radio" id="radio104" value="E" <?php if($val_ltype == 'E') echo 'checked'; ?> onclick="disableTextbox()">
                <label for="radio104" class="col-md-1">E-mail</label>
            </div>
            
             <div class="form-group row">
                <label class="col-md-2">ලියාපදිංචි අංකය</label>
                <input type="text" name="r_no" value="<?php echo $val_rno; ?>" class="form-control col-md-5" id="text_reg_no" />
            </div> 
            
            <div class="form-group row radio-pink-gap">
                <label class="col-md-2">වැදගත් බව</label>

                <input name="l_impo" type="radio" id="radio300" value="n" <?php if($val_limpo == 'n') echo 'checked'; ?>>
                <label for="radio300" class="col-md-1">සාමාන්‍ය</label>                        
            
                <input name="l_impo" type="radio" id="radio301" value="i" <?php if($val_limpo == 'i') echo 'checked'; ?>>
                <label for="radio301" class="col-md-1">ඉතා හදිස්සි</label>                     
            
                <input name="l_impo" type="radio" id="radio302" value="r" <?php if($val_limpo == 'r') echo 'checked'; ?>>
                <label for="radio302" class="col-md-1">සිහි කැඳවීම්</label>
				
				<input name="l_impo" type="radio" id="radio303" value="p" <?php if($val_limpo == 'p') echo 'checked'; ?>>
                <label for="radio303" class="col-md-1">මහජන පැමිණිලි</label>
                
            </div>
            
        		<button type="submit" id="submit_btn" class="btn btn-info btn-block col-md-2">ඇතුලත් කරන්න</button>
        </form>
   
    <script>
	function disableTextbox(){	
		if(document.getElementById("radio100").checked||document.getElementById("radio102").checked||document.getElementById("radio103").checked||document.getElementById("radio104").checked){
			document.getElementById("text_reg_no").disabled = true;
			document.getElementById("text_reg_no").value = "0";	
			}				
		}
		
	function enableTextbox(){
		document.getElementById("text_reg_no").value = "";
		document.getElementById("text_reg_no").disabled = false;
		}		
	
	
    </script>   
   
    </div>

    </div>
</div>


<!--Date time picker-->
<script type="text/javascript" src="js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/dtpicker/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/dtpicker/locales/bootstrap-datetimepicker.uk.js" charset="UTF-8"></script>



<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	$('.form_date').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	$('.form_time').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>






      
<?php 
include("includes/footer.php"); 
?>