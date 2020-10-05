<?php
//session_start();
require_once "dblogin.php";
require_once('utilities.php');
$error = '';
$loginmessage = '';
$det='';
if (isset($_REQUEST["submit"]))
{
	$uname = $_REQUEST["uname"];
	$passwd = $_REQUEST["passwd"];
	if ($uname=='')
	{
		$error = "Enter your username";
	} else if (_login($db,$uname,$passwd))
	{
		$UserStatusID = $_SESSION["UserStatusID"];
		/*if ($UserStatusID==1) 
		{ 
			$_SESSION["logged_in"] = 0;
			$error = "Your account has not been activated.  Please Check your email for activation instructions";
		} else
		{*/
			$det="email=".$uname."&password=".$passwd;
			$det=encrypt_url($det);
			$_SESSION["logged_in"] = 1;
			
		//}
	} else
	{		 
		$error = "invalid username or password";
	}
}	

if (isset($_SESSION["logged_in"]))
{
	//echo 'logged in';	
	//echo 'session is '.$_SESSION["logged_in"];
	$logged_in = $_SESSION["logged_in"];
	if ($logged_in==1)
	{
		$UserName = $_SESSION["UserName"];
		$UserID = $_SESSION["UserID"];
		$FullName = $_SESSION["UserFullNames"];
		$loginmessage = $FullName;
	} else
	{
		
	}
} else
{
	//echo 'not logged in';
	$logged_in = 0;
	$loginmessage = '';
}

//print_r($_SESSION);
//print_r (session_name());
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

    <!-- Load JavaScript Libraries -->
	<script src="js/metro.min.js"></script>
	<script type="text/javascript"src="js/jquery/jquery-latest.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.popupwindow.js"></script>

    <script src="js/jquery/jquery.widget.min.js"></script>
    <script src="js/jquery/jquery.mousewheel.js"></script>
    <script src="js/prettify/prettify.js"></script>
    <script src="scripts.js"></script>

    <!-- Metro UI CSS JavaScript plugins -->
    <script src="js/load-metro.js"></script>

    <!-- Local JavaScript -->
    <script src="js/docs.js"></script>    
    <script src="js/jquery/jquery.dataTables.js"></script>
	
	
	<!-- pdf printing -->
	<script type="text/javascript" src="js/media.js"></script> 
	<script src="//cdn.jsdelivr.net/jquery.metadata/2.0/jquery.metadata.min.js"></script> 
	
	<!-- HI CHARTS BI------  -->
	
	<script src="highcharts/js/highcharts.js"></script>
	<script src="highcharts/js/modules/data.js"></script>
	<script src="highcharts/js/modules/drilldown.js"></script> 
	<script src="highcharts/js/highcharts-more.js"></script>
	<script src="highcharts/js/modules/solid-gauge.js"></script>
	<script src="highcharts/js/modules/funnel.js"></script>
	<script src="highcharts/js/modules/exporting.js"></script>
	
	 <script src="js/metro/metro-datepicker.js"></script>
    <script src="js/metro/metro-calendar.js"></script>	

   	<script type="text/javascript">
	var profiles =
	{

		window800:
		{
			height:800,
			width:800,
			status:1
		},

		window200:
		{
			height:200,
			width:200,
			status:1,
			resizable:0
		},

		windowCenter:
		{
			height:300,
			width:400,
			center:1
		},

		windowNotNew:
		{
			height:300,
			width:400,
			center:1,
			createnew:0
		},

		windowCallUnload:
		{
			height:300,
			width:400,
			center:1,
			onUnload:unloadcallback
		},

	};

	function unloadcallback(){
		alert("unloaded");
	};


   	$(document).ready(function(){
   		$(".popupwindow").popupwindow(profiles);
		console.log("from here", $(".popupwindow").popupwindow);
   	});
	</script>
	
    <link rel="stylesheet" type="text/css" href="ajaxtabs/ajaxtabs.css" />

    <title>County Revenue Collection (BI)</title>
</head>
<body class="metro">
    <header class="bg-dark" data-load="header.html"></header>
	<div class="container">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><h1>
        	<!--<a href="/"><i class="icon-arrow-left-3 fg-darker smaller"></i></a> --><small class="on-right">County Revenue Collection (BI)</small></h1></td>
    <td width="50%" align="right"><?php if ($logged_in==1) {?> 
      <span class="icon-user on-left fg-darkBlue"></span><a href="#" onClick="loadpage('userprofile.php?i=1','content')"><?php echo $loginmessage; ?></a>&nbsp;&nbsp;&nbsp;<span class="icon-exit on-left fg-darkBlue"></span><a href="logout.php">Logout</a>&nbsp;&nbsp;&nbsp;<span class="icon-exit on-left fg-darkBlue"></span><a href="curl.php?det=<?php echo $det ?>">Public</a><?php } ?>
      </td>
  </tr>
</table>

   		
         <?php 
		 	if ($logged_in==1)
			{
		 		//include 'menu2.php'; 
			}
			else
			{	
				include 'menu1.php';
			}
				
		?>   
        <div id="loader" style="height:20px"> </div>       
        <div id="content">        
			<?php
			if ($logged_in==1)
			{
				if (isset($_REQUEST['defaultpage']))
				{
					$defaultpage = $_REQUEST['defaultpage'];
					include $defaultpage;
				} else
				{
					$page="<script type='text/javascript'>
						loadmypage2('dashboard.php?i=1','content','loader','listpages','','TestTable','1')
					</script>";
					
					echo ($page);					
				}
			} else{			
				include 'login.php';	}	
			?>
        
        </div>
        <pre class="prettyprint" style="text-align:center">Copyright © 2017</pre>
    </div>
	
</body>
</html>