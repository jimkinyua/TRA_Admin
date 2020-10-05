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
$A_ServiceID=$_REQUEST['A_ServiceID'];
$historyString=$_REQUEST['historyString'];
$A_ServiceName='';

$sql="select ServiceName from Services where ServiceID=$A_ServiceID";
$result=sqlsrv_query($db,$sql);
$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if ($result)
{
	$A_ServiceName=$rw['ServiceName'];
}



if (isset($_REQUEST['delete']))
{
	$ServicePlusID=$_REQUEST['ServicePlusID'];
	$sql="Delete from ServicePlus where ServicePlusID=$ServicePlusID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Service Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	$ServiceID=$_REQUEST['A_ServiceID'];
	$ServicePlusID=$_REQUEST['ServicePlusID'];
	$Service_Add=$_REQUEST['ServiceID'];
	$Amount=$_REQUEST['Amount'];

	
	
	if ($ServicePlusID=='0')
	{
		$sql="Insert into ServicePlus (ServiceID,service_add,CreatedBY,Amount)
		Values('$A_ServiceID',$Service_Add,$CreatedUserID,'$Amount')";

	} else
	{
		$sql="Update ServicePlus set 
		ServiceID=$ServiceID,Service_Add=$Service_Add,CreatedBY=$CreatedUserID,Amount=$Amount 
		where ServicePlusID=$ServicePlusID";
	}
		
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{
		$rst=SaveTransaction($db,$UserID," Created/Updated the service Fees for service number ".$ServiceID);	
		$msg = "Service Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = $sql;//"Details Failed to save";		
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
        <legend>Accompanying Service(s) for [<?php echo $A_ServiceName; ?>]</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left" colspan="5"><a href="#" onClick="loadmypage('serviceplus.php?A_ServiceID=<?php echo $A_ServiceID; ?>','content')">Add</a></th>					
                  </tr>
				  <tr>
					<th colspan="5" class="text-left"><?php echo $msg; ?></th>
				  </tr>
                <tr>
                    <th  class="text-left">Service ID</th>
					<th  class="text-left">Service Name</th>
					<th  class="text-left">Amount</th>
					<th  class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>