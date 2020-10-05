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
	$BusinessTypeID=$_REQUEST['BusinessTypeID'];
	$sql="Delete from BusinessType where BusinessTypeID=$BusinessTypeID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "BusinessType Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$BusinessTypeID=$_REQUEST['BusinessTypeID'];
	$BusinessTypeName=$_REQUEST['BusinessTypeName'];	
	$SubCountyID=$_REQUEST['SubCountyID'];
	
	if ($BusinessTypeID=='0')
	{
		$sql="Insert into BusinessType (BusinessTypeName,SubCountyID,CreatedBY)
		Values('$BusinessTypeName',$SubCountyID,$CreatedUserID)";

	} else
	{
		$sql="Update BusinessType set BusinessTypeName='$BusinessTypeName',SubCountyID=$SubCountyID where BusinessTypeID=$BusinessTypeID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "BusinessType Saved Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Details Failed to save";
				
	}	
}
?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
		<script>
		alert('hre');
	</script>
<body class="metro">
        <div class="example">
        <legend>Business Types</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('businesstype.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="14%" class="text-left">Business Type</th>
                    <th width="12%" class="text-left">Notes</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 


</div>
</div>


