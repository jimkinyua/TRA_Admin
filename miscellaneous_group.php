<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$MiscellaneousGroupName='';
$Description='';
$MiscellaneousGroupID='0';
$FormID='';
$CreatedDate="";
$PrimaryMiscellaneous="";


if (isset($_REQUEST['edit']))
{	
	$MiscellaneousGroupID=	$_REQUEST['MiscellaneousGroupID'];
	
	$sql = "SELECT * FROM MiscellaneousGroup where MiscellaneousGroupID = $MiscellaneousGroupID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$MiscellaneousGroupName=$myrow['MiscellaneousGroupName'];
		$MiscellaneousGroupID=$myrow['MiscellaneousGroupID'];
	}	
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Miscellaneous Group</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Miscellaneous Group Name</label>
					
                	<div class="input-control text" data-role="input-control">
						<input name="MiscellaneousGroupName" id="MiscellaneousGroupName" type="text" value="<?php echo $MiscellaneousGroupName; ?>"></input>                    	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

				</td>
			</tr>                         
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('miscellaneous_group_list.php?'+
                                            '&MiscellaneousGroupName='+this.form.MiscellaneousGroupName.value+
                                            '&MiscellaneousGroupID='+<?php echo $MiscellaneousGroupID; ?>+       
        									'&save=1','content','loader','listpages','','MiscellaneousGroups')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('Miscellaneousgroup_list.php?i=1','content','loader','listpages','','MiscellaneousGroups')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>