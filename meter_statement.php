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


$StatementID=0;
$RecordDate='';
$MeterReading="";
$Amount=0;
$Balance=0;
$UnitsUsed=0;
$UnitPrice=75;

if(isset($_REQUEST['SerialNo'])){$SerialNo=$_REQUEST['SerialNo'];}

if (isset($_REQUEST['save']))
{
		
	$SerialNo=$_REQUEST['SerialNo'];
	$RecordDate=$_REQUEST['RecordDate'];
	$MeterReading=$_REQUEST['MeterReading'];
	$Description='Meter Reading';
	$CustomerID=$_REQUEST['CustomerID'];

	$BillNumber='Water Bill '.date('m').'-'.date('Y');
	$ServiceID=1138;


	$sql="select * from fnLastMeterRecord ($SerialNo)";
	$result=sqlsrv_query($db,$sql);

	while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$Balance=$rw['Balance'];
		$LastReading=$rw['LastReading'];
	}

	$UnitsUsed=(double)$MeterReading-(double)$LastReading;	

	$sql="select UnitPrice from tarrifs where $UnitsUsed>=[From] and $UnitsUsed<=[To]";
	$result=sqlsrv_query($db,$sql);
	while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$UnitPrice=$rw['UnitPrice'];
	}

	$Amount=$UnitsUsed*(double)$UnitPrice;


	$Balance+=(double)$Amount;	


	$LastReading=$MeterReading;

	
	$RecordDate=date_create($_REQUEST['RecordDate']);
	$RecordDate=date_format($RecordDate,"d/m/Y");	
	
		
	$sql = "set dateformat dmy INSERT INTO MeterStatement (MeterNo,RecordDate,[Description],LastReading,UnitsUsed,UnitPrice,Amount,Balance,CreatedBy) 
		    VALUES('$SerialNo','$RecordDate','$Description','$LastReading','$UnitsUsed','$UnitPrice','$Amount','$Balance','$CreatedUserID')" ;	

	$result=sqlsrv_query($db,$sql);

	$sql = "set dateformat dmy INSERT INTO MeterStatement (MeterNo,RecordDate,[Description],LastReading,UnitsUsed,UnitPrice,Amount,Balance,CreatedBy) 
		    VALUES('$SerialNo','$RecordDate','$Description','$LastReading','$UnitsUsed','$UnitPrice','$Amount','$Balance','$CreatedUserID')" ;	

	$result=sqlsrv_query($db,$sql);

	if($result)
	{

		//Create Invoice
		$s_sql="set dateformat dmy insert into ServiceHeader (CustomerID,ServiceID,CreatedBy,ServiceStatusID) 
		Values('$CustomerID',$ServiceID,'$CreatedUserID','5') SELECT SCOPE_IDENTITY() AS ID";
		$s_result = sqlsrv_query($db, $s_sql);
				
		if ($s_result)
		{
			$ServiceHeaderID=lastid($s_result);
			
			$s_sql="set dateformat dmy insert into InvoiceHeader (CustomerID,ServiceHeaderID,Description,CreatedBy,InvoiceNo) 
			Values('$CustomerID',$ServiceHeaderID,'$BillNumber','$CreatedUserID','$SerialNo') SELECT SCOPE_IDENTITY() AS ID";
			$s_result1 = sqlsrv_query($db, $s_sql);
					
			if ($s_result1)
			{
				$InvoiceHeaderID=lastid($s_result1);				
								
				//insert into invoiceLines

				$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Description,Amount,CreatedBy) 
						Values($InvoiceHeaderID,$ServiceHeaderID,$ServiceID,'$BillNumber',$Amount,'$CreatedUserID')";						
				$s_result2 = sqlsrv_query($db, $s_sql);
				//echo 'invoiceheader lines done';	
				$loopOkey=true;
				$PermitCost=$ServiceAmount;
				$InvoiceAmount+=$ServiceAmount;
				if ($s_result2)
				{								
					//check whether there are carrier  charges
				    $sql="select sp.service_add ServiceID,s.ServiceName, sc.Amount 
					from serviceplus sp
					join services s on sp.service_add=s.serviceid
					join servicecharges sc on sp.service_add=sc.serviceid                                 
					join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID                                      
					and fy.isCurrentYear=1			            
					and sp.serviceid=1";

					//echo $sql;

					$s_result2 = sqlsrv_query($db, $sql);
					while ($row = sqlsrv_fetch_array( $s_result2, SQLSRV_FETCH_ASSOC))
					{									
						$ServiceAmount=$row["Amount"];
						$ServiceName=$row['ServiceName'];
						$ServiceID=$row['ServiceID'];
						$InvoiceAmount+=$ServiceAmount;
						
						$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Amount,CreatedBy) 
								Values($InvoiceHeaderID,$ServiceHeaderID,$ServiceID,$ServiceAmount,$CreatedUserID)";
						$result3 = sqlsrv_query($db, $s_sql);
						if (!$result3)
						{
							DisplayErrors();
							$loopOkey=false;
							break;
						}else
						{
							$Balance+=$ServiceAmount;
							$sql = "set dateformat dmy INSERT INTO MeterStatement (MeterNo,RecordDate,[Description],LastReading,UnitPrice,Amount,Balance,CreatedBy) 
							    VALUES('$SerialNo','$RecordDate','$ServiceName','$LastReading',$ServiceAmount,'$ServiceAmount','$Balance','$CreatedUserID')";	

							$result=sqlsrv_query($db,$sql);
						}

					}
					if($loopOkey==true)
					{
						$mail=true;	
						$rst=SaveTransaction($db,$CreatedUserID," Created Water Bill Invoice Number ".$InvoiceHeaderID);
							
						sqlsrv_commit($db);

						$ViewBtn  = '<a href="reports.php?rptType=Invoice&ServiceHeaderID='.$ServiceHeaderID.'&InvoiceHeaderID='.$InvoiceHeaderID.'" target="_blank">Click to View</a>';

						$msg="Invoice No $InvoiceHeaderID Created Successfully. $ViewBtn";															
					}

				}else
				{
					DisplayErrors().'<BR>';
					$Sawa=false;
				}
				
			}else{
				DisplayErrors();
				$sawa=false;
			}
			
		}else{
			DisplayErrors();
			$Sawa=false;
		}

		
	}else{
		DisplayErrors();
		echo $sql;
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
        <legend>METER STATEMENT (Meter No: <?php echo $SerialNo; ?>)</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th  class="text-left">Record Date</th>                    
                    <th  class="text-left">Description</th>                    
                    <th  class="text-left">Meter Reading</th>
                    <th  class="text-left">Units Used</th>
                    <th  class="text-left">Units Cost</th>
                    <th  class="text-left">Amount</th>
                    <th  class="text-left">Balance</th>                    
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>