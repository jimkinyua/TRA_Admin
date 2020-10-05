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

$OwnerName='';
$MotherPlotNo='';
$RatesPayable='';
$PostalCode='';
$Town='';
$Mobile='';
$Email='';
$Url='';
$QrString='';

$upn='';
$plotno='';
$lrn='';
$titleno='';
$names='';
$ownernames='';
$upn='0';


$Authority='';
if (isset($_REQUEST['plotno'])) { $plotno = $_REQUEST['plotno']; }
if (isset($_REQUEST['lrno'])) { $lrno = $_REQUEST['lrno']; }
if (isset($_REQUEST['upn'])) { $upn = $_REQUEST['upn']; }
if (isset($_REQUEST['owner'])) { $ownernames = $_REQUEST['owner']; }
if (isset($_REQUEST['Authority'])) { $Authority = $_REQUEST['Authority']; }

//print_r ($_REQUEST);
if (isset($_REQUEST['import']))
{
	
}


if (isset($_REQUEST['Search']))
{
	
}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

<body class="metro">
	<div class="example">        
		<legend>IMPORT USER FROM PUBLIC</legend> 
		<form>
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>				
					<th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<td><label>Search By</label>
						<div class="input-control select" data-role="input-control">						
							<select name="SearchBy"  id="SearchBy">
                                <option value="1">ID No</option>
                                <option value="2" selected="selected">Email</option>
                                <option value="3">Names</option>
                           </select> 							
						</div>
					</td>
					<td >
						<label>Value</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="SearchValue" name="SearchValue" value="<?php echo $SearchValue; ?>" autofocus></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>										
					<td>
						<br><br>
											
						 <input name="btnSearch" type="button" onclick="loadmypage('user_import.php?'+
						'&SearchBy='+this.form.SearchBy.value+
						'&SearchValue='+this.form.SearchValue.value+
												'&search=1','content','loader','listpages','','AgentDetails','SearchBy='+this.form.SearchBy.value+':SearchValue='+this.form.SearchValue.value+'','<?php echo $_SESSION['UserID']; ?>')" value="Search"> 
					
					</td>
					<td></td>
				</tr>				
				<tr>
					<th  class="text-left">IDNO</th>
					<th  class="text-left">Names</th>
					<th  class="text-left">Email</th>					
					<th  class="text-left"></th>
				</tr>
			</thead>

			<tbody>
				<tbody>
					<?php
						echo $mdata;
					?>
                <tbody>			
			</tbody>
		</table> 
		<form>
	</div>
</body>


