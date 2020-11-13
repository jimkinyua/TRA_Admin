<?php

/**
 * Updated By PHP Office365 Generator 2020-08-17T19:25:17+00:00 16.0.20405.12007
 */
namespace Office365\SharePoint;

use Office365\Runtime\DeleteEntityQuery;
use Office365\Runtime\ClientObject;
/**
 * Specifies 
 * a list 
 * item attachment.<174>
 */
class Attachment extends ClientObject
{
    public function deleteObject()
    {
        $qry = new DeleteEntityQuery($this);
        $this->getContext()->addQuery($qry);
    }
    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->getProperty("FileName");
    }
    /**
     * @return string
     */
    public function getServerRelativeUrl()
    {
        return $this->getProperty("ServerRelativeUrl");
    }
    /**
     * Specifies 
     * the file name of the list item attachment.
     * @var string
     */
    public function setFileName($value)
    {
        $this->setProperty("FileName", $value, true);
    }
    /**
     * Specifies 
     * the server-relative 
     * URL of a list item attachment.
     * @var string
     */
    public function setServerRelativeUrl($value)
    {
        $this->setProperty("ServerRelativeUrl", $value, true);
    }
    /**
     * The file 
     * name of the attachment as a SPResourcePath.
     * @return SPResourcePath
     */
    public function getFileNameAsPath()
    {
        if (!$this->isPropertyAvailable("FileNameAsPath")) {
            return null;
        }
        return $this->getProperty("FileNameAsPath");
    }
    /**
     * The file 
     * name of the attachment as a SPResourcePath.
     * @var SPResourcePath
     */
    public function setFileNameAsPath($value)
    {
        $this->setProperty("FileNameAsPath", $value, true);
    }
    /**
     * The 
     * server-relative-path of the attachment.
     * @return SPResourcePath
     */
    public function getServerRelativePath()
    {
        if (!$this->isPropertyAvailable("ServerRelativePath")) {
            return null;
        }
        return $this->getProperty("ServerRelativePath");
    }
    /**
     * The 
     * server-relative-path of the attachment.
     * @var SPResourcePath
     */
    public function setServerRelativePath($value)
    {
        $this->setProperty("ServerRelativePath", $value, true);
    }
}