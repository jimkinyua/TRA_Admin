<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_id();
	session_start();
}
$msg ='';
$CreatedBy = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}


$plotno='';
$lrno='';
$OwnerName='';
$MotherPlotNo='';
$RatesPayable='';
$GroundRent=0;
$GroundRentBalance=0;
$OtherCharges=0;
$PenaltyBalance=0;
$OtherChargesBalance=0;
$ApplicationID='';
$Url='';
$upn='0';
$OwnerPin='';
$OwnerID='';

	

$upn=isset($_REQUEST['upn']) ? $_REQUEST['upn'] : '';

 //getDetails($db,$upn);

	$sql="select * from land where upn='$upn'";
	//echo $sql;
	$query=sqlsrv_query($db,$sql);
	
	if($query){	
		
		while($row=sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC)){
			$OwnerName=$row['LaifomsOwner'];
			$Authority = $row['LocalAuthorityID'];
			$LaifomsUPN=$row['LaifomsUPN'];
			$PrincipalBalance=$row['PrincipalBalance'];
			$PenaltyBalance=$row['PenaltyBalance'];
			$GroundRentBalance=$row['GroundRentBalance'];
			$OtherChargesBalance=$row['OtherChargesBalance'];
			$plotno = $row['PlotNo'];
			$lrno = $row['LRN'];				
		}
	}else{
		DisplayErrors();
	}



if($_REQUEST['save']==1){
	$upn=isset($_REQUEST['upn']) ? $_REQUEST['upn'] : '';
	$object=isset($_REQUEST['object']) ? $_REQUEST['object'] : '';
	$Amount=isset($_REQUEST['Amount']) ? $_REQUEST['Amount'] : '';
	$adjustmentDate=isset($_REQUEST['adjustmentDate']) ? $_REQUEST['adjustmentDate'] : '';
	$Description=isset($_REQUEST['Description']) ? $_REQUEST['Description'] : '';

	$Description= 'Plot Upn: '.$upn.'; '.$Description;

	$principalAmount=0;
	$penaltyAmount=0;
	$GroundRentAmount=0;
	$OtherChargesAmount=0;

	$PageID=49;
	$Action=1;

	// if($object=='Principal'){
	// 	$principalAmount=$Amount;
	// }elseif($object=='Penalty'){
	// 	$penaltyAmount=$Amount;
	// }elseif($object=='GroundRent'){
	// 	$GroundRentAmount=$Amount;
	// }elseif($object=='OtherCharges'){
	// 	$OtherChargesAmount=$Amount;
	// }

	$ReferenceNumber='Ajustment-'.time();
	$sql="set dateformat dmy Insert into LandAdjustments (CreatedBy,upn,object,amount,adjustmentDate,Description,ReferenceNumber)
		values ($CreatedBy,$upn,'$object',$Amount,'$adjustmentDate','$Description','$ReferenceNumber')";
	$result=sqlsrv_query($db,$sql);
	if($result)
	{
		$sql="set dateformat dmy Insert into ApprovalEntry(SenderID,PageID,DocumentNo, Comments, Action,ApprovalStatus,ApprovalStage)
          values($CreatedBy,'$PageID','$ReferenceNumber','$Description','$Action',0,0)";
        $qry=sqlsrv_query($db,$sql);

        if ($qry)
        {
            $msg="Land Adjustment sent for Approval";
        }else
        {
			DisplayErrors();
			$msg="There was some errors Internally";
		}
	}else
	{
		DisplayErrors();
		echo $sql;
		$msg="There was some Errors";
	}

}


?>
	<link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

	 <script type="text/javascript">
    	$(".datepicker").datepicker();
    </script>
<body class="metro">
<div class="example">
<form>
	<fieldset>
	  <legend>Adjust Plot Statement</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="4" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
		  <tr>
			<td>
				<label width="25%">Authority</label>
				<div class="input-control select" data-role="input-control">						
						<select name="Authority"  id="Authority">
							<option value="0"></option>
							<option value="96" <?php echo $Authority==96?'selected="selected"':'' ?> >MUNICIPALITY</option>
							<option value="856" <?php echo $Authority==856?'selected="selected"':'' ?>>WARENG C. COUNCIL</option>															
					  </select>							
				</div>				
			</td>			
                <td width="25%">
                	<label>Plot No</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="plotno" type="text" id="plotno" value="<?php echo $plotno; ?>" disabled>
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="25%"><label>LR No</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="lrno" type="text" id="lrno" value="<?php echo $lrno; ?>" disabled>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="25%"><label>Owner</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="owner" type="text" id="owner" value="<?php echo $OwnerName; ?>" disabled>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
          	</tr>						
			
			<tr>
				
				<td width="25%">
					<label>Land Rates (Balance)</label>
					<div class="input-control text" data-role="input-control">
						<input name="PrincipalBal" type="text" id="PrincipalBal" value="<?php echo number_format($PrincipalBalance,2); ?>" readonly>
						<button class="btn-clear" tabindex="-1"></button>
					</div>	
				</td>														
				<td width="25%">
					<label>Penalty (Balance)</label>
					<div class="input-control text" data-role="input-control">
						<input name="Penalty" type="text" id="Penalty" value="<?php echo number_format($PenaltyBalance,2); ?>" readonly>
						<button class="btn-clear" tabindex="-1"></button>
					</div>	
				</td>
				<td width="25%">
				  <label>Ground Rent (Balance)</label>
				  <div class="input-control text" data-role="input-control">
					  <input name="GroundRentBalance" type="text" id="GroundRentBalance" value="<?php echo number_format($GroundRentBalance,2); ?>" readonly>
						<button class="btn-clear" tabindex="-1"></button>
				  </div>
				</td>
				<td width="25%">
				  <label>Other Charges (Balance)</label>
				  <div class="input-control text" data-role="input-control">
					  <input name="OtherChargesBalance" type="text" id="OtherChargesBalance" value="<?php echo number_format($OtherChargesBalance,2); ?>" readonly>
						<button class="btn-clear" tabindex="-1"></button>
				  </div>
				</td>						
          	</tr> 
          	<tr>
          		<td colspan="4"><h3>STATEMENT ADJUSTMENT</h3></td>
          	</tr>
          	<tr>	
				<td width="25%">
					<label>Adjustment Description</label>
					<div class="input-control text" data-role="input-control">
						<input name="Description" type="text" id="Description" >
						<button class="btn-clear" tabindex="-1"></button>
					</div>	
				</td>														
				<td><label>Adjustment Date(dd/mm/yyyy)</label>
					<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">					
						<input type="text" id="adjustmentDate" name="adjustmentDate" ></input>
						<button class="btn-date" type="button"></button>			
					</div>
				</td>
				<td width="25%">
				  <label width="25%">What to Modify</label>
					<div class="input-control select" data-role="input-control">						
							<select name="object"  id="object">
								<option value="Principal">Principal</option>
								<option value="Penalty">Penalty</option>
								<option value="GroundRent">GroundRent</option>
								<option value="OtherCharges">OtherCharges</option>															
						  </select>							
					</div>				  
				</td>
				<td width="25%">
				  <label>Adjustment amount</label>
				  <div class="input-control text" data-role="input-control">
					  <input name="Amount" type="text" id="Amount" >
						<button class="btn-clear" tabindex="-1"></button>
				  </div>
				</td>
										
          	</tr> 
          	<tr>
          		<td>
          			<input name="Button" type="button" onclick="loadpage('adjust_plot.php?'+
					'&save=1'+
					'&Authority='+this.form.Authority.value+
					'&object='+this.form.object.value+
			        '&plotno='+this.form.plotno.value+
					'&lrno='+this.form.lrno.value+						        
					'&Amount='+this.form.Amount.value+
					'&adjustmentDate='+this.form.adjustmentDate.value +
					'&Description='+this.form.Description.value +
					'&upn='+<?php echo $upn; ?>+'','content','loader','listpages','','','<?php echo $upn ?>')" value="Save">
          		</td>
          	</tr>          				
        </table>	 
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>