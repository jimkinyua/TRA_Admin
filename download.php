<?php
 

//print_r($_REQUEST); exit;


    $ID=$_REQUEST['ID']; 
    $mfile= 'Block '.$ID; 
    zipFilesAndDownload($mfile);

    


function zipFilesAndDownload($FirmName)
{
    $file_path='/pdfdocs/DemandNotices/';
    $zip = new ZipArchive();

    // if (strpos($a, 'are') !== false) {
    //     echo 'true';
    // }

    $zip_name = $FirmName.".zip"; // Zip name


    $zip->open($zip_name,  ZipArchive::CREATE);

    //echo $zip_name;exit;

    foreach (new DirectoryIterator(getcwd().$file_path) as $file) 
    {
        
      $path = getcwd().$file_path.$file;
      echo 'w';
      if ($file->isFile()) {   
        if(strstr($file,$FirmName)) $zip->addFromString(basename($path),  file_get_contents($path));          
      }
    }
    
    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='".$zip_name."'");
    header('Content-Length: ' . filesize($zip_name));
    header("Location: ".$zip_name);
    
}