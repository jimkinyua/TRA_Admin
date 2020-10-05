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
	$PeriodID=$_REQUEST['PeriodID'];
	$sql="Delete from WaiverPeriods where PeriodID=$PeriodID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Waiver Period Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{
	$PeriodID=$_REQUEST['PeriodID'];	
	$StartDate=$_REQUEST['StartDate'];
	$EndDate=$_REQUEST['EndDate'];
	$WaiverPercentage=$_REQUEST['WaiverPercentage'];
	$MemoNo=$_REQUEST['MemoNo'];

	//print_r($_REQUEST); exit;

	$MemoNo=$_REQUEST['MemoNo'];
	
	if ($PeriodID=='0')
	{
		$sql="set dateformat dmy Insert into WaiverPeriods (StartDate,EndDate,MemoNo,WaiverPercentage,CreatedBy)
		Values('$StartDate','$EndDate','$MemoNo','$WaiverPercentage','$CreatedUserID')";

	} else
	{
		$sql="Update WaiverPeriods set EndDate='$EndDate',StartDate='$StartDate',MemoNo='$MemoNo' where PeriodID=$PeriodID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Waiver Periods Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";
				
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
        <legend>WaiverPeriods</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
				  <tr>
					<th class="text-left"><a href="#" onClick="loadmypage('WaiverPeriod.php?i=1','content')">Add</a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				  </tr>
                <tr>
                    <th width="15%" class="text-left">Start Date</th>
                    <th width="15%" class="text-left">End Date</th>
					<th width="15%" class="text-left">MemoNo</th>
					<th width="15%" class="text-left">Waiver Percentage</th>
					<th width="15%" class="text-left">Status</th>
                    <th width="15%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 
		</div>
</div>


