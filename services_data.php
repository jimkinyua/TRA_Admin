<?php
require 'DB_PARAMS/connect.php';
$DisplayArray = array(0=>'False', 1=>'True');
$Option = 'invoices';
$channel = array();
$sql = "select s.*,sc.CategoryName,dp.DepartmentName,sg.ServiceGroupName from Services s 
		inner join ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
		inner join Departments dp on s.DepartmentID=dp.DepartmentID 
		inner join ServiceGroup sg on s.ServiceGroupID=sg.ServiceGroupID";
$result = sqlsrv_query($db, $sql);	
while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	
	
	
	extract($row);
	
	$CustomerName =  '<a href="#" onClick="loadmypage(\'receipts.php?approve=1&InvoiceHeaderID='.$InvoiceHeaderID.'\',\'content\',\'loader\',\'receipts\')">'.$CustomerName.'</a>';	
	
	$Date 	= date('d/m/Y',strtotime($CreatedDate));
	$channel[] = array(
				$ServiceID,
				$ServiceName,
				$CategoryName,
				$DepartmentName,
				$ServiceGroupName
	);
}  
$rss = (object) array('aaData'=>$channel);
$json = json_encode($rss);
echo $json;
?>