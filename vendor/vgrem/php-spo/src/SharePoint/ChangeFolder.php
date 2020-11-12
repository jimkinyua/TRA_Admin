<?php

/**
 * Updated By PHP Office365 Generator 2019-11-16T19:41:02+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * Specifies 
 * a change on a folder not contained 
 * in a list 
 * or document 
 * library.The RelativeTime property is not included in the default 
 * scalar property set for this type.
 */
class ChangeFolder extends Change
{

    /**
     * Identifies 
     * the folder 
     * that has changed.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe document identifier 
     *   in the change fields (2) item of 
     *   the change collection is NULL.
     * @return string
     */
    public function getUniqueId()
    {
        if (!$this->isPropertyAvailable("UniqueId")) {
            return null;
        }
        return $this->getProperty("UniqueId");
    }
    /**
     * Identifies 
     * the folder 
     * that has changed.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe document identifier 
     *   in the change fields (2) item of 
     *   the change collection is NULL.
     * @var string
     */
    public function setUniqueId($value)
    {
        $this->setProperty("UniqueId", $value, true);
    }
    /**
     * Identifies 
     * the site 
     * (2) that contains the changed folder.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe site identifier in 
     *   the change fields (2) item of 
     *   the change collection is NULL.
     * @return string
     */
    public function getWebId()
    {
        if (!$this->isPropertyAvailable("WebId")) {
            return null;
        }
        return $this->getProperty("WebId");
    }
    /**
     * Identifies 
     * the site 
     * (2) that contains the changed folder.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe site identifier in 
     *   the change fields (2) item of 
     *   the change collection is NULL.
     * @var string
     */
    public function setWebId($value)
    {
        $this->setProperty("WebId", $value, true);
    }
}
