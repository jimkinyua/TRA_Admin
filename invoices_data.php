<?php
require 'DB_PARAMS/connect.php';
$DisplayArray = array(0=>'False', 1=>'True');
$Option = 'invoices';
$channel = array();
$sql = "select distinct sh.ServiceHeaderID,ih.InvoiceHeaderID, ih.CustomerID,ih.InvoiceDate,c.CustomerName,s.ServiceName,ih.Paid,sum(il.Amount) Amount
		from InvoiceHeader ih
		inner join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
		inner join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
		inner join Customer c on sh.CustomerID=c.CustomerID	
		inner join Services s on sh.ServiceID=s.ServiceID 

		group by sh.ServiceHeaderID, ih.CustomerID,ih.InvoiceDate,c.CustomerName,s.ServiceName,ih.Paid,ih.InvoiceHeaderID,sh.ServiceHeaderID";
$result = sqlsrv_query($db, $sql);	
while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	
	
	
	extract($row);
	
	$CustomerName =  '<a href="#" onClick="loadmypage(\'receipts.php?approve=1&InvoiceHeaderID='.$InvoiceHeaderID.'\',\'content\',\'loader\',\'receipts\')">'.$CustomerName.'</a>';	
	
	$Date 	= date('d/m/Y',strtotime($CreatedDate));
	$channel[] = array(
				$InvoiceHeaderID,
				$InvoiceDate,
				$CustomerName,
				$ServiceName,
				$Amount,
				$Paid
	);
}  
$rss = (object) array('aaData'=>$channel);
$json = json_encode($rss);
echo $json;
?>