<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ServiceID='';
$SubSystemID='';
$$ServiceCharge='';
$ServiceName='';
$PermitCost=0;


if (isset($_REQUEST['PermitCost'])){$PermitCost=$_REQUEST['PermitCost'];}
if (isset($_REQUEST['SubSystemID'])){$SubSystemID=$_REQUEST['SubSystemID'];}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>CONSERVANCY CHARGES SETUP</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Permit Cost (From)</label>
                	<div class="input-control text" data-role="input-control">   
                         <input type="text" name="from" id="from" value="<?php echo $to; ?>">                   	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                </td>
          	</tr>
            <tr>
                <td width="50%">
                    <label>Permit Cost (To)</label>
                    <div class="input-control text" data-role="input-control"> 
                        <input type="text" name="to" id="to" value="<?php echo $to; ?>">                    
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                </td>
            </tr>          
			<tr>
			  <td><label>Sub System</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="SubSystemID"  id="SubSystemID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM SubSystems ORDER BY 1";
						
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["SubSystemID"];
                                $s_name = $row["SubSystemName"];
                                if ($SubSystemID==$s_id) 
                                {
                                    $selected = 'selected="selected"';
                                } else
                                {
                                    $selected = '';
                                }												
                             ?>
                        <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                        <?php 
                            }
                        }
                        ?>
                      </select>
                    
                  </div></td>
			  <td></td>
		  </tr>  
 
			<tr>
                <td width="50%">
                	<label>Conservancy Amount</label>
                	<div class="input-control text" data-role="input-control">
                    	<input type="text" name="Amount" id="Amount" value="<?php echo $Amount; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                </td>
          	</tr>                              
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('conservancy_charges_list.php?'+
                                            '&SubSystemID='+this.form.SubSystemID.value+
                                            '&from='+this.form.from.value+ 
                                            '&to='+this.form.to.value+ 
                                            '&Amount='+this.form.Amount.value+      
        									'&save=1','content','loader','listpages','','ConservancyCharges')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('service_charges_list.php?ServiceID='+<?php echo $ServiceID; ?>+'','content','loader','listpages','','service_charges','<?php echo $ServiceID; ?>')">
	  
	  <!--<input value="Charges" onclick="loadmypage('service_charges_list.php?ServiceID='+1614+'&amp;ServiceName='+this.form.ServiceName.value+'','content','loader','listpages','','service_charges','1614')" type="Button"> -->
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>