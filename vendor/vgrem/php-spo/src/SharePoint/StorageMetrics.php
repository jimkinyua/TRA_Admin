<?php

/**
 * Updated By PHP Office365 Generator 2019-11-17T16:07:15+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientObject;

/**
 * Represents 
 * storage-related metrics.
 */
class StorageMetrics extends ClientObject
{
    /**
     * @return string
     */
    public function getLastModified()
    {
        if (!$this->isPropertyAvailable("LastModified")) {
            return null;
        }
        return $this->getProperty("LastModified");
    }
    /**
     * @var string
     */
    public function setLastModified($value)
    {
        $this->setProperty("LastModified", $value, true);
    }
    /**
     * @return integer
     */
    public function getTotalFileCount()
    {
        if (!$this->isPropertyAvailable("TotalFileCount")) {
            return null;
        }
        return $this->getProperty("TotalFileCount");
    }
    /**
     * @var integer
     */
    public function setTotalFileCount($value)
    {
        $this->setProperty("TotalFileCount", $value, true);
    }
    /**
     * @return integer
     */
    public function getTotalFileStreamSize()
    {
        if (!$this->isPropertyAvailable("TotalFileStreamSize")) {
            return null;
        }
        return $this->getProperty("TotalFileStreamSize");
    }
    /**
     * @var integer
     */
    public function setTotalFileStreamSize($value)
    {
        $this->setProperty("TotalFileStreamSize", $value, true);
    }
    /**
     * @return integer
     */
    public function getTotalSize()
    {
        if (!$this->isPropertyAvailable("TotalSize")) {
            return null;
        }
        return $this->getProperty("TotalSize");
    }
    /**
     * @var integer
     */
    public function setTotalSize($value)
    {
        $this->setProperty("TotalSize", $value, true);
    }
}