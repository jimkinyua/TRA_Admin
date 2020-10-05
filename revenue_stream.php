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
$RevenueStreamID='0';
$RevenueStreamCode='';
$RevenueCategoryID='';
$FormID='';
$CreatedDate="";
$PrimaryService="";


if (isset($_REQUEST['edit']))
{	
	$RevenueStreamID=	$_REQUEST['RevenueStreamID'];
	
	$sql = "select rs.RevenueStreamID,rs.RevenueStreamCode,rs.RevenueStreamName,rc.RevenueCategoryID,rc.RevenueCategoryName,dp.DepartmentID,dp.DepartmentName 
		from RevenueStreams rs 
		left join RevenueCategories rc on rs.RevenueCategoryID=rc.RevenueCategoryID
        join Departments dp on rs.DepartmentID=dp.DepartmentID 
		where rs.RevenueStreamID = $RevenueStreamID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$RevenueStreamName=$myrow['RevenueStreamName'];
		$RevenueStreamID=$myrow['RevenueStreamID'];
		$RevenueCategoryID=$myrow['RevenueCategoryID'];
		$RevenueStreamCode=$myrow['RevenueStreamCode'];
        $DepartmentID=$myrow['DepartmentID'];
	}	
}
 //echo $sql;


?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Revenue Stream</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Revenue Stream Name</label>
					
                	<div class="input-control text" data-role="input-control">
						<input name="RevenueStreamName" id="RevenueStreamName" type="text" value="<?php echo $RevenueStreamName; ?>"></input>                    	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

				</td>
			</tr>
			<tr>
				<td><label>Revenue Category</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="RevenueCategoryID"  id="RevenueCategoryID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM RevenueCategories ORDER BY RevenueCategoryID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["RevenueCategoryID"];
                                    $s_name = $row["RevenueCategoryName"];
                                    if ($RevenueCategoryID==$s_id) 
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
			</tr>
            <tr>
                <td><label>Department</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="DepartmentID"  id="DepartmentID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Departments ORDER BY DepartmentID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["DepartmentID"];
                                    $s_name = $row["DepartmentName"];
                                    if ($DepartmentID==$s_id) 
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
            </tr>
			<tr>
                <td width="50%">
                	<label>Code</label>
					
                	<div class="input-control text" data-role="input-control">
						<input name="RevenueStreamCode" id="RevenueStreamCode" type="text" value="<?php echo $RevenueStreamCode; ?>"></input>                    	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

				</td>
			</tr>			
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('revenue_stream_list.php?'+
                                            '&RevenueStreamName='+this.form.RevenueStreamName.value+
											'&RevenueStreamCode='+this.form.RevenueStreamCode.value+
                                            '&RevenueStreamID='+<?php echo $RevenueStreamID; ?>+ 
											'&RevenueCategoryID='+this.form.RevenueCategoryID.value+
                                            '&DepartmentID='+this.form.DepartmentID.value+ 
        									'&save=1','content','loader','listpages','','RevenueStreams')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('revenue_stream_list.php?i=1','content','loader','listpages','','RevenueStreams')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>