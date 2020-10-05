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
			  <td><label>Scrutiny Fees</label>
				<div class="input-control select" data-role="input-control">
					<select name="scrutiny_ServiceID"  id="scrutiny_ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM services where ServiceName like 'Scrutiny%' ORDER BY ServiceName";
					
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
						<input type="text" id="scrutiny_Amount" name="scrutiny_Amount" value="<?php echo $scrutiny_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>
          <tr>
			  <td><label>Application Forms</label>
				<div class="input-control select" data-role="input-control">
					<select name="application_ServiceID"  id="application_ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceName like 'Application%' ORDER BY ServiceName";
					
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
						<input type="text" id="application_Amount" name="application_Amount" value="<?php echo $application_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>
          <tr>
			  <td><label>Public Health</label>
				<div class="input-control select" data-role="input-control">
					<select name="health_ServiceID"  id="health_ServiceID">
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceName like 'Public Health%' ORDER BY ServiceName";
					
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
						<input type="text" id="health_Amount" name="health_Amount" value="<?php echo $health_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>
		  <tr>
			  <td><label>Structural Fees</label>
				<div class="input-control select" data-role="input-control">
					<select name="structural_ServiceID"  id="structural_ServiceID">
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceName like 'Structural%' ORDER BY ServiceName";
					
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
						<input type="text" id="structural_Amount" name="structural_Amount" value="<?php echo $structural_Amount; ?>" style="width: 100px; text-align:right"></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>				  
                  </td>
          </tr>
		  <tr>
			  <td><label>Occupation Permit</label>
				<div class="input-control select" data-role="input-control">
					<select name="occupation_ServiceID"  id="occupation_ServiceID">
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceName like '%Occupation%' ORDER BY ServiceName";
					
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
						<input type="text" id="occupation_Amount" name="occupation_Amount" value="<?php echo $occupation_Amount; ?>" style="width: 100px; text-align:right"></input>
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
                                            '&application_Amount='+this.form.application_Amount.value+ 
											'&health_Amount='+this.form.health_Amount.value+ 
											'&scrutiny_Amount='+this.form.scrutiny_Amount.value+ 
											'&structural_Amount='+this.form.structural_Amount.value+ 
											'&occupation_Amount='+this.form.occupation_Amount.value+ 
											'&Os_Amount='+this.form.Os_Amount.value+ 
                                            '&Description='+this.form.Description.value+ 
											'&application_ServiceID='+this.form.application_ServiceID.value+
											'&health_ServiceID='+this.form.health_ServiceID.value+
											'&scrutiny_ServiceID='+this.form.scrutiny_ServiceID.value+
											'&structural_ServiceID='+this.form.structural_ServiceID.value+
											'&occupation_ServiceID='+this.form.occupation_ServiceID.value+
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