<?php
require 'DB_PARAMS/connect.php';
$DisplayArray = array(0=>'False', 1=>'True');
$Option = 'clients';
$channel = array();
/*$sql = "SELECT sh.ServiceHeaderID AS ApplicationID,sh.ServiceStatusID,ss.ServiceStatusName, s.ServiceName,c.CustomerID, c.CustomerName, sh.SubmissionDate,s.ServiceID,s.ServiceCategoryID
FROM dbo.ServiceHeader AS sh INNER JOIN 
dbo.Services AS s ON sh.ServiceID = s.ServiceID INNER JOIN
dbo.Customer AS c ON sh.CustomerID = c.CustomerID INNER JOIN 
dbo.ServiceStatus ss ON sh.ServiceStatusID=ss.ServiceStatusID where s.ServiceCategoryID<>1 and sh.ServiceStatusID not in (0,7)";
//echo $sql;
$result = sqlsrv_query($db, $sql);	
while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	extract($row);
	$SubmissionDate= date('d/m/Y',strtotime($SubmissionDate));	
	$CustomerName =  '<a href="#" onClick="loadpage(\'service_approval.php?approve=1&ApplicationID='.$ApplicationID.'&CustomerName='.$CustomerName.'&CustomerID='.$CustomerID.'&ServiceID='.$ServiceID.'&ServiceName='.$ServiceName.'&CurrentStatus='.$ServiceStatusID.'&ServiceCategoryID='.$ServiceCategoryID.'\',\'content\')">'.$CustomerName.'</a>';
	

	$channel[] = array(			
				$ApplicationID,
				$CustomerName,				
				$ServiceName,
				$SubmissionDate,
				$ServiceStatusName		
	);
	
}*/  

$rss = (object) array('aaData'=>$channel);
$json = json_encode($rss);
echo $json;
?>