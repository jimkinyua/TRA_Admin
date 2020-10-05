<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

//echo 'My Ip:'.GetRemoteMac();

$AgentID=$_REQUEST['AgentID'];
$fromDate=$_REQUEST['fromDate'];
$toDate=$_REQUEST['toDate'];


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
		<legend>USER TRANSACTION LOGS</legend> 
		<form>       
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
			  	<td colspan="4">
			  		<table width="100%">
			  			<tr>
						<td><label >Agent/User</label>
							<div class="input-control select" data-role="input-control">								
								<select name="AgentID"  id="AgentID">
									<option value="0" selected="selected"></option>
									<?php 
									$s_sql = "select distinct ag.AgentID,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName Names 
											from Users u 
											join Agents ag on u.AgentID=ag.AgentID 
											order by ag.FirstName+' '+ag.MiddleName+' '+ag.LastName";									
									$s_result = sqlsrv_query($db, $s_sql);
									if ($s_result) 
									{ //connection succesful 
										while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
										{
											$s_id = $row["AgentID"];
											$s_name = $row["Names"];
											if ($AgentID==$s_id) 
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
							</div>
						</td>
						<td><label>From Date</label>
								<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">						
									<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>		<button class="btn-date" type="button"></button>					
								</div>
						</td>
						<td><label>To Date</label>
							<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">						
								<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>
								<button class="btn-date" type="button"></button>								
							</div>
						</td>
						
						<td><label>&nbsp;</label>
						<input name="btnSearch" type="button" onclick="loadmypage('logs_list.php?'+
									'&AgentID='+this.form.AgentID.value+	
									'&fromDate='+this.form.fromDate.value+								
									'&toDate='+this.form.toDate.value+
									'&search=1','content','loader','listpages','','UserLogs','AgentID='+this.form.AgentID.value+':fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+'')" value="Search">
						</td>			  
					</tr>
			  		</table>
			  	</td>
			  </tr>

			<tr>
				<th  class="text-left" width="30%">User</th>
				<th  class="text-left" width="30%">Transaction Description</th>
				<th  class="text-left" width="20">Workstation Mac Address</th>
				<th  class="text-left" width="20%">Date/Time</th>				
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 
	</form>
	</div>
</body>


