<?php
//session_start();
require 'DB_PARAMS/connect.php';

$ApplicationID=$_REQUEST['ApplicationID'];
$ServiceID=$_REQUEST['ServiceID'];
$SubSystemID=$_REQUEST['SubSystemID'];


	$sql="select Distinct s.ServiceID,s.ServiceName, sc.Amount 
			from ServiceCharges sc
			join Services s on sc.ServiceID=s.ServiceID
			join FinancialYear fy on fy.FinancialYearID=sc.FinancialYearId
			where  SubSystemId=$SubSystemID and sc.ServiceID=$ServiceID and fy.isCurrentYear=1";

	$result=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$mdata.='<tr>
			<td>'.number_format($row['ServiceID'],2).'</td>
			<td>'.$row['ServiceName'].'</td>
			<td>'.number_format($row['Amount'],2).'</td>		
			</tr>';
	}
  //echo $sql;
	$sql="select distinct  s2.ServiceID,s2.ServiceName ,sc.Amount
			from ServiceCharges sc
			join ServicePlus sp on sp.service_add=sc.ServiceID
			join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID
			join ServiceHeader sh on sh.ServiceID=sp.ServiceID
			join Services s1 on sp.ServiceID=s1.ServiceID
			join Services s2 on sp.Service_add=s2.ServiceID
			and sh.ServiceHeaderID=$ApplicationID
			and fy.isCurrentYear=1
			and sc.SubSystemId=3";

			//echo $sql;

			$result=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{
				$mdata.='<tr>
					<td>'.number_format($row['ServiceID'],2).'</td>
					<td>'.$row['ServiceName'].'</td>
					<td>'.number_format($row['Amount'],2).'</td>		
					</tr>';
			}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Aplication Charges</title>	
</head>
<body class="metro">
	<div class="example" class="display" cellspacing="0" width="100%">
            <table class="table striped bordered hovered">
                <thead>				
                <tr>
                    <th class="text-left">Service ID</th>
                    <th class="text-left">Service Name</th>
					<th class="text-left">Amount</th>					
                </tr>
                </thead>
				<tbody>
					<?php
						echo $mdata;
					?>
                <tbody>
                
                </tbody>
            </table>
        </div>
</body>
</html>
