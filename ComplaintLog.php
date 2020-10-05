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


$msg = $_REQUEST['msg'];    

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET );

$ComplaintID=$_REQUEST['ComplaintID'];

$sql="select * from Complaints where ComplaintID='$ComplaintID'";
$result = sqlsrv_query($db, $sql);
if($result)
{
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        extract($row);        
    }    
}else{
    $msg="Report failed to retrieve";       
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>Complaint Log</legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
                </tr>

            </tr>
            <tr>
                <td width="50%">
                    <label>Application Number</label>
                    <div class="input-control text" data-role="input-control">
                    <input type="text" id="refno" name="refno" value="<?php echo $RefNumber; ?>" disabled="disabled" ></input>
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
                    <textarea id="Description" name="Description" disabled="disabled"><?php echo $Description; ?></textarea>                       
                    </div>
                </td>
                <td width="50%">

                </td>
            </tr> 
            <tr>
                <td width="50%">
                <label>Respondent Comment</label>
                    <div class="input-control textarea" data-role="input-control">
                    <textarea id="response" name="response" value="<?php echo $response; ?>"></textarea>                       
                    </div>
                </td>
                <td width="50%">

                </td>
            </tr> 
            <tr>
              <td><label>Status</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="Status"  id="Status">
                        <?php 
                            $selected="";
                            if ($Status=="1")
                            {
                                $selected="selected";
                            }                           
                            
                        ?>
                        <option value="1" <?php echo $selected; ?>>Resolved</option>
                        <option value="0" <?php echo $selected; ?>>Not Resolved</option>
                      </select>                      
                  </div></td>
                  <td>
                </td>
          </tr>          		

        </table>

		<input name="Button" type="button" onclick="loadmypage('complaints_list.php?i=1'+
					'&refno='+this.form.refno.value+	
                    '&ComplaintID='+<?php echo $ComplaintID; ?>+ 
                    '&Description='+this.form.response.value+
                    '&Status='+this.form.Status.value+
                    '&response=1'+
                    '','content','loader','listpages','','Complaints','')" value="Save">

	</fieldset>
</form>
</div>