<?php

/**
 * Generated by phpSPO model generator 2020-05-26T22:12:31+00:00 
 */
namespace Office365\Graph;

use Office365\Runtime\ClientObject;
use Office365\Runtime\ResourcePath;
class DeviceConfiguration extends ClientObject
{
    /**
     * @return string
     */
    public function getDescription()
    {
        if (!$this->isPropertyAvailable("Description")) {
            return null;
        }
        return $this->getProperty("Description");
    }
    /**
     * @var string
     */
    public function setDescription($value)
    {
        $this->setProperty("Description", $value, true);
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        if (!$this->isPropertyAvailable("DisplayName")) {
            return null;
        }
        return $this->getProperty("DisplayName");
    }
    /**
     * @var string
     */
    public function setDisplayName($value)
    {
        $this->setProperty("DisplayName", $value, true);
    }
    /**
     * @return integer
     */
    public function getVersion()
    {
        if (!$this->isPropertyAvailable("Version")) {
            return null;
        }
        return $this->getProperty("Version");
    }
    /**
     * @var integer
     */
    public function setVersion($value)
    {
        $this->setProperty("Version", $value, true);
    }
    /**
     * @return DeviceConfigurationDeviceOverview
     */
    public function getDeviceStatusOverview()
    {
        if (!$this->isPropertyAvailable("DeviceStatusOverview")) {
            $this->setProperty("DeviceStatusOverview", new DeviceConfigurationDeviceOverview($this->getContext(), new ResourcePath("DeviceStatusOverview", $this->getResourcePath())));
        }
        return $this->getProperty("DeviceStatusOverview");
    }
    /**
     * @return DeviceConfigurationUserOverview
     */
    public function getUserStatusOverview()
    {
        if (!$this->isPropertyAvailable("UserStatusOverview")) {
            $this->setProperty("UserStatusOverview", new DeviceConfigurationUserOverview($this->getContext(), new ResourcePath("UserStatusOverview", $this->getResourcePath())));
        }
        return $this->getProperty("UserStatusOverview");
    }
}