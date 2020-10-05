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

$UserRoleID="0";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['UserRoleID'])){$UserRoleID=$_REQUEST['UserRoleID'];}
	
	$sql = "SELECT * FROM UserRoles where UserRoleID = '$UserRoleID'";
	$result = sqlsrv_query($db, $sql);	
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$UserID=$myrow['UserID'];
		$RoleCenterID=$myrow['RoleCenterID'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Transaction Types</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
			  <td><label>UserName</label>
                  <div class="input-control text" data-role="input-control">
                      <input type="TransactionName" id="TransactionName" value="">

                  </div>
			 </td>
			  <td></td>
		  </tr> 
			<tr>
			  <td><label>RoleCenter</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="RoleCenterID"  id="RoleCenterID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM RoleCenters ORDER BY RoleCenterID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["RoleCenterID"];
                                    $s_name = $row["RoleCenterName"];
                                    if ($RoleCenterID==$s_id) 
                                    {
                                        $selected = 'selected="selected"';
                                    } else
                                    {
                                        $selected = '';
                                    }												
                                 ?>
                            <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                            <?php 
                                }
                            }
                            ?>
                      </select>
                    
                  </div></td>
			  <td></td>
		  </tr>                           
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('transactiontypes_list.php?'+
        											'&TransactionName='+this.form.TransactionName.value+
                                                    '&RoleCenterID='+this.form.RoleCenterID.value+
                                                    '&TransactionTypeID='+<?php echo $UserRoleID ?>+
                                                    '&save=1','content','loader','listpages','','TransactionTypes')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('user_roles_list.php?i=1','content','loader','listpages','','UserRoles')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>