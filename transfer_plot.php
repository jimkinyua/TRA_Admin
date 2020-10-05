<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$Createdplotno = $_SESSION['plotno'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

$plotno='';
$lrno='';
$NewPlotNo='';
$PlotSize=0;
$NewSiteValue=0;
$OwnerName='';
$MotherPlotNo='';
$RatesPayable='';
$Url='';
$upn='0';
$OwnerPin='';
$OwnerID='';
$Balance=0;
$To_CustomerID='';
$to_CustomerID='';
$RecipientName='';


	$upn = $_REQUEST['upn'];	
	$sql = "SELECT l.*
	FROM land l left join LandApplication la on la.lrn=l.lrn and la.plotno=l.plotno 
	where l.upn = $upn";
	
	$result = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$lrno=$myrow['LRN'];
		$plotno=$myrow['PlotNo'];
		$OwnerName=$myrow['LaifomsOwner'];
		$MotherPlotNo=$myrow['MPlotNo'];
		$RatesPayable=$myrow['RatesPayable'];
		$Authority=$myrow['LocalAuthorityID'];
		$Balance=(double)$myrow['Balance'];
	}
	
	if ($_REQUEST['Find']==1)
	{
		$to_CustomerID=$_REQUEST['CustomerID'];
		$sql="Select Customername,CustomerID From Customer where CustomerID='$to_CustomerID' or IDNo='$to_CustomerID'";
		$result=sqlsrv_query($db,$sql);

		while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{
			$RecipientName=$rw['Customername'];	
			$To_CustomerID=$rw['CustomerID'];
		}
	}

if (isset($_REQUEST['transfer']))
{	
	//print_r($_REQUEST);
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	
	$upn = $_REQUEST['upn'];
	$lrno=$_REQUEST['lrno'];
	$plotno=$_REQUEST['PlotNo'];	
	$SiteValue=$_REQUEST['SiteValue'];
	$PlotSize=$_REQUEST['PlotSize'];	
	$TitleYear=$_REQUEST['TitleYear'];	
	$Title=$_REQUEST['Title'];	
	$Balance=(double)$_REQUEST['Balance'];
	
	$to_CustomerID=$_REQUEST['CustomerID'];
	$RecipientName=$_REQUEST['RecipientName'];
	$To_CustomerID=$_REQUEST['To_CustomerID'];
	//print_r($_REQUEST);
	if($RecipientName=="")
	{
		$msg="The Recipient Customer must be given";
	}else if ($to_CustomerID==$To_CustomerID){
		$msg="The recepient Customer must be different from the donor";		
	}	
	else if($Balance>0){
		$msg="The the plot has outstanding land rates, so it cannot be Transfered";		
	}else
	{
		$sql="select 1 from LandOwner where upn='$upn' and CustomerID='$To_CustomerID' and Active=1";
		$result=sqlsrv_query($db,$sql,$params,$options);
		$num=sqlsrv_num_rows($result);
		
		if ((double)$num>0){
			$msg="The recipient must be diffent from the current owner";
		}else{
			$sql="update LandOwner Set Active=0 where UPN='$upn'";
			$result=sqlsrv_query($db,$sql);
			if(!$result)
			{						
				DisplayErrors();
			}else
			{
				$sql="insert into landOwner (UPN,lrn,PlotNo,CustomerID) 
				values('$upn','$lrno','$plotno','$To_CustomerID')";			
				$result=sqlsrv_query($db,$sql);
				if($result)
				{
					$sql="update Land Set LaifomsOwner='$RecipientName',CustomerID='$To_CustomerID' where UPN='$upn'";					
					$result=sqlsrv_query($db,$sql);
					if(!$result){						
						DisplayErrors();
					}else
					{
						//echo $sql;
						$msg="Land Transfered Successfully";
					}					
				}else{
					DisplayErrors();
				}
			}
		}
			
				
	}
}
?>
<body class="metro">
	<div class="example">
	<form>
		<fieldset>
		  <legend>Transfer Plot From <?php echo $OwnerName; ?></legend>
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr>
				  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
			  </tr>
			  <tr>
					<td>
						<label width="33%">Authority</label>
							<div class="input-control select" data-role="input-control">						
								<select name="Authority"  id="Authority">
									<option value="0"></option>
									<option value="96" <?php echo $Authority==96?'selected="selected"':'' ?> >MUNICIPALITY</option>
									<option value="856" <?php echo $Authority==856?'selected="selected"':'' ?>>WARENG C. COUNCIL</option>															
							  </select>							
						</div>				
					</td>
					<td width="33%">
						<label>Plot No</label>
						<div class="input-control text" data-role="input-control">
							<input name="plotno" type="text" id="plotno" value="<?php echo $plotno; ?>" disabled>
							<button class="btn-clear" tabindex="-1"></button>
					  </div>
					</td>			
					<td width="33%"><label>LR No</label>
						<div class="input-control text" data-role="input-control">
							<input name="lrno" type="text" id="lrno" value="<?php echo $lrno; ?>" disabled>
							<button class="btn-clear" tabindex="-1"></button>
						</div>
					</td>
				</tr>
				<tr>			
					<td>
						<label>Title Number</label>
						<div class="input-control text" data-role="input-control">
							<input name="title" type="text" id="title" value="<?php echo $title; ?>" disabled>
							<button class="btn-clear" tabindex="-1"></button>
						</div>	
					</td>
					<td>
						<label>Title Year</label>
						 <div class="input-control text" data-role="input-control">
							<input name="TitleYear" type="text" id="TitleYear" value="" disabled>
							<button class="btn-clear" tabindex="-1"></button>
						</div>	
					</td>
					<td>
						<label>Current Rates Balance(Arrears)</label>
						 <div class="input-control text" data-role="input-control">
							<input name="Balance" type="text" id="Balance" value="<?php echo $Balance; ?>" disabled>
							<button class="btn-clear" tabindex="-1"></button>
						</div>	
					</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3" align="center"><br><hr>
					</td>
				</tr>			
				<tr>
					<td colspan="3" align="center">RECIPIENT<br><hr>
					</td>
				</tr>
				<tr>
					<td>
						<label>Recipient Customer ID</label>
						<div class="input-control text" data-role="input-control">
							<input name="CustomerID" type="text" id="CustomerID" value="<?php echo $to_CustomerID; ?>">
							<button class="btn-clear" tabindex="-1"></button>
						</div>	
					</td>
					<td>
						<label>&nbsp;</label>
						<input name="Button" type="button" onclick="loadpage('transfer_plot.php?'+	
						'&upn='+<?php echo $upn; ?>+
						'&CustomerID='+this.form.CustomerID.value+				
						'&Find=1&search=1','content','loader','listpages')" value="Find">
					</td>
					<td>
						<label>&nbsp;</label>
						<div class="input-control text" data-role="input-control">
							<input name="RecipientName" type="text" id="RecipientName" value="<?php echo $RecipientName; ?>" disabled>
							<button class="btn-clear" tabindex="-1"></button>
						</div>	
					</td>
				</tr>				
			</table>
			<input name="Button" type="button" onclick="loadpage('transfer_plot.php?'+
						'&lrno='+this.form.lrno.value+
						'&PlotNo='+this.form.plotno.value+
						'&RecipientName='+this.form.RecipientName.value+
						'&To_CustomerID='+<?php echo $To_CustomerID; ?>+	
						'&Balance='+this.form.Balance.value+	
						'&upn='+<?php echo $upn; ?>+
						'&transfer=1','content','loader','listpages')" value="save">
			
		  <!-- <input type="reset" value="Cancel" onClick="loadmypage('land_from_laifoms.php?i=1','content','loader','listpages','','LAIFOMS_LAND_LIST')"> -->
			<div style="margin-top: 20px">
	</div>

		</fieldset>
	</form>
	</div>
</body>