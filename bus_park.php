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

$ParkID="0";
$ParkName="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['ParkID'])){$ParkID=$_REQUEST['ParkID'];}
	
	$sql = "SELECT * FROM BusParks where ParkID = '$ParkID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ParkName=$myrow['ParkName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Bus Park</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Route Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="ParkName" name="ParkName" value="<?php echo $ParkName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>                           
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('bus_park_list.php?'+
        											'&ParkName='+this.form.ParkName.value+
                                                    '&ParkID='+<?php echo $ParkID ?>+
                                                    '&save=1','content','loader','listpages','','BusParks')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('bus_park_list.php?i=1','content','loader','listpages','','BusParks')">
        
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>