<?php 
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');
	
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$ComplaintID=0;
$Description='';

$msg = $_REQUEST['msg'];    

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET );




?>
<div class="example">
<form>
	<fieldset>
	  <legend>Complaint Reporting</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
             
		  </tr>
            <tr>
                <td width="50%">
                    <label>Application Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="refno" name="refno" value="<?php echo $RefNumber; ?>" ></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr> 
		  </tr>
            <tr>
                <td width="50%">
                    <label>Description of the Issue</label>
                    <div class="input-control textarea" data-role="input-control">
                        <textarea id="Description" name="Description" value="<?php echo $Description; ?>"></textarea>                       
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>           		
                     
        </table>

		<input name="Button" type="button" onclick="loadmypage('complaints_list.php?i=1'+
					'&refno='+this.form.refno.value+	
                    '&ComplaintID='+<?php echo $ComplaintID; ?>+ 
                    '&Description='+this.form.Description.value+
                    '&save=1'+
                    '','content','loader','listpages','','Complaints','')" value="Save">

	</fieldset>
</form>
</div>