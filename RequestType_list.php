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
	$PeriodID=$_REQUEST['CountyID'];
	$sql="Delete from Counties where CountyId=$CountyID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "County Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{
    // echo '<pre>';
    // print_r($_REQUEST);
    // exit;

	$CountyID=$_REQUEST['CountyID'];	
	$CountyName=$_REQUEST['CountyName'];
    $Region=$_REQUEST['Region'];
    $PeriodID=$_REQUEST['PeriodID'];

    

	if (empty($PeriodID))
	{
        // exit('2');
        $sql="set dateformat dmy Insert into Counties 
        (CountyName,TraRegionCode)
		Values('$CountyName','$CountyID')";

	} else
	{
        exit('8');
        $sql="Update Counties set
        CountyName='$CountyName',TraRegionCode='$Region' 
        where CountyId=$CountyID";
        // exit($sql);
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "County Saved Successfully";			
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
        <legend>Request Approvers For <?= $SubSystemName ?> </legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
				  <tr>
					<th class="text-left"><a href="#" onClick="loadmypage('WaiverPeriod.php?i=1','content')">Add</a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				  </tr>
                <tr>
                    <th width="15%" class="text-left">Request Type</th>
                    <th width="15%" class="text-left">TRA Region</th>
					<th width="15%" class="text-left">Action</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 
		</div>
</div>


