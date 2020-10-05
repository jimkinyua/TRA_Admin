<?php
session_start();
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];
//$msg = $_REQUEST['msg'];

print_r($_REQUEST);

unset($_SESSION['logged_in']);
unset($_SESSION['UserName']);
// kill session variables
setcookie('JOBPORTAL', $_SESSION["UserFullNames"], time() - 10);

$sql="Update Users Set LoginStatus=0 where AgentID=$UserID";
echo $sql;
$result=sqlsrv_query($db,$sql);
if($result)
{
	$rst=SaveTransaction($db,$UserID,"Logged Out Successfully ");
}else
{
	DisplayErrors();
} 
$ssid=session_id();
echo $ssid;

	session_unset(); 
 	session_destroy();

 $ssid=session_id();
echo $ssid;

header('Location: index.php');
?>

