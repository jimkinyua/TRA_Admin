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

$ServiceStatusID="0";
$ServiceStatusName="";
$StatusToDisplay="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['ServiceStatusID'])){$ServiceStatusID=$_REQUEST['ServiceStatusID'];}
	
	$sql = "SELECT * FROM ServiceStatus where ServiceStatusID = '$ServiceStatusID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceStatusName=$myrow['ServiceStatusName'];
		$StatusToDisplay=$myrow['ServiceStatusDisplay'];
	}	
		
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit ServiceStatus</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>ServiceStatus Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="ServiceStatusName" name="ServiceStatusName" value="<?php echo $ServiceStatusName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
       		<tr>
                <td width="50%">
                    <label>Status to Display</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="StatusToDisplay" name="StatusToDisplay" value="<?php echo $StatusToDisplay; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%"></td>
            </tr>  
			                          
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('service_status_list.php?'+
        											'&ServiceStatusName='+this.form.ServiceStatusName.value+
                                                    '&StatusToDisplay='+this.form.StatusToDisplay.value+
                                                    '&ServiceStatusID='+<?php echo $ServiceStatusID ?>+
                                                    '&save=1','content','loader','listpages','','ServiceStatus')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('service_status_list.php?i=1','content','loader','listpages','','ServiceStatus')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>