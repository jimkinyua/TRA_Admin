<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];
$View=0;
$Edit=0;
$Add=0;
$Delete=0;

//echo $UserID;
/*$PageID=2;
$myRights=getrights($db,$UserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

print_r($myRights);
exit;*/

if (isset($_REQUEST['reset']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	
		$sql="exec spResetInvoice $ApplicationID";
		//echo $sql;
		$result=sqlsrv_query($db,$sql);
		//echo 'sawa';
		if (!$result)
		{			
			$msg="Error Resseting the Invoice";
		}else
		{
			//echo 'no error';
		}
		
	if ($msg="")
	{		
		$msg="Application reset Successfully";
	}	
}



?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

    <script type="text/javascript">
    	$(".datepicker").datepicker();
    </script>



    
	<body class="metro">
        <div class="example">
        <legend>Sent Application Invoices</legend>
		<form>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('clients.php?add=1','content')"></a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
				  <tr>
				  	<td colspan="7">
				  		<table width="100%">
				  			<tr>
				  				<td><label>From Date </label>
										<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">					
											<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>
											<button class="btn-date" type="button"></button>				
										</div>
								</td>
								<td><label>To Date</label>
									<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">						
										<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>
										<button class="btn-date" type="button"></button>		
									</div>
								</td>
								<td><label>Invoice Number</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" id="InvoiceHeaderID" name="InvoiceHeaderID" value="<?php echo $InvoiceHeaderID ?>"></input>
										<button class="btn-clear" type="button"></button>		
									</div>
								</td>
								<td width="20%"><label>Application No</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" id="ServiceHeaderID" name="ServiceHeaderID" value="<?php echo $ServiceHeaderID; ?>"></input>									
									</div>
								</td>
								<td width="20%"><label>Business Name</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>"></input>									
									</div>
								</td>								
								<td><label>&nbsp;</label>
								<input name="btnSearch" type="button" onclick="loadmypage('invoices_list.php?'+
											'&fromDate='+this.form.fromDate.value+								
											'&toDate='+this.form.toDate.value+
											'&search=1','content','loader','listpages','','invoices-a','fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':InvoiceHeaderID='+this.form.InvoiceHeaderID.value+':ServiceHeaderID='+this.form.ServiceHeaderID.value+':CustomerName='+this.form.CustomerName.value+'','<?php echo $_SESSION['UserID']; ?>')" value="Search">
											
											<!--,'fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+' -->
								</td>
				  			</tr>
				  		</table>
				  	</td>				
								  
				</tr>
                <tr>
                    <th width="5%" class="text-left"> Invoice Number</th>
                     <th width="12%" class="text-left">Application ID</th>                   
                    <th width="20%" class="text-left">CustomerName</th>
                    <th width="12%" class="text-left">ServiceName</th>
                    <th width="12%" class="text-left">Invoice Amount</th>
                    <th width="12%" class="text-left">Paid</th>
					<th width="12%" class="text-left">Action</th>
                </tr>
                </thead>
	
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th class="text-left"> Invoice Number</th>
                     <th class="text-left">InvoiceDate</th>                   
                    <th class="text-left">CustomerName</th>
                    <th class="text-left">ServiceName</th>
                    <th class="text-left">Invoice Amount</th>
                    <th class="text-left">Paid</th>
					<th class="text-left"></th>					
                </tr>
                </tfoot>
            </table>
	</form>
	
    </div>
    </div>