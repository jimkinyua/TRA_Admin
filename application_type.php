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

$ApplicationTypeID="0";
$ApplicationTypeName="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['ApplicationTypeID'])){$ApplicationTypeID=$_REQUEST['ApplicationTypeID'];}
	
	$sql = "SELECT * FROM ApplicationTypes where ApplicationTypeID = '$ApplicationTypeID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ApplicationTypeName=$myrow['ApplicationTypeName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Application Type</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Application Type</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="ApplicationTypeName" name="ApplicationTypeName" value="<?php echo $ApplicationTypeName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>                                     
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('application_types_list.php?'+
		'&ApplicationTypeName='+this.form.ApplicationTypeName.value+
        '&ApplicationTypeID='+<?php echo $ApplicationTypeID ?>+
        '&save=1','content','loader','listpages','','ApplicationTypes')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('application_types_list.php?i=1','content','loader','listpages','','ApplicationTypes')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>