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
$CreatedBy = $_SESSION['UserID'];
$ChargeID='';
$ServiceHeaderID='';
$Description='';

$fromDate=date('d/m/Y');
$toDate=date('d/m/Y');
$Customer='';

if(isset($_REQUEST['fromDate'])){$fromDate=$_REQUEST['fromDate'];}
if(isset($_REQUEST['toDate'])){$toDate=$_REQUEST['toDate'];}
if(isset($_REQUEST['Customer'])){$Customer=$_REQUEST['Customer'];}


if (isset($_REQUEST['delete']))
{
	$ApplicationID=$_REQUEST['ApplicationID'];
	//print_r($_REQUEST);
	
	$sql="exec spDeleteMiscelaneous $ApplicationID";
	$result=sqlsrv_query($db,$sql);
	if($result){
		
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$msg=$row['Message'];
		}		
	}else{
		DisplayErrors();
	}
	
}

if (isset($_REQUEST['save']))
{	
	$Total='';
	$booklet_Amount=0;
	$booklet_ServiceID='';
	$stamp_Amount=0;
	$stamp_ServiceID='';
	$yellow_Amount=0;
	$yellow_ServiceID='';
	$os_ServiceID='';
	$scrutiny_Amount=0;
	$scrutiny_ServiceID='';
	$application_Amount=0;
	$application_ServiceID='';
	$health_ServiceID='';
	$health_Amount=0;
	$occupation_Amount=0;
	$occupation_ServiceID='';
	$structural_Amount=0;
	$structural_ServiceID='';
	$MainServiceID='';
	$liquor_ServiceID='';
	$liquor_Amount=0;
	$hygine_ServiceID='';
	$hygine_Amount=0;

	$inpection_Amount=0;
	$inspection_ServiceID='';


	
	$result4a=null;
	$result4b=null;
	$result4c=null;
	$result4d=null;
	$result4e=null;
	$result4f=null;
	$result4g=null;
	$result4h=null;
	$result4i=null;
	
/* 	echo $CreatedBy;
	exit; */
	
	/* if (isset($_REQUEST['yellow_ServiceID']))
	{
		$yellow_ServiceID=$_REQUEST['yellow_ServiceID'];
	} */
	//print_r ($_REQUEST);
	
	
	if (isset($_REQUEST['yellow_ServiceID']))
	{
		if (isset($_REQUEST['yellow_ServiceID'])){ $yellow_ServiceID=$_REQUEST['yellow_ServiceID'];}
		if (isset($_REQUEST['booklet_ServiceID'])){ $booklet_ServiceID=$_REQUEST['booklet_ServiceID'];}
		if (isset($_REQUEST['stamp_ServiceID'])){ $stamp_ServiceID=$_REQUEST['stamp_ServiceID'];}		
		
		if (isset($_REQUEST['booklet_Amount'])){ $booklet_Amount=$_REQUEST['booklet_Amount'];}
		if (isset($_REQUEST['stamp_Amount'])){ $stamp_Amount=$_REQUEST['stamp_Amount'];}
		if (isset($_REQUEST['yellow_Amount'])){ $yellow_Amount=$_REQUEST['yellow_Amount'];}	

		$MainServiceID=$yellow_ServiceID; 
	}elseif (isset($_REQUEST['liquor_ServiceID']))
	{
		if (isset($_REQUEST['liquor_ServiceID'])){ $liquor_ServiceID=$_REQUEST['liquor_ServiceID'];}
		if (isset($_REQUEST['hygine_ServiceID'])){ $hygine_ServiceID=$_REQUEST['hygine_ServiceID'];}
		if (isset($_REQUEST['health_ServiceID'])){ $health_ServiceID=$_REQUEST['health_ServiceID'];}		
		
		if (isset($_REQUEST['liquor_Amount'])){ $liquor_Amount=$_REQUEST['liquor_Amount'];}
		if (isset($_REQUEST['hygine_Amount'])){ $hygine_Amount=$_REQUEST['hygine_Amount'];}
		if (isset($_REQUEST['health_Amount'])){ $health_Amount=$_REQUEST['health_Amount'];}	

		$MainServiceID=$liquor_ServiceID; 
	}elseif (isset($_REQUEST['liquor_applic_ServiceID']))
	{
		//print_r($_REQUEST); exit;
		if (isset($_REQUEST['liquor_applic_ServiceID'])){ $liquor_applic_ServiceID=$_REQUEST['liquor_applic_ServiceID'];}
		if (isset($_REQUEST['Inspection_ServiceID'])){ $Inspection_ServiceID=$_REQUEST['Inspection_ServiceID'];}		
		
		if (isset($_REQUEST['Application_Amount'])){ $Application_Amount=$_REQUEST['Application_Amount'];}
		if (isset($_REQUEST['Inspection_Amount'])){ $Inspection_Amount=$_REQUEST['Inspection_Amount'];}
	

		$MainServiceID=$liquor_applic_ServiceID; 
	}elseif(isset($_REQUEST['scrutiny_ServiceID']))
	{	
		if (isset($_REQUEST['scrutiny_ServiceID'])){ $scrutiny_ServiceID=$_REQUEST['scrutiny_ServiceID'];}
		if (isset($_REQUEST['application_ServiceID'])){ $application_ServiceID=$_REQUEST['application_ServiceID'];}
		if (isset($_REQUEST['health_ServiceID'])){ $health_ServiceID=$_REQUEST['health_ServiceID'];}
		if (isset($_REQUEST['occupation_ServiceID'])){ $occupation_ServiceID=$_REQUEST['occupation_ServiceID'];}
		if (isset($_REQUEST['structural_ServiceID'])){ $structural_ServiceID=$_REQUEST['structural_ServiceID'];}
		
		if (isset($_REQUEST['scrutiny_Amount'])){ $scrutiny_Amount=$_REQUEST['scrutiny_Amount'];}
		if (isset($_REQUEST['application_Amount'])){ $application_Amount=$_REQUEST['application_Amount'];}
		if (isset($_REQUEST['health_Amount'])){ $health_Amount=$_REQUEST['health_Amount'];}
		if (isset($_REQUEST['occupation_Amount'])){ $occupation_Amount=$_REQUEST['occupation_Amount'];}
		if (isset($_REQUEST['structural_Amount'])){ $structural_Amount=$_REQUEST['structural_Amount'];}

		$MainServiceID=$scrutiny_ServiceID;		
	}else{
		if (isset($_REQUEST['Os_Amount'])){ $Os_Amount=$_REQUEST['Os_Amount'];}	
		if (isset($_REQUEST['os_ServiceID'])){ $os_ServiceID=$_REQUEST['os_ServiceID'];}

		$MainServiceID=$os_ServiceID;
	}

	if (isset($_REQUEST['CustomerName'])){ $CustomerName=$_REQUEST['CustomerName'];}	
	if (isset($_REQUEST['Description'])){ $Description=$_REQUEST['Description'];}

	if (isset($_REQUEST['Os_Amount'])){ $Os_Amount=$_REQUEST['Os_Amount'];}	
	if (isset($_REQUEST['os_ServiceID'])){ $os_ServiceID=$_REQUEST['os_ServiceID'];}
	

	if ($CustomerName==''){
		$msg="You Must Enter the Custmer Name";
		//header("Location:miscellaneous.php");
	}else{

	
	$Total=(double)$Os_Amount+(double)$booklet_Amount;+(double)$stamp_Amount;+(double)$yellow_Amount+(double)$scrutiny_Amount+(double)$application_Amount+(double)$health_Amount;+(double)$structural_Amount+(double)$occupation_Amount;
	
	//echo $MainServiceID;
	
	$sqla='';
	$CustomerID='TEST';
	
	$ServiceStatusID='5';
	$InvoiceDate=date('d/m/Y');
	$InvoiceNo=time();
	
	if ($ChargeID=='')
	{
		$sql="select ca.CustomerID from CustomerAgents ca 
				join Customer c on ca.CustomerID=c.CustomerID 
				where AgentID='$CreatedBy' and c.Type='individual'";
		
		$result0=sqlsrv_query($db,$sql);
		while($roww=sqlsrv_fetch_array($result0,SQLSRV_FETCH_ASSOC))
		{
			$CustomerID=$roww['CustomerID'];
		}
				
		
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
			
			$sql="set dateformat dmy insert into InvoiceHeader (InvoiceDate,InvoiceNo,CustomerID,CustomerName,Description,CreatedBy) Values('$InvoiceDate','$InvoiceNo',$CustomerID,'$CustomerName','$Description','$CreatedBy') SELECT SCOPE_IDENTITY() AS ID";
			$result3 = sqlsrv_query($db, $sql);	
			if ($result3)
			{
				$InvoiceHeaderID=lastid($result3);
				
				//Yellow Fever
				
				if($booklet_ServiceID!=='' || $booklet_Amount!==''){
					//echo 'ndani';
					
					$sqla="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$booklet_ServiceID,$booklet_Amount,'$CreatedBy')";
					$result4a = sqlsrv_query($db, $sqla);						
				}
				if($yellow_ServiceID!=='' and $yellow_Amount!==''){
					
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$yellow_ServiceID,$yellow_Amount,'$CreatedBy')";
					$result4b = sqlsrv_query($db, $sql);					
				}
				if($stamp_ServiceID!=='' and $stamp_Amount!==''){
					
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$stamp_ServiceID,$stamp_Amount,'$CreatedBy')";
					$result4c = sqlsrv_query($db, $sql);					
				}

				//Liquor Application
				
				if($liquor_applic_ServiceID!=='' || $Application_Amount!==''){
					//echo 'ndani';
					
					$sqla="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$liquor_applic_ServiceID,$Application_Amount,'$CreatedBy')";
					$result4a = sqlsrv_query($db, $sqla);						
				}
				if($Inspection_ServiceID!=='' and $Inspection_Amount!==''){
					
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$Inspection_ServiceID,$Inspection_Amount,'$CreatedBy')";
					$result4b = sqlsrv_query($db, $sql);					
				}
				
				//Liquor Licence
				
				if($liquor_ServiceID!=='' || $liquor_Amount!==''){
					//echo 'ndani';
					
					$sqla="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$liquor_ServiceID,$liquor_Amount,'$CreatedBy')";
					$result4a = sqlsrv_query($db, $sqla);						
				}
				if($hygine_ServiceID!=='' and $hygine_Amount!==''){
					
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$hygine_ServiceID,$hygine_Amount,'$CreatedBy')";
					$result4b = sqlsrv_query($db, $sql);					
				}
				
				
				//SCRUTINY SFDA
				if($scrutiny_ServiceID!=='' and $scrutiny_Amount!==''){
					
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$scrutiny_ServiceID,$scrutiny_Amount,'$CreatedBy')";
					$result4d = sqlsrv_query($db, $sql);						
				}
				if($application_ServiceID!=='' and $application_Amount!==''){

					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$application_ServiceID,$application_Amount,'$CreatedBy')";
					$result4e = sqlsrv_query($db, $sql);					
				}
				if($health_ServiceID!=='' and $health_Amount!==''){
					//echo 'Health:'.$health_Amount;
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$health_ServiceID,$health_Amount,'$CreatedBy')";
					$result4f = sqlsrv_query($db, $sql);					
				}
				if($structural_ServiceID!=='' and $structural_Amount!==''){
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$structural_ServiceID,$structural_Amount,'$CreatedBy')";
					$result4g = sqlsrv_query($db, $sql);					
				}
				if($occupation_ServiceID!=='' and $occupation_Amount!==''){
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$occupation_ServiceID,$occupation_Amount,'$CreatedBy')";
					$result4h = sqlsrv_query($db, $sql);					
				}
				
				//others
				if($os_ServiceID!=='' and $Os_Amount!==''){
					$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$os_ServiceID,$Os_Amount,'$CreatedBy')";
					$result4i = sqlsrv_query($db, $sql);					
				}

				
				
				
				$sql="Insert into Miscellaneous (ServiceHeaderID,CustomerName,Description,Amount,CreatedBy)
				Values('$ServiceHeaderID','$CustomerName','$Description','$Total','$CreatedBy')";
				//echo $sql;
				$result2=sqlsrv_query($db,$sql);
				if($result2)
				{						
					
				}else
				{
					echo 'Result 2 failed'.'<br>'.$sql;
				}					
							
			}else
			{
				echo 'Result 3 failed'.'<br>'.$sql;
			}
			
		}else
		{
			echo 'Result 1 failed'.'<br>'.$sql;
		}

		if($result && $result2 && $result3)
		{
			$rst=SaveTransaction($db,$UserID," Created Miscellaneous Invoice Number ".$InvoiceHeaderID);
			sqlsrv_commit($db);

			$ViewBtn  = '<a href="reports.php?rptType=Invoice&ServiceHeaderID='.$ServiceHeaderID.'&InvoiceHeaderID='.$InvoiceHeaderID.'" target="_blank">Click to View</a>';

			$msg="Invoice No $InvoiceHeaderID Created Successfully. $ViewBtn";

			//$msg="Invoice Created Successfully";

			//invoice printing
			// $feedBack=createInvoice($db,$ServiceHeaderID,$cosmasRow,$Description,$CustomerName,$InvoiceHeaderID);					  
			// $msg=$feedBack[1];			
			$Sawa=true;
		}else
		{
			sqlsrv_rollback($db);
			$msg="Transaction Failed";
			$Sawa=false;
		}		

	} else
	{
		echo 'Update Functionality Pending';
	}
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
	    <form>
		<legend>Miscellaneous Income</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('miscellaneous.php?i=1','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			  <tr>
                    <th colspan="6" class="text-center">
					<table width="100%">
						<tr>
						  <td><label>From Date (dd/mm/yyyy)</label>
								<div class="input-control text" data-role="input-control">						
									<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>				
								</div>
							</td>
							<td><label>To Date (dd/mm/yyyy)</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>				
									</div>
								</td>
								<td><label>Customer</label>
										<div class="input-control text" data-role="input-control">						
											<input type="text" width="100" id="Customer" name="Customer" value="<?php echo $Customer ?>"></input>				
										</div>
								</td>								
								
								<td><label>&nbsp;</label>
								<input name="btnSearch" type="button" onclick="loadmypage('miscellaneous_list.php?'+
											'&fromDate='+this.form.fromDate.value+								
											'&toDate='+this.form.toDate.value+
											'&Customer='+this.form.Customer.value+								'&search=1','content','loader','listpages','','Miscellaneous','fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':Customer='+this.form.Customer.value+'')" value="Search">
								</td>
						</tr>
					</table>
					</th>
                  </tr> 
			<tr>
				<th width="10%" class="text-left">Application ID</th>
				<th width="20%" class="text-left">Customer Name</th>
				<th width="20%" class="text-left">Description</th>
				<th width="20%" class="text-left">Amount</th>
				<th width="20%" class="text-left">Date</th>
				<th width="10%" class="text-left">&nbsp;</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 

		</form>
	</div>
</body>


