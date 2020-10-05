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

$sql="Select ServiceName from services where ServiceID=$ServiceID";

$result=sqlsrv_query($db,$sql);
while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
	$ServiceName=$row['ServiceName'];
}




if (isset($_REQUEST['delete']))
{
	$ServiceID=	$_REQUEST['ServiceID'];
	$SubSystemID=$_REQUEST['SubSystemID'];
	$FinancialYearID=$_REQUEST['FinancialYearID'];
	
	$sql = "DELETE FROM ServiceCharges WHERE ServiceID=$ServiceID and SubSystemID=$SubSystemID and FinancialYearID=$FinancialYearID";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$rst=SaveTransaction($db,$UserID," Deleted the service charges for service number ".$ServiceID." SubSystem ".$SubSystemID);
		$msg = "Record Deleted Successfully";
	} else
	{
		$msg = "Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['save']))
{	
	$ServiceID=	$_REQUEST['ServiceID'];
	$SubSystemID=$_REQUEST['SubSystemID'];
	$FinancialYearID=$_REQUEST['FinancialYearID'];
	$ServiceCharge=$_REQUEST['ServiceCharge'];
	
	$sql="if not exists(select * from ServiceCharges where ServiceID=$ServiceID and SubSystemID=$SubSystemID and FinancialYearID=$FinancialYearID)
	insert into ServiceCharges(ServiceID,SubSystemID,FinancialYearID,Amount) values($ServiceID,$SubSystemID,$FinancialYearID,$ServiceCharge)
	else
	update ServiceCharges set Amount=$ServiceCharge where ServiceID=$ServiceID and SubSystemID=$SubSystemID and FinancialYearID=$FinancialYearID";
	$result = sqlsrv_query($db, $sql);

	$rst=SaveTransaction($db,$UserID," Created/Update the service charges for service number ".$ServiceID." SubSystem ".$SubSystemID);

	if (!$result)
	{
		DisplayErrors();
		$msg = "Commit Failed";	
		redirect($_REQUEST, $msg, "service_charges.php");
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
        <legend>Service Charges</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left" colspan="5"><?php echo $ServiceName; ?></th>                  
                  </tr>                                  
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('service_charges.php?add=1&ServiceID='+<?php echo $ServiceID; ?>+'','content')">New</a></th>
                    <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					<th class="text-left"><a href="#" onClick="<?php echo $historyString; ?>">Back</a></th> 
                  </tr>
                <tr>
                    <th width="25%" class="text-left">Financial Year</th>
                    <th width="35%" class="text-left">Sub System</th>
                    <th width="30%" class="text-left">Amount</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>