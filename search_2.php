<?php include("includes/header.php"); ?>

<?php
// add dbconnection
include ('dbconnect.php');

//create query
$query = "SELECT * FROM letter";
$result = mysqli_query($conn, $query);


?>
  
<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>ලිපි පිළිබඳ සෙවීම් කිරීම</h3>

<div class="alert alert-info" role="alert">
<?php
 	//echo $_GET['idcheck'];
 	if(isset($_GET['idcheck'])){
		$rdate = $_GET['rdate'];
		$ltime = $_GET['ltime'];
		echo "සාර්ථකව ඇතුලත්කරන ලද තොරතුරු : " . $rdate . "," . $ltime;
	} elseif(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		echo "Item " . $delete . " sucsessfully deleted.";
	} else {
		echo " සෙවීම් කිරීම සඳහා දිනය ඇතුලත්කර සොයන්න ක්ලික් කරන්න." ;
	}  
?>
</div>

    <div class="row">
    <div class="col-sm-12">
        <form role="form" action="search_date_range.php" method="post" >   
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >ආරම්භක දිනය ඇතුලත් කරන්න</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="s_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
            
            <div class="row">
                <div class="col-sm-12">
        <form role="form" action="search_date_range.php" method="post" >   
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >අවසාන දිනය ඇතුලත් කරන්න</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="e_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
 			</div>
 
  <!--           
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >ලියුමේ දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="l_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
           
            <div class="form-group row">
                <label class="col-md-2">ලියුමේ දිනය</label>
                <input type="text" name="l_date" class="form-control col-md-5" />
            </div>
            
			<div class="form-group row">
                <label class="col-md-2">ලිපි අංකය</label>
                <input type="text" name="l_no" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">ලිපියේ මාතෘකාව</label>
                <input type="text" name="l_title" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">අංශයේ අංකය</label>
                <input type="text" name="sec_no" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">විශය අංකය</label>
                <input type="text" name="sub_no" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row radio-pink-gap">
                <label class="col-md-2">ලිපි වර්ගය</label>
            <input type="text" name="l_type" class="form-control col-md-5" />
            </div>
            
            <div class="form-group row">
                <input name="l_type" type="radio" id="radio100" value="N">
                <label for="radio100" class="col-md-2">සාමාන්‍ය තැපැල්</label>                        
            
                <input name="l_type" type="radio" id="radio101" value="R">
                <label for="radio101" class="col-md-2">ලියාපදිංචි තැපැල්</label>                     
            
                <input name="l_type" type="radio" id="radio102" value="H">
                <label for="radio102" class="col-md-2">අතින් භාරදුන්</label>
            </div>
            
             <div class="form-group row">
                <label class="col-md-2">ලියාපදිංචි අංකය</label>
                <input type="text" name="r_no" class="form-control col-md-5" />
            </div> -->
            
        		<button type="submit" class="btn btn-info btn-block col-md-2">සොයන්න</button>
        </form>
   
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