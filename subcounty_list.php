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
	$SubCountyID=$_REQUEST['SubCountyID'];
	$sql="Delete from SubCounty where SubCountyID=$SubCountyID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "SubCounty Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	$SubCountyID=$_REQUEST['SubCountyID'];
	$SubCountyName=$_REQUEST['SubCountyName'];	
	
	if ($SubCountyID=='0')
	{
		$sql="Insert into SubCounty (SubCountyName,CreatedBY)
		Values('$SubCountyName',$CreatedUserID)";

	} else
	{
		$sql="Update SubCounty set SubCountyName='$SubCountyName' where SubCountyID=$SubCountyID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "SubCounty Saved Successfully";			
	} else
	{
	/*	DisplayErrors(); 
		echo "<br>".$sql;*/
		
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
        <legend>Users</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('subcounty.php?1=1','content')">Add</a></th>
                    <th colspan="2" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="10%" class="text-left">SubCountyID</th>
                    <th width="50%" class="text-left">SubCounty Name</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>