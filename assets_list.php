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

$PageID=65;
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
	$AssetID=$_REQUEST['AssetID'];
	$sql="Delete from Assets where AssetID=$AssetID";
	
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

	$AssetID=$_REQUEST['AssetID'];
	$AssetName=$_REQUEST['AssetName'];	
	$AssetTypeID=$_REQUEST['AssetTypeID'];
	$RegistrationNumber=$_REQUEST['RegistrationNumber'];
	$DepartmentID=$_REQUEST['DepartmentID'];
	$DepreciationRate=$_REQUEST['DepreciationRate'];
	$AcquisitionDate=$_REQUEST['AcquisitionDate'];
	$Remarks=$_REQUEST['Remarks'];
	$AcquisitionCost=$_REQUEST['AcquisitionCost'];


	//print_r($_REQUEST); 
	
	if ($AssetID=='0')
	{
		$sql="Insert into Assets (RegistrationNumber,AssetName,AssetTypeID,CreatedBY,DepartmentID,DepreciationRate,AcquisitionDate,Remarks,AcquisitionCost)
		Values('$RegistrationNumber','$AssetName',$AssetTypeID,$ActiveUserID,$DepartmentID,$DepreciationRate,$AcquisitionDate,'$Remarks',$AcquisitionCost)";

	} else
	{
		$sql="Update Assets set AssetName='$AssetName'
		,AssetTypeID=$AssetTypeID
		,RegistrationNumber='$RegistrationNumber'
		,DepartmentID='$DepartmentID'
		,DepreciationRate='$DepreciationRate'
		,AcquisitionDate='$AcquisitionDate'
		,Remarks='$Remarks'
		,AcquisitionCost='$AcquisitionCost'

		 where AssetID=$AssetID";


		 // echo '<br>'.$sql;
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
        <legend>Assets</legend>
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
							<th class="text-left"><a href="#" onClick="loadmypage('asset.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Add</a></th>

                            <?php
						}
					?>
                    
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
					<th  class="text-left">Registration Number</th>
                    <th  class="text-left">Asset Names</th>
                    <th  class="text-left">Asset Type</th>	
                    <th  class="text-left">Acquisition Cost</th>
                    <th  class="text-left">Depreciation Value</th>
                    <th  class="text-left">Book Value</th>					
                    <th  class="text-left">&nbsp;</th>
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