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


if (isset($_REQUEST['save']))
{	
	$VehicleID=$_REQUEST['VehicleID'];
    $RouteID=$_REQUEST['RouteID'];
    $ParkID=$_REQUEST['ParkID'];
    $CapacityID=$_REQUEST['CapacityID'];
    $RegNo=$_REQUEST['RegNo'];

    $sql="update CustomerVehicles set Route=$RouteID,BusParkID=$ParkID,SittingCapacity=$CapacityID 
    where VehicleID=$VehicleID";

	//echo $sql; exit;

	$result = sqlsrv_query($db, $sql);

	if (!$result)
	{
		DisplayErrors();
		echo $sql; exit;
		$msg = "Commit Failed";		
	}else
	{
		$sql="select c.CustomerName 
        from CustomerVehicles cv
        join BusParks bp on cv.BusParkID=bp.ParkID 
        join MatatuRoutes mr on cv.[Route]=mr.RouteID
        join SittingCapacity sc on cv.SittingCapacity=sc.ID
        join Customer c on cv.CustomerID=c.CustomerID
        where cv.VehicleID=$VehicleID";

	//echo $sql;

	$result = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
	    $CustomerName=$myrow['CustomerName'];
	}
    

		$rst=SaveTransaction($db,$UserID," Modified the Sacco Vehicles Reg Number ".$RegNo." for ". $CustomerName." Sacco");

		$msg = "Saved Details Successfully";			
	}
}
if (isset($_REQUEST['delete']))
{	
	$VehicleID=$_REQUEST['VehicleID'];

	$sql="select c.CustomerName 
        from CustomerVehicles cv
        join BusParks bp on cv.BusParkID=bp.ParkID 
        join MatatuRoutes mr on cv.[Route]=mr.RouteID
        join SittingCapacity sc on cv.SittingCapacity=sc.ID
        join Customer c on cv.CustomerID=c.CustomerID
        where cv.VehicleID=$VehicleID";

	//echo $sql;

	$result = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
	    $CustomerName=$myrow['CustomerName'];
	}
    

    $sql="delete from CustomerVehicles where VehicleID=$VehicleID";

	//echo $sql; exit;

	$result = sqlsrv_query($db, $sql);

	if (!$result)
	{
		DisplayErrors();
		echo $sql; exit;
		$msg = "Commit Failed";		
	}else
	{
		$rst=SaveTransaction($db,$UserID," deleted a vehicle  the Sacco Named ".$CustomerName);

		$msg = "Deleted Successfully";			
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
        <legend>Sacco Vehicles</legend>        
        <table class="table striped hovered dataTable" id="dataTables-1">
            <thead>
            	<tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('sacco_vehicle.php?add=1','content')">Add</a></th>
                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
              	</tr>                  
                <tr>
                    <th  class="text-left">Reg Number</th>
                    <th  class="text-left">Sitting Capacity</th>
                    <th class="text-left">Bus Park</th>
                    <th  class="text-left">Route</th>
                    <th  class="text-left">&nbsp;</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
	</div>

</div>