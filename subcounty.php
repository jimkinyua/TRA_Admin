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

$SubCountyID="0";
$SubCountyName="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['SubCountyID'])){$SubCountyID=$_REQUEST['SubCountyID'];}
	
	$sql = "SELECT * FROM SubCounty where SubCountyID = '$SubCountyID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$SubCountyName=$myrow['SubCountyName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Sub County</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Sub County Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="SubCountyName" name="SubCountyName" value="<?php echo $SubCountyName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>                           
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('subcounty_list.php?'+
        											'&SubCountyName='+this.form.SubCountyName.value+
                                                    '&SubCountyID='+<?php echo $SubCountyID ?>+
                                                    '&save=1','content','loader','listpages','','subcounties')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('subcounty_list.php?i=1','content','loader','listpages','','subcounties')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>