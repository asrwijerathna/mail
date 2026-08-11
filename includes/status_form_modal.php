 
<div class="container bg-light" style="padding-top:20px; padding-bottom:20px">


<div class="alert alert-info" role="alert">
1. ලිපිය ලද බව වාර්තා කරන්න.
2. ලිපිය සම්බන්ධයෙන් ඔබ ගත් ක්‍රියාමාර්ගය වාර්තා කරන්න.
3. වැඩ අවසන්වන ලිපි වැඩ අවසන් බව වාර්තා කරන්න. 
</div>

    <div class="row">

    <div class="col-sm-12">
    
    
         <form role="form" action="insert_status.php?damid=<?php echo $damid; ?>&page_no=<?php echo $page_no; ?>
         <?php if($damid==14){ ?>
         &id=<?php echo $l_id_no.@$back_lno.@$back_recived_date.@$back_letter_date.@$back_sender_no.@$back_sender.@$back_title.@$back_section_no.@$back_subject_no.@$back_type.@$back_reg_no;?>
         <?php } ?>
         " method="post" name="status_form<?php echo $l_id_no;?>" id="status_form<?php echo $l_id_no;?>" onsubmit="return validateForm<?php echo $l_id_no;?>()" >
        
            <div class="form-group row">            	
                <label for="dtp_input2" class="col-md-2" >දිනය</label>  
                <div class="controls">
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" >
                        <input class="form-control col-md-5" type="text" name="action_date" id="action_date" value="" placeholder="YYYY-MM-DD" readonly>
                        <span class="input-group-addon" aria-describedby="basic-addon2">X<span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon" aria-describedby="basic-addon2">DATE<span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	        </div>
            
            <div class="form-group row radio-pink-gap">
                <label class="col-md-2">වර්ථමාන තත්ත්වය</label>

                            
                <input name="a_type" type="radio" id="radio102_<?php echo $l_id_no;?>" value="REC" onclick="disableTextbox<?php echo $l_id_no;?>()">
                <label for="radio102_<?php echo $l_id_no;?>" class="col-md-2">ලිපිය ලැබුණා</label>               
 
                <input name="a_type" type="radio" id="radio103_<?php echo $l_id_no;?>" value="WOK" onclick="enableTextbox<?php echo $l_id_no;?>()">
                <label for="radio103_<?php echo $l_id_no;?>" class="col-md-2">ක්‍රියා කරමින් පවතී</label>                        
            
                <input name="a_type" type="radio" id="radio101_<?php echo $l_id_no;?>" value="CLD" onclick="enableTextbox<?php echo $l_id_no;?>()">
                <label for="radio101_<?php echo $l_id_no;?>" class="col-md-2">වැඩ අවසන්</label>  
                

            </div> 
           
			<div class="form-group row">
                <label class="col-md-2">ගන්නා ලද ක්‍රියා මාර්ගය</label>
                <input type="text" name="action" class="form-control col-md-5" id="text_action" value="" />
            </div>
            

            

            
             <div class="form-group row">
                <label class="col-md-2">ලිපි අංකය</label>
                <input type="text" name="l_no" class="form-control col-md-1" readonly="readonly" value="<?php echo $l_id_no; ?>"/>
                
            </div> 
            
        		<button type="submit" class="btn btn-info btn-block col-md-2">ඇතුලත් කරන්න</button>
             
        </form>
        
        

    
    </div>

    </div>
</div>
<?php
//}
?>


<!-- Javascript Status Insert form validation -->    
<script>    
    function validateForm<?php echo $l_id_no;?>() {
        var x = document.forms["status_form<?php echo $l_id_no;?>"]["action_date"].value;
        var y = document.forms["status_form<?php echo $l_id_no;?>"]["action"].value;
        
        // Correctly get radio button value
        var z = "";
        var radios = document.forms["status_form<?php echo $l_id_no;?>"]["a_type"];
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) { z = radios[i].value; break; }
        }

        if (x == "") {
            alert("කරුණාකර දිනය ඇතුලත් කරන්න");
            return false;
        } else if (z == "") {
            alert("කරුණාකර වර්තමාන තත්ත්වය ඇතුලත් කරන්න");        
            return false;
        } else if (y == "") {
            alert("කරුණාකර ලියුමේ ගන්නාලද ක්‍රියා මාර්ගය ඇතුලත් කරන්න");
            return false;
        } else {
            // Allow normal form submit (POST to insert_status.php)
            return true;
        }   
    }

<!-- Javascript Status Insert form radio button select and insert value to text box function -->  
function disableTextbox<?php echo $l_id_no;?>(){
    document.forms["status_form<?php echo $l_id_no;?>"]["action"].value= "ලිපිය ලැබුණා.";  
    }
    
function enableTextbox<?php echo $l_id_no;?>(){
    document.forms["status_form<?php echo $l_id_no;?>"]["action"].value = "";
    }       
</script>

