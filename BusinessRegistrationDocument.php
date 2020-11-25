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

$FormID="0";
$FormName="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['DocTypeID'])){$DocTypeID=$_REQUEST['DocTypeID'];}
	
	$sql = "SELECT * FROM BusinessRegistrationDocumentTypes where DocTypeID = '$DocTypeID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$DocumentName=$myrow['DocumentName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Business Registration Documents</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>DocumentName</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="DocumentName" name="DocumentName" value="<?php echo $DocumentName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Is Mandatory</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceHeaderType"  id="Mandatory">
                            <option  disabled> Select Option</option>
                            <option value="1" > Yes</option>
                            <option value="0" > No</option>

                           
                      </select>
                    
                  </div></td>
			  <td></td>
			</tr>			
        </table>
		<input name="Button" type="button" onclick="loadmypage('Business_Documents.php?'+
        											'&DocumentName='+this.form.DocumentName.value+
													'&Mandatory='+this.form.Mandatory.value+
                                                    '&FormID='+<?php echo $DocTypeID ?>+													
                                                    '&save=1','content','loader','listpages','','BusinessDocuments')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('Business_Documents.php?i=1','content','loader','listpages','','BusinessDocuments')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>