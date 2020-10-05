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


	$upn = $_REQUEST['upn'];	
	$sql = "SELECT l.*  
	FROM land l 
	where l.upn = $upn";
	
	//echo $sql;
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
		$FarmID=$myrow['FarmID'];
	}

if (isset($_REQUEST['subdevide']))
{	
	//print_r($_REQUEST);

	$upn = $_REQUEST['upn'];
	$lrno=$_REQUEST['lrno'];
	$plotno=$_REQUEST['PlotNo'];
	$NewPlotNo=$_REQUEST['NewPlotNo'];
	$SiteValue=$_REQUEST['SiteValue'];
	$PlotSize=$_REQUEST['PlotSize'];
	$NewSiteValue=$_REQUEST['NewSiteValue'];
	$TitleYear=$_REQUEST['TitleYear'];
	$NewTitleYear=$_REQUEST['NewTitleYear'];	
	$Title=$_REQUEST['Title'];	
	$FarmID=$_REQUEST['FarmID'];	
	$Balance=(double)$_REQUEST['Balance'];	
	$OwnerName=$_REQUEST['OwnerName'];	

	// print_r($_REQUEST);
	// exit;
	if($Balance>'0'){
		$msg="The the plot has outstanding land rates, so it canno be subdevided";		
	}else
	{
		//$msg="You can Subdevide";	
		
		$sql="select 1 from land where lrn='$lrno' and plotno='$NewPlotNo' and LocalAuthorityID=$Authority and FirmID=$FarmID";
		//echo $sql;
		$qry=sqlsrv_query($db,$sql,$params,$options);
		if($qry){
			
		}else{
			DisplayErrors();
		}	
		if(sqlsrv_num_rows($qry)>0){
			$msg= 'plot exists';
		}else if($NewSiteValue==0){
			$msg='The Value of the new plot must be stated ';
		}else if($PlotSize==0){
			$msg='The Size of the new plot must be stated ';
		}else if($NewTitleYear==0){
			$msg='The Tittle Year of the new plot must be stated ';
		}else{
			$excess=0;
			$acres=0;
			if($Authority==96){
			$RatesPayable=0.02*(double)$NewSiteValue;					
			}else{
				$RatesPayable=60;
				$excess=0;
				$acres=(double)$Size*2.4765;
				if((double)$acres>5){
					$excess=ceil((double)$acres-5)*10;				
				}
				$RatesPayable+=(double)$excess; 
			}
			
			$PenaltyPayable=.03*(double)$RatesPayable;
			
			
			$sql="insert into land (lrn,PlotNo,RatesPayable,MotherUPN,SiteValue,TitleYear,Balance,PenaltyBalance,LocalAuthorityID,FirmID,LaifomsOwner) 
				values('$lrno','$NewPlotNo','$RatesPayable','$upn','$NewSiteValue','$NewTitleYear','0','0','$Authority','$FarmID','$OwnerName')";			
			$result=sqlsrv_query($db,$sql);
			if($result){

				$sql="update Land Set Active=0 where UPN='$upn'";					
				$result=sqlsrv_query($db,$sql);
				if($result){			
					$msg="Plot Subdevided successfully";			
				}else{
					DisplayErrors();
				}		
			}else{
				DisplayErrors();
			}
		}
		
	}
}

?>
<body class="metro">
	<div class="example">
	<form>
		<fieldset>
		  <legend>Subdevide Plot</legend>
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
						<label>Current Owner</label>
						<div class="input-control text" data-role="input-control">
							<input name="OwnerName" type="text" id="OwnerNames" value="<?php echo $OwnerName; ?>" disabled>
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
					<td colspan="3" align="center">CHILD PLOT<br><hr>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table width="100%">
							<tr>
								<td>
									<label>New Plot No</label>
									<div class="input-control text" data-role="input-control">
										<input name="NewPlotNo" type="text" id="NewPlotNo" value="<?php echo $NewPlotNo; ?>">
										<button class="btn-clear" tabindex="-1"></button>
									</div>							
								</td>
								<td>
									<label>Plot Value </label>
									<div class="input-control text" data-role="input-control">
										<input name="NewSiteValue" type="text" id="NewSiteValue" value="<?php echo $NewSiteValue; ?>" placeholder="">
										<button class="btn-clear" tabindex="-1"></button>
								  </div>
								</td>
								<td>
									<label>Plot Size (Ha)</label>
									<div class="input-control text" data-role="input-control">
										<input name="PlotSize" type="text" id="PlotSize" value="<?php echo $PlotSize; ?>" placeholder="">
										<button class="btn-clear" tabindex="-1"></button>
								  </div>
								</td>							
								<td>
									<label>Title Year</label>
									 <div class="input-control text" data-role="input-control">
										<input name="NewTitleYear" type="text" id="NewTitleYear" value="" placeholder="">
										<button class="btn-clear" tabindex="-1"></button>
									</div>	
								</td>
								<td><label>Farm</label>
				                    <div class="input-control select" data-role="input-control">
				                    	
				                    	<select name="FarmID"  id="FarmID">
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
									<label>&nbsp;</label>
									<input name="Button" type="button" onclick="loadpage('subdevide_plot.php?'+
									'&lrno='+this.form.lrno.value+
									'&PlotNo='+this.form.plotno.value+
									'&NewPlotNo='+this.form.NewPlotNo.value+
									'&NewSiteValue='+this.form.NewSiteValue.value+
									'&PlotSize='+this.form.PlotSize.value+
									'&TitleYear='+this.form.TitleYear.value+
									'&NewTitleYear='+this.form.NewTitleYear.value+
									'&FarmID='+this.form.FarmID.value+
									'&Balance='+<?php echo $Balance ?>+
									'&OwnerName='+this.form.OwnerName.value+
									'&upn='+<?php echo $upn; ?>+
									'&subdevide=1','content','loader','listpages','','ChildrenPlots','<?php echo $upn ?>')" value="save">
								</td>
							</tr>
							<tr>
								<table width="100%" class="table striped hovered dataTable" id="dataTables-1">
									<thead>
										<tr>
											<th width="10%" class="text-left">UPN</th>
											<th width="10%" class="text-left">lrno</th>
											<th width="15%" class="text-left">Plot Number</th>
											<th width="15%" class="text-left">Annual Rates</th>
											<th width="10%" class="text-left">Balance</th>
											<th width="40%" class="text-left">OwnerName</th>											
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>						
							</tr>
						</table>					
					</td>                
				</tr>
				
				
			</table>
			
			
			
		  <!-- <input type="reset" value="Cancel" onClick="loadmypage('land_from_laifoms.php?i=1','content','loader','listpages','','LAIFOMS_LAND_LIST')"> -->
			<div style="margin-top: 20px">
	</div>

		</fieldset>
	</form>
	</div>
</body>