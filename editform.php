<?php include("includes/header.php"); ?>


<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">
<h3>ලිපි තොරතුරු සංශෝධනය කිරීම</h3>

<?php
$id=$_GET['id'];
include('dbconnect.php');

$query="SELECT * FROM letter WHERE letter_no='$id'";
$result = mysqli_query($conn, $query);

while($row=mysqli_fetch_assoc($result)){
?>


<div class="row">

<div class="col-sm-12">

<form role="form" action="edit.php" method="post">

 			<div class="form-group row">
                <label class="col-md-2">ලිපි අංකය : </label>
                <input type="text" name="id" class="form-control col-md-1" value="<?php echo $row['letter_no'];?>" readonly="readonly"/>        
            </div>

            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >ලිපිය ලැබුණු දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="l_recive_date" value="<?php echo $row['recived_date'];?>" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
            
            <div class="form-group row">
                <label class="col-md-2">ලියුම ලැබුණු වේලාව</label>
                <input type="text" name="l_time" class="form-control col-md-5" value="<?php echo $row['recived_time'];?>"/>
            </div>
            
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >ලියුමේ දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="l_date" value="<?php echo $row['letter_date'];?>" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
            
<!--            <div class="form-group row">
                <label class="col-md-2">ලියුමේ දිනය</label>
                <input type="text" name="l_date" class="form-control col-md-5" />
            </div>-->

            <div class="form-group row">
                <label class="col-md-2">ලිපිය එවන්නා</label>
                <input type="text" name="sender" class="form-control col-md-5" value="<?php echo $row['sender'];?>" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">ලිපි අංකය</label>
                <input type="text" name="l_no" class="form-control col-md-5" value="<?php echo $row['sender_no'];?>" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">ලිපියේ මාතෘකාව</label>
                <input type="text" name="l_title" class="form-control col-md-5" value="<?php echo $row['title'];?>" />
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">අංශයේ අංකය</label>
                <input type="text" name="sec_no" class="form-control col-md-5" value="<?php echo $row['section_no'];?>"/>
            </div>
            
            <div class="form-group row">
                <label class="col-md-2">විශය අංකය</label>
                <input type="text" name="sub_no" class="form-control col-md-5" value="<?php echo $row['subject_no'];?>" />
            </div>
            
            <div class="form-group row radio-pink-gap">
            <label class="col-md-2">ලිපි වර්ගය</label>

            <?php			
			//$type=$row['type'];
			switch($row['type']){
				case "N":

					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio100\" value=\"N\" checked=\"checked\">";
					echo "<label for=\"radio100\" class=\"col-md-1\">සාමාන්‍ය තැපැල්</label>";                        
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio101\" value=\"R\">";
					echo "<label for=\"radio101\" class=\"col-md-1\">ලියාපදිංචි තැපැල්</label>";                     
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio102\" value=\"H\">";
					echo "<label for=\"radio102\" class=\"col-md-1\">අතින් භාරදුන්</label>";
										
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio103\" value=\"F\" >";
					echo "<label for=\"radio103\" class=\"col-md-1\">Fax</label>";
										
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio104\" value=\"E\" >";
					echo "<label for=\"radio104\" class=\"col-md-1\">E-Mail</label>";
										
					
				break;
				
				case "R":

					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio100\" value=\"N\" >";
					echo "<label for=\"radio100\" class=\"col-md-1\">සාමාන්‍ය තැපැල්</label>";                        
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio101\" value=\"R\" checked=\"checked\">";
					echo "<label for=\"radio101\" class=\"col-md-1\">ලියාපදිංචි තැපැල්</label>";                     
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio102\" value=\"H\">";
					echo "<label for=\"radio102\" class=\"col-md-1\">අතින් භාරදුන්</label>";
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio103\" value=\"F\" >";
					echo "<label for=\"radio103\" class=\"col-md-1\">Fax</label>";
										
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio104\" value=\"E\" >";
					echo "<label for=\"radio104\" class=\"col-md-1\">E-Mail</label>";
	
					
				break;
				
				case "H":
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio100\" value=\"N\" >";
					echo "<label for=\"radio100\" class=\"col-md-1\">සාමාන්‍ය තැපැල්</label>";                        
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio101\" value=\"R\" >";
					echo "<label for=\"radio101\" class=\"col-md-1\">ලියාපදිංචි තැපැල්</label>";                     
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio102\" value=\"H\" checked=\"checked\">";
					echo "<label for=\"radio102\" class=\"col-md-1\">අතින් භාරදුන්</label>";
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio103\" value=\"F\" >";
					echo "<label for=\"radio103\" class=\"col-md-1\">Fax</label>";
										
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio104\" value=\"E\" >";
					echo "<label for=\"radio104\" class=\"col-md-1\">E-Mail</label>";
						
					
				break;
				
				case "F":
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio100\" value=\"N\" >";
					echo "<label for=\"radio100\" class=\"col-md-1\">සාමාන්‍ය තැපැල්</label>";                        
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio101\" value=\"R\" >";
					echo "<label for=\"radio101\" class=\"col-md-1\">ලියාපදිංචි තැපැල්</label>";                     
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio102\" value=\"H\" >";
					echo "<label for=\"radio102\" class=\"col-md-1\">අතින් භාරදුන්</label>";
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio103\" value=\"F\" checked=\"checked\">";
					echo "<label for=\"radio103\" class=\"col-md-1\">Fax</label>";
										
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio104\" value=\"E\" >";
					echo "<label for=\"radio104\" class=\"col-md-1\">E-Mail</label>";
							
					
				break;
				
				case "E":
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio100\" value=\"N\" >";
					echo "<label for=\"radio100\" class=\"col-md-1\">සාමාන්‍ය තැපැල්</label>";                        
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio101\" value=\"R\" >";
					echo "<label for=\"radio101\" class=\"col-md-1\">ලියාපදිංචි තැපැල්</label>";                     
				
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio102\" value=\"H\" >";
					echo "<label for=\"radio102\" class=\"col-md-1\">අතින් භාරදුන්</label>";
					
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio103\" value=\"F\" >";
					echo "<label for=\"radio103\" class=\"col-md-1\">Fax</label>";
										
					echo "<input name=\"l_type\" type=\"radio\" id=\"radio104\" value=\"E\" checked=\"checked\">";
					echo "<label for=\"radio104\" class=\"col-md-1\">E-Mail</label>";
												
					
				break;				
				
				}
			
			?>
			</div>
            
             <div class="form-group row">
                <label class="col-md-2">ලියාපදිංචි අංකය</label>
                <input type="text" name="r_no" class="form-control col-md-5" value="<?php echo $row['reg_no'];?>"/>
            </div> 
            
            
			<div class="form-group row radio-pink-gap">
            <label class="col-md-2">වැදගත් බව</label>       
		
            <?php	
				switch($row['important']){
				case "n":

					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio300\" value=\"n\" checked=\"checked\">";
					echo "<label for=\"radio300\" class=\"col-md-1\">සාමාන්‍ය</label>";                        
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio301\" value=\"i\">";
					echo "<label for=\"radio301\" class=\"col-md-1\">ඉතා හදිස්සි</label>";                     
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio302\" value=\"r\">";
					echo "<label for=\"radio302\" class=\"col-md-1\">සිහි කැඳවීම්</label>";
					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio303\" value=\"p\">";
					echo "<label for=\"radio303\" class=\"col-md-1\">මහජන පැමිණිලි</label>"; 

				break;
				
				case "i":

					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio300\" value=\"n\">";
					echo "<label for=\"radio300\" class=\"col-md-1\">සාමාන්‍ය</label>";                        
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio301\" value=\"i\" checked=\"checked\">";
					echo "<label for=\"radio301\" class=\"col-md-1\">ඉතා හදිස්සි</label>";                     
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio302\" value=\"r\">";
					echo "<label for=\"radio302\" class=\"col-md-1\">සිහි කැඳවීම්</label>";
					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio303\" value=\"p\">";
					echo "<label for=\"radio303\" class=\"col-md-1\">මහජන පැමිණිලි</label>"; 

				break;
				
				case "r":

					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio300\" value=\"n\">";
					echo "<label for=\"radio300\" class=\"col-md-1\">සාමාන්‍ය</label>";                        
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio301\" value=\"i\">";
					echo "<label for=\"radio301\" class=\"col-md-1\">ඉතා හදිස්සි</label>";                     
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio302\" value=\"r\" checked=\"checked\">";
					echo "<label for=\"radio302\" class=\"col-md-1\">සිහි කැඳවීම්</label>";
					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio303\" value=\"p\">";
					echo "<label for=\"radio303\" class=\"col-md-1\">මහජන පැමිණිලි</label>"; 

				break;		

				case "p":

					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio300\" value=\"n\">";
					echo "<label for=\"radio300\" class=\"col-md-1\">සාමාන්‍ය</label>";                        
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio301\" value=\"i\">";
					echo "<label for=\"radio301\" class=\"col-md-1\">ඉතා හදිස්සි</label>";                     
				
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio302\" value=\"r\">";
					echo "<label for=\"radio302\" class=\"col-md-1\">සිහි කැඳවීම්</label>";
					
					echo "<input name=\"l_impo\" type=\"radio\" id=\"radio303\" value=\"p\" checked=\"checked\">";
					echo "<label for=\"radio303\" class=\"col-md-1\">මහජන පැමිණිලි</label>";   

				break;					
				
				}			
			
			?>
            </div>
            
        		<button type="submit" class="btn btn-info btn-block col-md-2">සංශෝධනය කරන්න</button>
			
<?php 
} 
//mysqli_close($conn);
?>
</form>

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