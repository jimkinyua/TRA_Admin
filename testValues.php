<?php
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once "dblogin.php";
	require_once('GlobalFunctions.php');
	require_once("dompdf/dompdf_config.inc.php");
	require_once('county_details.php');
	
	
	//$ApplicationID='19';
	/*$feedback=createPdf($db,$ApplicationID,$cosmasRow);								
	echo $feedback;*/
	
	//$feedback=createSBP($db,$ApplicationID,$cosmasRow);
	//echo $feedback;
?>	
<!DOCTYPE html>
	<html>

		<head>
			<script src="js/jquery/jquery.min.js"></script>
			<script src="js/accordion.js"></script>
			<script src="js/semantic.min.js"></script>
			<link rel="stylesheet" type="text/css" class="ui" href="css/New/semantic.min.css"/>
			<script>
				
				$(function() {
					
					$('.ui.accordion')
					.accordion()
					;
					
					var k = adding(1,2);
					//alert(k);
				});
				
				
				function adding(a,b)
				{
					var c = a + b;
					return c;				
				}
			</script>
		</head>
		
		<body id="example" class="accordion pushable" ontouchstart="">
		<div class="ui segment">
			<div class="ui fluid form">
			  <div class="two fields">
				<div class="field">
				  <label>First Name</label>
				  <input placeholder="First Name" type="text">
				</div>
				<div class="field">
				  <label>Last Name</label>
				  <input placeholder="Last Name" type="text">
				</div>
			  </div>
			  <div class="ui accordion field">
				<div class="title">
				  <i class="icon dropdown"></i>
				  Optional Details
				</div>
				<div class="content field">
				  <label>Maiden Name</label>
				  <input placeholder="Maiden Name" type="text">
				</div>
			  </div>
			  <div class="ui secondary submit button">Sign Up</div>
			</div>
		</div>
	</html>

	
	
	 
