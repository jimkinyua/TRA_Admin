<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$BusinessID=$_REQUEST['BusinessID'];
	$sql="Delete from Businesses where BusinessID=$BusinessID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Business Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$BusinessID=$_REQUEST['BusinessID'];
	$BusinessName=$_REQUEST['BusinessName'];
	$BusinessActivity=$_REQUEST['$BusinessActivity'];	
	$PhoneNo=$_REQUEST['PhoneNo'];
	$WardID=$_REQUEST['WardID'];
	$BusinessOwner=$_REQUEST['BusinessOwner'];
	$IDNo=$_REQUEST['IDNo'];
	
	if ($BusinessID=='0')
	{
		$sql="Insert into Businesses (BusinessName,WardID,BusinessActivity,BusinessOwner,IDNo,PhoneNo,CreatedBY)
		Values('$BusinessName',$WardID,'$BusinessActivity','$BusinessOwner','$IDNo','$PhoneNo',$CreatedUserID)";

	} else
	{
		$sql="Update Businesses set BusinessName='$BusinessName',WardID='$WardID',BusinessActivity='$BusinessActivity',BusinessOwner='$BusinessOwner',IDNO='$IDNo',PhoneNo='$PhoneNo' where BusinessID=$BusinessID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Business Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";				
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
	<link href="css/dataTables.tableTools.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
	
	<link href="css/new/metro.css" rel="stylesheet">
	<link href="css/new/semantic.min" rel="stylesheet">
    <script src="css/new/jquery.js"></script>
    <script src="css/new/metro.js"></script>


    <!-- Load JavaScript Libraries -->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery.widget.min.js"></script>
    <script src="js/jquery/jquery.mousewheel.js"></script>
    <script src="js/prettify/prettify.js"></script>
	<script src="js/metro/metro-datepicker.js"></script>
	
	<script src="js/jquery/jquery.dataTables.js"></script>
	<script src="js/jquery/dataTables.tableTools.js"></script>   

    <!-- Metro UI CSS JavaScript plugins -->
    <script src="js/load-metro.js"></script>

    <!-- Local JavaScript -->
    <script src="js/docs.js"></script>

    <!-- <script src="ckeditor/ckeditor.js"></script> -->
    <link rel="stylesheet" type="text/css" href="ajaxtabs/ajaxtabs.css" />
	
	<script src="scripts.js"></script>

    <title>County Revenue Collection</title>
</head>
<body class="metro">
	<div class="example">
        <legend>Businesss</legend>
<div class="ui accordion">
    <div class="active title">
      <i class="dropdown icon"></i>
      What is a dog?
    </div>
    <div class="active content">
      <p>A dog is a type of domesticated animal. Known for its loyalty and faithfulness, it can be found as a welcome guest in many households across the world.</p>
    </div>
    <div class="title">
      <i class="dropdown icon"></i>
      What kinds of dogs are there?
    </div>
    <div class="content">
      <p>There are many breeds of dogs. Each breed varies in size and temperament. Owners often select a breed of dog that they find to be compatible with their own lifestyle and desires from a companion.</p>
    </div>
    <div class="title">
      <i class="dropdown icon"></i>
      How do you acquire a dog?
    </div>
    <div class="content">
      <p>Three common ways for a prospective owner to acquire a dog is from pet shops, private owners, or shelters.</p>
      <p>A pet shop may be the most convenient way to buy a dog. Buying a dog from a private owner allows you to assess the pedigree and upbringing of your dog before choosing to take it home. Lastly, finding your dog from a shelter, helps give a good home to a dog who may not find one so readily.</p>
    </div>
  </div>

	</div>
</div>