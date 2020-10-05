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

$CustomerID='0';
$surname='';
$othernames='';
$plotno='';
$idno='';
$Mobile='';
$Email='';
$CustomerTypeID='';



if ($_REQUEST['edit']==1) {
    $CustomerID = $_REQUEST['CustomerID']; 

    $sql = "select * 
            from Customer              
            where CustomerID = $CustomerID 
            order by CustomerName";
            
    $result = sqlsrv_query($db, $sql);



    while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
    {
    	$CustomerName=$myrow['CustomerName'];       
    	$idno=$myrow['IDNO'];
    	$CustomerID=$myrow['CustomerID'];
    	$plotno=$myrow['PlotNo'];	
    	$Mobile=$myrow['Mobile1'];
        $CustomerTypeID=$myrow['CustomerTypeID'];        
    	$Email=$myrow['Email'];   	
    }	
}

?>
<script type="text/javascript">
        
 </script>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Customer</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
	       <tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
	       </tr>
			<tr>
				<td>
    				<label>Surname </label>
                     <div class="input-control text" data-role="input-control">
                            <input name="surname" type="text" id="surname" value="<?php echo $CustomerName; ?>" >
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
				</td>
                <td>
                    <label>ID No/Reg No</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="idno" type="text" id="idno" value="<?php echo $idno; ?>" >
                        <button class="btn-clear" tabindex="-1"></button>
                    </div> 
                </td>
				 	
          	</tr>
			<tr>
            		
            <td>
               	  <label>Mobile</label>
               	  <div class="input-control text" data-role="input-control">
                   	  <input name="Mobile" type="text" id="Mobile" value="<?php echo $Mobile; ?>" >
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                <label>Plot Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="plotno" type="text" id="plotno" value="<?php echo $plotno; ?>" >
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>  
                </td>
          	</tr> 			
			<tr>
                
                <td width="50%">
                	<label>Official Email</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" >
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
                <td><label>Account Type</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="CustomerTypeID"  id="CustomerTypeID">
                            <option value="1" selected>Individual</option>                        
                            <option value="2" >Corporate</option>
                        
                      </select>                       
                  </div>
                </td>				

          	</tr>
 			
			                       
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('customers_list.php?'+
        '&Email='+this.form.Email.value+
        '&idno='+this.form.idno.value+
        '&Surname='+this.form.surname.value+       
        '&Mobile='+this.form.Mobile.value+        
        '&plotno='+this.form.plotno.value+
        '&CustomerTypeID='+this.form.CustomerTypeID.value+        
		'&CustomerID='+<?php echo $CustomerID; ?>+
        '&save=1','content','loader','listpages','','Customers')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('customers_list.php?i=1','content','loader','listpages','','Customers')">					 
      									 
        <span class="table_text">
        
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>