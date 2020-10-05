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
$SetupID=$_REQUEST['SetupID'];
$ServiceID=$_REQUEST['ServiceID'];
$FixedPrice=$_REQUEST['FixedPrice'];
$StoreyedAmount=$_REQUEST['StoreyedAmount'];
$NonStoreyedAmount=$_REQUEST['NonStoreyedAmount'];


//print_r($_REQUEST); exit;

if (isset($_REQUEST['delete']))
{
	$SetupID=	$_REQUEST['SetupID'];
	$ApplicationTypeID=$_REQUEST['ApplicationTypeID'];
	$ApplicationCategoryID=$_REQUEST['ApplicationCategoryID'];
	
	$sql = "DELETE FROM PlanApprovalSetup WHERE SetupID=$SetupID ";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$rst=SaveTransaction($db,$UserID," Deleted the service charges for service number ".$SetupID." SubSystem ".$ApplicationTypeID);
		$msg = "Record Deleted Successfully";
	} else
	{
		DisplayErrors();

		$msg = $sql;//"Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['save']))
{	
	$SetupID=$_REQUEST['SetupID'];
	$ApplicationTypeID=$_REQUEST['ApplicationTypeID'];
	$ApplicationCategoryID=$_REQUEST['ApplicationCategoryID'];
	$NonStoreyedAmount=$_REQUEST['NonStoreyedAmount'];
	$StoreyedAmount=$_REQUEST['StoreyedAmount'];
	$ServiceID=$_REQUEST['ServiceID'];
	$UnitOfCharge=$_REQUEST['UnitOfCharge'];
	$ApplyToNonStoreyed=$_REQUEST['ApplyToNonStoreyed'];


	//print_r($_REQUEST);
	
	
	$sql="if not exists(select * from PlanApprovalSetup where SetupID=$SetupID)
	insert into PlanApprovalSetup(ApplicationTypeID,ApplicationCategoryID,ServiceID,UnitOfCharge,NonStoreyedAmount,StoreyedAmount,ApplyToNonStoreyed,CreatedBy) 
		values($ApplicationTypeID,$ApplicationCategoryID,$ServiceID,'$UnitOfCharge',$NonStoreyedAmount,$StoreyedAmount,$ApplyToNonStoreyed,$CreatedUserID)
	else
		update PlanApprovalSetup set NonStoreyedAmount=$NonStoreyedAmount,StoreyedAmount=$StoreyedAmount,UnitOfCharge='$UnitOfCharge',
	ApplicationCategoryID=$ApplicationCategoryID, ApplicationTypeID=$ApplicationTypeID,ApplyToNonStoreyed=$ApplyToNonStoreyed
	where SetupID=$SetupID";

	//echo $sql;

	$result = sqlsrv_query($db, $sql);

	$rst=SaveTransaction($db,$UserID," Created/Updated the Plan approval setup for Type number ".$ApplicationTypeID." Category  ".$ApplicationCategoryID);

	if (!$result)
	{
		DisplayErrors();
		echo $sql;
		$msg = "Commit Failed";			
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
        <legend>Plan Approval Charges</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left" colspan="5"><?php echo $ServiceName; ?></th>                  
                  </tr>                                  
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('application_charge.php?add=1','content')">New</a></th>
                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th					 
                  </tr>
                <tr>
                    <th  class="text-left">Application Type</th>
                    <th  class="text-left">Category</th>
                    <th  class="text-left">Service</th>
                    <th  class="text-left">Unit Of Charge</th>
                    <th  class="text-left">Amount (Non Storeyed)</th>
                    <th  class="text-left">Amount (Storeyed)</th>
                    <th  class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>