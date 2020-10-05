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

$IsService='';
$ServiceID='';
$Description='';
$ServiceTreeID='0';
$ParentID ='';
$CreatedDate="";
$CreatedUserID="";
$IsItService='';

if (isset($_REQUEST['edit']))
{	
	$ServiceTreeID=	$_REQUEST['ServiceTreeID'];
	
	$sql = "SELECT * FROM ServiceTrees where ServiceTreeID = $ServiceTreeID";
	$result = sqlsrv_query($db, $sql);

   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$IsService=$myrow['IsService'];
		$ParentID=$myrow['ParentID'];		
		$Description=$myrow['Description'];
		$ServiceID=$myrow['ServiceID'];	
	}
		
	if ($IsService == 1) 
	{
		$IsItService = 'checked="checked"';
	}	
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit ServiceTree</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
			  <br>
		  </tr>
		  <tr>
			<td width='50%'>
				<a href="#" onClick="deleteConfirm2('Are you sure you want to Delete?','service_trees_list.php?delete=1&ServiceTreeID=<?php echo $ServiceTreeID; ?>','content','loader','listpages','','ServiceTrees')">Delete Node</a>
			</td>
			<td>
			</td>
		  </tr>

			<tr>
                <td width="50%">
                	<label>Tree Description</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="Description" id="Description"><?php echo $Description; ?></textarea>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
			<tr>
			  <td><label>Parent</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="ParentID"  id="ParentID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM ServiceTrees where isService<>1 ORDER BY ParentID";
						
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ServiceTreeID"];
                                $s_name = $row["Description"];
                                if ($ParentID==$s_id) 
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
            <td width="50%">
                <div class="input-control checkbox">
                    <label>
                        <input name="IsService" id="IsService" type="checkbox" <?php echo $IsItService; ?>/>
                        <span class="check"></span>
                        Is Service
                    </label>
                </div>
            </td>
            <td width="50%">

                </td>
        </tr>  
          <tr>
			  <td><label>Service</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="ServiceID"  id="ServiceID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM Services ORDER BY ServiceID";
						
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ServiceID"];
                                $s_name = $row["ServiceName"];
                                if ($ServiceID==$s_id) 
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
                  <td>
                  </td>
          </tr>
		 <tr>
			<td width="50%">
				<div class="input-control text" data-role="input-control">
					<input name="To" type="text" class="table_text" id="To" style="width:100%" value="" onkeyup="autocompletEmail('To','To_list_id')" />
																											  
					<ul id="To_list_id"></ul>
					<button type="button" class="btn-clear" tabindex="-1"></button>
				</div>			
			</td>
			<td></td>
		 </tr>
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('service_trees_list.php?'+
                                            '&IsService='+this.form.IsService.checked+
                                            '&ParentID='+this.form.ParentID.value+ 
                                            '&Description='+this.form.Description.value+ 
                                            '&ServiceID='+this.form.ServiceID.value+ 
                                            '&ServiceTreeID='+<?php echo $ServiceTreeID; ?>+       
        									'&save=1','content','loader','listpages','','ServiceTrees')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('service_trees_list.php?i=1','content','loader','listpages','','ServiceTrees')">
	 <!-- <input name="createFlatWindow" id="createFlatWindow" type="button" class="button"  value="Create Flat Window" onclick="flatWindow()"> -->
        
		<div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>