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

$ServiceID='';
$SubSystemID='';
$LinkServiceID='0';
$FinancialYearID='';
$ChargeTypeID ='';
$$ServiceCharge='';
$ServiceName='';


if (isset($_REQUEST['ServiceID'])){$ServiceID=$_REQUEST['ServiceID'];}
if (isset($_REQUEST['SubSystemID'])){$SubSystemID=$_REQUEST['SubSystemID'];}
if (isset($_REQUEST['FinancialYearID'])){$FinancialYearID=$_REQUEST['FinancialYearID'];}
if (isset($_REQUEST['ServiceAmount'])){$ServiceCharge=$_REQUEST['ServiceAmount'];}




//if (isset($_REQUEST['edit']))
//{	
/*	$ServiceID=	$_REQUEST['ServiceID'];
	$SubSystemID=$_REQUEST['SubSystemID'];
	$FinancialYearID=$_REQUEST['FinancialYearID'];
	$ServiceCharge=$_REQUEST['ServiceAmount'];*/
	
	$sql = "select ServiceName from services s
			where s.ServiceID=$ServiceID 
			order by s.ServiceID";

			
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceName=$myrow['ServiceName'];
	}	
//}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Service Charge</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Service Name1</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="ServiceName" id="ServiceName" disabled="disabled"><?php echo $ServiceName; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>
           	<td><label>Financial Year</label>
                    <div class="input-contr
          <tr>ol select" data-role="input-control">
                        <select name="FinancialYearID"  id="FinancialYearID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from FinancialYear order by 1";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["FinancialYearID"];
                                $s_name = $row["FinancialYearName"];
                                if ($FinancialYearID==$s_id) 
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
			  <td><label>Sub System</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="SubSystemID"  id="SubSystemID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM SubSystems ORDER BY 1";
						
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["SubSystemID"];
                                $s_name = $row["SubSystemName"];
                                if ($SubSystemID==$s_id) 
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
                	<label>Service Charge</label>
                	<div class="input-control text" data-role="input-control">
                    	<input type="text" name="ServiceCharge" id="ServiceCharge" value="<?php echo $ServiceCharge; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>                              
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('service_charges_list.php?'+
                                            '&SubSystemID='+this.form.SubSystemID.value+
                                            '&FinancialYearID='+this.form.FinancialYearID.value+ 
                                            '&ServiceCharge='+this.form.ServiceCharge.value+ 
                                            '&ServiceID='+<?php echo $ServiceID; ?>+       
        									'&save=1','content','loader','listpages','','service_charges','+<?php echo $ServiceID; ?>+')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('service_charges_list.php?ServiceID='+<?php echo $ServiceID; ?>+'','content','loader','listpages','','service_charges','<?php echo $ServiceID; ?>')">
	  
	  <!-- <input value="Charges" onclick="loadmypage('service_charges_list.php?ServiceID='+1614+'&amp;ServiceName='+this.form.ServiceName.value+'','content','loader','listpages','','service_charges','1614')" type="Button">  -->
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>