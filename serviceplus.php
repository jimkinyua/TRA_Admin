<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

print_r($_POST);

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$A_ServiceID="0";
$A_serviceName="";
$ServiceID="";
$ServicePlusID=0;
$Amount=0;

$A_ServiceID=$_REQUEST['A_ServiceID'];
$ServicePlusID=isset($_REQUEST['ServicePlusID'])?$_REQUEST['ServicePlusID']:0;

if($ServicePlusID==0){
   $sql="select s.ServiceName, '0' service_add,0 Amount from 
    Services s  
    where s.ServiceID=$A_ServiceID"; 
}else{
   $sql="select s.ServiceName,sp.service_add,Amount from 
    Services s left join ServicePlus sp on sp.ServiceID=s.ServiceID 
    where sp.serviceplusid=$ServicePlusID"; 
}


$result=sqlsrv_query($db,$sql);
$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if ($result)
{
	$A_serviceName=$rw['ServiceName'];
    $Service_Add=$rw['service_add'];
    $Amount=$rw['Amount'];
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add Accompanying Services</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Applied Service</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="A_serviceName" name="A_serviceName" value="<?php echo $A_serviceName; ?>" disabled></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Accompanying Service (Fees)</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceID"  id="ServiceID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Services s join ServiceGroup sg on s.ServiceGroupID=sg.ServiceGroupID 
                            where sg.PrimaryService=0 
                            ORDER BY ServiceID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["ServiceID"];
                                    $s_name = $row["ServiceName"];
                                    if ($Service_Add==$s_id) 
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
                <label>Fees Amount</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" name="Amount" id="Amount" value="<?php echo $Amount; ?>"></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div>
            </td>
            <td width="50%">

                </td>
        </tr> 
                                    
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('serviceplus_list.php?'+
													'&A_ServiceID='+<?php echo $A_ServiceID; ?>+
                                                    '&ServiceID='+this.form.ServiceID.value+ 
                                                    '&Amount='+this.form.Amount.value+                                                   
													'&ServicePlusID='+<?php echo $ServicePlusID; ?>+
                                                    '&save=1','content','loader','listpages','','ServicePlus','<?php echo $A_ServiceID; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('serviceplus_list.php?A_ServiceID=<?php echo $A_ServiceID; ?>','content','loader','listpages','','ServicePlus','<?php echo $A_ServiceID; ?>')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>