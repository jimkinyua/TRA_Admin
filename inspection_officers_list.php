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
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}


if (isset($_REQUEST['delete']))
{
	$AuditTeamID = $_REQUEST['AuditTeamID'];
	$AuditID		= $_REQUEST['AuditID'];
	$sql = "DELETE FROM InspectionOfficers WHERE UserID = '$User_ID'";
	$result  = sqlsrv_query($db, $sql);
	if ($result)
	{
		$msg = "Inspection Officer Deleted Successfully";
	} else
	{
		$msg = "Failed to Delete Audit Team";
	}		
}

		$sql="SELECT u.Email, u.UserID, ag.FirstName, ag.Middlename, ag.LastName, ins.ServiceHeaderID, ag.AgentID FROM 
	Users u inner join agents ag on u.agentid=ag.agentID inner join Inspections ins on u.AgentID = ins.UserID 
	where ins.ServiceHeaderID='$ApplicationID'";
	//  echo $sql; exit;
	$s_result=sqlsrv_query($db,$sql);
		if ($s_result){
			?>
			<table>
				<th>Inspection Officer</th>

			<?php
			while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
				{									
					$UserID = $row['UserID'];
					$User_ID = $row['AgentID'];
					$ServiceHeaderID = $row['ServiceHeaderID'];
					$FirstName = $row['FirstName'];
					$LastName = $row['LastName'];

					?>
						<tr>
							<td><?php echo $FirstName; ?> <?php echo $LastName; ?></td>
							<td align="left" class="tabletext"><div align="center">
								<a href="#" onClick="deleteConfirm2('Are you sure you want to delete the officer','LicenceAppliactionApprovedByOfficerCard.php?deleteofficer=1&ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>&User_ID=<?php echo $User_ID; ?>','content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">Remove</a>

								

							</div></td>
						</tr>

					<?php
					
						}

$row = sqlsrv_has_rows( $s_result );
	if ($row == false)
	{
	?>
       <tr class="tabletype2tdOdd" >
      <td colspan="8" align="center">No Inspection Officer Has Been Added Yet!</td>
      </tr>
      <?php
			}			
		}
?>