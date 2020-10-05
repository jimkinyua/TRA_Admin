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

$BankID=0;
$BankName=$row['$BankName'];    
$Active=0;
$AccountNumber='';
$ShowPublic=0;
$Branch=0;

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['BankID'])){$BankID=$_REQUEST['BankID'];}
	
	$sql = "SELECT * FROM Banks where BankID = '$BankID'";
	$result = sqlsrv_query($db, $sql);
   	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$BankName=$row['BankName'];  
        $Active=$row['Active'];
        $AccountNumber=$row['AccountNumber'];
        $Branch=$row['Branch'];
        $ShowPublic=$row['ShowPublic'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Bank</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Bank Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="BankName" name="BankName" value="<?php echo $BankName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
			<tr>
                <td width="50%">
                    <label>Account Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="AccountNumber" name="AccountNumber" value="<?php echo $AccountNumber; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
			<tr>
                <td width="50%">
                    <label>Branch</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Branch" name="Branch" value="<?php echo $Branch; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
			<td><label>Show Public</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ShowPublic"  id="ShowPublic">
                        <?php 
                            $selected="";
                            if ($ShowPublic=="1")
                            {
                                $selected="selected";
                            }                           
                            
                        ?>
                        <option value="0" <?php echo $selected; ?>>No</option>
                        <option value="1" <?php echo $selected; ?>>Yes</option>
                      </select>
                      
                  </div></td>
                  <td>
                  </td>
          </tr>
			
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('banks_list.php?'+
        											'&BankName='+this.form.BankName.value+
													'&AccountNumber='+this.form.AccountNumber.value+
													'&Branch='+this.form.Branch.value+
                                                    '&ShowPublic='+this.form.ShowPublic.value+
                                                    '&BankID='+<?php echo $BankID ?>+
                                                    '&save=1','content','loader','listpages','','Banks')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('subcounty_list.php?i=1','content','loader','listpages','','subcounties')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>