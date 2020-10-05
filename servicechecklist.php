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

$ServiceGroupID='';
$ParameterID='';
$LinkServiceID='0';
$ParameterCategoryID='';
$ChargeTypeID ='';
$$ServiceCharge='';
$ServiceGroupName='';


if (isset($_REQUEST['ServiceGroupID'])){$ServiceGroupID=$_REQUEST['ServiceGroupID'];}
if (isset($_REQUEST['ParameterID'])){$ParameterID=$_REQUEST['ParameterID'];}
if (isset($_REQUEST['ParameterCategoryID'])){$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];}
if (isset($_REQUEST['ServiceAmount'])){$ServiceCharge=$_REQUEST['ServiceAmount'];}




//if (isset($_REQUEST['edit']))
//{	
/*	$ServiceGroupID=	$_REQUEST['ServiceGroupID'];
	$ParameterID=$_REQUEST['ParameterID'];
	$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];
	$ServiceCharge=$_REQUEST['ServiceAmount'];*/
	
	$sql = "select ServiceGroupName from ServiceGroup s
			where s.ServiceGroupID=$ServiceGroupID 
			order by s.ServiceGroupID";
			
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceGroupName=$myrow['ServiceGroupName'];
	}	
//}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Checklist</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Service Group</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="ServiceGroupName" id="ServiceGroupName" disabled="disabled"><?php echo $ServiceGroupName; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
          <tr>
           	<td><label>Parameter Category</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ParameterCategoryID"  id="ParameterCategoryID" onchange="loadpage('filter_parameters.php?ParameterCategoryID='+this.options[this.selectedIndex].value,'Params')">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from ChecklistParameterCategories order by 1";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ParameterCategoryID"];
                                $s_name = $row["ParameterCategoryName"];
                                if ($ParameterCategoryID==$s_id) 
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
			  <td><label>Parameter</label>
                    <div class="input-control select" data-role="input-control" id="Params">
                    	<?php include 'filter_parameters.php'; ?>                    
                  </div></td>
			  <td></td>
		  </tr>                               
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('servicechecklist_list.php?'+
                                            '&ParameterID='+this.form.ParameterID.value+
                                            '&ParameterCategoryID='+this.form.ParameterCategoryID.value+ 
                                            '&ServiceGroupID='+<?php echo $ServiceGroupID; ?>+       
        									'&save=1','content','loader','listpages','','ServiceChecklist','+<?php echo $ServiceGroupID; ?>+')" value="Save">
                                            
      <input type="reset" value="Cancel" onClick="loadmypage('service_charges_list.php?ServiceGroupID='+<?php echo $ServiceGroupID; ?>+'','content','loader','listpages','','service_charges','<?php echo $ServiceGroupID; ?>')">
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>