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

$IsService='';
$ServiceID='';
$Description='';
$ServiceTreeID='0';
$ParentID ='';
$CreatedDate="";
$CreatedUserID="";
$IsItService='';
$ChargeID='0';

$Inspection_Amount=2000;
$Application_Amount=1000;

$Inspection_ServiceID='';
$Application_ServiceID='';

if (isset($_REQUEST['edit']))
{	
	$ServiceTreeID=	$_REQUEST['ServiceTreeID'];
	
	$sql = "SELECT * FROM ServiceTrees where ServiceTreeID = $ServiceTreeID";
	$result = sqlsrv_query($db, $sql);

   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$IsService=$myrow['IsService'];
		$ParentID=$myrow['ParentID'];		
		$Description=$myrow['Description'];
		$ServiceID=$myrow['ServiceID'];	
	}
		
	if ($IsService == 1) 
	{
		$IsItService = 'checked="checked"';
	}	
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Micelleneous Charges</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
			  <br>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Customer Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>			
			<tr>
                <td width="50%">
                	<label>Charge Description</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="Description" id="Description"><?php echo $Description; ?></textarea>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>  
          <tr>
			  <td><label>Liquor Application Fee</label>
				<div class="input-control select" data-role="input-control">
					<select name="Application_ServiceID"  id="Application_ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceID='2813' ORDER BY ServiceName";
					
					$s_result = sqlsrv_query($db, $s_sql);
					if ($s_result) 
					{ //connection succesful 
						while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						{
							$s_id = $row["ServiceID"];
							$s_name = $row["ServiceName"];
							if ($ServiceID==$s_id) 
							{
								$selected = 'selected="selected"';
							} else
							{
								$selected = 'selected="selected"';
							}												
						 ?>
					<option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
					<?php 
						}
					}
					?>
				  </select>
                  </div></td>
                  <td>
					<label>Amount</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="Application_Amount" name="Application_Amount" value="<?php echo $Application_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>
          <tr>
			  <td><label>Inspection Fee</label>
				<div class="input-control select" data-role="input-control">
					<select name="Inspection_ServiceID"  id="Inspection_ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceID='2812' ORDER BY ServiceName";
					
					$s_result = sqlsrv_query($db, $s_sql);
					if ($s_result) 
					{ //connection succesful 
						while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						{
							$s_id = $row["ServiceID"];
							$s_name = $row["ServiceName"];
							if ($ServiceID==$s_id) 
							{
								$selected = 'selected="selected"';
							} else
							{
								$selected = 'selected="selected"';
							}												
						 ?>
					<option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
					<?php 
						}
					}
					?>
				  </select>
                  </div></td>
                  <td>
					<label>Amount</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="Inspection_Amount" name="Inspection_Amount" value="<?php echo $Inspection_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>
          <tr>
			  <td><label>Other Service</label>
				<div class="input-control select" data-role="input-control">
					<select name="os_ServiceID"  id="os_ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM Services ORDER BY ServiceName";
					
					$s_result = sqlsrv_query($db, $s_sql);
					if ($s_result) 
					{ //connection succesful 
						while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						{
							$s_id = $row["ServiceID"];
							$s_name = $row["ServiceName"];
							if ($ServiceID==$s_id) 
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
                  <td>
					<label>Amount</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="Os_Amount" name="Amount" value="<?php echo $Os_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>            
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('miscellaneous_list.php?'+
                                            '&CustomerName='+this.form.CustomerName.value+
                                            '&Application_Amount='+this.form.Application_Amount.value+ 
											'&Inspection_Amount='+this.form.Inspection_Amount.value+ 
											'&Os_Amount='+this.form.Os_Amount.value+ 
                                            '&Description='+this.form.Description.value+ 
											'&liquor_applic_ServiceID='+this.form.Application_ServiceID.value+
											'&Inspection_ServiceID='+this.form.Inspection_ServiceID.value+
                                            '&os_ServiceID='+this.form.os_ServiceID.value+
											'&ChargeID='+<?php echo $ChargeID ?>+											
        									'&save=1','content','loader','listpages','','Miscellaneous')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('miscellaneous_list.php?i=1','content','loader','listpages','','Miscellaneous')">
	 <!-- <input name="createFlatWindow" id="createFlatWindow" type="button" class="button"  value="Create Flat Window" onclick="flatWindow()"> -->
        
		<div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>