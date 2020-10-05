<?php
	
	require_once 'ssrsphp/SSRSReport.php';
	$settings = parse_ini_file("ssrsphp/app.config", 1);

	$ssrs_report = new SSRSReport(new Credentials($settings["UID"], $settings["PASWD"]),$settings["SERVICE_URL"]);

	$catalogItems = $ssrs_report->ListChildren("/", true);

	$list='';

     $reports = array();
     
     foreach ($catalogItems as $catalogItem)
     {
        if($catalogItem->Type == "Report") 
        {
            $controls .= "<option value='$catalogItem->Path' label='$catalogItem->Name' >".$catalogItem->Name."</option>";
    		$value=$catalogItem->Path;                

            $sth="loadmypage('reportdisplay.php?reportName=$value','content')";
         	$list.="<li><a href='#' onclick=$sth>".$catalogItem->Name."</a></li>";
        }
     }
     
?>

<div class="example" >
    
    <h3>System Reports</h3>
    <ul id="reportslist">
    	<?php echo $list; ?>
    </ul>
    <!-- <form name="reportForm" id="reportForm" method="POST" action="GetReports.php">
    	
    </form> -->

    <script type="text/javascript">
        function exportType(value)
        {
            if(value.match("HTML."))
                exportReportDiv.style.visibility = 'hidden';
            else
                exportReportDiv.style.visibility = '';
        }

        function getParameters()
        {
            reportForm.parameters.value = true;
            reportForm.submit();
        }

        function setReport(report)
        {
        	
            if(report != "")
            {            	
                reportForm.reportName.value = report;
                reportForm.submit();
            }
        }

        function renderReport()
        {
            
            value = reportForm.exportSelect.value;
            reportForm.parameters.value = false;
            if(reportForm.exportName.value == "" && !value.match("HTML."))
            {
                alert("Please enter a name for the report!");
                return;
            }

            if(value.match("HTML."))
                reportForm.action = "GetReports.php";
            else
                reportForm.action = "ssrsphp/Download.php";
            reportForm.submit();
        }

        function submitenter(e)
        {
            var keycode;
            if (window.event) keycode = window.event.keyCode;
            else if (e) keycode = e.which;
            else return true;

            if (keycode == 13)
            {
                renderReport();
                return false;
            }
            else
                return true;
        }
          
     </script>
    	
</div>