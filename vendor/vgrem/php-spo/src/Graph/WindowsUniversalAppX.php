<?php

/**
 * Generated by phpSPO model generator 2020-05-26T22:12:31+00:00 
 */
namespace Office365\Graph;

use Office365\Runtime\ClientObject;
use Office365\Runtime\ResourcePath;
class WindowsUniversalAppX extends ClientObject
{
    /**
     * @return string
     */
    public function getIdentityName()
    {
        if (!$this->isPropertyAvailable("IdentityName")) {
            return null;
        }
        return $this->getProperty("IdentityName");
    }
    /**
     * @var string
     */
    public function setIdentityName($value)
    {
        $this->setProperty("IdentityName", $value, true);
    }
    /**
     * @return string
     */
    public function getIdentityPublisherHash()
    {
        if (!$this->isPropertyAvailable("IdentityPublisherHash")) {
            return null;
        }
        return $this->getProperty("IdentityPublisherHash");
    }
    /**
     * @var string
     */
    public function setIdentityPublisherHash($value)
    {
        $this->setProperty("IdentityPublisherHash", $value, true);
    }
    /**
     * @return string
     */
    public function getIdentityResourceIdentifier()
    {
        if (!$this->isPropertyAvailable("IdentityResourceIdentifier")) {
            return null;
        }
        return $this->getProperty("IdentityResourceIdentifier");
    }
    /**
     * @var string
     */
    public function setIdentityResourceIdentifier($value)
    {
        $this->setProperty("IdentityResourceIdentifier", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsBundle()
    {
        if (!$this->isPropertyAvailable("IsBundle")) {
            return null;
        }
        return $this->getProperty("IsBundle");
    }
    /**
     * @var bool
     */
    public function setIsBundle($value)
    {
        $this->setProperty("IsBundle", $value, true);
    }
    /**
     * @return string
     */
    public function getIdentityVersion()
    {
        if (!$this->isPropertyAvailable("IdentityVersion")) {
            return null;
        }
        return $this->getProperty("IdentityVersion");
    }
    /**
     * @var string
     */
    public function setIdentityVersion($value)
    {
        $this->setProperty("IdentityVersion", $value, true);
    }
    /**
     * @return WindowsMinimumOperatingSystem
     */
    public function getMinimumSupportedOperatingSystem()
    {
        if (!$this->isPropertyAvailable("MinimumSupportedOperatingSystem")) {
            return null;
        }
        return $this->getProperty("MinimumSupportedOperatingSystem");
    }
    /**
     * @var WindowsMinimumOperatingSystem
     */
    public function setMinimumSupportedOperatingSystem($value)
    {
        $this->setProperty("MinimumSupportedOperatingSystem", $value, true);
    }
}