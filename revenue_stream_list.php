<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$RevenueStreamID=$_REQUEST['RevenueStreamID'];
	$sql="Delete from RevenueStreams where RevenueStreamID=$RevenueStreamID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Revenue Stream Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	//print_r($_REQUEST);
	// exit;
	$RevenueStreamID=$_REQUEST['RevenueStreamID'];
	$RevenueStreamCode=$_REQUEST['RevenueStreamCode'];
	$RevenueStreamName=$_REQUEST['RevenueStreamName'];
	$RevenueCategoryID=$_REQUEST['RevenueCategoryID'];
	$DepartmentID=$_REQUEST['DepartmentID'];
	
	if ($RevenueStreamID=='0')
	{
		$sql="Insert into RevenueStreams (RevenueStreamName,RevenueStreamCode,RevenueCategoryID,CreatedBY,DepartmentID)
		Values('$RevenueStreamName','$RevenueStreamCode',$RevenueCategoryID,$CreatedUserID,$DepartmentID)";

	} else
	{
		$sql="Update RevenueStreams set RevenueStreamName='$RevenueStreamName',RevenueStreamCode=$RevenueStreamCode,RevenueCategoryID='$RevenueCategoryID', DepartmentID='$DepartmentID' where RevenueStreamID=$RevenueStreamID";
	}	
	$result = sqlsrv_query($db, $sql);
	//echo $sql;
	if ($result)
	{	
		$msg = "Revenue Stream Saved Successfully";			
	} else
	{
		DisplayErrors();
		//echo '<br>'. $sql;
		$msg = "Details Failed to save";				
	}	
}

?>
<div class="example">
<legend>Revenue Stream
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('revenue_stream.php?add=1','content')">Add</a></th>
        <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">Revenue Stream Code</th>
          <th width="25%" class="text-left">Revenue Stream Name</th>
		  <th width="25%" class="text-left">Revenue Category</th>
		  <th width="15%" class="text-left">Current Budget</th>
		  <th width="5%" class="text-left">&nbsp;</th>
          <th width="5%" class="text-left">&nbsp;</th>
          <th width="5%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
