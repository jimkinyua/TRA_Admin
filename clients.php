<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ClientID='';
$ClientName='';
$PostalAddress='';
$PostalCode='';
$Town='';
$CountryID='';
$PhysicalAddress='';
$Telephone1='';
$Telephone2='';
$Mobile1='';
$Mobile2='';
$Email='';
$Url='';
$CreatedDate='';
$UserID='';
$Notes='';

if (isset($_REQUEST['ClientID'])) { $ClientID = $_REQUEST['ClientID']; }
if (isset($_REQUEST['ClientName'])) { $ClientName = $_REQUEST['ClientName']; }
if (isset($_REQUEST['PostalAddress'])) { $PostalAddress = $_REQUEST['PostalAddress']; }
if (isset($_REQUEST['PostalCode'])) { $PostalCode = $_REQUEST['PostalCode']; }
if (isset($_REQUEST['Town'])) { $Town = $_REQUEST['Town']; }
if (isset($_REQUEST['Telephone1'])) { $Telephone1 = $_REQUEST['Telephone1']; }
if (isset($_REQUEST['Telephone2'])) { $Telephone2 = $_REQUEST['Telephone2']; }
if (isset($_REQUEST['Mobile1'])) { $Mobile1 = $_REQUEST['Mobile1']; }
if (isset($_REQUEST['Mobile2'])) { $Mobile2 = $_REQUEST['Mobile2']; }
if (isset($_REQUEST['Email'])) { $Email = $_REQUEST['Email']; }
if (isset($_REQUEST['Url'])) { $Url = $_REQUEST['Url']; }
if (isset($_REQUEST['CountryID'])) { $CountryID = $_REQUEST['CountryID']; }
if (isset($_REQUEST['PhysicalAddress'])) { $PhysicalAddress = $_REQUEST['PhysicalAddress']; }
if (isset($_REQUEST['Notes'])) { $Notes = $_REQUEST['Notes']; }

if (isset($_REQUEST['edit']))
{	
	$ClientID = $_REQUEST['ClientID'];	
	$sql = "SELECT * FROM Clients where ClientID = $ClientID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ClientID=$myrow['ClientID'];
		$ClientName=$myrow['ClientName'];
		$PostalAddress=$myrow['PostalAddress'];
		$PostalCode=$myrow['PostalCode'];
		$Town=$myrow['Town'];
		$CountryID=$myrow['CountryID'];
		$PhysicalAddress=$myrow['PhysicalAddress'];
		$Telephone1=$myrow['Telephone1'];
		$Telephone2=$myrow['Telephone2'];
		$Mobile1=$myrow['Mobile1'];
		$Mobile2=$myrow['Mobile2'];
		$Email=$myrow['Email'];
		$Url=$myrow['Url'];
		$CreatedDate=$myrow['CreatedDate'];
		$UserID=$myrow['UserID'];
		$Notes=$myrow['Notes'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Clients</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Client Name</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="ClientName" type="text" id="ClientName" value="<?php echo $ClientName; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">&nbsp;</td>
          	</tr>
			<tr>
                <td width="50%">
                <label>Postal Address</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="PostalAddress" type="text" id="PostalAddress" value="<?php echo $PostalAddress; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>	
                </td>
                <td width="50%">
                <label>Postal Code</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="PostalCode" type="text" id="PostalCode" value="<?php echo $PostalCode; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
              </div>	
				</td>
          	</tr>
			<tr>
			  <td><label>Town</label>
			    <div class="input-control text" data-role="input-control">
			      <input name="Town" type="text" id="Mobile3" value="<?php echo $Town; ?>" placeholder="" />
			      <button class="btn-clear" tabindex="-1"></button>
		        </div></td>
			  <td>
              <label>Country</label>
              <div class="input-control select" data-role="input-control">
			    <select name="CountryID"  id="CountryID">
			      <option value="0" selected="selected">SELECT COUNTRY</option>
			      <?php 
                        $s_sql = "SELECT * FROM Countries ORDER BY CountryName";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["CountryID"];
                                $s_name = $row["CountryName"];
                                if ($CountryID==$s_id) 
                                {
                                    $selected = 'selected="selected"';
                                } else
                                {
                                    $selected = '';
                                }												
                             ?>
			      <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
			      <?php 
                            }
                        }
                        ?>
		        </select>
		      </div></td>
		  </tr>
			<tr>
                <td width="50%">
               	  <label>Mobile 1</label>              	  
<div class="input-control text" data-role="input-control">
                   	  <input name="Mobile1" type="text" id="Mobile1" value="<?php echo $Mobile1; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                	<label>Telephone1</label>
                	<div class="input-control text" data-role="input-control">
                        <input name="Telephone1" type="text" id="Telephone1" value="<?php echo $Telephone1; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
          	</tr>
			<tr>
			  <td><label>Mobile 2</label>		    
  <div class="input-control text" data-role="input-control">
			      <input name="Mobile2" type="text" id="Mobile2" value="<?php echo $Mobile2; ?>" placeholder="" />
			      <button class="btn-clear" tabindex="-1"></button>
		        </div></td>
			  <td><label>Telephone 2</label>			    
  <div class="input-control text" data-role="input-control">
			      <input name="Telephone2" type="text" id="Telephone2" value="<?php echo $Telephone2; ?>" placeholder="" />
		        <button class="btn-clear" tabindex="-1"></button>
		        </div></td>
		  </tr> 
 			<tr>
 			  <td colspan="2"><label>Physical Address</label>
  <div class="input-control text" data-role="input-control">
    <input name="PhysicalAddress" type="text" id="Mobile4" value="<?php echo $PhysicalAddress; ?>" placeholder="" />
    <button class="btn-clear" tabindex="-1"></button>
  </div></td>
		  </tr>
 			<tr>
                <td width="50%">
                	<label>Email</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                	<label>Website</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="Url" type="text" id="Url" value="<?php echo $Url; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
          	</tr>
 			<tr>
 			  <td colspan="2"><label>Notes</label>
                <div class="input-control textarea" data-role="input-control">
                  <textarea name="Notes" rows="4" id="Mobile5" placeholder=""><?php echo $Notes; ?></textarea>
              </div></td>
		  </tr>
		</table>
		<input name="Button" type="button" onclick="loadmypage('clients_list.php?'+
        'ClientID='+this.form.ClientID.value+
        '&ClientName='+this.form.ClientName.value+
        '&PostalAddress='+this.form.PostalAddress.value+
        '&PostalCode='+this.form.PostalCode.value+
        '&Town='+this.form.Town.value+
        '&CountryID='+this.form.CountryID.value+
        '&PhysicalAddress='+this.form.PhysicalAddress.value+
        '&Telephone1='+this.form.Telephone1.value+
        '&Telephone2='+this.form.Telephone2.value+
        '&Mobile1='+this.form.Mobile1.value+
        '&Mobile2='+this.form.Mobile2.value+
        '&Email='+this.form.Email.value+
        '&Url='+this.form.Url.value+
        '&Notes='+this.form.Notes.value+
        '&save=1','content','loader','clients')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('clients_list.php?i=1','content','loader','listpages','','applications')">
        <span class="table_text">
        <input name="ClientID" type="hidden" id="ClientID" value="<?php echo $ClientID;?>" />
<input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>