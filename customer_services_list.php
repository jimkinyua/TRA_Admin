<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];


 if ($_REQUEST['reset']=="1")
 {

 	$CustomerID=$_REQUEST['CustomerID'];
 	$CustomerName=$_REQUEST['CustomerName'];
 	$IDNO=$_REQUEST['IDNO'];
 	$Email=$_REQUEST['Email'];

 	$sql=
 	"select ag.IDNO 
 	from CustomerAgents ca 
 	join agents ag on ca.AgentID=ag.AgentID 
 	where ca.CustomerID=$CustomerID";
 	$qry=sqlsrv_query($db,$sql);
	 
	 //exit($sql);
	 
 	while($row=sqlsrv_fetch_array($qry,SQLSRV_FETCH_ASSOC))
 	{
 		$IdNO=$row['IDNO'];

 		$sql="exec spResetAccount $IdNO";
       
 		$qr2=sqlsrv_query($db,$sql);
 		if($qr2)
 		{
 			$msg="Account Reset Successfully";
            echo $msg;
 		}else{
            $msg="Reset failed";
        }
 	}
 }


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
	<body class="metro">
			<div class="example">
			<legend>Customer and Services</legend>  
			<form>      
			<table class="table striped hovered dataTable" id="dataTables-1">
				<thead>
                <tr>
                    <!-- <td colspan="6">
                        <td colspan="6" align="center" style="color:#F00"><?php echo $msg; ?></td>
                    </td> -->
                </tr>
				<tr>
                    <td colspan="6">
                        <table width="100%">
                            <tr>
                                
                                <td><label>Customer Name</label>
                                        <div class="input-control text" data-role="input-control">                      
                                            <input type="text" width="100" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName ?>"></input>                
                                        </div>
                                </td>
                                <td><label>ID Number</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="IDNO" name="IDNO" value="<?php echo $IDNO ?>"></input>  
                                    </div>
                                </td>
                                <td><label>Email</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="Email" name="Email" value="<?php echo $Email ?>"></input>             
                                    </div>
                                </td>                                
                                <td><label>&nbsp;</label>
                                <input name="btnSearch" type="button" onclick="loadmypage('customer_services_list.php?'+
                                            '&CustomerName='+this.form.CustomerName.value+                              
                                            '&IDNO='+this.form.IDNO.value+
                                            '&Email='+this.form.Email.value+                                
                                            '&search=1','content','loader','listpages','','Customers','CustomerName='+this.form.CustomerName.value+':IDNO='+this.form.IDNO.value+':Email='+this.form.Email.value+'')" value="Search">
                                </td>
                            <tr>
                        </table>
                    </td>
                </tr>
				<tr>
					<th  class="text-left">CustomerID</th>
					<th  class="text-left">CustomerName</th>
					<th  class="text-left">Mobile No</th>
					<th  class="text-left">Email</th>
					<th  class="text-left">ID No</th>
					<th  class="text-left">No Of Services</th>
					<th  class="text-left"></th>
				</tr>
				</thead>

				<tbody>
				</tbody>
			</table> 
		</form>
	</div>
	</div>


