<?php
	
	require_once 'ssrsphp/SSRSReport.php';
    $settings = parse_ini_file("ssrsphp/app.config", 1);

    $ssrs_report = new SSRSReport(new Credentials($settings["UID"], $settings["PASWD"]),$settings["SERVICE_URL"]);

    $reportname='';

    //print_r($_REQUEST); exit;

	if(key_exists("reportName", $_REQUEST))
     { 

         $query = $_REQUEST["reportName"];  
         $reportname = $_REQUEST["reportName"]; 

         $parmVals = getReportParameters();
        
         //this makes it easier to access the stored values below
         $arr = array();
         if(!empty ($parmVals))
         {
             //print_r($parmVals); exit;
             foreach ($parmVals as $key => $val)
             {
                 //error checking code to print the values retrieved
                 //echo "\n<br />parameters[$key]=$val->Name:$val->Value";

                 $arr[$val->Name] = $val->Value;
             }
         }

         //get report parameters based on either defaults or changed values
         $reportParameters = $ssrs_report->GetReportParameters($query, null, true, $parmVals, null);
          


         $i=0;
         $i=0;
         //$controls .= "\n<div class='example' style='float: right; width: 100px;'>";
         // $controls .= "\n<input type='button' onclick='renderReport();' value='Submit' style='float: right;' />";
         // $controls .= getExportFormats($ssrs_report);
         // $controls .= "\n<a style='float: right;' href='GetReports.php'>Choose Report</a>";
         // $controls .= "\n</div>";
         $controls .= "\n<table class='table striped hovered dataTable' id='tableToolsTable' width='100%'>";
         $controls .="\n<tr>";

         $columns=sizeof($reportParameters);

         foreach($reportParameters as $reportParameter)
         {
             //are we opening or continuing a row?
             if($i%2 == 0)
                 $controls .= "<td>";
             else
                 $controls .= "<td>";

             //get the default value
             $default = null;
             foreach($reportParameter->DefaultValues as $vals)
                 foreach($vals as $key=>$def)
                     $default = $def;

             
             $controls .= $reportParameter->Name . "</td><td>";

             //If there is a list, then it needs to be a Select box
             if(sizeof($reportParameter->ValidValues) > 0)
             {
                //print_r('One'); exit;
                 $dependencies = empty($reportParameter->Dependencies) ? "onchange='getParameters();'" : "";
                 $controls .= "\n<select name='$reportParameter->Name'>";
                 foreach($reportParameter->ValidValues as $values)
                {
                    //choose the default value only if nothing is set
                    //print_r($reportParameter); exit;

                     if($parmVals == null)
                         $selected = ($values->Value == $default)
                                         ? "selected='selected'"
                                         : "";
                     else
                         $selected = (key_exists($reportParameter->Name, $arr) && $values->Value == $arr[$reportParameter->Name])
                                         ? "selected='selected'"
                                         : "";
                     $controls .= "\n<option value='" . $values->Value . "' label='" . $values->Label . "' $selected>". $values->Label ."</option>";
                    
                }
                 $controls .= "\n</select\n>";
             }
             //Boolean needs to be a CheckBox
             else if($reportParameter->Type == "Boolean")
             {
                
                 //choose the default value only if nothing is set
                 if($parmVals == null)
                     $selected = (!empty($default) && $default != "False")
                                     ? "checked='checked'"
                                     : "";
                 else
                     $selected = (key_exists($reportParameter->Name, $arr) && !empty($arr[$reportParameter->Name]))
                                     ? "checked='checked'"
                                     : "";
                 $controls .= "\n<input name='$reportParameter->Name' type='checkbox' $selected/>";
             }else if($reportParameter->Type == "DateTime") //it is a date time value
             {
                
                 //choose the default value only if nothing is set
                 if($parmVals == null)
                     $selected = (!empty($default) && $default != "False")
                                     ? "checked='checked'"
                                     : "";
                 else
                     $selected = (key_exists($reportParameter->Name, $arr) && !empty($arr[$reportParameter->Name]))
                                     ? "checked='checked'"
                                     : "";
                 $controls .="<div class='input-control text datepicker' data-role='input-control'>";
                 $controls .= "\n<input id='$reportParameter->Name' name='$reportParameter->Name' type='text'/>";
                 $controls .="<button class='btn-date' type='button'></button>";
                 $controls .="</div>";
             }
             //the other types should be entered in TextBoxes (DateTime, Integer, Float)
             else
             {

                 //choose the default value only if nothing is set
                 if($parmVals == null)
                     $selected = (!empty($default))
                                     ? "value='" . $default . "'"
                                     : "";
                 else
                     $selected = (key_exists($reportParameter->Name, $arr) && !empty($arr[$reportParameter->Name]))
                                     ? "value='" . $arr[$reportParameter->Name] . "'"
                                     : "";
                 $controls .= "\n<input name='$reportParameter->Name' type='text' $selected/>";
             }


             //same deal, are we continuing or closing a row?
             if($i%2 == 0)
                 $controls .= "</td>";
             else
                 $controls .= "</td>";
             $i++;
         }

         $controls .="<td>";
         $controls .= "\n<div style='float: right; width: 100px;'>";
        
         $sth="loadmypage('reportdisplay.php?reportName=$reportname&paramss=getParameters()','content')";
         //$list.="<li><a href='#' onclick=$sth>".$catalogItem->Name."</a></li>";

         $controls .= "\n<input type='button' onclick=renderReport() value='View' style='float: right;' />";
         //$controls .= "\n<input type='button' onclick=$sth value='View' style='float: right;' />";
         $controls .= getExportFormats($ssrs_report);
         $controls .= "\n<a style='float: right;' href='reportdisplay.php'>Choose Report</a>";
         $controls .= "\n</div>";
         $controls .="</td>";
         $controls .="\n</tr>";
         $controls .= "\n</table>";

         $controls .= "\n<input type='hidden' value='' name='parameters' id='parameters' />";
         $controls .= "\n<div id='exportReportDiv' style='visibility: hidden; background: gray; width: 700px;' >";
         $controls .= "\nExport Name: <input name='exportName' type='text' onkeypress='submitenter(event);' />";
         $controls .= "\n</div>";
     }


     function getReportParameters()
     {  


            if(key_exists("parameters", $_REQUEST))
            {              
                 $parameters = array(); 
                 $i=0;
                 foreach($_REQUEST as $key => $post)
                 {
                     if($key == "reportName")
                         continue;
                     if($key == "parameters")
                         continue;
                     if($key == "exportSelect")
                         continue;
                     if($key == "exportName")
                         continue;
                     if($key == "params")
                         continue;
                     if(strpos($key,'rc:') === 0)
                        continue;
                     if(strpos($key,'rs:') === 0)
                        continue;
                     if(strpos($key,'ps:') === 0)
                        continue;
                     if(!empty($post))
                     {
                        //print_r($post); exit;
                         $parameters[$i] = new ParameterValue();
                         $parameters[$i]->Name = $key;
                         $parameters[$i]->Value = $post;
                         $i++;
                     }
                     if($i > 100)
                         break;
                }                 
                 return $parameters;
            }
            else
                return null;
    }

    function getExportFormats($ssrs_report)
    {
        $extensions = $ssrs_report->ListRenderingExtensions();
        $result = array();
        foreach($extensions as $extension)
        {
            $result[] = $extension->Name;
        }

        $controls = "Export Format: <select id='exportSelect' name='exportSelect' onchange='exportType(value)' >";
        foreach ($result as $format)
        {
            $selected = ($format == "HTML4.0")
                            ? "selected='selected'"
                            : "";

            if($format != "RGDI" && $format != "RPL")
                $controls .= "\n<option value='$format' label='$format' $selected>".$format." </option>";
        }
        $controls .= "\n</select>";
        return $controls;
    }
     


?>
 <script type="text/javascript">
            $(".datepicker").datepicker();
</script>
    <div class="example" >    
        <form name="reportForm" id="reportForm" method="POST" action="reportdisplay.php">
            <?php echo $controls; ?>  
        </form> 
    </div>   	
</body>

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
            alert(reportForm.parameters);

            reportForm.submit();
        }

        function setReport(report)
        {
            
            if(report != "")
            {               
                reportForm.reportName.value = report;
                return reportForm.submit();
            }
        }

        function renderReport()
        {

            $('#reportForm').submit(function() { // catch the form's submit event
                $.ajax({ // create an AJAX call...
                    data: $(this).serialize(), // get the form data
                    type: $(this).attr('method'), // GET or POST
                    url: $(this).attr('action'), // the file to call
                    success: function(response) { // on success..
                        $('#content').html(response); // update the DIV
                    }
                });
                return false; // cancel original event to prevent form submitting
            });
            
            
            // value = reportForm.exportSelect.value;
            // reportForm.parameters.value = false;
            // if(reportForm.exportName.value == "" && !value.match("HTML."))
            // {
            //     alert("Please enter a name for the report!");
            //     return;
            // }

            // if(value.match("HTML."))

            //     reportForm.action = "GetReports.php";
            // else
            //     reportForm.action = "ssrsphp/Download.php";
            // reportForm.submit();
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
    