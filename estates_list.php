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
$EstateID=0;
$ServiceID=0;
if (isset($_REQUEST['save']))
{	
	//print_r($_REQUEST);
	$EstateID=$_REQUEST['EstateID'];
	$EstateName=$_REQUEST['EstateName'];	
	$ServiceID=$_REQUEST['ServiceID'];

	if($ServiceID=='0'){
		$ServiceID='2819';
	}
	
	if ($EstateID=='0')
	{
		$sql="Insert into Estates (EstateName,ServiceID,CreatedBY)
		Values('$EstateName','$ServiceID',$CreatedUserID)";

	} else
	{
		$sql="Update Estates set EstateName='$EstateName' where EstateID=$EstateID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Estate Created/Modified Successfully";			
	} else
	{
		DisplayErrors();
		ECHO $sql;
		$msg = "Details Failed to save";
				
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
		<legend>Estates</legend>        
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
			  <tr>
				<th class="text-left"><a href="#" onClick="loadmypage('estate.php?i=1','content')">Add</a></th>
				<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
			  </tr>
			<tr>
				<th width="14%" class="text-left">EstateID</th>
				<th width="12%" class="text-left">Estate Name</th>
				<th width="12%" class="text-left">&nbsp;</th>
			</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 


	</div>
</body>


