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
$CreatedUserID = $_SESSION['UserID'];

$ServiceName=$_REQUEST['ServiceName'];
$ServiceID=$_REQUEST['ServiceID'];
$historyString=$_REQUEST['histString'];



// if (isset($_REQUEST['delete']))
// {
// 	$ServiceID=	$_REQUEST['ServiceID'];
// 	$SubSystemID=$_REQUEST['SubSystemID'];
// 	$FinancialYearID=$_REQUEST['FinancialYearID'];
	
// 	$sql = "DELETE FROM ConservancyCharges WHERE ServiceID=$ServiceID and SubSystemID=$SubSystemID and FinancialYearID=$FinancialYearID";
// 	//echo $sql;
// 	$result = sqlsrv_query($db, $sql);
// 	if ($result)
// 	{
// 		$rst=SaveTransaction($db,$UserID," Deleted the service charges for service number ".$ServiceID." SubSystem ".$SubSystemID);
// 		$msg = "Record Deleted Successfully";
// 	} else
// 	{
// 		$msg = "Record Failed to be Deleted";
// 	}
// }

if (isset($_REQUEST['save']))
{	

	$from=	$_REQUEST['from'];
	$to=	$_REQUEST['to'];
	$SubSystemID=$_REQUEST['SubSystemID'];
	$Amount=$_REQUEST['Amount'];
	
	$sql="
	insert into Conservancy([From],[To],SubSystemID,Amount,CreatedBy) values($from,$to,$SubSystemID,$Amount,$CreatedUserID)";

	$result = sqlsrv_query($db, $sql);

	$rst=SaveTransaction($db,$UserID," Created/Update the Conservancy charges for permit costing between ".$from." and  ".$to ." for SubSystem ".$SubSystemID);

	if (!$result)
	{
		DisplayErrors();
		echo $sql;
		$msg = "Commit Failed";	
		redirect($_REQUEST, $msg, "conservancy_charges.php");
		exit;		
	}else
	{
		$msg = "Saved Details Successfully";			
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
        <legend>Conservancy Charges</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                	<tr>
	                    <th class="text-left"><a href="#" onClick="loadpage('conservancy_charges.php?add=1','content')">Add</a></th>
	                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  	</tr>                  
	                <tr>
	                    <th  class="text-left">From</th>
	                    <th  class="text-left">To</th>
	                    <th  class="text-left">Sub System</th>
	                    <th  class="text-left">Amount</th>
	                    <th  class="text-left">&nbsp;</th>
	                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>