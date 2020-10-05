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
	if (isset($_REQUEST['FormID'])){$FormID=$_REQUEST['FormID'];}
	
	$sql = "SELECT * FROM Forms where FormID = '$FormID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$FormName=$myrow['FormName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Forms</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Form Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="FormName" name="FormName" value="<?php echo $FormName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Service Type</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceHeaderType"  id="ServiceHeaderType">
                            <option value="0" selected="selected"></option>
                            <?php 
							
							$s_sql = "SELECT * FROM ServiceHeaderType ORDER BY ServiceHeaderType";							
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["ServiceHeaderType"];
                                    $s_name = $row["ServiceHeaderTypeName"];
                                    if ($ServiceHeaderType==$s_id) 
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
			  <td></td>
			</tr>			
        </table>
		<input name="Button" type="button" onclick="loadmypage('forms_list.php?'+
        											'&FormName='+this.form.FormName.value+
													'&ServiceHeaderType='+this.form.ServiceHeaderType.value+
                                                    '&FormID='+<?php echo $FormID ?>+													
                                                    '&save=1','content','loader','listpages','','Forms')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('forms_list.php?i=1','content','loader','listpages','','Forms')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>