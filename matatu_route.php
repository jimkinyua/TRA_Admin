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

$RouteID="0";
$RouteName="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['RouteID'])){$RouteID=$_REQUEST['RouteID'];}
	
	$sql = "SELECT * FROM MatatuRoutes where RouteID = '$RouteID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$RouteName=$myrow['RouteName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Matatu Route</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Route Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="RouteName" name="RouteName" value="<?php echo $RouteName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>                           
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('matatu_routes_list.php?'+
        											'&RouteName='+this.form.RouteName.value+
                                                    '&RouteID='+<?php echo $RouteID ?>+
                                                    '&save=1','content','loader','listpages','','MatatuRoutes')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('matatu_routes_list.php?i=1','content','loader','listpages','','MatatuRoutes')">
        
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>