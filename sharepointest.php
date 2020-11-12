<?php
//SHAREPOINT UPLOAD
function actionSend_to_sharepoint($filepath, $MetaData, $ctx)
{  //read list
	//$this->actionShrpnt_attach($target_file,$desc,$applicantno,$docno);
	$localPath = $filepath;
	try {
	
		
		$targetFolderUrl ='Licensing';// \Yii::$app->params['BenefitsFolderUrl'];
		$list = ensureList($ctx->getWeb(), $targetFolderUrl, \Office365\PHP\Client\SharePoint\ListTemplateType::DocumentLibrary);
		// print_r($list);
		// exit;

		$fileName = basename($localPath); //The Uploaded Document Name
		// exit($fileName);
		
		$fileCreationInformation = new \Office365\PHP\Client\SharePoint\FileCreationInformation();
		$fileCreationInformation->Content = file_get_contents($localPath);
		// echo '<pre>';
		// print_r(file_get_contents($localPath));
		// exit;

		$fileCreationInformation->Url = $fileName;

		$uploadFile = $list->getRootFolder()->getFiles()->add($fileCreationInformation);
		//->getFolderByServerRelativeUrl($targetFolderUrl)->getFiles()->add($fileCreationInformation);
	
		$ctx->executeQuery(); //Upload Document

		// $uploadFile->getListItemAllFields()->update(); //Returns associated list item entity
		// $uploadFile->getListItemAllFields()->setProperty('ApplicantNumberTest',$MetaData['BenefitNumber']); ////update associated list item entity
		// $uploadFile->getListItemAllFields()->setProperty('DocumentDescription',$MetaData['DocumentDescription']); ////update associated list item entity
		//http://tra-edms/home/Licensing/images.jpg?csf=1&e=AT46oO
		// $uploadFile->getListItemAllFields()->update(); ////tell query to update entity
		// $ctx->executeQuery();
		ECHO 'Uploaded Succesfully!';
		// return true;
	


	} 
	
	catch (Exception $e) {
		print $e->getMessage() ;
	}
}

?>