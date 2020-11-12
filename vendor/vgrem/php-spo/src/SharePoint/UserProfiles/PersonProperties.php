<?php

/**
 * Updated By PHP Office365 Generator 2019-11-16T20:05:23+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\UserProfiles;

use Office365\Runtime\ClientObject;
use Office365\SharePoint\KeyValueCollection;

class PersonProperties extends ClientObject
{
    /**
     * @return string
     */
    public function getAccountName()
    {
        if (!$this->isPropertyAvailable("AccountName")) {
            return null;
        }
        return $this->getProperty("AccountName");
    }
    /**
     * @var string
     */
    public function setAccountName($value)
    {
        $this->setProperty("AccountName", $value, true);
    }
    /**
     * @return array
     */
    public function getDirectReports()
    {
        if (!$this->isPropertyAvailable("DirectReports")) {
            return null;
        }
        return $this->getProperty("DirectReports");
    }
    /**
     * @var array
     */
    public function setDirectReports($value)
    {
        $this->setProperty("DirectReports", $value, true);
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
     * @return string
     */
    public function getEmail()
    {
        if (!$this->isPropertyAvailable("Email")) {
            return null;
        }
        return $this->getProperty("Email");
    }
    /**
     * @var string
     */
    public function setEmail($value)
    {
        $this->setProperty("Email", $value, true);
    }
    /**
     * @return array
     */
    public function getExtendedManagers()
    {
        if (!$this->isPropertyAvailable("ExtendedManagers")) {
            return null;
        }
        return $this->getProperty("ExtendedManagers");
    }
    /**
     * @var array
     */
    public function setExtendedManagers($value)
    {
        $this->setProperty("ExtendedManagers", $value, true);
    }
    /**
     * @return array
     */
    public function getExtendedReports()
    {
        if (!$this->isPropertyAvailable("ExtendedReports")) {
            return null;
        }
        return $this->getProperty("ExtendedReports");
    }
    /**
     * @var array
     */
    public function setExtendedReports($value)
    {
        $this->setProperty("ExtendedReports", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsFollowed()
    {
        if (!$this->isPropertyAvailable("IsFollowed")) {
            return null;
        }
        return $this->getProperty("IsFollowed");
    }
    /**
     * @var bool
     */
    public function setIsFollowed($value)
    {
        $this->setProperty("IsFollowed", $value, true);
    }
    /**
     * @return string
     */
    public function getLatestPost()
    {
        if (!$this->isPropertyAvailable("LatestPost")) {
            return null;
        }
        return $this->getProperty("LatestPost");
    }
    /**
     * @var string
     */
    public function setLatestPost($value)
    {
        $this->setProperty("LatestPost", $value, true);
    }
    /**
     * @return array
     */
    public function getPeers()
    {
        if (!$this->isPropertyAvailable("Peers")) {
            return null;
        }
        return $this->getProperty("Peers");
    }
    /**
     * @var array
     */
    public function setPeers($value)
    {
        $this->setProperty("Peers", $value, true);
    }
    /**
     * @return string
     */
    public function getPersonalSiteHostUrl()
    {
        if (!$this->isPropertyAvailable("PersonalSiteHostUrl")) {
            return null;
        }
        return $this->getProperty("PersonalSiteHostUrl");
    }
    /**
     * @var string
     */
    public function setPersonalSiteHostUrl($value)
    {
        $this->setProperty("PersonalSiteHostUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getPersonalUrl()
    {
        if (!$this->isPropertyAvailable("PersonalUrl")) {
            return null;
        }
        return $this->getProperty("PersonalUrl");
    }
    /**
     * @var string
     */
    public function setPersonalUrl($value)
    {
        $this->setProperty("PersonalUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getPictureUrl()
    {
        if (!$this->isPropertyAvailable("PictureUrl")) {
            return null;
        }
        return $this->getProperty("PictureUrl");
    }
    /**
     * @var string
     */
    public function setPictureUrl($value)
    {
        $this->setProperty("PictureUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        if (!$this->isPropertyAvailable("Title")) {
            return null;
        }
        return $this->getProperty("Title");
    }
    /**
     * @var string
     */
    public function setTitle($value)
    {
        $this->setProperty("Title", $value, true);
    }
    /**
     * @return KeyValueCollection
     */
    public function getUserProfileProperties()
    {
        if (!$this->isPropertyAvailable("UserProfileProperties")) {
            return null;
        }
        return $this->getProperty("UserProfileProperties");
    }
    /**
     * @var KeyValueCollection
     */
    public function setUserProfileProperties($value)
    {
        $this->setProperty("UserProfileProperties", $value, true);
    }
    /**
     * @return string
     */
    public function getUserUrl()
    {
        if (!$this->isPropertyAvailable("UserUrl")) {
            return null;
        }
        return $this->getProperty("UserUrl");
    }
    /**
     * @var string
     */
    public function setUserUrl($value)
    {
        $this->setProperty("UserUrl", $value, true);
    }
}
