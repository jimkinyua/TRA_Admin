<?php 
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');
	
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$EstateID='';
$HouseNumber='';
$DonorBalance=0;
$R_Balance=0;
$R_HouseNumber='';
$R_EstateID='';
$D_UHN='';
$R_UHN='';
$CurrentTenant='';
$R_Tenant='';
$transfer_amount='';

if ($_REQUEST['search']==1){
	
	if(isset($_REQUEST['EstateID']))
	{
		echo $CurrentTenant;
		$CurrentTenant='';
		$DonorBalance=0;
		$EstateID=$_REQUEST['EstateID'];		
		if(isset($_REQUEST['HouseNumber']))
		{		
			$HouseNumber=$_REQUEST['HouseNumber'];		
			$sql="Select Balance,CurrentTenant,uhn from tenancy where HouseNumber='$HouseNumber'";
			$result=sqlsrv_query($db,$sql);
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{
				$DonorBalance=$rw['Balance'];
				$CurrentTenant=$rw['CurrentTenant'];
				$D_UHN=$rw['uhn'];
			}		
		}
	}
	if(isset($_REQUEST['R_EstateID']))
	{
		$R_Tenant='';
		$R_Balance='';
		$R_EstateID=$_REQUEST['R_EstateID'];
		
		if(isset($_REQUEST['R_HouseNumber']))
		{
			
			$R_HouseNumber=$_REQUEST['R_HouseNumber'];		
			$sql="Select Balance,CurrentTenant,uhn from tenancy where HouseNumber='$R_HouseNumber'";
			$result=sqlsrv_query($db,$sql);
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{
				$R_Balance=$rw['Balance'];
				$R_Tenant=$rw['CurrentTenant'];
				$R_UHN=$rw['uhn'];
			}
		}
	}
	if(isset($_REQUEST['transfer_amount'])){$transfer_amount=$_REQUEST['transfer_amount'];}
	
	if($_REQUEST['transfer']==1)
	{
		if((double)$DonorBalance>1)
		{
			$msg="The Donor must have overpaid to be able to transfer";
		}else if(abs((double)$DonorBalance)<$transfer_amount)
		{
			$msg="The transfer amount is more that the overpaid";
		}else{
			$msg= 'You can transfer';
		
		
		
			//remove from donor
			$DonorBalance+=$transfer_amount;
			$sql="Set dateformat dmy insert into HouseReceipts (uhn,EstateID,HouseNumber,DateReceived,[Description],DocumentNo,Amount,Balance) 
				Values($D_UHN,$EstateID,'$HouseNumber',getdate(),'Transfer To $R_HouseNumber','Transfer To $R_HouseNumber',$transfer_amount,$DonorBalance)";
			
			$result=sqlsrv_query($db,$sql);
			if($result)
			{
				$sql=" UPDATE Tenancy SET Balance=$DonorBalance where HouseNumber='$HouseNumber' and EstateID='$EstateID'";
				$result=sqlsrv_query($db,$sql);
				if($result)
				{
					//Recipient
					$transfer_amount=(double)$transfer_amount*(-1);
					$R_Balance+=$transfer_amount;
					$sql="Set dateformat dmy insert into HouseReceipts (uhn,EstateID,HouseNumber,DateReceived,[Description],DocumentNo,Amount,Balance) 
					Values($R_UHN,$R_EstateID,'$R_HouseNumber',getdate(),'Transfer From $HouseNumber','Transfer From $HouseNumber',$transfer_amount,$R_Balance)";
					
					$result=sqlsrv_query($db,$sql);
					if($result)
					{
						$sql=" UPDATE Tenancy SET Balance='$R_Balance' where HouseNumber='$R_HouseNumber' and EstateID='$R_EstateID'";
						$result=sqlsrv_query($db,$sql);
						if($result)
						{
							$msg="Transfer Successful";
							$transfer_amount=0;
						}
					}else
					{						
						DisplayErrors();
					}		
					
				}else
				{					
					DisplayErrors();
				}
			}else
			{
				echo $sql;
				DisplayErrors();
			}

		}
		
	}	
}

{
	
}
if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>House To House Transfer Of Funds</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="4" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="30%"><label>Estate</label>
					<div class="input-control select" data-role="input-control">						
						<select name="EstateID"  id="EstateID" onchange="loadmypage('funds_transfer_house.php?search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+													
													'&DonorBalance='+this.form.DonorBalance.value+
                                                    '&R_HouseNumber='+this.form.R_HouseNumber.value+
													'&R_Balance='+this.form.R_Balance.value+
                                                    '','content','loader','listpages')">
							<option value="0" selected="selected"></option>
							<?php 
							$s_sql = "SELECT * FROM Estates ORDER BY EstateName";									
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["EstateID"];
									$s_name = $row["EstateName"];
									if ($EstateID==$s_id) 
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
					</div>
				</td>
				<td>
					<label>House No</label>
						<div class="input-control select" data-role="input-control">
						<select name="HouseNumber"  id="HouseNumber" onchange="loadmypage('funds_transfer_house.php?search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+													
													'&DonorBalance='+this.form.DonorBalance.value+
                                                    '&R_HouseNumber='+this.form.R_HouseNumber.value+
													'&R_Balance='+this.form.R_Balance.value+
                                                    '','content','loader','listpages')">
							<option value="0" selected></option>
							<?php 
							$s_sql = "SELECT HouseNumber FROM Houses where EstateID='$EstateID'  ORDER BY HouseNumber";									
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["HouseNumber"];
									$s_name = $row["HouseNumber"];
									if ($HouseNumber==$s_id) 
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
					</div>						
				</td>					
				<td>
					<label>Current Tenant</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="CurrentTenant" name="CurrentTenant" value="<?php echo $CurrentTenant; ?>" disabled></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>						
				</td>
				<td>
					<label>Current Balance</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="DonorBalance" name="DonorBalance" value="<?php echo $DonorBalance; ?>" disabled></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>						
				</td>
            </tr> 
		  </tr>
           <tr>
                <td><label>Recipient Estate</label>
					<div class="input-control select" data-role="input-control">						
						<select name="R_EstateID"  id="R_EstateID" onchange="loadmypage('funds_transfer_house.php?search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+
													'&R_EstateID='+this.form.R_EstateID.value+														
													'&DonorBalance='+this.form.DonorBalance.value+
                                                    '&R_HouseNumber='+this.form.R_HouseNumber.value+
													'&R_Balance='+this.form.R_Balance.value+
                                                    '','content','loader','listpages')">
							<option value="0" selected="selected"></option>
							<?php 
							$s_sql = "SELECT * FROM Estates ORDER BY EstateName";									
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["EstateID"];
									$s_name = $row["EstateName"];
									if ($R_EstateID==$s_id) 
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
					</div>
				</td>
				<td>
					<label>House No</label>
					<div class="input-control select" data-role="input-control">
						<select name="R_HouseNumber"  id="R_HouseNumber" onchange="loadmypage('funds_transfer_house.php?search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+
													'&R_EstateID='+this.form.R_EstateID.value+														
													'&DonorBalance='+this.form.DonorBalance.value+
                                                    '&R_HouseNumber='+this.form.R_HouseNumber.value+
													'&R_Balance='+this.form.R_Balance.value+
                                                    '','content','loader','listpages')">
							<option value="0" selected></option>
							<?php 
							$s_sql = "SELECT HouseNumber FROM Houses where EstateID=$R_EstateID ORDER BY HouseNumber";									
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["HouseNumber"];
									$s_name = $row["HouseNumber"];
									if ($R_HouseNumber==$s_id) 
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
					</div>						
				</td>					
				<td>
					<label>Current Tenant</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="R_Tenant" name="R_Tenant" value="<?php echo $R_Tenant; ?>" disabled></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>						
				</td>
				<td>
					<label>Current Balance</label>
					<div class="input-control text" data-role="input-control">
						<input type="text" id="R_Balance" name="R_Balance" value="<?php echo $R_Balance; ?>" disabled></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>						
				</td>
            </tr> 
		  </tr> 
			<tr>
				<td width="100px">
					<label>Transfer Amount</label>
					<div class="input-control text" data-role="input-control" align="right">
						<input type="text" id="amount" name="amount" value="<?php echo $transfer_amount; ?>" ></input>
						<button class="btn-clear" tabindex="-1"></button>
					</div>
				</td>
				<td>            
				</td>
				<td>            
				</td>
			</tr>			                    
        </table>
		<input name="Button" type="button" onclick="deleteConfirm2('Are you sure you want to Transfer those Funds?','funds_transfer_house.php?transfer=1'+
													'&search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+
													'&R_EstateID='+this.form.R_EstateID.value+														
													'&DonorBalance='+this.form.DonorBalance.value+
                                                    '&R_HouseNumber='+this.form.R_HouseNumber.value+
													'&R_Balance='+this.form.R_Balance.value+
													'&transfer_amount='+this.form.amount.value+													
                                                    '','content','loader','listpages')" value="Transfer">

	</fieldset>
</form>
</div>