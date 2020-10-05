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

$MenuGroupID="0";
$MenuGroupName="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['MenuGroupID'])){$MenuGroupID=$_REQUEST['MenuGroupID'];}
	
	$sql = "SELECT * FROM MenuGroups where MenuGroupID = '$MenuGroupID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$MenuGroupName=$myrow['MenuGroupName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit MenuGroup</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>MenuGroup Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="MenuGroupName" name="MenuGroupName" value="<?php echo $MenuGroupName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>                                      
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('menu_groups_list.php?'+
        											'&MenuGroupName='+this.form.MenuGroupName.value+
                                                    '&MenuGroupID='+<?php echo $MenuGroupID ?>+
                                                    '&save=1','content','loader','listpages','','MenuGroups')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('menu_groups_list.php?i=1','content','loader','listpages','','MenuGroups')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>