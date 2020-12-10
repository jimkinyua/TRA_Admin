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

$FormColumnID="0";
$FormColumnName="";
$ColumnDataTypeID="";
$ColumnSize="";
$FormID=$_REQUEST['FormID'];
$FormName='';
$Priority='';
$Mandatory='';
$Notes='';
$FilterColumnID='';

$sql="select FormName from Forms where FormID=$FormID";
$result=sqlsrv_query($db,$sql);
$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if ($result)
{
	$FormName=$rw['FormName'];
}



if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['FormColumnID'])){$FormColumnID=$_REQUEST['FormColumnID'];}
	
	$sql = "SELECT fc.*,f.FormName FROM FormColumns fc
		inner join Forms f on fc.FormID=f.FormID
		left join FormSections fs on fc.FormSectionID=fs.FormSectionID 
		where fc.FormColumnID = '$FormColumnID'";
		
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$FormColumnName=$myrow['FormColumnName'];
		$FormSectionID=$myrow['FormSectionID'];
		$FormID=$myrow['FormID'];
		$FormName=$myrow['FormName'];
		$ColumnDataTypeID=$myrow['ColumnDataTypeID'];
		$ColumnSize=$myrow['ColumnSize'];
		$Priority=$myrow['Priority'];;
		$Mandatory=$myrow['Mandatory'];;
		$Notes=$myrow['Notes'];;
		$FilterColumnID=$myrow['FilterColumnID'];;
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit <?php echo $FormName; ?> Columns</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>FormColumn Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="FormColumnName" name="FormColumnName" value="<?php echo $FormColumnName; ?>"></input>
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
							if ($FormID==''){
                            	$s_sql = "SELECT * FROM Forms ORDER BY FormID";
							}else{
								$s_sql = "SELECT * FROM Forms where FormID=$FormID";
							}
                            
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
          <tr>
			  <td><label>Form Section</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="FormSectionID"  id="FormSectionID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM FormSections where FormID=$FormID ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["FormSectionID"];
                                    $s_name = $row["FormSectionName"];
                                    if ($FormSectionID==$s_id) 
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
          <tr>
			  <td><label>Data Type</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ColumnDataTypeID"  id="ColumnDataTypeID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM ColumnDataType ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["ColumnDataTypeID"];
                                    $s_name = $row["ColumnDataTypeName"];
                                    if ($ColumnDataTypeID==$s_id) 
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
            <!-- <tr>
                <td width="50%">
                    <label>Field Size</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="ColumnSize" name="ColumnSize" value="<?php echo $ColumnSize; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> -->
		  </tr>                          
            <!-- <tr>
                <td width="50%">
                    <label>Priority</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Priority" name="Priority" value="<?php echo $Priority; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
                </td>
            </tr> -->
		  </tr>                          
            <tr>
                <td width="50%">
                    <label>Notes</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Notes" name="Notes" value="<?php echo $Notes; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label class="input-control checkbox">
                    	<input type="checkbox" id="Mandatory" name="Mandatory" checked="checked" />
                        <span class="check"></span>
                        <span class="caption">Is the Field Mandatory?</span>                    
                    </label>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
			  <td><label>Filter Column</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="FilterColumnID"  id="FilterColumnID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM FormColumns where FormID=$FormID ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["FormColumnID"];
                                    $s_name = $row["FormColumnName"];
                                    if ($ColumnDataTypeID==$s_id) 
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

		<input name="Button" type="button" onclick="loadmypage('form_columns_list.php?'+
        											'&FormColumnName='+this.form.FormColumnName.value+
                                                    '&FormID='+this.form.FormID.value+
                                                    '&FormSectionID='+this.form.FormSectionID.value+
                                                    '&ColumnDataTypeID='+this.form.ColumnDataTypeID.value+
                                                    '&Notes='+escape(this.form.Notes.value)+
                                                    '&Mandatory='+this.form.Mandatory.checked+
                                                    '&FilterColumnID='+this.form.FilterColumnID.value+
                                                    '&FormColumnID='+<?php echo $FormColumnID; ?>+
                                                    '&save=1','content','loader','listpages','','FormColumns','<?php echo $FormID; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('form_columns_list.php?FormID=<?php echo $FormID; ?>&FormName=<?php echo $FormName; ?>','content','loader','listpages','','FormColumns','<?php echo $FormID; ?>')"> 												
                                                    
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>