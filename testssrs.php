<?php
require_once 'ssrsphp/SSRSReport.php';
require_once 'ssrsphp/reportserver_config.php';

header('Content-type: text/html; charset=utf-8');



try

{
    $ssrs_report = new SSRSReport(new Credentials(UID, PASWD), SERVICE_URL);     
    $catalogItems = $ssrs_report->ListChildren("/", true);

    $reports = array();
    
   
    
    foreach ($catalogItems as $catalogItem) {

       if ($catalogItem->Type == ItemTypeEnum::$Report) 
       {

            //echo 'here';

           $reports[] = array(

                                "Name" => $catalogItem->Name,

                                "Path" => $catalogItem->Path

                             );

        }   

    }           
    
}

catch (SSRSReportException $serviceException)

{
    echo 'Shida';

    echo $serviceException->GetErrorMessage();

} 

print_r($reports);

?>   