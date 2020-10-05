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
$CreatedBy=$CreatedUserID;

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
$lrn='';
$plotno='';
$CustomerName='';

$upn=$_REQUEST['upn'];

$registered=0;

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

$sql="exec spBillPlot_test5 $upn";
$result=sqlsrv_query($db,$sql);

$qry="exec spRefreshLandStatement5 '$upn'";
$s_result = sqlsrv_query($db, $qry);

$sql="select lrn,plotno,LaifomsOwner,ExemptRates, (select Balance from dbo.fnlastplotrecord($upn))Balance,LocalAuthorityID from land where upn=$upn";
$result=sqlsrv_query($db,$sql);
//echo $sql;
while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{
	$lrn=$row['lrn'];
	$plotno=$row['plotno'];
	$CustomerName=$row['LaifomsOwner'];
	$Balance=$row['Balance'];
	$LocalAuthorityID=$row['LocalAuthorityID'];
	$ExemptRates=$row['ExemptRates'];

	if($ExemptRates==1){
		$Balance=0;
	}
}

$sql="select 1 from landapplication la
join Land l on l.lrn=la.lrn and l.plotno=la.plotno 
where l.upn=$upn";

$result=sqlsrv_query($db,$sql,$params,$options);

$rows=sqlsrv_num_rows($result);
if($rows>0){
	$registered=1;
}else{
	$registered=0;	
}

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

if (isset($_REQUEST['save']))
{
	$ApplicationType='';
	$ApplicationCategory='';	
	$ApplicationTypeID=	$_REQUEST['ApplicationTypeID'];
	$ApplicationCategoryID=$_REQUEST['ApplicationCategoryID'];
	$CustomerName=$_REQUEST['CustomerName'];
	$LocalAuthorityID=$_REQUEST['LocalAuthorityID'];

	
	$sqmeters=$_REQUEST['sqmeters'];
	$NoOfFloors=$_REQUEST['NoOfFloors'];
	$upn=$_REQUEST['upn'];

	$MainServiceID='2750';
	$ServiceStatusID='5';
	$InvoiceDate=date('d/m/Y');
	$InvoiceNo=time();
	$Total=0;
	

	$sql = "SELECT * FROM ApplicationTypes where ApplicationTypeID=$ApplicationTypeID";                        
    $result = sqlsrv_query($db, $sql);
    while($roww=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$ApplicationType=$roww['ApplicationTypeName'];
	}

	$sql = "SELECT * FROM ApplicationCategories where ApplicationCategoryID=$ApplicationCategoryID";                        
    $result = sqlsrv_query($db, $sql);
    while($roww=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$ApplicationCategory=$roww['ApplicationCategoryName'];
	}

	$sql="select l.upn, isnull(lo.CustomerID,sh.CustomerID)CustomerID,la.LRN lrn,la.PlotNo plotno,c.CustomerName,la.ServiceHeaderID,fn.laifomsUPN
		from land l 
		join LandApplication la on la.LRN=l.LRN and la.PlotNo=l.PlotNo 
		left join LandOwner lo on lo.LRN=l.LRN and lo.PlotNo=l.PlotNo
		join serviceheader sh on la.ServiceHeaderID=sh.ServiceHeaderID
		join Customer c on isnull(lo.CustomerID,sh.CustomerID)=c.CustomerID
		cross apply (select [value] laifomsUPN from fnFormData(sh.ServiceHeaderID) where FormColumnID=12265) fn
		left join (select ServiceHeaderID,[Value] from FormData where FormColumnID=13270 ) lauth on 
		lauth.Value=l.LocalAuthorityID and lauth.ServiceHeaderID=sh.ServiceHeaderID 
		where l.UPN=$upn and l.LaifomsUPN=fn.laifomsUPN";


	$result=sqlsrv_query($db,$sql,$params,$options);

	$rows=sqlsrv_num_rows($result);
	if($rows==0)
	{
		$sql="
			select l.upn, isnull(lo.CustomerID,sh.CustomerID)CustomerID,la.LRN lrn,la.PlotNo plotno,c.CustomerName,la.ServiceHeaderID
			from land l 
			join LandApplication la on la.LRN=l.LRN and la.PlotNo=l.PlotNo
			left join LandOwner lo on lo.LRN=l.LRN and lo.PlotNo=l.PlotNo 
			join serviceheader sh on la.ServiceHeaderID=sh.ServiceHeaderID
			join Customer c on isnull(lo.CustomerID,sh.CustomerID)=c.CustomerID
			join (select ServiceHeaderID,Value LocalAuthorityID 
			from FormData 
			where  FormColumnID=13270) fd on fd.ServiceHeaderID=sh.ServiceHeaderID and fd.LocalAuthorityID= l.LocalAuthorityID
			left join (select ServiceHeaderID,[Value] from FormData where FormColumnID=13270 ) lauth on 
			lauth.Value=l.LocalAuthorityID and lauth.ServiceHeaderID=sh.ServiceHeaderID 
			where l.UPN=$upn
		";

		$result=sqlsrv_query($db,$sql);
	}
	
	//echo $sql; exit;
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$lrn=$row['lrn'];
		$plotno=$row['plotno'];
		$CustomerID=$row['CustomerID'];
	}

	$plot="Plot Details(Upn:$upn; Block: $lrn; Plot: $plotno)";
	$Description= $ApplicationType. ' for '.$ApplicationCategory.' Category (Area:'.$sqmeters.'; No of Floors:'.$NoOfFloors.'),'.$plot;

	if(sqlsrv_begin_transaction($db)===false)
	{
		$msg=sqlsrv_errors();
		$Sawa=false;
	}
	
	$sql="Insert into ServiceHeader (CustomerID,ServiceID,ServiceStatusID,CreatedBy)
	Values('$CustomerID',$MainServiceID,'$ServiceStatusID',$CreatedBy) SELECT SCOPE_IDENTITY() AS ID";
	
	$result=sqlsrv_query($db,$sql);
	if($result)
	{
		$ServiceHeaderID=lastid($result);
		
		$sql="set dateformat dmy 
		Insert into InvoiceHeader (InvoiceDate,InvoiceNo,CustomerID,CustomerName,Description,CreatedBy,ServiceHeaderID) 
		Values('$InvoiceDate','$InvoiceNo','$CustomerID','$CustomerName','$Description','$CreatedBy',$ServiceHeaderID) 
		SELECT SCOPE_IDENTITY() AS ID";

		$result3 = sqlsrv_query($db, $sql);	
		if ($result3)
		{
			$InvoiceHeaderID=lastid($result3);
			$Commit=0;

			$sql="select * from PlanApprovalSetup where ApplicationTypeID=$ApplicationTypeID 
			AND ApplicationCategoryID=$ApplicationCategoryID";
			//echo $sql;
			$result=sqlsrv_query($db,$sql);
			if($result)
			{
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{
					$ServiceID=$rw['ServiceID'];
					$UnitOfCharge=$rw['UnitOfCharge'];
					$NonStoreyedAmount=(double)$rw['NonStoreyedAmount'];
					$StoreyedAmount=(double)$rw['StoreyedAmount'];
					$ApplyToNonStoreyed=$rw['ApplyToNonStoreyed'];

					if($ApplyToNonStoreyed==0){
						if($NoOfFloors<=1){
							continue;
						}
					}

					if($NoOfFloors==1)
					{
						$Amount=$NonStoreyedAmount;	
					}else
					{
						$Amount=$StoreyedAmount;		
					}

					if($UnitOfCharge==2)
					{
						$Amount=(double)$sqmeters*$Amount;
					}else if($UnitOfCharge==3){
						$Amount=(double)$NoOfFloors*$Amount;
					}else if($UnitOfCharge==4){
						$Amount=(double)$NoOfFloors*$Amount;
					}else{ //fixed

					} 

					$sql="set dateformat dmy insert into InvoiceLines 
					(InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
					Values($InvoiceHeaderID,$ServiceHeaderID,'',$ServiceID,$Amount,'$CreatedBy')";
					
					$rs = sqlsrv_query($db, $sql);

					if($rs){

						$Total+=$Amount;
						$Commit=1;
					}else{
						DisplayErrors();
						$Commit=0;
					}

				}

				//Rates Clearance Certificate

				$ServiceID=448;

				if($LocalAuthorityID==96){
					$Amount=3000;
				}else{
					$Amount=2000;
				}

				$sql="set dateformat dmy insert into InvoiceLines 
					(InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
					Values($InvoiceHeaderID,$ServiceHeaderID,'',$ServiceID,$Amount,'$CreatedBy')";
					
				$rs = sqlsrv_query($db, $sql);

				if($rs){
					$Total+=$Amount;
					$Commit=1;
				}else{
					DisplayErrors();
					$Commit=0;
				}

				// $sql="Insert into Miscellaneous (ServiceHeaderID,CustomerName,Description,Amount,CreatedBy)
				// Values($ServiceHeaderID,'$CustomerName','$Description','$Total','$CreatedBy')";
				// //echo $sql;
				// $result2=sqlsrv_query($db,$sql);
				// if($result2)
				// {						
					
				// }else
				// {
				// 	echo 'Result 2 failed'.'<br>'.$sql;
				// }

			}else{

				DisplayErrors();
				$Commit=0;
			}
	

			if($Commit==1)
			{
				$rst=SaveTransaction($db,$UserID," Created a Plan Approval Invoice Number ".$InvoiceHeaderID);
				sqlsrv_commit($db);	

				$ViewBtn  = '<a href="reports.php?rptType=Invoice&ServiceHeaderID='.$ServiceHeaderID.'&InvoiceHeaderID='.$InvoiceHeaderID.'" target="_blank">Click to View</a>';

				$msg="Invoice No $InvoiceHeaderID Created Successfully. $ViewBtn";			
				
			}else
			{

				sqlsrv_rollback($db);
				$msg="Transaction Failed";
			}

		}else{
			echo $sql.'<br>';
			DisplayErrors();
		}
	}else{

	}
	
}

?>
<script type="text/javascript">     
    $(document).ready(function() {
            $("#NoOfFloors").keydown(function(event) {
                // Allow only backspace and delete
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190 ) {
                    // let it happen, don't do anything
                }
                else {
                    // Ensure that it is a number and stop the keypress
                    if (event.keyCode < 48 || event.keyCode > 57 ) {
                        event.preventDefault(); 
                    }   
                }
            });
        });
    $(document).ready(function() {
            $("#sqmeters").keydown(function(event) {
                // Allow only backspace and delete
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190) {
                    // let it happen, don't do anything
                }
                else {
                    // Ensure that it is a number and stop the keypress
                    if (event.keyCode < 48 || event.keyCode > 57 ) {
                        event.preventDefault(); 
                    }   
                }
            });
        });

</script>

<body>
<div class="example">
<form>
	<fieldset>
	  <legend>Building Plan Invoicing</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
			  <br>
		  </tr>
            <tr>
                <td width="50%">
                	<table width="100%">
                		<tr>
                			<td width="70%">
                				<label>Customer Name</label>
			                    <div class="input-control text" data-role="input-control">
			                        <input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>" disabled></input>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>
                			</td>
                			<td>
                				<label>Registration Status</label>
			                    <div class="input-control text" data-role="input-control">
			                        <input type="text" id="registered" name="registered" value="<?php echo $registered==1?"Registered":"Not Registered"; ?>" disabled></input>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>
                			</td>
                		</tr>
                	</table>
                    
                </td>
                <td width="50%">
            
                </td>
            </tr>			
			<tr>
                <td width="50%">
                	<table width="100%" border="0">
                		<tr>
                			<td>
                				<label>Plot Number: upn </label>
			                    <div class="input-control text" data-role="input-control">
			                        <input type="text" id="upn" name="upn" value="<?php echo $upn; ?>" disabled></input>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>
                			</td>
                			<td>
                				<label>Lrn No. </label>
			                    <div class="input-control text" data-role="input-control">
			                        <input type="text" id="lrn" name="lrn" value="<?php echo $lrn; ?>" disabled></input>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>
                			</td>
                			<td>
                				<label>Plot No. </label>
			                    <div class="input-control text" data-role="input-control">
			                        <input type="text" id="plotno" name="plotno" value="<?php echo $plotno; ?>" disabled></input>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>
                			</td>
                			<td>
                				<label>Rates Balance. </label>
			                    <div class="input-control text" data-role="input-control">
			                        <input type="text" id="balance" name="balance" value="<?php echo number_format($Balance,2); ?>" disabled></input>
			                        <button class="btn-clear" tabindex="-1"></button>
			                    </div>
                			</td>
                		</tr>
                	</table>
                    
                </td>
                <td width="50%">

                	</td>
          	</tr> 
          	<tr>
              <td><label>Application Type</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ApplicationTypeID"  id="ApplicationTypeID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM ApplicationTypes ORDER BY 1";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ApplicationTypeID"];
                                $s_name = $row["ApplicationTypeName"];
                                if ($ApplicationTypeID==$s_id) 
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
           	<td><label>Application Category</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ApplicationCategoryID"  id="ApplicationCategoryID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from ApplicationCategories order by 1";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ApplicationCategoryID"];
                                $s_name = $row["ApplicationCategoryName"];
                                if ($ApplicationCategoryID==$s_id) 
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
              </td>
          </tr>
          <tr>
                <td width="50%">
                    <label>Area (Square Meters)</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="sqmeters" name="sqmeters" value="<?php echo $sqmeters; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>No of Floors</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="NoOfFloors" name="NoOfFloors" value="<?php echo $NoOfFloors; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>
                        
                     
        </table>
		<input name="Button" type="button" onclick="

		upnnn=this.form.upn.value;
		sqmeters=this.form.sqmeters.value;
		NoOfFloors=this.form.NoOfFloors.value;
		AppType=this.form.ApplicationTypeID.value;
		AppCategory=this.form.ApplicationCategoryID.value;
		CustName=this.form.CustomerName.value;
		RatesBalance=this.form.balance.value;
		Registered=this.form.registered.value;

		RatesBalance=parseFloat(RatesBalance.replace(',',''));

		if(Registered=='0'){
			alert('This Plot is not registered in the UGPay System. Kindly Register first.');
			exit;
		}else if(RatesBalance>1){
			alert('The plot has rates balance. Kindly get an invoice and clear rates first');
			exit;
		}else if(sqmeters==''){
			alert('The area (Sq. Meters) must be stated');
			exit;
		}else if(NoOfFloors=='0'){
			alert('The number of floors must be stated');
			exit;
		}else if(CustName==''){
			alert('The customer name must be stated');
			exit;
		}else if(AppType=='0'){
			alert('The application type must be stated');
			exit;
		}else if(AppCategory=='0'){
			alert('The Application Category must be stated');
			exit;
		}

		loadmypage('miscellaneous_bpa.php?'+
                                            '&CustomerName='+this.form.CustomerName.value+
                                            '&ApplicationTypeID='+this.form.ApplicationTypeID.value+ 
											'&ApplicationCategoryID='+this.form.ApplicationCategoryID.value+
											'&sqmeters='+this.form.sqmeters.value+
											'&upn='+this.form.upn.value+

											'&NoOfFloors='+this.form.NoOfFloors.value+
											'&ChargeID='+<?php echo $ChargeID ?>+
											'&LocalAuthorityID='+<?php echo $LocalAuthorityID ?>+									
        									'&save=1','content','loader','listpages','','Miscellaneous')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('miscellaneous_list.php?i=1','content','loader','listpages','','Miscellaneous')">
	 <!-- <input name="createFlatWindow" id="createFlatWindow" type="button" class="button"  value="Create Flat Window" onclick="flatWindow()"> -->
        
		<div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>