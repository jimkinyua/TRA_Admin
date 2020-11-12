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

		$sql = "select * from (SELECT sh.ServiceHeaderID,c.CustomerName,s.ServiceName,ss.ServiceStatusName,sh.ServiceStatusID,
				ins.InspectionID, ROW_NUMBER() OVER (
				                     PARTITION BY sh.ServiceHeaderID
				                     ORDER BY ins.InspectionID DESC
				         	   ) AS [ROW NUMBER]
				 FROM ServiceHeader AS sh 
				INNER JOIN Services AS s ON sh.ServiceID = s.ServiceID 
				 inner join ServiceCategory sc on sc.ServiceCategoryID = sh.ServiceCategoryID
				inner Join ServiceGroup sg on sg.ServiceGroupID = sc.ServiceGroupID
				 INNER JOIN Customer AS c ON sh.CustomerID = c.CustomerID 
				 INNER JOIN ServiceStatus ss ON sh.ServiceStatusID=ss.ServiceStatusID 
				 INNER JOIN Inspections ins on ins.ServiceHeaderID=sh.ServiceHeaderID 
				 JOIN Users u on u.AgentID=ins.UserID where ins.InspectionStatusID>0 and sh.ServiceStatusID !=1 and sc.ServiceGroupID=11)
				groups
				WHERE groups.[ROW NUMBER] = 1
				ORDER BY groups.InspectionID DESC";
					// exit($sql);		
			?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<div class="row">

	<h2>Classification and Grading Applications</h2>

	<table id="example" class="table striped hovered dataTable" style="width:100%">
                <thead>
                  <tr>
                    <th  class="text-left"> Establishment Name</th>
                    <th  class="text-left"> Classification Type</th>
                    <th  class="text-left"> Verdict</th>
                   </tr>
                
                </thead>
                <?php
                $result=sqlsrv_query($db,$sql);
				while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
					$CustomerName = $row['CustomerName'];
					$ServiceName = $row['ServiceName'];
					$ApplicationID	= $row['ServiceHeaderID'];
					// $ServiceStatusName = $row['ServiceStatusName'];
					$ServiceStatusID = $row['ServiceStatusID'];

                		if($ServiceStatusID == 4){
                			$ServiceStatusName == 'Graded';
                		}else{
                			$ServiceStatusName == 'In Progress';
                		}

                		
					?>
                <tr>
                	<td><?php echo $CustomerName; ?></td>
                	<td>
                		 <input name="Button" type="button" onclick="loadmypage('Classification_inspection_list.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="<?php echo $ServiceName; ?>">
                			
                		</td>
                		<td><?php
                		if($ServiceStatusID == 4){
                			echo 'Graded';
                		}elseif($ServiceStatusID==1){
                			echo 'Re-Inspection';
                		}elseif($ServiceStatusID==6){
                			echo 'Failed';
                		}else{
                			echo 'In Progress';
                		}
                		 
                		  ?></td>
                	
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


