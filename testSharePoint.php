<?php

require 'DB_PARAMS/connect.php';

// require_once('phpSPO-master/vendor/autoload.php');
require_once "phpSPO/src/autoloader.php";

use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\Runtime\Utilities\RequestOptions;

use Office365\PHP\Client\SharePoint\ListCreationInformation;
use Office365\PHP\Client\SharePoint\SPList;
use Office365\PHP\Client\SharePoint\Web;

// require "phpSPO-master/src/Auth/NetworkCredentialContext.php"; 


$Url = "http://tra-edms:82";//"http://rbadev-shrpnt";
$username ='TRA\Administrator';//'rbadev\administrator';
$password = 'Admin@support12020a'; //'rba123!!';

// echo Shrpnt_test($Url,$username,$password);exit;

$TargetLibrary="Documents";
$destination="C:\Users\Administrator\Documents\dummy.pdf";
// exit($destination);
// $destination="C:\Users\Administrator\Documents\dummy.pdf";
$DocumentMetadata=array();
$SharePointURL = Shrpnt_attach($Url,$username,$password,$destination, $TargetLibrary, $DocumentMetadata);
exit($SharePointURL);

// $FullUrl = $Url.'/'.$TargetLibrary.'/'.'document for upload.pdf';
// echo $FullUrl;
// exit();


// 'Tender_Document_Path'=> Yii::$app->params['sharepointUrl'].'/'.Yii::$app->params['SupplierDocumentsURL'].'/'.$UploadedFileName
// http://rbss-svr/circulars/document%20for%20upload.pdf


function Shrpnt_attach($Url,$username,$password,$filepath, $targetLibraryTitle, $DocumentMetadata){
    try {
        $authCtx = new NetworkCredentialContext($username, $password);
        $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
        $ctx = new ClientContext($Url, $authCtx);
        $site = $ctx->getSite();
        $ctx->load($site); //load site settings            
        $ctx->executeQuery();

        $list = ensureList($ctx->getWeb(),$targetLibraryTitle, \Office365\PHP\Client\SharePoint\ListTemplateType::DocumentLibrary);
// print_r($list);exit;
        uploadToSP($filepath, $list, $DocumentMetadata);
        
    }
    catch (Exception $e) {
        print 'Authentication failed: ' .  $e->getMessage(). "\n";
    }
}

function Shrpnt_test($Url,$username,$password){
    //'rba123!!';

    try {
        $authCtx = new NetworkCredentialContext($username, $password);
        
        $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
        
        $ctx = new ClientContext($Url, $authCtx);
        $targetLibraryTitle="Shared Documents";
        //$list = ensureList($ctx->getWeb(),$targetLibraryTitle, \Office365\PHP\Client\SharePoint\ListTemplateType::DocumentLibrary);

        // print_r($list);exit;
        
        $site = $ctx->getSite();
        
        $ctx->load($site); //load site settings            
        
        $ctx->executeQuery();
        echo 'connected';
    }
    catch (Exception $e) {
        // print_r($e);exit;
        print 'Authentication failed: ' .  $e->getMessage(). "\n";
        echo $e->getMessage();
    } 
}

function ensureList(Web $web, $listTitle, $type, $clearItems = true) {
        $ctx = $web->getContext();
        $lists = $web->getLists()->filter("Title eq '$listTitle'")->top(1);
        $ctx->load($lists);
        $ctx->executeQuery();
        if ($lists->getCount() == 1) {
            $existingList = $lists->getData()[0];
            if ($clearItems) {
                //self::deleteListItems($existingList);
            }
            return $existingList;
        }
        // return ListExtensions::createList($web, $listTitle, $type);
    }

function uploadToSP($localFilePath, \Office365\PHP\Client\SharePoint\SPList $targetList, $DocumentMetadata) {
        $ctx = $targetList->getContext();        
        $fileCreationInformation = new \Office365\PHP\Client\SharePoint\FileCreationInformation();
        $fileCreationInformation->Content = file_get_contents($localFilePath);
        $fileCreationInformation->Url = basename($localFilePath);
        // print_r($fileCreationInformation); exit;
        $uploadFile = $targetList->getRootFolder()->getFiles()->add($fileCreationInformation);
        $ctx->executeQuery();

        // $uploadFile->getListItemAllFields()->setProperty('Title', $DocumentMetadata['SupplierNumber'].' - '.$DocumentMetadata['DocumentType']);
        // $uploadFile->getListItemAllFields()->setProperty('Supplier_x0020_Name', $DocumentMetadata['SupplierNumber']);//Document_x0020_Number
        // $uploadFile->getListItemAllFields()->setProperty('RFX', $DocumentMetadata['RFX']);//
        // $uploadFile->getListItemAllFields()->setProperty('Document_x0020_Type', $DocumentMetadata['DocumentType']);
        // $uploadFile->getListItemAllFields()->setProperty('Document_x0020_No_x002e_', $DocumentMetadata['DocumentNumber']);
        // $uploadFile->getListItemAllFields()->setProperty('Expiry_x0020_Date', $DocumentMetadata['ExpiryDate']);
        // $uploadFile->getListItemAllFields()->setProperty('Reference_x0020_Number', $DocumentMetadata['TenderNumber']);
        // $uploadFile->getListItemAllFields()->update();
        // $ctx->executeQuery();
    }


?>