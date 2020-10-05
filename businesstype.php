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

$BusinessTypeID="0";
$BusinessTypeName="";
$Notes='';
if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['BusinessTypeID'])){$BusinessTypeID=$_REQUEST['BusinessTypeID'];}
	
	$sql = "SELECT * FROM BusinessType where BusinessTypeID = '$BusinessTypeID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$BusinessTypeName=$myrow['BusinessTypeName'];
		$Notes=$myrow['Notes'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit BusinessTypes</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Business Type</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="BusinessTypeName" name="BusinessTypeName" value="<?php echo $BusinessTypeName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label>Description</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Notes" name="Notes" value="<?php echo $Notes; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 			
			                        
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('businesstypes_list.php?'+
        											'&BusinessTypeName='+this.form.BusinessTypeName.value+
                                                    '&Notes='+this.form.Notes.value+                                                    
                                                    '&save=1','content','loader','listpages','','BusinessTypes')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('businesstypes_list.php?i=1','content','loader','listpages','','BusinessTypes')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>