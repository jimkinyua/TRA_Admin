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
	$RevenueStreamID=$_REQUEST['RevenueBudgetID'];
	$sql="Delete from RevenueBudget where RevenueBudgetID=$RevenueBudgetID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Revenue Budget Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	//print_r($_REQUEST);
	
	$RevenueStreamID=$_REQUEST['RevenueStreamID'];
	$FinancialYearID=$_REQUEST['FinancialYearID'];
	$Amount=$_REQUEST['Amount'];
	
	if ($RevenueBudgetID=='0')
	{
		$sql="Insert into RevenueBudget (RevenueStreamID,FinancialYearID,Amount)
		Values('$RevenueStreamID','$FinancialYearID',$Amount)";
	} else
	{
		$sql="Update RevenueStreams 
		set RevenueStreamID='$RevenueStreamID',FinancialYearID=$FinancialYearID,Amount='$Amount' 
		where RevenueBudgetID=$RevenueBudgetID";
	}	
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{	
		$msg = "Budget Saved Successfully";			
	} else
	{
		DisplayErrors();
		echo '<br>'. $sql;
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
          <th width="30%" class="text-left">Revenue Stream</th>
          <th width="25%" class="text-left">FinancialYear</th>
		  <th width="25%" class="text-left">Budget</th>
          <th width="10%" class="text-left">&nbsp;</th>
          <th width="10%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
