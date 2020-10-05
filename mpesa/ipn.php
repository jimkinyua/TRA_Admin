<?php
$postdata = file_get_contents("php://input");
$timestamp = time();
$myFile = 'files/Report'."$timestamp.txt";
$fh = fopen($myFile, 'w');
require_once('utilities.php');
require_once 'DB_PARAMS/connect.php';
require_once('smsgateway.php');
fwrite($fh, 'Start');
fwrite($fh, $postdata);
fwrite($fh, 'End');
fclose($fh);


$id = $_REQUEST['id'];
$orig = $_REQUEST['orig'];
$dest = $_REQUEST['dest'];
$tstamp = $_REQUEST['tstamp'];
$text = $_REQUEST['text'];
$customer_id = $_REQUEST['customer_id'];
$user = $_REQUEST['user'];
$pass = $_REQUEST['pass'];
$routemethod_id = $_REQUEST['routemethod_id'];
$routemethod_name = $_REQUEST['routemethod_name'];
$mpesa_code = $_REQUEST['mpesa_code'];
$mpesa_acc = $_REQUEST['mpesa_acc'];
$mpesa_msisdn = $_REQUEST['mpesa_msisdn'];
$mpesa_trx_date = $_REQUEST['mpesa_trx_date'];
$mpesa_trx_time = $_REQUEST['mpesa_trx_time'];
$mpesa_amt = $_REQUEST['mpesa_amt'];
$mpesa_sender = $_REQUEST['mpesa_sender'];

$ReceiptID='0';



//PRINT_R($_REQUEST);EXIT;
//http://localhost:2000/revenueadmin/mpesa/ipn.php?mpesa_code=123456&mpesa_acc=586918&mpesa_amt=90&orig=MPESA&dest=0725463120&text=testing&mpesa_sender=cosmas%20ngeno&id=1&tstamp=12/12/2016&mpesa_trx_date=12/12/2017

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

//print_r($_REQUEST);
$query ="set dateformat dmy if not exists (select 1 from MPESA where mpesa_code='$mpesa_code') INSERT INTO mpesa 
(
id
,orig 
,dest 
,tstamp 
,[text]
,[customer id] 
,[user]
,[password] 
,[routemethod id] 
,[routemethod name] 
,mpesa_code 
,mpesa_acc 
,mpesa_msisdn 
,mpesa_trx_date 
,mpesa_trx_time 
,mpesa_amt 
,mpesa_sender 
)
VALUES
(
'$id'
,'$orig'
,'$dest'
,'$tstamp'
,'$text'
,'$customerid'
,'$user'
,'$pass'
,'$routemethod_id'
,'$routemethod_name'
,'$mpesa_code'
,'$mpesa_acc'
,'$mpesa_msisdn'
,'$mpesa_trx_date'
,'$mpesa_trx_time'
,'$mpesa_amt'
,'$mpesa_sender'
)";

//?id=1&orig=1&dest=1&tstamp=1&text=1&customerid=1&user=1&pass=1&routemethod_id=1&routemethod_name=1&mpesa_code=1&mpesa_acc=1&mpesa_msisdn=1&mpesa_trx_date=1&mpesa_trx_time=1&mpesa_amt=1&mpesa_sender=1

$result = sqlsrv_query($db, $query);

if($result)
{
	$name=explode(" ", $mpesa_sender);
	$fname= strtoupper($name[0]);

	$Message="Dear $fname, We have received KSh. $mpesa_amt for Account No. $mpesa_acc. Call 0720646464 for any engeiries. Thank you.";
	sendSms($mpesa_msisdn,$Message);
	$msg = 'OK|Thank you for your payment1';
	//echo $query.'<br><br><br>';
} else 
{
	$ln="----".$mpesa_code."---";
	$myfile = file_put_contents('MPESA_ERR.txt', $query.PHP_EOL , FILE_APPEND | LOCK_EX);
	$myfile = file_put_contents('MPESA_ERR.txt', $ln.PHP_EOL , FILE_APPEND | LOCK_EX);

	DisplayErrors();
	$msg ='FAIL|Unfortunately our system was unable to process your payment';
}

$msg1='';
//does the receipt already exist?

$sql="select 1 from receipts where referencenumber='$mpesa_code' and Status=1";
	$s_result = sqlsrv_query($db, $sql,$params,$options);

	$rows=sqlsrv_num_rows($s_result);

	if($rows>0){
		
		$msg1[0] = '0';
		$msg1[1] = 'The Receipt is already in the system, unless it is cancelled, you cannot use';
		echo $msg1[1];
		return $msg1;

	}

//does the invoiceno exists

$ServiceHeaderID=0;

$sql="Select Distinct ServiceHeaderID from InvoiceLines where InvoiceHeaderID='$mpesa_acc'";

$result3=sqlsrv_query($db,$sql,$params,$options );
if($result3)
{
	$records=sqlsrv_num_rows($result3);
	if($records>0)
	{
		
		while($row=sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC))
		{
			$ServiceHeaderID=$row['ServiceHeaderID'];

			$sql="Select 1 from Receipts where referencenumber='$mpesa_code'";

			$result=sqlsrv_query($db,$sql,$params,$options);

			if($result)
			{
				$records=sqlsrv_num_rows($result);
				if($records>0)
				{
					return;
				}
			}

			
			$query2 = " insert into  Receipts ([ReceiptDate],[ReceiptMethodID],[ReferenceNumber],BankID,[Amount],[ReceiptStatusID],CreatedBy) 
			VALUES(convert(date,getdate()),'1','$mpesa_code','1014','$mpesa_amt','1','82321') 
			SELECT SCOPE_IDENTITY() AS ID";

			$result1 = sqlsrv_query($db, $query2);
			if ($result1)
			{
				$ReceiptID=lastid($result1);
				
				$query4="if not exists(select 1 from ReceiptLines where ReceiptID=$ReceiptID and InvoiceHeaderID='$mpesa_acc')
				Insert into ReceiptLines (ReceiptID,InvoiceHeaderID,Amount,CreatedBy)
				VALUES('$ReceiptID','$mpesa_acc','$mpesa_amt','82321')";		
				$result2 = sqlsrv_query($db, $query4);
				if($result2)
				{
					//echo $query4;
					//echo 'Lines done';		
				}else
				{
					//DisplayErrors();
				}
			}else
			{
				
				//DisplayErrors();
			}

			if($result1 and $result2)
			{
				$msg1 = 'Receipting Done';
			} else 
			{
				//DisplayErrors();
				$msg1 = 'Error in Receipting';
			}			
			
		}
	}else
	{
		$msg1="The reference number entered cannot be matched with any Invoice from the county";
	}
}else
{
	
}

echo $msg.'<br>'.$msg1;





?>