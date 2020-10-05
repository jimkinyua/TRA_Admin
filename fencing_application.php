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

$sql="select lrn,plotno,LaifomsOwner, (select Balance from dbo.fnlastplotrecord($upn))Balance,LocalAuthorityID from land where upn=$upn";
$result=sqlsrv_query($db,$sql);
//echo $sql
while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{
	$lrn=$row['lrn'];
	$plotno=$row['plotno'];
	$CustomerName=$row['LaifomsOwner'];
	$Balance=$row['Balance'];
	$LocalAuthorityID=$row['LocalAuthorityID'];
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



if (isset($_REQUEST['save']))
{
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

	$FencingType='';	
	$FencingTypeID=	$_REQUEST['FencingTypeID'];
	$CustomerName=$_REQUEST['CustomerName'];
	$LocalAuthorityID=$_REQUEST['LocalAuthorityID'];

	
	$Length=$_REQUEST['Length'];
	
	$upn=$_REQUEST['upn'];

	$ServiceStatusID='5';
	$InvoiceDate=date('d/m/Y');
	$InvoiceNo=time();
	$Total=0;
	

	$sql = "SELECT ft.*,fs.ServiceID FROM ApplicationTypes ft 
	join FencingSetup fs on fs.FencingTypeID=ft.ApplicationTypeID 
	where ft.ApplicationTypeID=$FencingTypeID";  
	                    
    $result = sqlsrv_query($db, $sql);
    while($roww=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$FencingType=$roww['ApplicationTypeName'];
		$MainServiceID=$roww['ServiceID'];
	}




	$sql="select l.upn, sh.CustomerID,la.LRN lrn,la.PlotNo plotno,c.CustomerName,la.ServiceHeaderID,fn.laifomsUPN
		from land l 
		join LandApplication la on la.LRN=l.LRN and la.PlotNo=l.PlotNo 
		join serviceheader sh on la.ServiceHeaderID=sh.ServiceHeaderID
		join Customer c on sh.CustomerID=c.CustomerID
		cross apply (select [value] laifomsUPN from fnFormData(sh.ServiceHeaderID) where FormColumnID=12265) fn
		left join (select ServiceHeaderID,[Value] from FormData where FormColumnID=13270 ) lauth on 
		lauth.Value=l.LocalAuthorityID and lauth.ServiceHeaderID=sh.ServiceHeaderID 
		where l.UPN=$upn and l.LaifomsUPN=fn.laifomsUPN";


	$result=sqlsrv_query($db,$sql,$params,$options);

	$rows=sqlsrv_num_rows($result);
	if($rows==0){
		$sql="select l.upn, sh.CustomerID,la.LRN lrn,la.PlotNo plotno,c.CustomerName,la.ServiceHeaderID
		from land l 
		join LandApplication la on la.LRN=l.LRN and la.PlotNo=l.PlotNo 
		join serviceheader sh on la.ServiceHeaderID=sh.ServiceHeaderID
		join Customer c on sh.CustomerID=c.CustomerID
		
		left join (select ServiceHeaderID,[Value] from FormData where FormColumnID=13270 ) lauth on 
		lauth.Value=l.LocalAuthorityID and lauth.ServiceHeaderID=sh.ServiceHeaderID 
		where l.UPN=$upn ";

		$result=sqlsrv_query($db,$sql);
	}
	
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$lrn=$row['lrn'];
		$plotno=$row['plotno'];
		$CustomerID=$row['CustomerID'];
	}


	

	echo 'fsfs'.$CustomerID;

	$plot="Plot Details(Upn:$upn; Block: $lrn; Plot: $plotno)";
	$Description= $FencingType. '<br>(Measurements:'.$Length.'),'.$plot;


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
		$CustomerName=str_replace("'", "", $CustomerName);
		
		$sql="set dateformat dmy 
		Insert into InvoiceHeader (InvoiceDate,InvoiceNo,CustomerID,CustomerName,Description,CreatedBy,ServiceHeaderID) 
		Values('$InvoiceDate','$InvoiceNo',$CustomerID,'$CustomerName','$Description','$CreatedBy',$ServiceHeaderID) 
		SELECT SCOPE_IDENTITY() AS ID";

		$result3 = sqlsrv_query($db, $sql);	
		if ($result3)
		{

			$InvoiceHeaderID=lastid($result3);
			$Commit=0;

			$sql="select * from FencingSetup where FencingTypeID=$FencingTypeID";
			//echo $sql;
			$result=sqlsrv_query($db,$sql);
			if($result)
			{
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{
					$ServiceID=$rw['ServiceID'];
					$Fixed=$rw['fixed'];
					$Percentage=$rw['Percentage'];					
					$Amount=(double)$rw['Amount'];
					$Minimum=(double)$rw['Minimum'];

					if($Fixed==0){	

						if($Percentage==1){
							$Amount=($Amount/100)*$Length;
						}else{
							$Amount=(double)$Length*$Amount;
						}			
						 

						if($Amount<$Minimum){
							$Amount=$Minimum;
						}
					}

					$sql="set dateformat dmy insert into InvoiceLines 
					(InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
					Values($InvoiceHeaderID,$ServiceHeaderID,'',$ServiceID,$Amount,'$CreatedBy')";
					
					$rs = sqlsrv_query($db, $sql);

					if($rs)
					{
						$Total+=$Amount;
						$Commit=1;
					}else{
						DisplayErrors();
						$Commit=0;
					}
				}
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
			//echo $sql;
			DisplayErrors();
		}
	}else{
		DisplayErrors();
		echo $sql;
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
            $("#Length").keydown(function(event) {
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
          		<td>
          			<div class="input-control checkbox" data-role="input-control">
          				<label class="inline-block">
                            <input type="checkbox" id="SeparateApplicant" name="SeparateApplicant"/>
                            <span class="check"></span>
                            Applicant is not the Plot's Owner
                        </label>
          			</div>
          		</td>
          	</tr>
          	<tr>
              <td><label>Fencing Type</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="FencingTypeID"  id="FencingTypeID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM ApplicationTypes where ApplicationTypeID in (select FencingTypeID from FencingSetup) ORDER BY 1";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ApplicationTypeID"];
                                $s_name = $row["ApplicationTypeName"];
                                if ($FencingTypeID==$s_id) 
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
                <td width="50%">
                    <label>Quantifyable Units (Meters/Portions)</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Length" name="Length" value="<?php echo $Length; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>                        
                     
        </table>
		<input name="Button" type="button" onclick="

		upnnn=this.form.upn.value;
		Length=this.form.Length.value;		
		FencingTypeID=this.form.FencingTypeID.value;
		CustName=this.form.CustomerName.value;
		RatesBalance=this.form.balance.value;
		Registered=this.form.registered.value;
		sapplicant=this.form.SeparateApplicant.checked;

		RatesBalance=parseFloat(RatesBalance.replace(',',''));

		if(Registered=='0'){
			alert('This Plot is not registered in the UGPay System. Kindly Register first.');
			exit;
		}else if(RatesBalance>1){
			alert('The plot has rates balance. Kindly get an invoice and clear rates first');
			exit;
		}else if(Length==''){
			alert('The area (Sq. Meters) must be stated');
			exit;
		}else if(CustName==''){
			alert('The customer name must be stated');
			exit;
		}else if(FencingTypeID=='0'){
			alert('The Fencing type must be stated');
			exit;
		}

		if(sapplicant=='1')
		{

			loadmypage('fencing_application_b.php?upn='+this.form.upn.value+'','content')
		}else
		{
			loadmypage('fencing_application.php?'+
            '&CustomerName='+this.form.CustomerName.value+
            '&FencingTypeID='+this.form.FencingTypeID.value+ 											
			'&Length='+this.form.Length.value+
			'&upn='+this.form.upn.value+
			'&ChargeID='+<?php echo $ChargeID ?>+
			'&LocalAuthorityID='+<?php echo $LocalAuthorityID ?>+									
			'&save=1','content','loader','listpages','','Miscellaneous')
		}

        " value="Save">        
		<div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>