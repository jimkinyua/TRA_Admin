<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
/* $HouseNumber='';
$EstateID='';
$currenttenant=''; */

if (isset($_REQUEST['HouseNumber'])) { $HouseNumber = $_REQUEST['HouseNumber']; }
if (isset($_REQUEST['EstateID'])) { $EstateID = $_REQUEST['EstateID']; }
if (isset($_REQUEST['currenttenant'])) { $upn = $_REQUEST['currenttenant']; }



?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
	<div class="example">        
		<legend>LAIFOMS Tenant(s)</legend> 	
		<form>
			<table class="table striped hovered dataTable" id="dataTables-1">
				<thead>
					<tr>
						<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					</tr>
					<tr>
						<td><label>Estate</label>
							<div class="input-control select" data-role="input-control">
								
								<select name="EstateID"  id="EstateID">
									<option value="0" selected="selected"></option>
									<?php 
									$s_sql = "SELECT * FROM Estates ORDER BY EstateName";									
									$s_result = sqlsrv_query($db, $s_sql);
									if ($s_result) 
									{ //connection succesful 
										while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
										{
											$s_id = $row["EstateID"];
											$s_name = $row["EstateName"];
											if ($EstateID==$s_id) 
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
						<td>
							<label>House No</label>
							<div class="input-control text" data-role="input-control">
								<input type="text" id="HouseNumber" name="HouseNumber" value="<?php echo $HouseNumber; ?>"></input>
								<button class="btn-clear" tabindex="-1"></button>
							</div>						
						</td>					
						<td colspan="2">
							<label>Current Tenant</label>
							<div class="input-control text" data-role="input-control">
								<input type="text" id="currenttenant" name="currenttenant" value="<?php echo $currenttenant; ?>"></input>
								<button class="btn-clear" tabindex="-1"></button>
							</div>						
						</td>
						<td></td>
						<td>
							<br><br>
												
							<input name="btnSearch" type="button" onclick="loadmypage('house_from_laifoms.php?'+
							'&EstateID='+this.form.EstateID.value+
							'&HouseNumber='+this.form.HouseNumber.value+
							'&currenttenant='+this.form.currenttenant.value+
							'&search=1','content','loader','listpages','','LAIFOMS_HOUSE_LIST','EstateID='+this.form.EstateID.value+':HouseNumber='+this.form.HouseNumber.value+':currenttenant='+this.form.currenttenant.value+'')" value="Search"> 
						
						</td>
					</tr>
					<tr>
						<th width="20%" class="text-left">Estate Name</th>
						<th width="10%" class="text-left">House No</th>
						<th width="10%" class="text-left">Monthly</th>
						<th width="15%" class="text-left">Balance</th>
						<th width="20%" class="text-left">Tenant</th>
						<th width="15%" class="text-left"></th>							
					</tr>
				</thead>

				<tbody>
				</tbody>
			</table> 
		</form>
		
	</div>
</body>


