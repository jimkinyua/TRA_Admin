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

$RevenueStreamName='';
$Description='';
$RequirementID='0';
$RevenueStreamCode='';
$ServiceCategoryID='';
$FormID='';
$CreatedDate="";
$PrimaryService="";
$action='';
$ServiceCategoryID = $_REQUEST['ServiceCategoryID'];

if($RequirementID=='0'){
    $action="ADD";
}else{
    $action="EDIT";
}

$sql="select CategoryName from ServiceCategory where ServiceCategoryID=$ServiceCategoryID";

$result=sqlsrv_query($db,$sql);
while ($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
    $CategoryName=$row['CategoryName'];
}


?>
<div class="example">
<form>
	<fieldset>
	  <legend><?= $action; ?> Required Attachment</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>

			<tr>
				<td width="50%"><label>Service Category</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceCategoryID"  id="ServiceCategoryID" disabled="disabled">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM ServiceCategory ORDER BY ServiceCategoryID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["ServiceCategoryID"];
                                    $s_name = $row["CategoryName"];
                                    if ($ServiceCategoryID==$s_id) 
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
                    
                  </div>
				</td>
                <td></td>
			</tr>
            <tr>
                <td width="50%"><label>Document</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="DocumentID"  id="DocumentID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Documents ORDER BY DocumentID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["DocumentID"];
                                    $s_name = $row["DocumentName"];
                                    if ($DocumentID==$s_id) 
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
                    
                  </div>
                </td>
                <td></td>
            </tr>			
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('required_documents_list.php?ServiceCategoryID=<?= $ServiceCategoryID; ?>&DocumentID='+this.form.DocumentID.value+'&RequirementID=<?= $RequirementID; ?>&save=1','content','loader','listpages','','RequiredDocuments','<?= $ServiceCategoryID; ?>')" value="Save">
      <input type="button" value="Cancel" onClick="loadmypage('required_documents_list.php?ServiceCategoryID=<?= $ServiceCategoryID; ?>','content','loader','listpages','','RequiredDocuments','<?= $ServiceCategoryID; ?>')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>