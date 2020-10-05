<?php
$postdata = file_get_contents("php://input");
$timestamp = time();
$myFile = 'files/Report'."$timestamp.txt";
$fh = fopen($myFile, 'w');
fwrite($fh, 'Start');
fwrite($fh, $postdata);
fwrite($fh, 'End');
fclose($fh);

$response = '
{
   "status" : "01",
   "description" : "Accepted",
   "subscriber_message" : "Thank you for your payment"
} ';
//echo $response;
//
//exit;

require_once 'DB_PARAMS/connect.php';
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
$query ="INSERT INTO [CRONUS USA, Inc_\$Incomingsms] 
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
echo $query;
$result = sqlsrv_query($db, $query);

if($result){
	echo'OK|Thank you for your payment';
} else {
	echo'FAIL|Unfortunately your system was unable to process your payment';
}
?>