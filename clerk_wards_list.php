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


if (isset($_REQUEST['remove']))
{
	$RecordID=$_REQUEST['RecordID'];
	$sql="Update ClerkWard set status=0 where ID=$RecordID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Clerk Removed Successfully";			
	} else
	{
		DisplayErrors();
		echo $sql;
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$RecordID=$_REQUEST['RecordID'];
	$UserID=$_REQUEST['UserID'];
	$WardID=$_REQUEST['WardID'];	

	//print_r($_REQUEST); exit();


	
	
	if ($RecordID=='0')
	{
		$sql="if exists(select 1 from ClerkWard where UserID=$UserID and WardID=$WardID) begin Update ClerkWard set Status=1 where UserID=$UserID and WardID=$WardID end else begin Insert into ClerkWard (UserID,WardID,CreatedBY)
		Values('$UserID','$WardID',$ActiveUserID) end";

	} 

	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Record Saved Successfully";			
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
                    
                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                	<th>#</th>
					<th  class="text-left">Names</th>
					<th  class="text-left">IDNO</th>
					<th  class="text-left">Email</th>	
					<th  class="text-left">Ward</th>	
					<th  class="text-left">&nbsp</th>					
				</tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot>

                </tfoot>
            </table>


</div>
</div>