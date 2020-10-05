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
$UserID=$CreatedUserID;
$ChargeID=0;


$ServiceName=$_REQUEST['ServiceName'];
$ServiceID=$_REQUEST['ServiceID'];
$hisToCapacityryString=$_REQUEST['histString'];

$FromCapacity=0;
$ToCapacity=0;



if (isset($_REQUEST['save']))
{	

	$FromCapacity=	$_REQUEST['FromCapacity'];
	$ToCapacity=	$_REQUEST['ToCapacity'];
	$RouteID=$_REQUEST['RouteID'];
	$Amount=$_REQUEST['Amount'];
	$ChargeID=$_REQUEST['ChargeID'];

	$RouteName='';



	$sql="select RouteName from MatatuRoutes where routeID='$RouteID'";
	$result=sqlsrv_query($db,$sql);
	while($rw=sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)){
		$RouteName=$rw['RouteName'];
	}

	$sql='';

	if($ChargeID==0){	
		$sql="
		insert inTo RouteCharges(FromCapacity,ToCapacity,RouteID,Amount,CreatedBy) values($FromCapacity,$ToCapacity,$RouteID,$Amount,$CreatedUserID)";
	}else{
		$sql="update RouteCharges set FromCapacity='$FromCapacity', ToCapacity='$ToCapacity', Amount='$Amount' 
			where ChargeID='$ChargeID'";
	}

	//echo $sql; exit;

	$result = sqlsrv_query($db, $sql);

	if (!$result)
	{
		DisplayErrors();
		echo $sql; exit;
		$msg = "Commit Failed";		
	}else
	{
		$rst=SaveTransaction($db,$UserID," Created/Updated the RouteCharges charges for vehicles capacity ".$FromCapacity." and  ".$ToCapacity ." for ".$RouteName);

		$msg = "Saved Details Successfully";			
	}
}

if (isset($_REQUEST['delete']))
{	
	$ChargeID=$_REQUEST['ChargeID'];

	$sql="select * from RouteCharges where ChargeID=$ChargeID";

	// print_r($_REQUEST);
	// exit;

	$result = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
	    $FromCapacity=$myrow['FromCapacity'];
	    $ToCapacity=$myrow['ToCapacity'];
	}
    

    $sql="delete from RouteCharges where ChargeID=$ChargeID";

	//echo $sql; exit;

	$result = sqlsrv_query($db, $sql);

	if (!$result)
	{
		DisplayErrors();
		echo $sql; exit;
		$msg = "Commit Failed";		
	}else
	{
		$rst=SaveTransaction($db,$UserID," deleted a route tariff of between  ".$FromCapacity." and ".$ToCapacity);

		$msg = "Tariff Deleted Successfully";			
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
        <legend>RouteCharges Charges</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                	<tr>
	                    <th class="text-left"><a href="#" onClick="loadmypage('route_charge.php?add=1','content')">Add</a></th>
	                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  	</tr>                  
	                <tr>
	                    <th  class="text-left">Route</th>
	                    <th  class="text-left">FromCapacity</th>
	                    <th  class="text-left">ToCapacity</th>	                    
	                    <th  class="text-left">Amount</th>
	                    <th  class="text-left">&nbsp;</th>
	                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>