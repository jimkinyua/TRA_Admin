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

$PageID="0";
$PageName="";
$WairdID="";
$ApproverRoleCenterID='';
$MenuGroupID=0;

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['PageID'])){$PageID=$_REQUEST['PageID'];}
	
	$sql = "SELECT * FROM Pages where PageID = '$PageID'";
	$result = sqlsrv_query($db, $sql);

   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$PageName=$myrow['PageName'];
        $MenuGroupID=$myrow['MenuGroupID'];
        $PageGroupID=$myrow['PageGroupID'];
        $ApproverThree=$myrow['ApproverThree'];
        $ApproverTwo=$myrow['ApproverTwo'];
        $ApproverOne=$myrow['ApproverOne'];

	}	
   
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Page</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Page Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="PageName" name="PageName" value="<?php echo $PageName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Page Group</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="PageGroupID" name="PageGroupID" value="<?php echo $PageGroupID; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
			 <td><label>MenuGroup</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="MenuGroupID"  id="MenuGroupID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM MenuGroups ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["MenuGroupID"];
                                    $s_name = $row["MenuGroupName"];
                                    if ($MenuGroupID==$s_id) 
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
              <td><label>First Approver</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="ApproverOne"  id="ApproverOne">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM RoleCenters ORDER BY RoleCenterID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["RoleCenterID"];
                                    $s_name = $row["RoleCenterName"];
                                    if ($ApproverOne==$s_id) 
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
              <td><label>Second Approver</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="ApproverTwo"  id="ApproverTwo">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM RoleCenters ORDER BY RoleCenterID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["RoleCenterID"];
                                    $s_name = $row["RoleCenterName"];
                                    if ($ApproverTwo==$s_id) 
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
              <td><label>Third Approver</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="ApproverThree"  id="ApproverThree">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM RoleCenters ORDER BY RoleCenterID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["RoleCenterID"];
                                    $s_name = $row["RoleCenterName"];
                                    if ($ApproverThree==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('pages_list.php?'+
        											'&PageName='+this.form.PageName.value+
                                                    '&MenuGroupID='+this.form.MenuGroupID.value+
                                                    '&ApproverOne='+this.form.ApproverOne.value+
                                                    '&ApproverTwo='+this.form.ApproverTwo.value+
                                                    '&ApproverThree='+this.form.ApproverThree.value+
                                                    '&PageGroupID='+this.form.PageGroupID.value+
                                                    '&PageID='+<?php echo $PageID ?>+
                                                    
                                                    '&save=1','content','loader','listpages','','Pages')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('pages_list.php?i=1','content','loader','listpages','','Pages')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>