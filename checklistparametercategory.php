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

$ParameterCategoryName='';
$ParameterCategoryDescription='';
$ParameterCategoryID='0';
$FormID='';
$ServiceGroupID='';
$CreatedDate="";
$ServiceCode='';


if (isset($_REQUEST['edit']))
{	
	$ParameterCategoryID=	$_REQUEST['ParameterCategoryID'];
	
	$sql = "SELECT * FROM ChecklistParameterCategories where ParameterCategoryID = $ParameterCategoryID";

	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ParameterCategoryName=$myrow['ParameterCategoryName'];
		$ParameterCategoryDescription=$myrow['ParameterCategoryDescription'];
	}	
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Parameter Category</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Parameter Category Name</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="ParameterCategoryName" id="ParameterCategoryName"><?php echo $ParameterCategoryName; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
			<tr>
          <td width="50%">
          	<label>Parameter Category Description</label>
          	<div class="input-control textarea" data-role="input-control">
              	<textarea name="ParameterCategoryDescription" id="ParameterCategoryDescription"><?php echo $ParameterCategoryDescription; ?></textarea>
                  <button class="btn-clear" tabindex="-1"></button>
              </div>
          </td>
          <td width="50%">

          	</td>
    	</tr>			  
                        
        </table>
		<input name="Button" type="button" 
    onclick="loadmypage('checklistparametercategory_list.php?'+
                        '&ParameterCategoryName='+this.form.ParameterCategoryName.value+
                        '&ParameterCategoryDescription='+this.form.ParameterCategoryDescription.value+
                        '&ParameterCategoryID='+<?php echo $ParameterCategoryID; ?>+       
                        '&save=1','content','loader','listpages','','ChecklistParameterCategories')" 
                        value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('checklistparametercategory_list.php?i=1','content','loader','listpages','','ChecklistParameterCategories')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>