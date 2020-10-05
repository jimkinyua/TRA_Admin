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

$ServiceGroupName='';
$Description='';
$ServiceGroupID='0';
$FormID='';
$CreatedDate="";
$PrimaryService="";


if (isset($_REQUEST['edit']))
{	
	$ServiceGroupID=	$_REQUEST['ServiceGroupID'];
	
	$sql = "SELECT * FROM ServiceGroup where ServiceGroupID = $ServiceGroupID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceGroupName=$myrow['ServiceGroupName'];
		$ServiceGroupID=$myrow['ServiceGroupID'];
	}	
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Service Group</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Service Group Name</label>
					
                	<div class="input-control text" data-role="input-control">
						<input name="ServiceGroupName" id="ServiceGroupName" type="text" value="<?php echo $ServiceGroupName; ?>"></input>                    	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

				</td>
			</tr>                         
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('servicegroup_list.php?'+
                                            '&ServiceGroupName='+this.form.ServiceGroupName.value+
                                            '&ServiceGroupID='+<?php echo $ServiceGroupID; ?>+       
        									'&save=1','content','loader','listpages','','ServiceGroups')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('servicegroup_list.php?i=1','content','loader','listpages','','ServiceGroups')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>