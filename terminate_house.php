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
$RecipientName='';
$R_EstateID='';
$D_UHN='';
$R_UHN='';
$CurrentTenant='';
$R_Tenant='';
$transfer_amount='';
$To_CustomerID='';
$to_CustomerID='';
$From_CustomerID='';

if ($_REQUEST['search']==1){
	if(isset($_REQUEST['EstateID']))
	{
		$CurrentTenant='';
		$DonorBalance=0;
		$EstateID=$_REQUEST['EstateID'];		
		if(isset($_REQUEST['HouseNumber']))
		{	
	
			$HouseNumber=$_REQUEST['HouseNumber'];		
			$sql="Select Balance,CurrentTenant,uhn,CustomerID from tenancy where HouseNumber='$HouseNumber'";
			$result=sqlsrv_query($db,$sql);
			
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{
				
				$DonorBalance=$rw['Balance'];
				$CurrentTenant=$rw['CurrentTenant'];
				$From_CustomerID=$rw['CustomerID'];
				$D_UHN=$rw['uhn'];
			}		
		}
	}

	if($_REQUEST['Terminate']==1)
	{
		$Reason=$_REQUEST['Reason'];
		$to_CustomerID=$_REQUEST['CustomerID'];
		$RecipientName=$_REQUEST['RecipientName'];
		$To_CustomerID='0';
		
		$DonorBalance=(Double)$DonorBalance*(-1);
		$Description="Tenancy Termination";
		$DocumentNo=Time();
		if($CurrentTenant=="")
		{
			$msg="The House To be Terminated is not stated";
		}else{		
			
			$sql="Set dateformat dmy insert into HouseReceipts (uhn,EstateID,HouseNumber,DateReceived,[Description],DocumentNo,Amount,Balance) 
			Values('$D_UHN',$EstateID,'$HouseNumber',getdate(),'$Description','$DocumentNo','$DonorBalance','0')";
			
			$result=sqlsrv_query($db,$sql);
			if($result)
			{				
				$sql="Set dateformat dmy update Tenancy set CustomerID='$To_CustomerID',Balance='0',CurrentTenant='Vacant' 
				where HouseNumber='$HouseNumber' and EstateID='$EstateID'";
				
				$result=sqlsrv_query($db,$sql);
				if($result)
				{
					echo $sql;
					$sql="Set dateformat dmy 
					insert into TenancyTransfer (EstateID,HouseNumber,From_CustomerID,To_CustomerID,[Description],BalanceAtTransfer,CreatedBy) 
					Values('$EstateID','$HouseNumber','$From_CustomerID','$To_CustomerID','Termination ($Reason)',$DonorBalance*(-1),$CreatedUserID)";
					$result=sqlsrv_query($db,$sql);
					if($result)
					{
						$sql=" ";
						$result=sqlsrv_query($db,$sql);
						if($result)
						{
							$msg="Termination Successful";
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
			}else
			{
					echo $sql;
					DisplayErrors();				
			}
		}	
	}	
}
if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>Tenancy Termination</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="4" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="30%"><label>Estate</label>
					<div class="input-control select" data-role="input-control">						
						<select name="EstateID"  id="EstateID" onchange="loadmypage('terminate_house.php?search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+													
													'&DonorBalance='+this.form.DonorBalance.value+                                                    
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
						<select name="HouseNumber"  id="HouseNumber" onchange="loadmypage('terminate_house.php?search=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+													
													'&DonorBalance='+this.form.DonorBalance.value+                                                    
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
			<td colspan="2">
				<label>Reason For Termination</label>
				<div class="input-control textArea" data-role="input-control">
					<textArea name="Reason" id="Reason" width="300"></textArea>
				</div>	
			</td>			
			<td>				
			</td>
		</tr>			                    
        </table>
		<input name="Button" type="button" onclick="deleteConfirm2('Are you sure you want to Terminate the Tenancy?','terminate_house.php?transfer=1'+
        											'&HouseNumber='+this.form.HouseNumber.value+
													'&EstateID='+this.form.EstateID.value+													
													'&DonorBalance='+this.form.DonorBalance.value+
													'&Reason='+this.form.Reason.value+				
                                                    '&Terminate=1&search=1','content','loader','listpages')" value="Terminate">

	</fieldset>
</form>
</div>