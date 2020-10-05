<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$OwnerName='';
$MotherPlotNo='';
$RatesPayable='';
$PostalCode='';
$Town='';
$Mobile='';
$Email='';
$Url='';
$QrString='';

$upn='';
$plotno='';
$lrn='';
$titleno='';
$names='';
$ownernames='';
$upn='0';


$Authority='';
if (isset($_REQUEST['plotno'])) { $plotno = $_REQUEST['plotno']; }
if (isset($_REQUEST['lrno'])) { $lrno = $_REQUEST['lrno']; }
if (isset($_REQUEST['upn'])) { $upn = $_REQUEST['upn']; }
if (isset($_REQUEST['owner'])) { $ownernames = $_REQUEST['owner']; }
if (isset($_REQUEST['Authority'])) { $Authority = $_REQUEST['Authority']; }

//print_r ($_REQUEST);
if (isset($_REQUEST['import']))
{
	$upn=$_REQUEST['upn'];
	$Authority=$_REQUEST['Authority'];
	$lrn=$_REQUEST['lrn'];
	$plotno=$_REQUEST['plotno'];
	
	if($Authority==856){
		$DBase='LAIFOMS-W';			 
	}else{
		$DBase='LAIFOMS-M';
	}
	
	$sql="if not exists(select 1 from land where LocalAuthorityID=$Authority and lRN='$lrn' and PlotNo='$plotno' and laifomsUPN='$upn')
		insert into land(LocalAuthorityID,LRN,PlotNo,RatesPayable,GroundRent,OtherCharges,GroundRentBalance,OtherChargesBalance,Balance,laifomsUPN,LaifomsOwner,FirmID,Area,AreaInHa,PrincipalBalance,PenaltyBalance) 

  SELECT distinct p.LocalAuthorityID , IIF(p.BlockLRNumber='','NOLRN',p.BlockLRNumber) lRN,
  p.PlotNumber PlotNo,P.LandRates,p.GroundRent,p.OtherCharges,p.GroundRentBalance,p.OtherChargesBalance,p.CurrentBalance,p.UPN,cs.CustomerSupplierName,isnull(p.MarketCentreID,0)MarketCentreID,Area,AreaInHa,p.CurrentBalance-(p.GroundRentBalance+p.OtherChargesBalance+p.AccumulatedPenalty),p.AccumulatedPenalty 
		FROM [".$DBase."].dbo.[Property]  p join [".$DBase."].dbo.CustomerSupplier cs on p.CustomerSupplierID=cs.CustomerSupplierID
		where  p.UPN='$upn' 
		order by IIF(p.BlockLRNumber='','NOLRN',p.BlockLRNumber), p.PlotNumber SELECT SCOPE_IDENTITY() AS ID";
	// echo $sql;	
	// exit;
	$result=sqlsrv_query($db,$sql);
	if($result){
		$rst=SaveTransaction($db,$CreatedUserID,"Imported plot from  ".$DBase." upn number ".$upn);

		$sql="update lr set lr.penaltybalance=prs.accumulatedpenalty 
			  ,lr.GroundRent=prs.AnnualGroundRent
			  ,lr.GroundRentBalance=prs.GroundRentArrears
			  ,lr.OtherCharges=prs.OtherCharges
			  ,lr.OtherChargesBalance=prs.OtherChargesArrears
			from [".$DBase."].dbo.propertyratesstatement prs 
			join COUNTYREVENUE.dbo.LANDRECEIPTS lr on lr.DateReceived=prs.TransactionDate and prs.upn=lr.LaifomsUPN
			where lr.laifomsUPN='$upn' and lr.LocalAuthorityID=$Authority";

		$result=sqlsrv_query($db,$sql);

		if($result){
			$msg='Propery Imported Successfully';
		}else{
			DisplayErrors();
		}

		
	}else{
		DisplayErrors();
	}
}


if (isset($_REQUEST['Search']))
{
	$upn=$_REQUEST['upn'];
	$plotno=$_REQUEST['plotno'];
	$lrno=$_REQUEST['lrno'];
	$Authority=$_REQUEST['Authority'];
	
	print_r ($_REQUEST);
	exit;
	
	if ($upn!="")
	{
		$sql="select * from LAND where laifomsUPN='".$upn."'";
	}else
	{
		$sql="select * from LAND where lrn='".$lrn."' and PlotNo='".$plotno."'";
	}
	
	$result=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$mdata.='<tr>
						<td>'.$row['lrn'].'</td>
						<td>'.$row['upn'].'</td>
						<td>'.$row['RatesPayable'].'</td>
						<td>'.$row['PrincipalBalance'].'</td>
						</tr>';
	}
}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

<body class="metro">
	<div class="example">        
		<legend>LAIFOMS LAND</legend> 
		<form>
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>				
					<th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<td><label width="20%">Authority</label>
						<div class="input-control select" data-role="input-control">						
							<select name="Authority"  id="Authority">
								<option value="0"></option>
								<option value="96" <?php echo $Authority==96?'selected="selected"':'' ?> >MUNICIPALITY</option>
								<option value="856" <?php echo $Authority==856?'selected="selected"':'' ?>>WARENG C. COUNCIL</option>															
						  </select>							
						</div>
					</td>
					<td>
						<label>UPN</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="upn" name="upn" value="<?php echo $upn; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td>
						<label>Block No</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="lrno" name="lrno" value="<?php echo $lrno; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>					
					<td>
						<label>Plot No</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="plotno" name="plotno" value="<?php echo $plotno; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td>
						<label>Title No</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="titleno" name="titleno" value="<?php echo $titleno; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td>
						<label>Names</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="ownernames" name="ownernames" value="<?php echo $ownernames; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					
					<td>
						<br><br>
											
						 <input name="btnSearch" type="button" onclick="loadmypage('land_from_laifoms.php?'+
						'&Authority='+this.form.Authority.value+
						'&upn='+this.form.upn.value+
						'&plotno='+this.form.plotno.value+
						'&lrno='+this.form.lrno.value+
						'&ownernames='+this.form.ownernames.value+						'&search=1','content','loader','listpages','','LAIFOMS_LAND_LIST','upn='+this.form.upn.value+':plotno='+this.form.plotno.value+':lrn='+this.form.lrno.value+':owner='+this.form.ownernames.value+':Authority='+this.form.Authority.value+'','<?php echo $_SESSION['UserID']; ?>')" value="Search"> 
					
					</td>
				</tr>				
				<tr>
					<th width="10%" class="text-left">UPN</th>
					<th width="10%" class="text-left">lrno</th>
					<th width="15%" class="text-left">Plot Number</th>
					<th width="10%" class="text-left">Rates Payable</th>
					<th width="10%" class="text-left">Balance</th>
					<th width="30%" class="text-left">OwnerName</th>
					<th width="20%" class="text-left"></th>
				</tr>
			</thead>

			<tbody>
				<tbody>
					<?php
						echo $mdata;
					?>
                <tbody>			
			</tbody>
		</table> 
		<form>
	</div>
</body>


