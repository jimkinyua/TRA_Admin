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
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

		$sql = "set dateformat dmy SELECT top 100 sh.ServiceHeaderID 
				AS ApplicationID,sh.ServiceStatusID,ss.ServiceStatusName,sc.ServiceGroupID, s.ServiceName ,c.CustomerID, 
				c.CustomerName, sh.SubmissionDate,s.ServiceID,f.ServiceHeaderType ApplicationType,s.ServiceCategoryID,s.ServiceCategoryID 
				FROM dbo.ServiceHeader AS sh 
				INNER JOIN dbo.Services AS s ON sh.ServiceID = s.ServiceID 
				INNER JOIN dbo.Customer AS c ON sh.CustomerID = c.CustomerID 
				INNER JOIN dbo.ServiceStatus ss ON sh.ServiceStatusID=ss.ServiceStatusID 
				INNER JOIN DBO.ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID 
				inner join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID 
				INNER JOIN dbo.Forms f on sh.FormID=f.FormID 
				where sh.ServiceStatusID in (2,6) and sc.ServiceGroupID = 12 
				and (sc.InvoiceStage<>sc.LastStage or sh.ServiceStatusID<>sc.LastStage) 
				and sh.ServiceID not in (select ServiceID from ServiceTrees) order by sh.SubmissionDate desc";
							
			?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<div class="row">

	<h2>Trade and Facilitation Applications</h2>

	<table id="example" class="table striped hovered dataTable" style="width:100%">
                <thead>
                  <tr>
                    <th  class="text-left"> Client Name</th>
                    <th  class="text-left"> Application</th>
                    <th  class="text-left"> Status</th>
                   </tr>
                
                </thead>
                <?php
                $result=sqlsrv_query($db,$sql);
				while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
					$CustomerName = $row['CustomerName'];
					$ServiceName = $row['ServiceName'];
					$ServiceStatusName = $row['ServiceStatusName'];
					$ApplicationID	= $row['ApplicationID'];
					if($ServiceStatusName == trim('Inspection')){
						$ServiceStatusName = 'Approved';
					}else{
						$ServiceStatusName = $row['ServiceStatusName'];
					}
					?>
                <tr>
                	<td><?php echo $CustomerName; ?></td>
                	<td>
                		 <input name="Button" type="button" onclick="loadmypage('tradefacilitation.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="<?php echo $ServiceName; ?>">
                			
                		</td>
                	<td><?php echo $ServiceStatusName; ?></td>
                </tr>
             <?php }?>
            </table>
   
	<script type="text/javascript">
		$(document).ready(function() {
    $('#example').DataTable( {
        "pagingType": "full_numbers"
    } );
} );
	</script>
	  

		</div>


