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
$CustomerEmail='';
$Sawa=false;
$UserID = $_SESSION['UserID'];
$ServiceHeaderType='';

if (isset($_REQUEST['save']) && $_REQUEST['NextStatus']!='')
{
	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=$NextStatus;
	$ServiceHeaderType=$_REQUEST['ServiceHeaderType'];
	
	if ($NextStatus=='')
	{
		break;		
	}

	
	
	$s_sql="select * from Customer where CustomerID=$CustomerID";
	$s_result=sqlsrv_query($db,$s_sql);
	//echo $s_sql;
	if ($s_result)
	{					
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$CustomerEmail=$row['Email'];
		}
	}
	
	$s_sql="select ServiceStatusID from ServiceStatus where ServiceStatusID='$NextStatus'";
	$s_result=sqlsrv_query($db,$s_sql);

	if ($s_result){
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){			
			$NextStatusID=$row['ServiceStatusID'];
		}
	}
	
	$s_sql="Insert into ServiceApprovalActions(ServiceHeaderID,ServiceStatusID,NextServiceStatusID,Notes,CreatedBy) 
	Values ($ApplicationID,$CurrentStatus,$NextStatusID,'$Notes',$UserID)";	
 
	$s_result = sqlsrv_query($db, $s_sql);
	
	if ($s_result) 
	{	
		if ($NextStatusID=='')
		{
			exit;	
		}		
		
		if($NextStatusID==5)
		{			
			if($ServiceHeaderType==1)//Land Registration
			{
				$sql="insert into LandOwner (UPN,CustomerID)
					select ln.UPN,sh.CustomerID from land ln 
					join LandApplication la on la.LRN=ln.LRN and la.PlotNo=ln.PlotNo
					join ServiceHeader sh on la.ServiceHeaderID=sh.ServiceHeaderID
					where la.ServiceHeaderID=$ApplicationID";
					
				$result=sqlsrv_query($db,$sql);
				if ($result){
					$msg="Land Registered Successfully";
					$sawa=true;
				}else
				{
					DisplayErrors();
				}
			}				
			else if($ServiceHeaderType==2)
			{

				$sql="SELECT h.UHN,sh.CustomerID
				FROM Houses h 
				join HouseApplication ha on ha.HouseNo=h.HouseNo and ha.EstateID=h.EstateID
				join ServiceHeader sh on ha.ServiceHeaderID=sh.ServiceHeaderID
				where ha.ServiceHeaderID=$ApplicationID";
					
				$result=sqlsrv_query($db,$sql);
				if ($result)
				{
					while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
					{			
						$UHN=$row['UHN'];
						$CustomerID=$row['CustomerID'];
						
						$sql="update tenancy set CustomerID=$CustomerID where UHN='$UHN'";
						$feedBack=sqlsrv_query($db,$sql);
						if ($feedBack)
						{
							$msg="House Registered Successfully";
							$sawa=true;							
						}else
						{
							DisplayErrors();
							echo $sql;
						}				
					}
				}else
				{
					DisplayErrors();
				}
							
			}
			else if($ServiceHeaderType==3)
			{
				
			}else if($ServiceHeaderType==4)
			{
				
			}else 
			{
				
			}
			
			$InvoiceHeader="";
			$ServiceAmount=0;
			$InvoiceDate= date("d/m/Y");
			
			//get the amount
			
			$s_sql="select sc.amount,s.chargeable from servicecharges sc inner join 
			services s on sc.serviceid=s.serviceid inner join 
			serviceheader sh on sh.serviceid=s.serviceid
			where sh.ServiceHeaderID=$ApplicationID and sc.financialyearid=2 and sc.subsystemid=1";
			
			$s_result=sqlsrv_query($db,$s_sql);
			
			if ($s_result)
			{					
				while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
				{					
					$ServiceAmount=$row['amount'];
					$Chargeable=$row['Chargeable'];
				}
			}
			
			if ($Chargeable==0)
			{
				$msg='Service approved';
				$sawa=true;
			}else
			{
			
				if ($ServiceAmount<=0)
				{
					$msg="The cost of the service is not set, the process therefore aborts";
				}else
				{		
					$s_sql="insert into InvoiceHeader (InvoiceDate,CustomerID,CreatedBy) Values($InvoiceDate,$CustomerID,$UserID)";				
					//echo $s_sql;
					$s_result = sqlsrv_query($db, $s_sql);
					if ($s_result)
					{						
						//get the invoiceheader
						$s_sql="select InvoiceHeaderID from InvoiceHeader where CustomerID=$CustomerID and InvoiceDate=$InvoiceDate";
						$s_result=sqlsrv_query($db,$s_sql);
						//echo $s_sql;
						if ($s_result)
						{					
							while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
							{			
								$InvoiceHeader=$row['InvoiceHeaderID'];
							}
						}									
										
						//insert into invoiceLines
						
						$s_sql="insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Amount,CreatedBy) Values($InvoiceHeader,$ApplicationID,$ServiceAmount,$UserID)";
						$s_result = sqlsrv_query($db, $s_sql);
						if ($s_result)
						{											
							$msg=createInvoice($db,$ApplicationID,$cosmasRow);
							$Sawa=true;
						}else
						{
							$Sawa=false;
						}
					}
				}
			}
		}
	}
	else if ($NextStatusID==6)
	{
		//Inform the customer of the rejection
		$txt=$Notes;//"Your Service application have been rejected. Contact the county for the explanation";
		
		if($txt=="")
		{
			$msg="Kindly State the reason for rejection";
			$Sawa=false;					
		}else
		{
			$result=php_mailer($CustomerEmail,$cosmasRow['Email'],$cosmasRow['CountyName'],'Service Rejection',$txt,'','');
			$msg=$result;
			$Sawa=true;
		}				
	}	
	else if ($NextStatusID==7)
	{
		$Balance=0;			
		//insert into invoiceLines
		
		$s_sql="Select Balance From vwInvoices where ServiceHeaderID=$ApplicationID";
		$s_result = sqlsrv_query($db, $s_sql);
		if ($s_result)
		{	
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{							
				$Balance=$row['Balance'];
			}
		}
		
		if($Balance>0)//, for the sake of Bomet County Demo
		{
			$msg= "The service is not fully paid for";
			$Sawa=false;
		}else
		{
			
			//update the serviceheader table, generate the permit.
			$permitNo=time() .'-'.$ApplicationID;
			$validity=date('Y', strtotime('+1 year'));
			$expiryDate=date('d/m/Y');
			$sql="set dateformat dmy insert into Permits(permitNo,ServiceHeaderID,Validity,ExpiryDate) values('$permitNo',$applicationID,'@validity','$expiryDate')";

			$s_result = sqlsrv_query($db, $sql);
			if ($s_result)
			{
				$result=createPermit($db,$ApplicationID,$cosmasRow);
				$msg=$result;
				$Sawa=true;
			}else
			{
				DisplayErrors();
				$Sawa=false;
			}	
		}						
	}else
	{
		$Sawa=true;
	}		
	//move to the next status
	if($Sawa==true)
	{			
		$s_sql="Update ServiceHeader set ServiceStatusID=$NextStatus where ServiceHeaderID=$ApplicationID";	
		$s_result = sqlsrv_query($db, $s_sql);	
	}else
	{
		echo $s_sql;// 'Something is Wrong';
		echo '<br>';
		DisplayErrors();
	}			
}else
{
	echo $s_sql;// 'Something is Wrong';
	echo '<br>';
	DisplayErrors();
}
	

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

    
<body class="metro">
        <div class="example">
        <legend>Customer Applications</legend>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('clients.php?add=1','content')"></a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="5%" class="text-left"> ApplicationID</th>
                    <th width="12%" class="text-left">CustomerName</th>                   
                    <th width="20%" class="text-left">Service Name</th>
                    <th width="12%" class="text-left">Application Date</th>
                    <th width="12%" class="text-left">Current Status</th>
                </tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th class="text-left">Application ID</th>
                    <th class="text-left">Customer Name</th>                    
                    <th class="text-left">Service Name</th>
                    <th class="text-left">Application Date</th>   
                    <th class="text-left">Current Status</th>                
                </tr>
                </tfoot>
            </table>


		</div>
</body>