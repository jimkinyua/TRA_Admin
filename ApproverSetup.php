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

$SetupID=0;
$sql='';
$msg='';

if(isset($_REQUEST['edit'])){

	$SetupID=$_REQUEST['SetupID'];
	$UserID=$_REQUEST['UserID'];
	$SubCountyID=$_REQUEST['SubCountyID'];

	if ($SetupID==0)
	{
		$sql="Insert into ApproverSetup (UserID,SubCountyID,CreatedBy) Values($UserID,$SubCountyID,$CreatedUserID)";
	}else
	{
		$sql="Update ApproverSetup set SubCountyID='$SubCountyID' where ID='$SetupID'";
	}

	//echo $sql;

	$result=sqlsrv_query($db,$sql);
	if($result)
	{
		$rst=SaveTransaction($db,$CreatedUserID," Created/Update approval rights from User ".$UserID);

		$msg="Record Created/Updated Successfully";	
	}
}
if (isset($_REQUEST['remove']))
{
	$RecordID=$_REQUEST['SetupID'];
	$sql="Update ApproverSetup set status=0 where ID=$RecordID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Officer Removed Successfully";			
	} else
	{
		DisplayErrors();
		
		$msg = "Action failed";			
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
		<legend>List Of Approvers</legend> 
		<form>
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>                	
					<th class="text-left"><a href="#" onClick="loadmypage('Approver_subcounty.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Add</a></th>      
					
                    
                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>				
				<tr>
					<th>#</th>
					<th  class="text-left">Names</th>
					<th  class="text-left">IDNo</th>
					<th  class="text-left">Email</th>	
					<th  class="text-left">Sub County</th>	
					<th  class="text-left">&nbsp</th>					
				</tr>
			</thead>

			<tbody>
				<tbody>
					<?php
						echo $mdata;
					?>
                <tbody>			
			</tbody>
		</table> 
		<form>
	</div>
</body>


