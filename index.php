<?php
//session_start();
require_once "dblogin.php";
require_once('utilities.php');
$error = '';
$loginmessage = '';
$det='';
if (isset($_REQUEST["submit"]))
{
	//print_r($_SESSION);
	$uname = $_REQUEST["uname"];
	$passwd = $_REQUEST["passwd"];
	if ($uname=='')
	{
		$error = "Enter your username";
	} else if (_login($db,$uname,$passwd))
	{
		$UserStatusID = $_SESSION["UserStatusID"];		
		$det="email=".$uname."&password=".$passwd;
		$det=encrypt_url($det);
		$_SESSION["logged_in"] = 1;
		
	} else
	{		 
		$error = $_SESSION["fail_reason"];
	}
}	

if (isset($_SESSION["logged_in"]))
{
	$logged_in = $_SESSION["logged_in"];
	$DefaultMenuGroupID=$_SESSION["DefaultMenuGroupID"];
	if ($logged_in==1)
	{
		$UserName = $_SESSION["UserName"];
		$UserID = $_SESSION["UserID"];
		$FullName = $_SESSION["UserFullNames"];
		$loginmessage = $FullName.' ('.$_SESSION["RoleCenterName"].')';
	} else
	{
		
	}
} else
{
	//echo 'not logged in';
	$logged_in = 0;
	$loginmessage = '';
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
	<link href="css/layout.css" rel="stylesheet">
	<link href="css/style_2.css" rel="stylesheet">
	
    <link href="js/prettify/prettify.css" rel="stylesheet">

    <script type="text/javascript">
		var oldSession=<?php echo json_encode($_SESSION['ID']); ?>	

	</script>

    <!-- Load JavaScript Libraries -->
	<script src="js/metro.min.js"></script>

	<script type="text/javascript" src="js/jquery/jquery-latest.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.popupwindow.js"></script>

    <script src="js/jquery/jquery.widget.min.js"></script>
    <script src="js/jquery/jquery.mousewheel.js"></script>
    <script src="js/prettify/prettify.js"></script>
    <script src="scripts.js"></script>
	<script src="js/my_scripts.js"></script>
	<script src="js/renderpage.js"></script>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <!-- Metro UI CSS JavaScript plugins -->
    <script src="js/load-metro.js"></script>

    <!-- Local JavaScript -->
    <script src="js/docs.js"></script>    
    <script src="js/jquery/jquery.dataTables.js"></script>
	<script src="js/metro/metro-calendar.js"></script>
	<script src="js/metro/metro-datepicker.js"></script>
	
	<!-- jspdf -->
	<script src="js/jspdf.min.js"></script>
	<script src="js/jspdf.plugin.autotable.js"></script>

	
	
	
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
	
    <link href="css/select2.min.css" rel="stylesheet" />
	<script src="js/select2.min.js"></script>

	<!-- <script type="text/javascript">
        window.onload = function() {
		    if (window.jQuery) {  
		        // jQuery is loaded  
		        alert("Yeah!");
		    } else {
		        // jQuery is not loaded
		        alert("Doesn't Work");
		    }
		}
    </script> -->

	<script type="text/javascript">
	  //   $(document).ready(function() 
	  //   {
			// 	setInterval(function() 
			// 	{
			// 		$.get('printpermit.php', function(response) {
			// 	  		console.log( "success", response );
			// 		})
			// 			.done(function(response) {
			// 	    	console.log( "second success", response );
			// 	  })

			// 	  .fail(function(error) 
			// 	  {
			// 	    //alert( "error" );
			// 	    console.error(error)
			// 	  })
				  
			// 	  .always(function() {
			// 	    //alert( "finished" );
			// 	  });

			// 	}, 5000)

				
			// });
	</script> 	


    <title>TRA (Admin)</title>
</head>

<body class="metro">
    <header class="bg-dark" data-load="header.html"></header>
	<div class="container">

	

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><h1>
        	<!--<a href="/"><i class="icon-arrow-left-3 fg-darker smaller"></i></a> --><small class="on-right">TRA LICENCING SYSTEM</small></h1></td>
    <td width="50%" align="right"><?php if ($logged_in==1) {?> 
      <span class="icon-user on-left fg-darkBlue"></span><a href="#" onClick="loadpage('userprofile.php?i=1','content')"><?php echo $loginmessage; ?></a>&nbsp;&nbsp;&nbsp;<span class="icon-exit on-left fg-darkBlue"></span><a href="logout.php">Logout</a>&nbsp;&nbsp;&nbsp;<span class="icon-exit on-left fg-darkBlue"></span><a href="curl.php?det=<?php echo $det ?>">Public</a><?php } ?>
      </td>
  </tr>
</table>
   		
     	<?php 
		 	if ($logged_in==1)
		    include 'menu2.php'; 
			else
				include 'menu1.php';					   
		?>   
        <div id="loader" style="height:20px"> </div>       
        <div id="content">        
			<?php
			if ($logged_in==1)
			{
				if (isset($_REQUEST['defaultpage']))
				{
					echo 'Session: '.$_SESSION['RoleCenter'];
					$defaultpage = $_REQUEST['defaultpage'];
					include $defaultpage;
				} else
				{
					$seion_init="<script type='text/javascript'>							
							CheckSession('".$_SESSION['UserID']."');
						</script>";	


					if($DefaultMenuGroupID=='2'){
						$page="<script type='text/javascript'>
							loadmypage('users_list.php?i=1','content','loader','listpages','','users','".$_SESSION['RoleCenter']."','".$_SESSION['UserID']."')
						</script>";							
					}elseif($DefaultMenuGroupID=='1'){
						$page="<script type='text/javascript'>
							loadmypage('clients_list.php?i=1','content','loader','listpages','','applications','".$_SESSION['RoleCenter']."','".$_SESSION['UserID']."')
						</script>";						
					}elseif($DefaultMenuGroupID=='3'){
						$page="<script type='text/javascript'>
							loadmypage('services_list.php?i=1','content','loader','listpages','','services','".$_SESSION['RoleCenter']."','".$_SESSION['UserID']."')
						</script>";						
					}elseif($DefaultMenuGroupID=='5'){
						$page="<script type='text/javascript'>
							loadmypage('mpesa_list.php?i=1','content','loader','listpages','','Mpesa','".$_SESSION['RoleCenter']."','".$_SESSION['UserID']."')
						</script>";						
					}else{
						
					}
					echo ($seion_init);
					echo ($page);					
				}
			} else
				include 'login.php';		
			?>
        
        </div>
        <pre class="prettyprint" style="text-align:center">Copyright © <?= date("Y") ?></pre>
    </div>
	<script>
		$("#datepicker").datepicker();
		$(".chosen").chosen(); 

		// alert ('here');

	</script>
</body>
</html>