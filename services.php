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

$ServiceName='';
$Description='';
$ServiceID='0';
$ServiceCode="";
$ServiceCategoryID='';
$ServiceGroupID ='';
$DepartmentID='';
$GlAccountNo='';
$CreatedDate="";
$CreatedUserID="";
$RevenueStream="";

if (isset($_REQUEST['edit']))
{	
	$ServiceID=	$_REQUEST['ServiceID'];
	
	$sql = "SELECT s.*,sg.ServiceGroupID FROM Services s inner join 
	ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID inner join
	ServiceGroup sg on sc.serviceGroupID=sg.ServiceGroupID
	where s.ServiceID = $ServiceID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceName=$myrow['ServiceName'];
		$ServiceCategoryID=$myrow['ServiceCategoryID'];
		$ServiceCode=$myrow['ServiceCode'];
		$DepartmentID=$myrow['DepartmentID'];
		$Description=$myrow['Description'];
		$GlAccountNo=$myrow['GlAccountNo'];
		$Chargeable=$myrow['Chargeable'];
		$ServiceGroupID=$myrow['ServiceGroupID'];
		$RevenueStreamID=$myrow['RevenueStreamID'];
		
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Service</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Service Name</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="ServiceName" id="ServiceName"><?php echo $ServiceName; ?></textarea>
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
						<input type="text" id="ServiceCode" name="ServiceCode" value="<?php echo $ServiceCode; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
			<tr>
                <td width="50%">
                	<label>Service Description</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="Description" id="Description"><?php echo $Description; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
          <tr>
			  <td><label>Service Group</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="ServiceGroupID"  id="ServiceGroupID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from ServiceGroup order by ServiceGroupName";
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
			  <td><label>Revenue Stream</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="RevenueStreamID"  id="RevenueStreamID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from RevenueStreams order by RevenueStreamID";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["RevenueStreamID"];
                                $s_name = $row["RevenueStreamName"];
                                if ($RevenueStreamID==$s_id) 
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
			  <td><label>Service Category</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="ServiceCategoryID"  id="ServiceCategoryID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM ServiceCategory ORDER BY CategoryName";
						
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
                    
                  </div></td>
			  <td></td>
		  </tr>  
 
          <tr>
			  <td><label>Gl Account</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="GlAccountNo"  id="GlAccountNo">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT [GlAccountNo],[GlAccountName] FROM [COUNTYREVENUE].[dbo].[GlAccounts] order by GlAccountName";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["GlAccountNo"];
                                $s_name = $row["GlAccountName"];
                                if ($GlAccountNo==$s_id) 
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
			  <td><label>Chargeable</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="Chargeable"  id="Chargeable">
						<?php 
							$selected="";
							if ($Chargeable=="1")
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
		<input name="Button" type="button" onclick="loadmypage('services_list.php?'+
                                            '&ServiceName='+this.form.ServiceName.value+
											'&ServiceCode='+this.form.ServiceCode.value+
                                            '&ServiceCategoryID='+this.form.ServiceCategoryID.value+
                                            '&ServiceGroupID='+this.form.ServiceGroupID.value+ 
											'&RevenueStreamID='+this.form.RevenueStreamID.value+ 
                                            '&Description='+this.form.Description.value+
											'&Chargeable='+this.form.Chargeable.value+
                                            '&GlAccountNo='+this.form.GlAccountNo.value+ 
                                            '&ServiceID='+<?php echo $ServiceID; ?>+       
        									'&save=1','content','loader','listpages','','services')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('services_list.php?i=1','content','loader','listpages','','services')">
      <input type="Button" value="Charges" onClick="loadmypage('service_charges_list.php?ServiceID='+<?php echo $ServiceID; ?>+'&ServiceName='+this.form.ServiceName.value+'','content','loader','listpages','','service_charges','<?php echo $ServiceID; ?>')">
	  
        
		
		<span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>