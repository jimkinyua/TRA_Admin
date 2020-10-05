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

    $SerialNo="";
    $DevicePin="";
    $CustomerID="";
    $DeviceSerialNo="";
    $CustomerID ="0";
    $MarketID='';
    $UserDeviceID=0;
    $InitialMeterReading;
    $CustomerName='';
    $ReadingDate=date('d-m-Y');
    $LastReading=0;

    if (isset($_REQUEST['CustomerID'])) { $CustomerID = $_REQUEST['CustomerID'];}


	$sql = "SELECT ud.DeviceSerialNo,isnull(ud.DeviceUserID,0)DeviceUserID,ud.InitialMeterReading,isnull(ud.UserDeviceID,0)UserDeviceID,c.CustomerName 
		FROM Customer c
		left join UserDevices ud on ud.CustomerID=c.CustomerID 
		where c.CustomerID = '$CustomerID'";
	
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$SerialNo=$myrow['DeviceSerialNo'];
		$DeviceUserID=$myrow['DeviceUserID'];		
		$CustomerName=$myrow['CustomerName'];
        $InitialMeterReading=$myrow['InitialMeterReading'];
        $UserDeviceID=$myrow['UserDeviceID'];
	}

    $sql="select * from fnLastMeterRecord ($SerialNo)";
    $result=sqlsrv_query($db,$sql);

    while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
        $LastReading=$rw['LastReading'];
    }	
	   

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">



<script type="text/javascript">     
    $(document).ready(function() {
            $("#MeterReading").keydown(function(event) {
                // Allow only backspace and delete
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 110) {
                    // let it happen, don't do anything
                }
                else {
                    // Ensure that it is a number and stop the keypress
                    if (event.keyCode < 48 || event.keyCode > 57 ) {
                        event.preventDefault(); 
                    }   
                }
            });
        });

</script>
<script type="text/javascript">
     $(".datepicker").datepicker();
</script>
<body class="metro">
<div class="example">
<form>
	<fieldset>
	  <legend>Meter Reading</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
		  <tr>
            <td width="50%">
                <label>Customer</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>" disabled></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>                    
            </td>
            <td></td>
          </tr> 
          <tr>
            <td width="50%">
                <label>Meter</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="SerialNo" name="SerialNo" value="<?php echo $SerialNo; ?>" disabled></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>                    
            </td>
            <td></td>
          </tr>
          <tr>
            <td width="50%">
                <label>Last Reading</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="LastReading" name="LastReading" value="<?php echo $LastReading; ?>" disabled></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>                    
            </td>
            <td></td>
          </tr>
          <tr>
            <td width="50%"><label>Reading Date</label>
                <div class="input-control text datepicker" data-role="input-control">                       
                    <input type="text" id="ReadingDate" name="ReadingDate"></input>
                    <button class="btn-date" type="button"></button>                
                </div>
            </td>
          </tr>
          <tr>
            <td width="50%">
                <label>New Meter Reading</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="MeterReading" name="MeterReading" value="<?php echo $MeterReading; ?>" ></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>                    
            </td>
            <td></td>
          </tr> 
        </table>
		<input name="Button" type="button" 
                onclick="
                
                newreading=this.form.MeterReading.value;
                lastreading=this.form.LastReading.value;

                /*alert ('new reading: '+newreading);
                alert ('last Reading: '+lastreading);*/

                if(parseInt(newreading)<parseInt(lastreading)){
                    alert('The new meter reading cannot be less than the last reading');
                }else{
                    loadmypage('meter_statement.php?'+
        			'SerialNo='+this.form.SerialNo.value+ 
                    '&ReadingDate='+this.form.ReadingDate.value+                       
                    '&MeterReading='+this.form.MeterReading.value+    
                    '&CustomerID='+<?php echo $CustomerID ?>+                                           
                    '&save=1','content','loader','listpages','','MeterStatement','<?php echo $SerialNo;  ?>')
                }

                " value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('user_devices_list.php?i=1','content','loader','listpces','','user_devices')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>