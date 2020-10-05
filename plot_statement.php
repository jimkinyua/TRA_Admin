<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
//require('class_test.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

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
    <div class="dataTables-1">
        <legend>Statement</legend>
        <form>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>                  
                <tr>
                    <th  class="text-left">Date Received</th>
					<th  class="text-left">Bill No</th>
                    <th  class="text-left">Description</th>
                    <th  class="text-left">Amount</th>   
                    <th  class="text-left">Principal</th>
                    <th  class="text-left">Penalty</th>
                    <th  class="text-left">Pen. Balance</th>
					<th  class="text-left">Grnd Rent</th>
                    <th  class="text-left">Other Charges</th>
                    <th  class="text-left">Balance</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </form>
    </div>
</body>