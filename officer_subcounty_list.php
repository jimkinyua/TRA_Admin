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
$ActiveUserID = $_SESSION['UserID'];

$PageID=5;
$myRights=getrights($db,$ActiveUserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

checkSession($db,$ActiveUserID);


if (isset($_REQUEST['delete']))
{
	$RecordID=$_REQUEST['RecordID'];
	$sql="Delete from ClerkWards where RecordID=$RecordID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Ward Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$RecordID=$_REQUEST['RecordID'];
	$WardID=$_REQUEST['WardID'];	
	$AssetTypeID=$_REQUEST['AssetTypeID'];
	$UserID=$_REQUEST['UserID'];
	
	if ($RecordID=='0')
	{
		$sql="Insert into ClerkWards (UserID,WardID,CreatedBY)
		Values('$UserID','$WardID',$ActiveUserID)";

	} else
	{
		$sql="Update ClerkWards set WardID='$WardID',AssetTypeID=$AssetTypeID,UserID='$UserID' where RecordID=$RecordID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Asset Saved Successfully";			
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
        <legend>Clerk-Wards Mapping</legend>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                  	<?php 
						if ($myRights['Add']==0)
						{
							?>
                            <th></th><?php
						}else
						{ ?>
							<th class="text-left"><a href="#" onClick="loadmypage('clerk_ward.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Add</a></th>

                            <?php
						}
					?>
                    
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
					<th  class="text-left">Clerk Name</th>
                    <th  class="text-left">Ward</th>
                    <th  class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot>

                </tfoot>
            </table>


</div>
</div>