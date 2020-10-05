<?php



?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
	<form>
	<div class="example">        
		<legend>LAIFOMS Permits</legend>		
		<hr>
		<legend>Current Owner(s)</legend> 		
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>
					<th class="text-left"><a href="#" onclick="loadmypage('clients_list.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value,'content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">Approve for <?php echo $CustomerName; ?></a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<th width="20%" class="text-left">Permit No</th>
					<th width="30%" class="text-left">Business Name</th>
					<th width="15%" class="text-left">Date Issued</th>
					<th width="15%" class="text-left">Permit Cost</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
		
	</div>
	</form>
</body>


