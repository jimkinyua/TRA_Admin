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

$ChecklistTypeName='';
$ChecklistTypeID='0';
$FormID='';
$ServiceGroupID='';
$CreatedDate="";
$ServiceCode='';


if (isset($_REQUEST['edit']))
{	
	$ChecklistTypeID=	$_REQUEST['ChecklistTypeID'];
	
	$sql = "SELECT * FROM ChecklistTypes where ChecklistTypeID = $ChecklistTypeID";

	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ChecklistTypeName=$myrow['ChecklistTypeName'];		
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
                	<label>Checlist Type Name</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="ChecklistTypeName" id="ChecklistTypeName"><?php echo $ChecklistTypeName; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>		  
                        
        </table>
		<input name="Button" type="button" 
    onclick="loadmypage('checklisttype_list.php?'+
                        '&ChecklistTypeName='+this.form.ChecklistTypeName.value+
                        '&ChecklistTypeID='+<?php echo $ChecklistTypeID; ?>+       
                        '&save=1','content','loader','listpages','','ChecklistTypes')" 
                        value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('checklisttype_list.php?i=1','content','loader','listpages','','ChecklistTypes')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>