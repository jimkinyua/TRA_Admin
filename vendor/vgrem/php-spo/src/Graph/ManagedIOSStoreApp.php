<?php

/**
 * Generated by phpSPO model generator 2020-05-26T22:12:31+00:00 
 */
namespace Office365\Graph;

use Office365\Runtime\ClientObject;
use Office365\Runtime\ResourcePath;
class ManagedIOSStoreApp extends ClientObject
{
    /**
     * @return string
     */
    public function getBundleId()
    {
        if (!$this->isPropertyAvailable("BundleId")) {
            return null;
        }
        return $this->getProperty("BundleId");
    }
    /**
     * @var string
     */
    public function setBundleId($value)
    {
        $this->setProperty("BundleId", $value, true);
    }
    /**
     * @return string
     */
    public function getAppStoreUrl()
    {
        if (!$this->isPropertyAvailable("AppStoreUrl")) {
            return null;
        }
        return $this->getProperty("AppStoreUrl");
    }
    /**
     * @var string
     */
    public function setAppStoreUrl($value)
    {
        $this->setProperty("AppStoreUrl", $value, true);
    }
    /**
     * @return IosDeviceType
     */
    public function getApplicableDeviceType()
    {
        if (!$this->isPropertyAvailable("ApplicableDeviceType")) {
            return null;
        }
        return $this->getProperty("ApplicableDeviceType");
    }
    /**
     * @var IosDeviceType
     */
    public function setApplicableDeviceType($value)
    {
        $this->setProperty("ApplicableDeviceType", $value, true);
    }
    /**
     * @return IosMinimumOperatingSystem
     */
    public function getMinimumSupportedOperatingSystem()
    {
        if (!$this->isPropertyAvailable("MinimumSupportedOperatingSystem")) {
            return null;
        }
        return $this->getProperty("MinimumSupportedOperatingSystem");
    }
    /**
     * @var IosMinimumOperatingSystem
     */
    public function setMinimumSupportedOperatingSystem($value)
    {
        $this->setProperty("MinimumSupportedOperatingSystem", $value, true);
    }
}