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
	$MarketID=$_REQUEST['MarketID'];
	$sql="Delete from Markets where MarketID=$MarketID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Market Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{	
	$MarketID=$_REQUEST['MarketID'];
	$MarketName=$_REQUEST['MarketName'];	
	$WardID=$_REQUEST['WardID'];	
	
	if ($MarketID=='0')
	{
		$sql="Insert into Markets (MarketName,WardID,CreatedBY)
		Values('$MarketName',$WardID,$CreatedUserID)";

	} else
	{
		$sql="Update Markets set MarketName='$MarketName',WardID=$WardID where MarketID=$MarketID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Market Saved Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Details Failed to save";
		//redirect($_REQUEST, $msg, "markets.php?MarketID=$MarketID");			
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
        <legend>Markets</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('markets.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="14%" class="text-left">MerketID</th>
                    <th width="12%" class="text-left">Market Name</th>
                    <th width="12%" class="text-left">Ward</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>