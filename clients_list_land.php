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
$CustomerEmail='';
$Sawa=false;
$Remark='';
$UserID = $_SESSION['UserID'];
$ServiceHeaderType='';
$mail=false;
$InvoiceNo='';
$SubSystemID=1;
$lrn='';
$plotno='';

if (isset($_REQUEST['delete']))
{
	$ApplicationID=$_REQUEST['ApplicationID'];
	$ServiceStatus=$_REQUEST['ServiceStatusID'];
	
	if($ServiceStatusID>'4')
	{
		$msg="The application has been approoved and thus cannot be deleted";
	}else
	{
		$sql="Delete from serviceheader where ServiceheaderID=$ApplicationID";
		$result=sqlsrv_query($db,$sql);
		if ($result)
		{
			$sql="Delete from ServiceApprovalActions where ServiceheaderID=$ApplicationID";
			$result1=sqlsrv_query($db,$sql);
			if ($result1)
			{
				
			}else
			{
				$msg="Error Deleting the Service actions";
			}
		}else
		{
			$msg="Error Deleting the Application";
		}		
	}	
	if ($msg="")
	{
		$msg="Application Deleted Successfully";
	}
	
}


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
	<script src="js/metro/metro-datepicker.js"></script>   
	<script src="js/metro/metro-calendar.js"></script>	

	<script type="text/javascript">
	    	$(".datepicker").datepicker();
	    </script>

    
<body class="metro">
        <div class="example">
        <legend>Land Rates Applications</legend>
		<form>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('clients.php?add=1','content')"></a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
				<tr>					
					<td><label>From Date</label>
							<div class="input-control text datepicker" data-role="input-control">						
								<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>	
								<button class="btn-date" type="button"></button>			
							</div>
					</td>
					<td><label>To Date </label>
						<div class="input-control text datepicker" data-role="input-control">						
							<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>	
							<button class="btn-date" type="button"></button>			
						</div>
					</td>
					<td><label>Block</label>
						<div class="input-control text" data-role="input-control">						
							<input type="text" id="lrn" name="lrn" value="<?php echo $lrn ?>"></input>						
						</div>
					</td>
					<td><label>Plot No</label>
						<div class="input-control text" data-role="input-control">						
							<input type="text" id="plotno" name="plotno" value="<?php echo $plotno ?>"></input>											
						</div>
					</td>
					
					<td><label>&nbsp;</label>
					<input name="btnSearch" type="button" onclick="loadmypage('clients_list_land.php?'+
								'&fromDate='+this.form.fromDate.value+								
								'&toDate='+this.form.toDate.value+
								'&lrn='+this.form.lrn.value+
								'&plotno='+this.form.plotno.value+
								'&search=1','content','loader','listpages','','applications_land','rolecenter=<?php echo $_SESSION['RoleCenter']; ?>:fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':lrn='+this.form.lrn.value+':plotno='+this.form.plotno.value+'')" value="Search">
					</td>			  
				</tr>
                <tr>
                    <th  class="text-left"> ID</th>
                    <th  class="text-left">CustomerName</th>                   
                    <th  class="text-left">Service Name</th>
                    <th  class="text-left">Application Date</th>
                    <th  class="text-left">Current Status</th>
					
                </tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th class="text-left">Application ID</th>
                    <th class="text-left">Customer Name</th>                    
                    <th class="text-left">Service Name</th>
                    <th class="text-left">Application Date</th>   
                    <th class="text-left">Current Status</th>   
					
                </tr>
                </tfoot>
            </table>
		</form>

		</div>
</body>