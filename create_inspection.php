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

?>

<div class="row">
Create a New Inspection
	
<form action="" name="" method="post">

	Select Establishment: 
	<div class="input-control select" data-role="input-control">
	<select name="c_id" required width="48">
        <option value="" selected="selected" >select establishment</option>
        <?php 
		$status_sql = "select CustomerID, CustomerName from Customer";
		$status_result = sqlsrv_query($db, $status_sql) or die ("failed to load Status");

		$selected = '';
	    while ($myrow = sqlsrv_fetch_array( $status_result, SQLSRV_FETCH_ASSOC)) 
	    {
			$c_id = $myrow ['CustomerID'];
			$c_name = $myrow['CustomerName'];
			if ($c_name==$c_id) 
			{
			   	$selected = 'SELECTED';
			} else
			{
				$selected = '';
			}	 
		 	?>
       <option value="<?php echo $c_id;?>"><?php echo $c_name;?></option> 

		
        <?php
	 }
	 ?>
      </select>
		</div>

		Select Service Type: 
	<div class="input-control select" data-role="input-control">
	<select name="s_id" required width="48">
        <option value="" selected="selected" >select Inspection Type</option>
        <?php 
		$status_sql = "select ServiceID,ServiceName from Services where ServiceCategoryID = 2033";
		$status_result = sqlsrv_query($db, $status_sql) or die ("failed to load Status");

		$selected = '';
	    while ($myrow = sqlsrv_fetch_array( $status_result, SQLSRV_FETCH_ASSOC)) 
	    {
			$s_id = $myrow ['ServiceID'];
			$s_name = $myrow['ServiceName'];
			if ($s_name==$s_id) 
			{
			   	$selected = 'SELECTED';
			} else
			{
				$selected = '';
			}	 
		 	?>
       <option value="<?php echo $s_id;?>"><?php echo $s_name;?></option> 

		
        <?php
	 }
	 ?>
      </select>
		</div>

		<input type="hidden" name="ServiceStatusID" value="1">
		<input type="hidden" name="ServiceHeaderType" value="0">
		<input type="hidden" name="ServiceCategoryID" value="2033">
		<input type="hidden" name="formID" value="1021">
		<input type="hidden" name="SubmissionDate" value="<?php echo date('d-m-Y');?>">
				Select Region: 
	<div class="input-control select" data-role="input-control">
	<select name="ss_id" required width="48">
        <option value="" selected="selected" >select Region</option>
        <?php 
		$status_sql = "select SubSystemID,SubSystemName from SubSystems";
		$status_result = sqlsrv_query($db, $status_sql) or die ("failed to load Status");

		$selected = '';
	    while ($myrow = sqlsrv_fetch_array( $status_result, SQLSRV_FETCH_ASSOC)) 
	    {
			$ss_id = $myrow ['SubSystemID'];
			$ss_name = $myrow['SubSystemName'];
			if ($ss_name==$ss_id) 
			{
			   	$selected = 'SELECTED';
			} else
			{
				$selected = '';
			}	 
		 	?>
       <option value="<?php echo $ss_id;?>"><?php echo $ss_name;?></option> 

		
        <?php
	 }
	 ?>
      </select>
		</div>

      <br>

	Notes:
	<div class="input-control textarea" data-role="input-control">
	 <textarea type="text" name="Notes" cols="20"></textarea>
	</div>
	 <br>

	
	<input name="save" type="button" onclick="loadmypage('inspections_list.php?'+
		'&c_id='+this.form.c_id.value+	
		'&s_id='+this.form.s_id.value+	
		'&ServiceStatusID='+this.form.ServiceStatusID.value+
		'&ServiceHeaderType='+this.form.ServiceHeaderType.value+
		'&ServiceCategoryID='+this.form.ServiceCategoryID.value+
		'&SubmissionDate='+this.form.SubmissionDate.value+
		'&formID='+this.form.formID.value+
		'&ss_id='+this.form.ss_id.value+											
		'&Notes='+this.form.Notes.value+
		'&addinspection=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Create Inspection">

</form>

