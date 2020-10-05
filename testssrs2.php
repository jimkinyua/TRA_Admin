<?php 
// include the SSRS library
require_once 'ssrsphp/SSRSReport.php';
require_once 'ssrsphp/reportserver_config.php';

function getPageURL()
{
    $PageUrl = $_SERVER["HTTPS"] == "on"? 'https://' : 'http://';
    $uri = $_SERVER["REQUEST_URI"];
    $index = strpos($uri, '?');
    if($index !== false)
    {
    $uri = substr($uri, 0, $index);
    }
    $PageUrl .= $_SERVER["SERVER_NAME"] .
                ":" .
                $_SERVER["SERVER_PORT"] .
                $uri;
    return $PageUrl;
}


// define("UID", "SERVER1\cosmas.ngeno");

// define("PASWD", "Attain12345!!");

// define("SERVICE_URL", "http://localhost/ReportServer");

define("REPORT", "/Reports/Revenue/PermitsIssued");

try

{
    $rs = new SSRSReport(new Credentials(UID, PASWD), SERVICE_URL);


    $executionInfo = $rs->LoadReport2(REPORT, NULL);


    $parameters = array();
    $parameters[0] = new ParameterValue();
    $parameters[0]->Name = "ServiceCode";
    $parameters[0]->Value = "110";
    $parameters[1] = new ParameterValue();
    $parameters[1]->Name = "fromDate";
    $parameters[1]->Value = "08/14/2017";
    $parameters[2] = new ParameterValue();
    $parameters[2]->Name = "toDate";
    $parameters[2]->Value = "08/18/2017";
    
    $rs->SetExecutionParameters2($parameters);


    // Require the Report to be rendered in HTML format
$renderAsHTML = new RenderAsHTML();

// Set the links in the reports to point to the php app

$renderAsHTML->ReplacementRoot = getPageURL();

// Set the root path on the server for any image included in the report
$renderAsHTML->StreamRoot = './images/';

// Execute the Report
$result_html = $rs->Render2($renderAsHTML,
                                 PageCountModeEnum::$Actual,
                                 $Extension,
                                 $MimeType,
                                 $Encoding,
                                 $Warnings,
                                 $StreamIds);

// Save all images on the server (under /images/ dir)
    foreach($StreamIds as $StreamId)
    {
        $renderAsHTML->StreamRoot = null;
        $result_png = $rs->RenderStream($renderAsHTML,
                                    $StreamId,
                                    $Encoding,
                                    $MimeType);

        if (!$handle = fopen("./images/" . $StreamId, 'wb'))
        {
            echo "Cannot open file for writing output";
            exit;
        }

        if (fwrite($handle, $result_png) === FALSE)
        {
            echo "Cannot write to file";
            exit;
        }
        fclose($handle);
    }
// include the Report within a Div on the page
echo '<html><body><br/><br/>';
echo '<div align="center">';
echo '<div style="overflow:auto; width:700px; height:600px">';
    echo $result_html;
echo '</div>';
echo '</div>';
echo '</body></html>';

}

catch(SSRSReportException $serviceException)

{

    echo $serviceException->GetErrorMessage();

}
