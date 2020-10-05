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

$CategoryName='';
$Description='';
$ServiceCategoryID='0';
$FormID='';
$ServiceGroupID='';
$CreatedDate="";
$ServiceCode='';


if (isset($_REQUEST['edit']))
{	
	$ServiceCategoryID=	$_REQUEST['ServiceCategoryID'];
	
	$sql = "SELECT * FROM ServiceCategory where ServiceCategoryID = $ServiceCategoryID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$CategoryName=$myrow['CategoryName'];
		$FormID=$myrow['FormID'];
		$ServiceGroupID=$myrow['ServiceGroupID'];
		$ServiceCode=$myrow['ServiceCode'];
		$Description=$myrow['Description'];
		$InvoiceStageID=$myrow['InvoiceStage'];
		$LastStageID=$myrow['LastStage'];
		$PrimaryService=$myrow['PrimaryService'];
	}	
}



?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit ServiceCategory</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>ServiceCategory Name</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="CategoryName" id="CategoryName"><?php echo $CategoryName; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
			<tr>
                <td width="50%">
                	<label>Category Description</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="Description" id="Description"><?php echo $Description; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
			<tr>
                <td width="50%">
                	<label>Service Code</label>
                	<div class="input-control text" data-role="input-control">
                    	<input type="text" name="ServiceCode" id="ServiceCode"><?php echo $ServiceCode; ?></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>			
			<tr>
			  <td><label>Service Form</label>
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
          <tr>
			  <td><label>Service Group</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="ServiceGroupID"  id="ServiceGroupID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from ServiceGroup order by ServiceGroupID";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ServiceGroupID"];
                                $s_name = $row["ServiceGroupName"];
                                if ($ServiceGroupID==$s_id) 
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
			  <td><label>Invoice Stage</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="InvoiceStageID"  id="InvoiceStageID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select ServiceStatusID,ServiceStatusName from ServiceStatus order by ServiceStatusID";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ServiceStatusID"];
                                $s_name = $row["ServiceStatusName"];
                                if ($InvoiceStageID==$s_id) 
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
			  <td><label>Last Stage</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="LastStageID"  id="LastStageID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select ServiceStatusID,ServiceStatusName from ServiceStatus order by ServiceStatusID";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ServiceStatusID"];
                                $s_name = $row["ServiceStatusName"];
                                if ($LastStageID==$s_id) 
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
			  <td><label>Is it a Primary Service</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="PrimaryService"  id="PrimaryService">
						<?php 
							if ($PrimaryService=="1")
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
		<input name="Button" type="button" onclick="loadmypage('servicecategory_list.php?'+
                                            '&CategoryName='+this.form.CategoryName.value+
                                            '&FormID='+this.form.FormID.value+
											'&PrimaryService='+this.form.PrimaryService.value+
                                            '&ServiceGroupID='+this.form.ServiceGroupID.value+ 
											'&ServiceCode='+this.form.ServiceCode.value+ 
                                            '&Description='+this.form.Description.value+ 
											'&InvoiceStageID='+this.form.InvoiceStageID.value+ 
											'&LastStageID='+this.form.LastStageID.value+ 
                                            '&ServiceCategoryID='+<?php echo $ServiceCategoryID; ?>+       
        									'&save=1','content','loader','listpages','','ServiceCategories')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('servicecategory_list.php?i=1','content','loader','listpages','','ServiceCategories')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>