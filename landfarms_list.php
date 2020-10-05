<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$FirmID=0;

$PageID=56;
$View=0;
$Add=0;
$Add=0;
$Delete=0;
$myRights=getrights($db,$ActiveUserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

if (isset($_REQUEST['save']))
{	

	$FirmID=$_REQUEST['FarmID'];
	$FirmName=$_REQUEST['FarmName'];	
	$LocalAuthorityID=$_REQUEST['LocalAuthorityID'];
	
	
	if ($FirmID=='0')
	{
		$sql="Insert into LandFirms (FirmName,LocalAuthorityID,CreatedBY)
		Values('$FirmName',$LocalAuthorityID,$CreatedUserID)";

	} else
	{
		$sql="Update LandFirms set FirmName='$FirmName',LocalAuthorityID=$LocalAuthorityID where FirmID=$FirmID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Farm Created/Modified Successfully";			
	} else
	{
		DisplayErrors();
		ECHO $sql;
		$msg = "Details Failed to save";
				
	}	
}
if (isset($_REQUEST['DNotice']))
{
	$FirmID=$_REQUEST['FirmID'];	
	createDemandNotice($db,$cosmasRow,$FirmID,'');
	$msg="Demand Notice(s) Created Successfully";
}


if (isset($_REQUEST['download']))
{
	$FirmID=$_REQUEST['FirmID'];
	$FirmName=$_REQUEST['FirmName'];

	zipFilesAndDownload($FirmName);
}


function zipFilesAndDownload($FirmName)
{
	$files = array('96-0-40530.pdf', '96-0-40531.pdf', '96-0-40532.pdf');
    $file_path='/pdfdocs/DemandNotices/';
	$zip = new ZipArchive();
	$zip_name = $FirmName.".zip"; // Zip name
	$zip->open($zip_name,  ZipArchive::CREATE);


	foreach (new DirectoryIterator(getcwd().$file_path) as $file) 
	{
	  $path = getcwd().$file_path.$file;
	  if ($file->isFile()) {
	   	//if(strstr($file,'-'.$FirmID.'-')) $zip->addFromString(basename($path),  file_get_contents($path));	
	   	if(strstr($file,$FirmName)) $zip->addFromString(basename($path),  file_get_contents($path));	      
	  }
	}
	
	$zip->close();

	header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='".$zip_name."'");
    header('Content-Length: ' . filesize($zip_name));
    header("Location: ".$zip_name);
    
}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
		
<body class="metro">
	<div class="example">
		<legend>LandFirms</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('landfarm.php?i=1','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th  class="text-left">Farm ID</th>
				<th  class="text-left">Farm Name</th>
				<th  class="text-left">Local Authotity</th>
				<th  class="text-left">No Of Plots</th>
				<th  class="text-left">Demand Notice</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


