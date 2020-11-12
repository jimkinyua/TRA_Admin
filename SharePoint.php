<?php
    require_once('phpSPO/src/autoloader.php');
    use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
    use Office365\PHP\Client\SharePoint\ClientContext;
    use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
    use Office365\PHP\Client\Runtime\Utilities\RequestOptions;
    
    use Office365\PHP\Client\SharePoint\ListCreationInformation;
    use Office365\PHP\Client\SharePoint\SPList;
    use Office365\PHP\Client\SharePoint\Web;

class SharePoint

{
 
      /**
     * @param Web $web
     * @param $listTitle
     * @param $type
     * @return SPList
     * @internal param ClientRuntimeContext $ctx
     */
    public  function createList(Web $web, $listTitle, $type)
    {
        $ctx = $web->getContext();
        $info = new ListCreationInformation($listTitle);
        $info->BaseTemplate = $type;
        $list = $web->getLists()->add($info);
        $ctx->executeQuery();
        return $list;
    }

    /**
     * @param \Office365\PHP\Client\SharePoint\SPList $list
     */
    public static function deleteList(\Office365\PHP\Client\SharePoint\SPList $list){
        $ctx = $list->getContext();
        $list->deleteObject();
        $ctx->executeQuery();
    }

    

    function uploadFiles($localFilePath, \Office365\PHP\Client\SharePoint\SPList $targetList)
    {

        $ctx = $targetList->getContext();

        $session = Yii::$app->session;

        if($session->has('metadata')){
            $metadata = $session->get('metadata');
        }

  

        $fileCreationInformation = new \Office365\PHP\Client\SharePoint\FileCreationInformation();
        $fileCreationInformation->Content = file_get_contents($localFilePath);
        $fileCreationInformation->Url = basename($localFilePath);

        //print_r($fileCreationInformation); exit;

        $uploadFile = $targetList->getRootFolder()->getFiles()->add($fileCreationInformation);
        $ctx->executeQuery();
        print "File {$uploadFile->getProperty('Name')} has been uploaded\r\n";

        //print_r($metadata[2]); exit;

        $uploadFile->getListItemAllFields()->setProperty('Title',basename($localFilePath));
        $uploadFile->getListItemAllFields()->setProperty('Application_x0020_Number',$metadata[1]);//Document_x0020_Number
        $uploadFile->getListItemAllFields()->setProperty('Document_x0020_Number',$metadata[2]);//
        $uploadFile->getListItemAllFields()->setProperty('Document_x0020_Description',$metadata[0]);
        $uploadFile->getListItemAllFields()->update();
        $ctx->executeQuery();
    }

    public static function ensureList(Web $web, $listTitle, $type, $clearItems = true)
    {
        $ctx = $web->getContext();
        $lists = $web->getLists();//->filter("Title eq '$listTitle'")->top(1);
        $ctx->load($lists);
        $ctx->executeQuery();
        if ($lists->getCount() == 1) {
            $existingList = $lists->getData()[0];
            if ($clearItems) {
                //self::deleteListItems($existingList);
            }
            return $existingList;
        }
        $ctx = $web->getContext();
        $info = new ListCreationInformation($listTitle);
        $info->BaseTemplate = $type;
        $list = $web->getFolders()->add($info);
        $ctx->executeQuery();

        return $list;
    }

}
?>