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

$GlAccountID="0";
$GlAccountName="";
$GlAccountNo="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['GlAccountID'])){$GlAccountID=$_REQUEST['GlAccountID'];}
	
	$sql = "SELECT * FROM GlAccounts where GlAccountID = '$GlAccountID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$GlAccountName=$myrow['GlAccountName'];
		$GlAccountNo=$myrow['GlAccountNo'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Gl Accounts</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Account Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="GlAccountNo" name="GlAccountNo" value="<?php echo $GlAccountNo; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Account Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="GlAccountName" name="GlAccountName" value="<?php echo $GlAccountName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>                                      
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('gl_accounts_list.php?'+
        											'&GlAccountName='+this.form.GlAccountName.value+
                                                    '&GlAccountNo='+this.form.GlAccountNo.value+
                                                    '&GlAccountID='+<?php echo $GlAccountID ?>+
                                                    '&save=1','content','loader','listpages','','GLAccounts')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('gl_accounts_list.php?i=1','content','loader','listpages','','GlAccounts')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>