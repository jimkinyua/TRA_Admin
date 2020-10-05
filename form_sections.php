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

$FormSectionID="0";
$FormSectionName="";
$FormID=$_REQUEST['FormID'];
$FormName='';



if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['FormSectionID'])){$FormSectionID=$_REQUEST['FormSectionID'];}
	
	$sql = "SELECT fs.*,f.FormName FROM FormSections fs inner join Forms f on fs.FormID=f.FormID where fs.FormSectionID = '$FormSectionID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$FormSectionName=$myrow['FormSectionName'];
		$FormID=$myrow['FormID'];
		$FormName=$myrow['FormName'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit <?php echo $FormName; ?> Sections</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>FormSection Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="FormSectionName" name="FormSectionName" value="<?php echo $FormSectionName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Form</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="FormID"  id="FormID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Forms ORDER BY FormID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["FormID"];
                                    $s_name = $row["FormName"];
                                    if ($FormID==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('form_sections_list.php?'+
        											'&FormSectionName='+this.form.FormSectionName.value+
                                                    '&FormID='+this.form.FormID.value+
                                                    '&FormSectionID='+<?php echo $FormSectionID; ?>+
                                                    '&save=1','content','loader','listpages','','FormSections','<?php echo $FormID; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('form_sections_list.php?FormID=<?php echo $FormID; ?>&FormName=<?php echo $FormName; ?>','content','loader','listpages','','FormSections','<?php echo $FormID; ?>')">
      												
                                                    <!--loadmypage('form_sections_list.php?FormID=2&FormName=Single Business Permit','content','loader','listpages','','FormSections',2) -->
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>