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

$RelecenterID='0';
$RoleCenterName='';
$PageID='';
$PageName='';

if (isset($_REQUEST['PageID'])){$PageID=$_REQUEST['PageID'];}
if (isset($_REQUEST['PageName'])){$PageName=$_REQUEST['PageName'];}
if (isset($_REQUEST['RoleCenterID'])){$RoleCenterID=$_REQUEST['RoleCenterID'];}


$sql="select RolecenterName from RoleCenters where RolecenterID=$RoleCenterID";

$result=sqlsrv_query($db,$sql);
if($result)
{
    while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
            $RoleCenterName=$rw['RolecenterName'];
    }
}

//roles
$sql="select * from Roles where RolecenterID=$RoleCenterID and PageID=$PageID";

$result=sqlsrv_query($db,$sql);
if($result){
    while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        $view=$rw['View']==1?'checked':'';
        $edit=$rw['Edit']==1?'checked':'';
        $add=$rw['Add']==1?'checked':'';
        $delete=$rw['Delete']==1?'checked':'';
    }
}

if (isset($_REQUEST['save']))
{	
	if (isset($_REQUEST['PageID'])){$PageID=$_REQUEST['PageID'];}
	
	$sql = "SELECT * FROM Pages where PageID = '$PageID'";
	$result = sqlsrv_query($db, $sql);

   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$PageName=$myrow['PageName'];
        $MenuGroupID=$myrow['MenuGroupID'];
        $AddActionApproverRoleCenterID=$myrow['AddActionApproverRoleCenterID'];
        $EditActionApproverRoleCenterID=$myrow['EditActionApproverRoleCenterID'];
        $DeleteActionApproverRoleCenterID=$myrow['DeleteActionApproverRoleCenterID'];

	}	
   
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit <?php echo $RoleCenterName; ?> Rights for <?php echo $PageName; ?> Page</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
               <div class="input-control checkbox">
                    <label>
                        <input type="checkbox" id="view" name="view" <?php echo $view; ?>/>
                        <span class="check"></span>
                        View
                    </label>
                </div>
            </tr>
            <tr>
               <div class="input-control checkbox">
                    <label>
                        <input type="checkbox" id="edit" name="edit" <?php echo $edit; ?>/>
                        <span class="check"></span>
                        Edit
                    </label>
                </div>
            </tr>
            <tr>
               <div class="input-control checkbox">
                    <label>
                        <input type="checkbox" id="add" name="add" <?php echo $add; ?>/>
                        <span class="check"></span>
                        Add
                    </label>
                </div>
            </tr>
            <tr>
               <div class="input-control checkbox">
                    <label>
                        <input type="checkbox" id="delete" name="delete" <?php echo $delete; ?> />
                        <span class="check"></span>
                        Delete
                    </label>
                </div>
            </tr>                                    
        </table>
		<input name="Button" type="button" onclick="loadmypage('role_center_roles.php?'+
                        '&RoleCenterID='+<?php echo $RoleCenterID; ?>+                        
                        '&view='+this.form.view.checked+
                        '&edit='+this.form.edit.checked+
                        '&add='+this.form.add.checked+
                        '&delete='+this.form.delete.checked+
                        '&PageID='+<?php echo $PageID; ?>+                        
                        '&update=1','content','loader','listpages','','RoleCenterRoles','<?= $RoleCenterID; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('role_center_roles.php?i=1','content','loader','listpages','','RoleCenterRoles','<?= $RoleCenterID; ?>')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>