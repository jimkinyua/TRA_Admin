<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}
$UserID = $_SESSION['UserID'];
$ProjectID = $_REQUEST['ProjectID'];

$ActivityID='';
$ActivityTitle='';
$Notes='';
$ActivitySequence='';
$PhaseID='';
$DependentActivityID='';
$DependencyTypeID='';

if (isset($_REQUEST['ActivityID'])) { $ActivityID = $_REQUEST['ActivityID']; }
if (isset($_REQUEST['ActivityTitle'])) { $ActivityTitle = $_REQUEST['ActivityTitle']; }
if (isset($_REQUEST['Notes'])) { $Notes = $_REQUEST['Notes']; }
if (isset($_REQUEST['ActivitySequence'])) { $ActivitySequence = $_REQUEST['ActivitySequence']; }
if (isset($_REQUEST['PhaseID'])) { $PhaseID = $_REQUEST['PhaseID']; }
if (isset($_REQUEST['DependentActivityID'])) { $DependentActivityID = $_REQUEST['DependentActivityID']; }
if (isset($_REQUEST['DependencyTypeID'])) { $DependencyTypeID = $_REQUEST['DependencyTypeID']; }

if (isset($_REQUEST['edit']))
{	
	$ActivityID = $_REQUEST['ActivityID'];	
	$sql = "SELECT * FROM Activities where ActivityID = $ActivityID";
	$result = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))  
	{
		$ActivityID=$myrow['ActivityID'];
		$ActivityTitle=$myrow['ActivityTitle'];
		$Notes=$myrow['Notes'];
		$ActivitySequence=$myrow['ActivitySequence'];
		$PhaseID=$myrow['PhaseID'];
		$DependentActivityID=$myrow['DependentActivityID'];
		$DependencyTypeID=$myrow['DependencyTypeID'];
		$CreatedDate=$myrow['CreatedDate'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Project Activity</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="3" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Activity Title</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="ActivityTitle" type="text" id="ActivityTitle" value="<?php echo $ActivityTitle; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="15%"><label>Sequence</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="ActivitySequence" type="text" id="ActivitySequence" value="<?php echo $ActivitySequence; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div></td>
                <td width="35%">&nbsp;</td>
          	</tr>
			<tr>
			  <td>              <label>Phase</label>
              <div class="input-control select" data-role="input-control">
			    <select name="PhaseID"  id="PhaseID">
			      <option value="0" selected="selected">SELECT PHASE</option>
			      <?php 
                        $s_sql = "SELECT * FROM Phases WHERE ProjectID = '$ProjectID' ORDER BY PhaseTitle";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["PhaseID"];
                                $s_name = $row["PhaseTitle"];
                                if ($PhaseID==$s_id) 
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
			  <td colspan="2">&nbsp;</td>
		  </tr>
			<tr>
			  <td><label>Dependent Activity</label>
                <div class="input-control select" data-role="input-control">
                  <select name="DependentActivityID"  id="DependentActivityID">
                    <option value="0" selected="selected">SELECT ACTIVITY</option>
                    <?php 
                        $s_sql = "Select * FROM Activities 
									JOIN Phases on Phases.PhaseID = Activities.ActivityID
									WHERE ProjectID = '$ProjectID'
									ORDER BY ActivityTitle
									";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ActivityID"];
                                $s_name = $row["ActivityTitle"];
                                if ($ActivityID==$s_id) 
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
			  <td colspan="2"><label>Dependency Type</label>
                <div class="input-control select" data-role="input-control">
                  <select name="DependencyTypeID"  id="DependencyTypeID">
                    <option value="0" selected="selected">SELECT PHASE</option>
                    <?php 
                        $s_sql = "SELECT * FROM DependencyTypes ORDER BY DependencyTypeName";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["DependencyTypeID"];
                                $s_name = $row["DependencyTypeName"];
                                if ($DependencyTypeID==$s_id) 
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
		  </tr>
			<tr>
                <td colspan="3">
                	<label>Notes</label>
                	<div class="input-control textarea" data-role="input-control">
                        <textarea name="Notes" rows="4" id="Notes" placeholder=""><?php echo $Notes; ?></textarea>
                    </div>
                </td>
            </tr>                       
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('activities_list.php?'+
        '&ActivityID='+this.form.ActivityID.value+
        '&ActivityTitle='+this.form.ActivityTitle.value+
        '&Notes='+this.form.Notes.value+
        '&ActivitySequence='+this.form.ActivitySequence.value+  
        '&PhaseID='+this.form.PhaseID.value+
        '&DependentActivityID='+this.form.DependentActivityID.value+
        '&DependencyTypeID='+this.form.DependencyTypeID.value+        
        '&save=1&ProjectID=<?php echo $ProjectID; ?>','tab','loader','activities','<?php echo $ProjectID; ?>')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('activities_list.php?ProjectID=<?php echo $ProjectID; ?>','tab','loader','activities','<?php echo $ProjectID; ?>')">
        <span class="table_text">
        <input name="ActivityID" type="hidden" id="ActivityID" value="<?php echo $ActivityID;?>" />
        </span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>