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
$ActiveUserID = $_SESSION['UserID'];
//$Createdplotno = $_SESSION['plotno'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$PageID=52;
$myRights=getrights($db,$ActiveUserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

//echo $Edit;

$plotno='';
$lrno='';
$OwnerName='';
$MotherPlotNo='';
$RatesPayable='';
$GroundRent=0;
$GroundRentBalance=0;
$OtherCharges=0;
$OtherChargesBalance=0;
$ApplicationID='';
$Url='';
$upn='0';
$OwnerPin='';
$OwnerID='';
$FarmID=0;
$editting=0;
$disabled='disabled';
$Action='';

$ApplicationID = isset($_REQUEST['ApplicationID']) ? $_REQUEST['ApplicationID'] : 0;	
$OwnerName = isset($_REQUEST['OwnerName']) ? $_REQUEST['OwnerName'] : '';
$Action=isset($_REQUEST['register']) ? 'Add' : isset($_REQUEST['edit']) ? 'Edit' : isset($_REQUEST['modify']) ? 'Edit' : 'View';

$sql="select c.customername from customer c join ServiceHeader sh on sh.CustomerID=c.CustomerID where sh.ServiceHeaderID=$ApplicationID";
$result=sqlsrv_query($db,$sql);
while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{
	$OwnerName=$row['CustomerName'];
}

if (isset($_REQUEST['edit']))
{	
	$upn = $_REQUEST['upn'];
	$editting=1;

	if($Edit==1){
		$disabled='';
	}else{
		$disabled='disabled';
	}

	$sql = "SELECT l.* 
	FROM land l left join LandApplication la on la.lrn=l.lrn and la.plotno=l.plotno 
	where l.upn = $upn";
	
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$lrno=$myrow['LRN'];
		$plotno=$myrow['PlotNo'];
		$FarmID=$myrow['FirmID'];
		$OwnerName=$myrow['LaifomsOwner'];
		$MotherPlotNo=$myrow['MPlotNo'];
		$RatesPayable=$myrow['RatesPayable'];
		$Penalty=$myrow['PenaltyBalance'];
		$PrincipalBal=$myrow['PrincipalBal'];
		$Balance=$myrow['Balance'];
		$LocalAuthorityID=$myrow['LocalAuthorityID'];
		$SiteValue=$myrow['SiteValue'];
		$AreaInHa=$myrow['AreaInHa'];
		$Area=$myrow['Area'];
		$TitleYear=$myrow['TitleYear'];
		$GroundRent=$myrow['GroundRent'];
		$OtherCharges=$myrow['OtherCharges'];
	}	
}
if (isset($_REQUEST['modify']))
{
	//print_r($_REQUEST); exit;
	$upn = $_REQUEST['upn'];
	$Penalty=$_REQUEST['Penalty'];
	$plotno=$_REQUEST['plotno'];
	$newPlotno=$_REQUEST['newPlotno'];
	$newLrno=$_REQUEST['newLrno'];
	$lrno=$_REQUEST['lrno'];
	$RatesPayable=$_REQUEST['RatesPayable'];
	$PrincipalBal=$_REQUEST['PrincipalBal'];
	$GroundRent=$_REQUEST['GroundRent'];
	$GroundRentBalance=$_REQUEST['GroundRentBalance'];
	$OtherCharges=$_REQUEST['OtherCharges'];
	$OtherChargesBalance=$_REQUEST['OtherChargesBalance'];
	$SiteValue=$_REQUEST['SiteValue'];
	$AreaInHa=$_REQUEST['Area'];
	$LocalAuthorityID=$_REQUEST['LocalAuthorityID'];
	$TitleYear=$_REQUEST['TitleYear'];

	$Area=(double)$AreaInHa*2.4765;

	if($LocalAuthorityID==96 || $LocalAuthorityID==800 || $LocalAuthorityID==600)
	{
		$RatesPayable=0.02*(double)$SiteValue;					
	}else{
		$RatesPayable=60;
		$excess=0;
		$acres=$Area;
		if((double)$acres>5){
			$excess=ceil((double)$acres-5)*10;				
		}
		$RatesPayable+=(double)$excess; 
	}
	
	$sql = "Update Land set 
	RatesPayable='$RatesPayable',GroundRent='$GroundRent',OtherCharges='$OtherCharges',GroundRentBalance='$GroundRentBalance',OtherChargesBalance='$OtherChargesBalance',PenaltyBalance='$Penalty',PrincipalBalance='$PrincipalBal',Balance=isnull(PrincipalBalance,0)+$Penalty+$OtherChargesBalance+$GroundRentBalance	,AreaInHa=$AreaInHa,Area=$Area,TitleYear='$TitleYear',SiteValue='$SiteValue',LocalAuthorityID=$LocalAuthorityID,lrn='$newLrno',PlotNo='$newPlotno' where upn = $upn";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
   	if($result){
   		$sql="update landApplication set plotno='$newPlotno',lrn='$newLrno' where lrn='$lrno' and PlotNo='$plotno'";
   		//echo $sql;
   		$result = sqlsrv_query($db, $sql);
   		if($result){

   			$rst=SaveTransaction($db,$ActiveUserID,"Modified plot details for block ".$lrno." Plot No ".$plotno. " Upn No.".$upn);

			if ($rst[0]==0)
			{
				$msg=$rst[1];
			}else
			{
				
			}

			$msg="Plot Modified succesfully";
		}
	}else{
		DisplayErrors();
	}

	$sql = "SELECT l.* 
	FROM land l left join LandApplication la on la.lrn=l.lrn and la.plotno=l.plotno 
	where l.upn = $upn";
	
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$lrno=$myrow['LRN'];
		$plotno=$myrow['PlotNo'];
		$FarmID=$myrow['FirmID'];
		$OwnerName=$myrow['LaifomsOwner'];
		$MotherPlotNo=$myrow['MPlotNo'];
		$RatesPayable=$myrow['RatesPayable'];
		$Penalty=$myrow['PenaltyBalance'];
		$PrincipalBal=$myrow['PrincipalBal'];
		$Balance=$myrow['Balance'];
		$LocalAuthorityID=$myrow['LocalAuthorityID'];
		$SiteValue=$myrow['SiteValue'];
		$AreaInHa=$myrow['AreaInHa'];
		$Area=$myrow['Area'];
		$TitleYear=$myrow['TitleYear'];
		$GroundRent=$myrow['GroundRent'];
		$OtherCharges=$myrow['OtherCharges'];
	}

	if($Edit==1){
		$disabled='';
	}else{
		$disabled='disabled';
	}	
}
if (isset($_REQUEST['add']))
{	
	$ApplicationID = isset($_REQUEST['ApplicationID']) ? $_REQUEST['ApplicationID'] : 0;	
	$OwnerName = isset($_REQUEST['OwnerName']) ? $_REQUEST['OwnerName'] : '';
	$CustomerID = isset($_REQUEST['CustomerID']) ? $_REQUEST['CustomerID'] : '';
	
	$sql = "SELECT * FROM landApplication where ServiceHeaderID = $ApplicationID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$lrno=$myrow['LRN'];
		$plotno=$myrow['PlotNo'];
		$MotherPlotNo=$myrow['MPlotNo'];
		$title=$myrow['TitleNo'];
	}

	$sql="select value from dbo.fnFormData($ApplicationID) WHERE FormColumnID='13270'";
	$result=sqlsrv_query($db,$sql);
	while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$LocalAuthorityID=$rw['value'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Plot Details</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
		  <tr>
			<td>
				<label width="20%">Authority</label>
					<div class="input-control select" data-role="input-control">						
						<select name="LocalAuthorityID"  id="LocalAuthorityID" <?php if($Edit==0){echo "disabled";} ?>>
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM LocalAuthority ORDER BY LocalAuthorityID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["LocalAuthorityID"];
                                    $s_name = $row["LocalAuthorityName"];
                                    if ($LocalAuthorityID==$s_id) 
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
			<td width="50%"><label>Upn</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="upn" type="text" id="upn" value="<?php echo $upn; ?>" disabled>
                        <button class="btn-clear" tabindex="-1"></button> 
                    </div>
                </td>
			</tr>
			<tr>
                <td width="50%">
                	<label>Plot No</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="plotno" type="text" id="plotno" value="<?php echo $plotno; ?>" <?php if($Edit==0){echo "disabled";} ?>>
                    	<input type="hidden" name="oldplotno" id="oldplotno" value="<?php echo $plotno; ?>">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%"><label>LR No</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="lrno" type="text" id="lrno" value="<?php echo $lrno; ?>" <?php if($Edit==0){echo "disabled";} ?>>
                    	<input type="hidden" name="oldlrno" id="oldlrno" value="<?php echo $lrno; ?>">
                        <button class="btn-clear" tabindex="-1"></button> 
                    </div>
                </td>
          	</tr>
			<tr>
                <td width="50%">
               	  <label>Mother PlotNo</label>
               	  <div class="input-control text" data-role="input-control">
                   	  <input name="MotherPlotNo" type="text" id="MotherPlotNo" value="<?php echo $MotherPlotNo; ?>" disabled>
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>			
				<td width="50%">
					<label>Title Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="title" type="text" id="title" value="<?php echo $title; ?>" disabled>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>	
                </td>

          	</tr>
						
			<tr>
				<td colspan="2">
					<table width="100%">
						<tr>
							<td width="33%">
							  <label>Rates Payable</label>
							  <div class="input-control text" data-role="input-control">
								  <input name="RatesPayable" type="text" id="RatesPayable" value="<?php echo $RatesPayable; ?>" disabled>
									<button class="btn-clear" tabindex="-1"></button>
							  </div>
							</td>
							<td width="33%">
								<label>Ground Rent (Payable)</label>
								<div class="input-control text" data-role="input-control">
									<input name="GroundRent" type="text" id="GroundRent" value="<?php echo $GroundRent; ?>" <?php if($Edit==0){echo "disabled";} ?>>
									<button class="btn-clear" tabindex="-1"></button>
								</div>	
							</td>
							<td width="33%">
								<label>Other Charges (Payable)</label>
								<div class="input-control text" data-role="input-control">
									<input name="OtherCharges" type="text" id="OtherCharges" value="<?php echo $OtherCharges; ?>"<?php if($Edit==0){echo "disabled";} ?>>
									<button class="btn-clear" tabindex="-1"></button>
								</div>	
							</td>
						</tr>
					</table>
				</td>
          	</tr>
			<tr>
				<td colspan="2">
					<table width="100%">
						<tr>
							<td width="25%">
								<label>Land Rates (Balance)</label>
								<div class="input-control text" data-role="input-control">
									<input name="PrincipalBal" type="text" id="PrincipalBal" value="<?php echo $Balance; ?>" disabled>
									<button class="btn-clear" tabindex="-1"></button>
								</div>	
							</td>														
							<td width="25%">
								<label>Penalty (Balance)</label>
								<div class="input-control text" data-role="input-control">
									<input name="Penalty" type="text" id="Penalty" value="<?php echo $Penalty; ?>" disabled>
									<button class="btn-clear" tabindex="-1"></button>
								</div>	
							</td>
							<td width="25%">
							  <label>Ground Rent (Balance)</label>
							  <div class="input-control text" data-role="input-control">
								  <input name="GroundRentBalance" type="text" id="GroundRentBalance" value="<?php echo $GroundRentBalance; ?>" disabled>
									<button class="btn-clear" tabindex="-1"></button>
							  </div>
							</td>
							<td width="25%">
							  <label>Other Charges (Balance)</label>
							  <div class="input-control text" data-role="input-control">
								  <input name="OtherChargesBalance" type="text" id="OtherChargesBalance" value="<?php echo $OtherChargesBalance; ?>" disabled>
									<button class="btn-clear" tabindex="-1"></button>
							  </div>
							</td>
						</tr>
					</table>
				</td>
          	</tr>
			
			<tr>                
                <td colspan="2">
                	<table width="100%">
                		<tr>
                			<td><label>Farm</label>
				                    <div class="input-control select" data-role="input-control">
				                    	
				                    	<select name="FarmID"  id="FarmID" <?php if($Edit==0){echo "disabled";} ?>>
				                            <option value="0" selected="selected"></option>
				                            <?php 
				                            $s_sql = "SELECT * FROM LandFirms ORDER BY 1";
				                            
				                            $s_result = sqlsrv_query($db, $s_sql);
				                            if ($s_result) 
				                            { //connection succesful 
				                                while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
				                                {
				                                    $s_id = $row["FirmID"];
				                                    $s_name = $row["FirmName"];
				                                    if ($FarmID==$s_id) 
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
	                		<td>
	                			<label>Land Value</label>
			                	<div class="input-control text" data-role="input-control">
			                        <input name="SiteValue" type="text" id="SiteValue" value="<?php echo $SiteValue; ?>" <?php if($Edit==0){echo "disabled";} ?>>
			                        <button class="btn-clear" tabindex="-1"></button>
			                  </div>
							</td>
							<td width="33%">
								<label>Area (Ha)</label>
			                     <div class="input-control text" data-role="input-control">
			                        <input name="Area" type="text" id="Area" value="<?php echo $AreaInHa; ?>" <?php if($Edit==0){echo "disabled";} ?>>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>	
			                </td>
							<td width="34%">
								<label>Title Year</label>
			                     <div class="input-control text" data-role="input-control">
			                        <input name="TitleYear" type="text" id="TitleYear" value="<?php echo $TitleYear; ?>" <?php if($Edit==0){echo "disabled";} ?>>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>	
			                </td>
                		</tr>
                		
                	</table>
                </td>
          	</tr> 			
        </table>

        

   
		
		<?php if($disabled!=="disabled"){ ?>
		<input name="Button" type="button" onclick="deleteConfirm2('Are you sure you want to modyfy the details',
		'plots.php?'+
		'&upn='+<?php echo $upn; ?>+
		'&plotno='+this.form.oldplotno.value+
		'&lrno='+this.form.oldlrno.value+
		'&LocalAuthorityID='+this.form.LocalAuthorityID.value+
		'&FarmID='+this.form.FarmID.value+
		'&newPlotno='+this.form.plotno.value+
		'&newLrno='+this.form.lrno.value+		
        '&RatesPayable='+this.form.RatesPayable.value+
		'&GroundRent='+this.form.GroundRent.value+
		'&GroundRentBalance='+this.form.GroundRentBalance.value+
		'&OtherCharges='+this.form.OtherCharges.value+
		'&OtherChargesBalance='+this.form.OtherChargesBalance.value+
		'&PrincipalBal='+this.form.PrincipalBal.value+
		'&Penalty='+this.form.Penalty.value+
		'&SiteValue='+this.form.SiteValue.value+
		'&Area='+this.form.Area.value+
		'&TitleYear='+this.form.TitleYear.value+
        '&modify=1','content','loader','listpages','','LAIFOMS_LAND')" value="Modify">

        <?php } ?>
		
		
			<input name="Button" type="button" onclick="loadmypage('land_invoicing.php?'+
			'&Authority='+this.form.LocalAuthorityID.value+
			'&FarmID='+this.form.FarmID.value+
	        '&plotno='+this.form.plotno.value+
			'&lrno='+this.form.lrno.value+
	        '&MotherPlotNo='+this.form.MotherPlotNo.value+
			'&SiteValue='+this.form.SiteValue.value+
			'&TitleYear='+this.form.TitleYear.value+
			'&ApplicationID=+<?php echo $ApplicationID; ?>'+
			'&OwnerName=+<?php echo $OwnerName; ?>'+
			'&CustomerID=<?php echo $CustomerID; ?>'+
			'&RatesPayable='+this.form.RatesPayable.value+
			'&GroundRent='+this.form.GroundRent.value+
			'&GroundRentBalance='+this.form.GroundRentBalance.value+
			'&OtherCharges='+this.form.OtherCharges.value+
			'&OtherChargesBalance='+this.form.OtherChargesBalance.value+
			'&PrincipalBal='+this.form.PrincipalBal.value+
			'&Penalty='+this.form.Penalty.value+
	        '&register=1','content','loader','listpages','','LAIFOMS_LAND')" value="Register">
		

		
		<?php if($disabled!=="disabled"){ ?>
			<input name="Button" type="button" onclick="loadmypage('subdevide_plot.php?'+
			'&Authority='+this.form.LocalAuthorityID.value+
			'&FarmID='+this.form.FarmID.value+
	        '&plotno='+this.form.plotno.value+
			'&lrno='+this.form.lrno.value+
	        '&MotherPlotNo='+this.form.MotherPlotNo.value+
			'&SiteValue='+this.form.SiteValue.value+
			'&TitleYear='+this.form.TitleYear.value +
			'&upn='+<?php echo $upn; ?>+'','content','loader','listpages','','ChildrenPlots','<?php echo $upn ?>')" value="Sub Devide">
		<?php } ?>

		<?php if($disabled!=="disabled"){ ?>
			<input name="Button" type="button" onclick="loadpage('transfer_plot.php?'+
			'&Authority='+this.form.LocalAuthorityID.value+
			'&FarmID='+this.form.FarmID.value+
	        '&plotno='+this.form.plotno.value+
			'&lrno='+this.form.lrno.value+
	        '&MotherPlotNo='+this.form.MotherPlotNo.value+
			'&SiteValue='+this.form.SiteValue.value+
			'&TitleYear='+this.form.TitleYear.value +
			'&upn='+<?php echo $upn; ?>+'','content','loader','listpages','','ChildrenPlots','<?php echo $upn ?>')" value="Transfer"> 
		<?php } ?>

		<?php if($disabled!=="disabled"){ ?>
			<input name="Button" type="button" onclick="loadmypage('adjust_plot.php?'+
			'&Authority='+this.form.LocalAuthorityID.value+
			'&FarmID='+this.form.FarmID.value+
	        '&plotno='+this.form.plotno.value+
			'&lrno='+this.form.lrno.value+
	        '&MotherPlotNo='+this.form.MotherPlotNo.value+
			'&SiteValue='+this.form.SiteValue.value+
			'&TitleYear='+this.form.TitleYear.value +
			'&upn='+<?php echo $upn; ?>+'','content','loader','listpages','','','<?php echo $upn ?>')" value="Adjust"> 
		<?php } ?>
		
      <!-- <input type="reset" value="Cancel" onClick="loadmypage('land_from_laifoms.php?i=1','content','loader','listpages','','LAIFOMS_LAND_LIST')"> -->
        <div style="margin-top: 20px">
</div>
	
	</fieldset>
</form>
</div>