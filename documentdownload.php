<?php
require_once 'DB_PARAMS/connect.php';
require_once 'utilities.php';



$fileID=$_REQUEST["id"];

if(isset($_REQUEST["BusinessDocId"])){
	$BusinessDocId=$_REQUEST["BusinessDocId"];

	$BusinessDocSQL="select DocumentPath  from BusinessAttachements
	 where BusinessRegistationDocID=$BusinessDocId";
	$BusinessDocSQLResult=sqlsrv_query($db,$BusinessDocSQL);
	while($rw=sqlsrv_fetch_array($BusinessDocSQLResult,SQLSRV_FETCH_ASSOC)){
		extract($rw);
	}
	ViewDocumentOnTheBrowser($DocumentPath);

}

$sql="select filePath  from Attachments where ID=$fileID";
$result=sqlsrv_query($db,$sql);
while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
	extract($rw);
}

// exit($filePath);
ViewDocumentOnTheBrowser($filePath);
// output_file($filePath, 'DownloadedFile', 'application/msword');

//Improvement Made by James Kinyua
function ViewDocumentOnTheBrowser($file){
		if(file_exists($file) ){
			// Header content type 
			header('Content-type: application/pdf'); 
			
			header('Content-Disposition: inline; filename="' . basename($file) . '"'); 
			
			header('Content-Transfer-Encoding: binary'); 
			
			header('Accept-Ranges: bytes'); 
			
			// Read the file 
			@readfile($file);
		}else{
					return 'File Does Not Exist';

		}
		
}
//This application is developed by www.webinfopedia.com
//visit www.webinfopedia.com for PHP,Mysql,html5 and Designing tutorials for FREE!!!
function output_file($file, $name, $mime_type='')
{
 /*
 This function takes a path to a file to output ($file),  the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
 */

 //Check the file premission
 // echo "output_file $file"; exit;
 if(!is_readable($file)) die('File not found or inaccessible!');

 $size = filesize($file);
 $name = rawurldecode($name);

 /* Figure out the MIME type | Check in array */
 $known_mime_types=array(
 	"pdf" => "application/pdf",
 	"txt" => "text/plain",
 	"html" => "text/html",
 	"htm" => "text/html",
	"exe" => "application/octet-stream",
	"zip" => "application/zip",
	"doc" => "application/msword",
	"xls" => "application/vnd.ms-excel",
	"ppt" => "application/vnd.ms-powerpoint",
	"gif" => "image/gif",
	"png" => "image/png",
	"jpeg"=> "image/jpg",
	"jpg" =>  "image/jpg",
	"php" => "text/plain"
 );

 if($mime_type==''){
	 $file_extension = strtolower(substr(strrchr($file,"."),1));
	 if(array_key_exists($file_extension, $known_mime_types)){
		$mime_type=$known_mime_types[$file_extension];
	 } else {
		$mime_type="application/force-download";
	 };
 };

 //turn off output buffering to decrease cpu usage
 @ob_end_clean();

 // required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');

 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');

 /* The three lines below basically make the
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

 // multipart-download and download resuming support
 if(isset($_SERVER['HTTP_RANGE']))
 {
	list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
	list($range) = explode(",",$range,2);
	list($range, $range_end) = explode("-", $range);
	$range=intval($range);
	if(!$range_end) {
		$range_end=$size-1;
	} else {
		$range_end=intval($range_end);
	}
	/*
	------------------------------------------------------------------------------------------------------
	//This application is developed by www.webinfopedia.com
	//visit www.webinfopedia.com for PHP,Mysql,html5 and Designing tutorials for FREE!!!
	------------------------------------------------------------------------------------------------------
 	*/
	$new_length = $range_end-$range+1;
	header("HTTP/1.1 206 Partial Content");
	header("Content-Length: $new_length");
	header("Content-Range: bytes $range-$range_end/$size");
 } else {
	$new_length=$size;
	header("Content-Length: ".$size);
 }

 /* Will output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r'))
 {
	if(isset($_SERVER['HTTP_RANGE']))
	fseek($file, $range);

	while(!feof($file) &&
		(!connection_aborted()) &&
		($bytes_send<$new_length)
	      )
	{
		$buffer = fread($file, $chunksize);
		print($buffer); //echo($buffer); // can also possible
		flush();
		$bytes_send += strlen($buffer);
	}
 fclose($file);
 } else
 //If no permissiion
 die('Error - can not open file.');
 //die
die();
}
//Set the time out
set_time_limit(0);

//path to the file

$SetupArray = SystemSetup($db);
$SetupDetails = explode('|',$SetupArray);
$DocumentPath = $SetupDetails[4];
$CustomerID = $_REQUEST['CustomerID'];
$RequiredDocumentID = $_REQUEST['RequiredDocumentID'];
$ComplaintID = $_REQUEST['ComplaintID'];
$IsComplaint = $_REQUEST['IsComplaint'];
$DocumentID = $_REQUEST['DocumentID'];
$RefNumber = $_REQUEST['RefNumber'];
$InternalModuleID = $_REQUEST['InternalModuleID'];
//echo"The $CustomerID";exit;
$FolderCustomerID = str_replace('/','-',$CustomerID);
$DocumentsDetailsArray = getDocumentDetails($db, $DocumentID);
$FullURL = $DocumentsDetailsArray[2];
$Physicalpath = $DocumentsDetailsArray[3];
$IsInternalDocument = $DocumentsDetailsArray[13];
$UserUploaded = $DocumentsDetailsArray[20];
$DOCPhysicalpath = $DocumentsDetailsArray[3];
//echo"InternalModuleID => $InternalModuleID Physicalpath => $Physicalpath IsInternalDocument => $IsInternalDocument <p>";
if($IsComplaint == 1){
	$sql="SELECT AdditionalDocumentFolderName from Documents WHERE DocumentID = '$DocumentID'";
    $result = sqlsrv_query($db, $sql);

    $AdditionalDocumentFolderName = '';
    if ($myrow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $AdditionalDocumentFolderName = $myrow['AdditionalDocumentFolderName'];
    }
	IF(!EMPTY($AdditionalDocumentFolderName))
		$Folder = $RefNumber."-".$RequiredDocumentID."/".$RefNumber."/".$AdditionalDocumentFolderName;
	ELSE
		$Folder = $RefNumber."-".$RequiredDocumentID."/".$RefNumber."/";

}else{
	$Folder = $FolderCustomerID."-".$RequiredDocumentID."/".$RefNumber;
}
if($Physicalpath == ""){
	IF($IsInternalDocument == 1){
		$file_path =$DocumentPath."/".$UserUploaded."/".$InternalModuleID."/".$RefNumber."-".$RequiredDocumentID;
		echo"Teh $file_path";
	}ELSE {

    //TODO DONE: @kharhys
    $req = "SELECT RequiredDocumentName FROM RequiredDocuments WHERE RequiredDocumentID = '$RequiredDocumentID'";
    $res = sqlsrv_query($db, $req);
    if($reqdoc = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC))
      $DocumentTypeName = $reqdoc['RequiredDocumentName'];

    $dir = explode('_', $_REQUEST['filename'])[0];
    if ($DocumentTypeName == 'Additional Documents')
      $file_path = $DocumentPath.$Folder."/".$dir."/".$_REQUEST['filename'];
    else
      $file_path = $DocumentPath.$Folder."/".$_REQUEST['filename'];

	}
}else{
	$file_path = $Physicalpath;
}


?>
