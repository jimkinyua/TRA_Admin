<?php

require_once 'DB_PARAMS/connect.php';
require_once 'utilities.php';
$UploadDirectory	= 'C:/COSBACKUP/Dev/County/'; //Upload Directory, ends with slash & make sure folder exist
$SuccessRedirect	= 'success.html'; //Redirect to a URL after success

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$NextStatus='1';

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}


if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die("Make sure Upload directory exist!");
}

if($_POST)
{   
   //Something else--
    $ApplicationID=$_POST['ApplicationID'];
	$CurrentStatus=$_POST['CurrentStatus'];
	$NextStatus=$_POST['NextStatus'];
	$Notes=$_POST['Description'];
	$DepartmentID=$_POST['Department'];
	$Amount=$_POST['Amount'];

	//--------------------Return Code Here----------------------------!
	if ($NextStatus=='1')//record is new
	{
		$s_sql="Insert into RequisitionHeader (DepartmentID,ApprovalStatusID,Notes)
		Values ('$DepartmentID',1,'$Notes')";
		
		$CurrentStatus='1'; //set the current status to 1
		
		$sql="Insert into RequisitionLines (RequisitionHeaderID,Description,Amount)
		Values($RequisitionHeaderID,$Notes,$Amount)";
								
	}else//old record
	{			
		//later	 return to $NextStatusID
		$s_sql="Update RequisitionHeader set ApprovalStatusID=$NextStatus where RequisitionHeaderID=$ApplicationID";				
	}

	if ($s_result = sqlsrv_query($db, $s_sql)==false)
	{
		echo 'Shida!';
		DisplayErrors();
		exit;
	}else
	{
		$RequisitionHeaderID='0';
		
		$s_sql="Select max(RequisitionHeaderID)RequisitionHeaderID  from RequisitionHeader";
		if (($s_result = sqlsrv_query($db, $s_sql))==false)
		{
			DisplayErrors();
			echo $s_sql;
			//exit;
		}
		

		
		while($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
		{
			$RequisitionHeaderID = $row['RequisitionHeaderID'];			
		}
		
		
		$s_sql="Insert into RequisitionApprovalActons(RequisitionHeaderID,RequisitionStatusID,NextRequisitionStatusID,Notes,CreatedBy) 
		Values ($RequisitionHeaderID,$CurrentStatus,$NextStatus,'$Notes',$CreatedUserID)";

		if ($s_result = sqlsrv_query($db, $s_sql)==false)
		{
			DisplayErrors();
			echo $s_sql;
			exit;
		}
		
		if ($NextStatus=='1'){	
			
			$s_sql="Insert into RequisitionLines (RequisitionHeaderID,Description,Amount)
			Values('$RequisitionHeaderID','$Notes','$Amount')";
			
			if ($s_result = sqlsrv_query($db, $s_sql)==false)
			{
				DisplayErrors();
				echo $s_sql;
				exit;
			}
		}
	}
		//------------------------------//
		//upload Files
		if(!isset($_POST['mName']) || strlen($_POST['mName'])<1)
		{
			$msg="File name for the budget breakdown attachment is empty!";
		}else if(!isset($_POST['mName_b']) || strlen($_POST['mName_b'])<1)
		{
			$msg="File name for the request breakdown attachment is empty!";
		}
		
		
		if($_FILES['mFile']['error'])
		{
			//File upload error encountered
			$msg=(upload_errors($_FILES['mFile']['error']));
		}else if($_FILES['mFile']['error'])
		{
			//File upload error encountered
			$msg=(upload_errors($_FILES['mFile_b']['error']));
		}
	
		$FileName			= strtolower($_FILES['mFile']['name']); //uploaded file name
		$FileTitle			= mysql_real_escape_string($_POST['mName']); // file title
		$ImageExt			= substr($FileName, strrpos($FileName, '.')); //file extension
		$FileType			= $_FILES['mFile']['type']; //file type
		$FileSize			= $_FILES['mFile']["size"]; //file size
		
		$FileName_b			= strtolower($_FILES['mFile_b']['name']); //uploaded file name
		$FileTitle_b			= mysql_real_escape_string($_POST['mName_b']); // file title
		$ImageExt_b			= substr($FileName, strrpos($FileName_b, '.')); //file extension
		$FileType_b			= $_FILES['mFile_b']['type']; //file type
		$FileSize_b			= $_FILES['mFile_b']["size"]; //file size
		
		$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
		$uploaded_date		= date("Y-m-d H:i:s");
		
		switch(strtolower($FileType))
		{
			//allowed file types
			case 'image/png': //png file
			case 'image/gif': //gif file 
			case 'image/jpeg': //jpeg file
			case 'application/pdf': //PDF file
			case 'application/msword': //ms word file
			case 'application/vnd.ms-excel': //ms excel file
			case 'application/x-zip-compressed': //zip file
			case 'text/plain': //text file
			case 'text/html': //html file
				break;
			default:
				die('Unsupported File!'); //output error
		}
	
	  
		//File Title will be used as new File name
		$NewFileName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($FileTitle));
		$NewFileName = $NewFileName.'_'.$RandNumber.$ImageExt;
		
		$NewFileName_b = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($FileTitle_b));
		$NewFileName_b = $NewFileName_b.'_'.$RandNumber.$ImageExt;
		
		
	   //Rename and save uploded file to destination folder.
	   if(move_uploaded_file($_FILES['mFile']["tmp_name"], $UploadDirectory . $NewFileName ))
	   {
		   $sql="INSERT INTO RequisitionFiles (RequisitionHeaderID,FileName, FileTitle, FileSize) VALUES ('$RequisitionHeaderID','$NewFileName', '$FileTitle',$FileSize)";
		   $result=sqlsrv_query($db,$sql);
		   if (!$result)
		   {
			  $msg="Saving the files failed"; 			   
		   }
		   
		   $sql="INSERT INTO RequisitionFiles (RequisitionHeaderID,FileName, FileTitle, FileSize) VALUES ('$RequisitionHeaderID','$NewFileName_b', '$FileTitle_b',$FileSize_b)";
		   $result=sqlsrv_query($db,$sql);
		   if (!$result)
		   {
			  $msg="Saving the files failed"; 			   
		   }
	   }else
	   {
			$msg='error uploading File!';
	   }
	   
	   header('Location: index.php?RL=1');

	
}

//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
        case UPLOAD_ERR_PARTIAL: 
            return 'The uploaded file was only partially uploaded'; 
        case UPLOAD_ERR_NO_FILE: 
            return 'No file was uploaded'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'Missing a temporary folder'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return 'Failed to write file to disk'; 
        case UPLOAD_ERR_EXTENSION: 
            return 'File upload stopped by extension'; 
        default: 
            return 'Unknown upload error'; 
    } 
} 
?>