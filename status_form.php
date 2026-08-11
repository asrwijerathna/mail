<?php include("includes/header.php"); ?>

<?php
// add dbconnection
include ('dbconnect.php');

//create query
$query = "SELECT * FROM letter";
$result = mysqli_query($conn, $query);


?>

<?php
$id=$_GET['id'];
include('dbconnect.php');

$query="SELECT * FROM action_tbl WHERE l_no='$id'";
$result = mysqli_query($conn, $query);

//while($row=mysqli_fetch_assoc($result)){
?>
  
  
<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>ලිපි සම්බන්ධයෙන්ගත් ක්‍රියා මාර්ග වාර්තා කිරීම</h3>

<div class="alert alert-info" role="alert">
1. ලිපිය ලද බව වාර්තා කරන්න.
2. ලිපිය සම්බන්ධයෙන් ඔබ ගත් ක්‍රියාමාර්ගය වාර්තා කරන්න.
3. වැඩ අවසන්වන ලිපි වැඩ අවසන් බව වාර්තා කරන්න. 
</div>

    <div class="row">

    <div class="col-sm-12">
<script>    
    function validateForm() {
    var x = document.forms["status_form"]["action_date"].value;
	var y = document.forms["status_form"]["action"].value;
	var z = document.forms["status_form"]["a_type"].value;

	if (x == "") {
        alert("කරුණාකර දිනය ඇතුලත් කරන්න");
        return false;
    }
	
	if (y == "") {
        alert("කරුණාකර ලියුමේ ගන්නාලද ක්‍රියා මාර්ගය ඇතුලත් කරන්න");
        return false;
    }
		
	if (z == "") {
        alert("කරුණාකර වර්තමාන තත්ත්වය ඇතුලත් කරන්න");
        return false;
    }	
	
}
</script>     
         <form role="form" action="insert_status.php?damid=9" method="post" name="status_form" onsubmit="return validateForm()" >
        
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="action_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
            
            <div class="form-group row radio-pink-gap">
                <label class="col-md-2">වර්ථමාන තත්ත්වය</label>
<!--            <input type="text" name="l_type" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
            	<input name="a_type" type="radio" id="radio100" value="NON">
                <label for="radio100" class="col-md-2">ක්‍රියා කර නැත</label>  -->
                            
                <input name="a_type" type="radio" id="radio102" value="REC" onclick="disableTextbox()">
                <label for="radio102" class="col-md-2">ලිපිය ලැබුණා</label>               
 
                <input name="a_type" type="radio" id="radio103" value="WOK" onclick="enableTextbox()">
                <label for="radio103" class="col-md-2">ක්‍රියා කරමින් පවතී</label>                        
            
                <input name="a_type" type="radio" id="radio101" value="CLD" onclick="enableTextbox()">
                <label for="radio101" class="col-md-2">වැඩ අවසන්</label>  
                
                  
            
<!--                <input name="a_type" type="radio" id="radio102" value="NOT">
                <label for="radio102" class="col-md-2">ක්‍රියාකර නොමැත</label>-->
            </div> 
           
			<div class="form-group row">
                <label class="col-md-2">ගන්නා ලද ක්‍රියා මාර්ගය</label>
                <input type="text" name="action" class="form-control col-md-5" id="text_action" />
            </div>
            

            

            
             <div class="form-group row">
                <label class="col-md-2">ලිපි අංකය</label>
                <input type="text" name="l_no" class="form-control col-md-1" readonly="readonly" value="<?php echo $id; ?>"/>
                
            </div> 
            
        		<button type="submit" class="btn btn-info btn-block col-md-2">ඇතුලත් කරන්න</button>
                
                <?php

				
				if(isset($_GET['back_lno'])){
					$back_lno="&back_lno=".$_GET['back_lno'];
					}else{
						$back_lno="";
						}
				if(isset($_GET['back_recived_date'])){
					$back_recived_date="&back_recived_date=".$_GET['back_recived_date'];
					}else{
						$back_recived_date="";
						}	
				if(isset($_GET['back_letter_date'])){
					$back_letter_date="&back_letter_date=".$_GET['back_letter_date'];
					}else{
						$back_letter_date="";
						}					
				if(isset($_GET['back_sender_no'])){
					$back_sender_no="&back_sender_no=".$_GET['back_sender_no'];
					}else{
						$back_sender_no="";
						}					
				if(isset($_GET['back_sender'])){
					$back_sender="&back_sender=".$_GET['back_sender'];
					}else{
						$back_sender="";
						}							
				if(isset($_GET['back_title'])){
					$back_title="&back_title=".$_GET['back_title'];
					}else{
						$back_title="";
						}				
				if(isset($_GET['back_section_no'])){
					$back_section_no="&back_section_no=".$_GET['back_section_no'];
					}else{
						$back_section_no="";
						}										
				if(isset($_GET['back_subject_no'])){
					$back_subject_no="&back_subject_no=".$_GET['back_subject_no'];
					}else{
						$back_subject_no="";
						}	
				if(isset($_GET['back_type'])){
					$back_type="&back_type=".$_GET['back_type'];					
					}else{
						$back_type="";
						}	
				if(isset($_GET['back_reg_no'])){
					$back_reg_no="&back_reg_no=".$_GET['back_reg_no'];
					}else{
						$back_reg_no="";
						}	
					
					$back_string = $back_lno.$back_recived_date.$back_letter_date.$back_sender_no.$back_sender.$back_title.$back_section_no.$back_subject_no.$back_type.$back_reg_no;						
?>				                				
				<?php
				if($_GET['dam']==2){
				?>
                <br /><a class="btn btn-info" role="button" href="mail_view.php
                <?php	
					//echo $_GET['dam'];
				?>
                ">ආපසු යන්න</a>
                <?php	
				}elseif($_GET['dam']==1){
				?>	
                 <br /><a class="btn btn-info" role="button" href="search.php?dam=1
                <?php
					echo $back_string;
				?>
                ">ආපසු යන්න</a>
                <?php	
					}				
				?>
                                
              
        </form>
    <script>
	function disableTextbox(){
		document.getElementById("text_action").value = "ලිපිය ලැබුණා.";
	<!--	document.getElementById("text_action").disabled = document.getElementById("radio102").checked; -->
		}
		
	function enableTextbox(){
		document.getElementById("text_action").value = "";
		document.getElementById("text_action").disabled = false;
		}		
	
	
    </script>
    
    </div>

    </div>
</div>
<?php
//}
?>

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