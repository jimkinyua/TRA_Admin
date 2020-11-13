<?php

/**
 * Updated By PHP Office365 Generator 2020-04-23T18:46:51+00:00 16.0.20008.12009
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientObject;
use Office365\Runtime\ResourcePath;
/**
 * This class 
 * contains the information necessary to read and change the sharing status of a 
 * SharePoint object. It also contains a reference to SharePoint specific settings 
 * denoted by "SharePointSettings".
 */
class ObjectSharingSettings extends ClientObject
{
    /**
     * Boolean 
     * indicating whether the sharing context operates under the access request mode.
     * @return bool
     */
    public function getAccessRequestMode()
    {
        if (!$this->isPropertyAvailable("AccessRequestMode")) {
            return null;
        }
        return $this->getProperty("AccessRequestMode");
    }
    /**
     * Boolean 
     * indicating whether the sharing context operates under the access request mode.
     * @var bool
     */
    public function setAccessRequestMode($value)
    {
        $this->setProperty("AccessRequestMode", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can use the People Picker to do any 
     * sharing.
     * @return bool
     */
    public function getBlockPeoplePickerAndSharing()
    {
        if (!$this->isPropertyAvailable("BlockPeoplePickerAndSharing")) {
            return null;
        }
        return $this->getProperty("BlockPeoplePickerAndSharing");
    }
    /**
     * Boolean 
     * indicating whether the current user can use the People Picker to do any 
     * sharing.
     * @var bool
     */
    public function setBlockPeoplePickerAndSharing($value)
    {
        $this->setProperty("BlockPeoplePickerAndSharing", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an organization View 
     * link.
     * @return bool
     */
    public function getCanCurrentUserManageOrganizationReadonlyLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserManageOrganizationReadonlyLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserManageOrganizationReadonlyLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an organization View 
     * link.
     * @var bool
     */
    public function setCanCurrentUserManageOrganizationReadonlyLink($value)
    {
        $this->setProperty("CanCurrentUserManageOrganizationReadonlyLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an organization Edit 
     * link.
     * @return bool
     */
    public function getCanCurrentUserManageOrganizationReadWriteLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserManageOrganizationReadWriteLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserManageOrganizationReadWriteLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an organization Edit 
     * link.
     * @var bool
     */
    public function setCanCurrentUserManageOrganizationReadWriteLink($value)
    {
        $this->setProperty("CanCurrentUserManageOrganizationReadWriteLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an anonymous View 
     * link.
     * @return bool
     */
    public function getCanCurrentUserManageReadonlyLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserManageReadonlyLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserManageReadonlyLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an anonymous View 
     * link.
     * @var bool
     */
    public function setCanCurrentUserManageReadonlyLink($value)
    {
        $this->setProperty("CanCurrentUserManageReadonlyLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an anonymous Edit 
     * link.
     * @return bool
     */
    public function getCanCurrentUserManageReadWriteLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserManageReadWriteLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserManageReadWriteLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can create or disable an anonymous Edit 
     * link.
     * @var bool
     */
    public function setCanCurrentUserManageReadWriteLink($value)
    {
        $this->setProperty("CanCurrentUserManageReadWriteLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an organization View link, if 
     * one has already been created.If one has 
     * not been created, the user cannot create one
     * @return bool
     */
    public function getCanCurrentUserRetrieveOrganizationReadonlyLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserRetrieveOrganizationReadonlyLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserRetrieveOrganizationReadonlyLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an organization View link, if 
     * one has already been created.If one has 
     * not been created, the user cannot create one
     * @var bool
     */
    public function setCanCurrentUserRetrieveOrganizationReadonlyLink($value)
    {
        $this->setProperty("CanCurrentUserRetrieveOrganizationReadonlyLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an organization Edit link, if 
     * one has already been created.If one has 
     * not been created, the user cannot create one.
     * @return bool
     */
    public function getCanCurrentUserRetrieveOrganizationReadWriteLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserRetrieveOrganizationReadWriteLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserRetrieveOrganizationReadWriteLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an organization Edit link, if 
     * one has already been created.If one has 
     * not been created, the user cannot create one.
     * @var bool
     */
    public function setCanCurrentUserRetrieveOrganizationReadWriteLink($value)
    {
        $this->setProperty("CanCurrentUserRetrieveOrganizationReadWriteLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an anonymous View link, if one 
     * has already been created.If one has 
     * not been created, the user cannot create one.
     * @return bool
     */
    public function getCanCurrentUserRetrieveReadonlyLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserRetrieveReadonlyLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserRetrieveReadonlyLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an anonymous View link, if one 
     * has already been created.If one has 
     * not been created, the user cannot create one.
     * @var bool
     */
    public function setCanCurrentUserRetrieveReadonlyLink($value)
    {
        $this->setProperty("CanCurrentUserRetrieveReadonlyLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an anonymous Edit link, if one 
     * has already been created.If one has 
     * not been created, the user cannot create one.
     * @return bool
     */
    public function getCanCurrentUserRetrieveReadWriteLink()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserRetrieveReadWriteLink")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserRetrieveReadWriteLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can retrieve an anonymous Edit link, if one 
     * has already been created.If one has 
     * not been created, the user cannot create one.
     * @var bool
     */
    public function setCanCurrentUserRetrieveReadWriteLink($value)
    {
        $this->setProperty("CanCurrentUserRetrieveReadWriteLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can share outside the tenancy, by inviting 
     * external users.
     * @return bool
     */
    public function getCanCurrentUserShareExternally()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserShareExternally")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserShareExternally");
    }
    /**
     * Boolean 
     * indicating whether the current user can share outside the tenancy, by inviting 
     * external users.
     * @var bool
     */
    public function setCanCurrentUserShareExternally($value)
    {
        $this->setProperty("CanCurrentUserShareExternally", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can only share within the tenancy
     * @return bool
     */
    public function getCanCurrentUserShareInternally()
    {
        if (!$this->isPropertyAvailable("CanCurrentUserShareInternally")) {
            return null;
        }
        return $this->getProperty("CanCurrentUserShareInternally");
    }
    /**
     * Boolean 
     * indicating whether the current user can only share within the tenancy
     * @var bool
     */
    public function setCanCurrentUserShareInternally($value)
    {
        $this->setProperty("CanCurrentUserShareInternally", $value, true);
    }
    /**
     * Boolean 
     * indicating whether email invitations can be sent.
     * @return bool
     */
    public function getCanSendEmail()
    {
        if (!$this->isPropertyAvailable("CanSendEmail")) {
            return null;
        }
        return $this->getProperty("CanSendEmail");
    }
    /**
     * Boolean 
     * indicating whether email invitations can be sent.
     * @var bool
     */
    public function setCanSendEmail($value)
    {
        $this->setProperty("CanSendEmail", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the current user can make use of Share-By-Link.
     * @return bool
     */
    public function getCanSendLink()
    {
        if (!$this->isPropertyAvailable("CanSendLink")) {
            return null;
        }
        return $this->getProperty("CanSendLink");
    }
    /**
     * Boolean 
     * indicating whether the current user can make use of Share-By-Link.
     * @var bool
     */
    public function setCanSendLink($value)
    {
        $this->setProperty("CanSendLink", $value, true);
    }
    /**
     * Boolean 
     * indicating whether the folder object can be shared.
     * @return bool
     */
    public function getCanShareFolder()
    {
        if (!$this->isPropertyAvailable("CanShareFolder")) {
            return null;
        }
        return $this->getProperty("CanShareFolder");
    }
    /**
     * Boolean 
     * indicating whether the folder object can be shared.
     * @var bool
     */
    public function setCanShareFolder($value)
    {
        $this->setProperty("CanShareFolder", $value, true);
    }
    /**
     * Default tokenized 
     * sharing link permission returned as Role.
     * @return integer
     */
    public function getDefaultShareLinkPermission()
    {
        if (!$this->isPropertyAvailable("DefaultShareLinkPermission")) {
            return null;
        }
        return $this->getProperty("DefaultShareLinkPermission");
    }
    /**
     * Default tokenized 
     * sharing link permission returned as Role.
     * @var integer
     */
    public function setDefaultShareLinkPermission($value)
    {
        $this->setProperty("DefaultShareLinkPermission", $value, true);
    }
    /**
     * Default tokenized 
     * sharing link type returned as SharingLinkKind.
     * @return integer
     */
    public function getDefaultShareLinkType()
    {
        if (!$this->isPropertyAvailable("DefaultShareLinkType")) {
            return null;
        }
        return $this->getProperty("DefaultShareLinkType");
    }
    /**
     * Default tokenized 
     * sharing link type returned as SharingLinkKind.
     * @var integer
     */
    public function setDefaultShareLinkType($value)
    {
        $this->setProperty("DefaultShareLinkType", $value, true);
    }
    /**
     * A dictionary 
     * object that lists the display name and the id of the SharePoint groups.
     * @return KeyValueCollection
     */
    public function getGroupsList()
    {
        if (!$this->isPropertyAvailable("GroupsList")) {
            return null;
        }
        return $this->getProperty("GroupsList");
    }
    /**
     * A dictionary 
     * object that lists the display name and the id of the SharePoint groups.
     * @var KeyValueCollection
     */
    public function setGroupsList($value)
    {
        $this->setProperty("GroupsList", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the site has the standard "Editor" 
     * role.
     * @return bool
     */
    public function getHasEditRole()
    {
        if (!$this->isPropertyAvailable("HasEditRole")) {
            return null;
        }
        return $this->getProperty("HasEditRole");
    }
    /**
     * Boolean 
     * that indicates whether or not the site has the standard "Editor" 
     * role.
     * @var bool
     */
    public function setHasEditRole($value)
    {
        $this->setProperty("HasEditRole", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the site has the standard "Reader" 
     * role.
     * @return bool
     */
    public function getHasReadRole()
    {
        if (!$this->isPropertyAvailable("HasReadRole")) {
            return null;
        }
        return $this->getProperty("HasReadRole");
    }
    /**
     * Boolean 
     * that indicates whether or not the site has the standard "Reader" 
     * role.
     * @var bool
     */
    public function setHasReadRole($value)
    {
        $this->setProperty("HasReadRole", $value, true);
    }
    /**
     * The URL 
     * of the site from which the shared object inherits permissions.
     * @return string
     */
    public function getInheritingWebLink()
    {
        if (!$this->isPropertyAvailable("InheritingWebLink")) {
            return null;
        }
        return $this->getProperty("InheritingWebLink");
    }
    /**
     * The URL 
     * of the site from which the shared object inherits permissions.
     * @var string
     */
    public function setInheritingWebLink($value)
    {
        $this->setProperty("InheritingWebLink", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the current user is a guest user.
     * @return bool
     */
    public function getIsGuestUser()
    {
        if (!$this->isPropertyAvailable("IsGuestUser")) {
            return null;
        }
        return $this->getProperty("IsGuestUser");
    }
    /**
     * Boolean 
     * that indicates whether or not the current user is a guest user.
     * @var bool
     */
    public function setIsGuestUser($value)
    {
        $this->setProperty("IsGuestUser", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the object to share is a picture 
     * library.
     * @return bool
     */
    public function getIsPictureLibrary()
    {
        if (!$this->isPropertyAvailable("IsPictureLibrary")) {
            return null;
        }
        return $this->getProperty("IsPictureLibrary");
    }
    /**
     * Boolean 
     * that indicates whether or not the object to share is a picture 
     * library.
     * @var bool
     */
    public function setIsPictureLibrary($value)
    {
        $this->setProperty("IsPictureLibrary", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the current user is a site collection administrator.
     * @return bool
     */
    public function getIsUserSiteAdmin()
    {
        if (!$this->isPropertyAvailable("IsUserSiteAdmin")) {
            return null;
        }
        return $this->getProperty("IsUserSiteAdmin");
    }
    /**
     * Boolean 
     * that indicates whether or not the current user is a site collection administrator.
     * @var bool
     */
    public function setIsUserSiteAdmin($value)
    {
        $this->setProperty("IsUserSiteAdmin", $value, true);
    }
    /**
     * The list 
     * item ID (if applicable).
     * @return string
     */
    public function getItemId()
    {
        if (!$this->isPropertyAvailable("ItemId")) {
            return null;
        }
        return $this->getProperty("ItemId");
    }
    /**
     * The list 
     * item ID (if applicable).
     * @var string
     */
    public function setItemId($value)
    {
        $this->setProperty("ItemId", $value, true);
    }
    /**
     * The object 
     * title.
     * @return string
     */
    public function getItemName()
    {
        if (!$this->isPropertyAvailable("ItemName")) {
            return null;
        }
        return $this->getProperty("ItemName");
    }
    /**
     * The object 
     * title.
     * @var string
     */
    public function setItemName($value)
    {
        $this->setProperty("ItemName", $value, true);
    }
    /**
     * The server 
     * relative object URL.
     * @return string
     */
    public function getItemUrl()
    {
        if (!$this->isPropertyAvailable("ItemUrl")) {
            return null;
        }
        return $this->getProperty("ItemUrl");
    }
    /**
     * The server 
     * relative object URL.
     * @var string
     */
    public function setItemUrl($value)
    {
        $this->setProperty("ItemUrl", $value, true);
    }
    /**
     * The unique 
     * ID of the parent list (if 
     * applicable).
     * @return string
     */
    public function getListId()
    {
        if (!$this->isPropertyAvailable("ListId")) {
            return null;
        }
        return $this->getProperty("ListId");
    }
    /**
     * The unique 
     * ID of the parent list (if 
     * applicable).
     * @var string
     */
    public function setListId($value)
    {
        $this->setProperty("ListId", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the sharing context operates under the permissions 
     * only mode (that is, adding to a group or hiding the groups drop-down in the 
     * SharePoint UI).
     * @return bool
     */
    public function getPermissionsOnlyMode()
    {
        if (!$this->isPropertyAvailable("PermissionsOnlyMode")) {
            return null;
        }
        return $this->getProperty("PermissionsOnlyMode");
    }
    /**
     * Boolean 
     * that indicates whether or not the sharing context operates under the permissions 
     * only mode (that is, adding to a group or hiding the groups drop-down in the 
     * SharePoint UI).
     * @var bool
     */
    public function setPermissionsOnlyMode($value)
    {
        $this->setProperty("PermissionsOnlyMode", $value, true);
    }
    /**
     * A value 
     * that indicates number of days an anonymous tokenized sharing link 
     * can be valid before it expires.
     * @return integer
     */
    public function getRequiredAnonymousLinkExpirationInDays()
    {
        if (!$this->isPropertyAvailable("RequiredAnonymousLinkExpirationInDays")) {
            return null;
        }
        return $this->getProperty("RequiredAnonymousLinkExpirationInDays");
    }
    /**
     * A value 
     * that indicates number of days an anonymous tokenized sharing link 
     * can be valid before it expires.
     * @var integer
     */
    public function setRequiredAnonymousLinkExpirationInDays($value)
    {
        $this->setProperty("RequiredAnonymousLinkExpirationInDays", $value, true);
    }
    /**
     * A 
     * dictionary object that lists the display name and the id of the SharePoint 
     * regular roles.
     * @return KeyValueCollection
     */
    public function getRoles()
    {
        if (!$this->isPropertyAvailable("Roles")) {
            return null;
        }
        return $this->getProperty("Roles");
    }
    /**
     * A 
     * dictionary object that lists the display name and the id of the SharePoint 
     * regular roles.
     * @var KeyValueCollection
     */
    public function setRoles($value)
    {
        $this->setProperty("Roles", $value, true);
    }
    /**
     * Boolean 
     * flag denoting if guest users are enabled for the site collection.
     * @return bool
     */
    public function getShareByEmailEnabled()
    {
        if (!$this->isPropertyAvailable("ShareByEmailEnabled")) {
            return null;
        }
        return $this->getProperty("ShareByEmailEnabled");
    }
    /**
     * Boolean 
     * flag denoting if guest users are enabled for the site collection.
     * @var bool
     */
    public function setShareByEmailEnabled($value)
    {
        $this->setProperty("ShareByEmailEnabled", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the client logic SHOULD warn the user that they 
     * are about to share with external email addresses.
     * @return bool
     */
    public function getShowExternalSharingWarning()
    {
        if (!$this->isPropertyAvailable("ShowExternalSharingWarning")) {
            return null;
        }
        return $this->getProperty("ShowExternalSharingWarning");
    }
    /**
     * Boolean 
     * that indicates whether or not the client logic SHOULD warn the user that they 
     * are about to share with external email addresses.
     * @var bool
     */
    public function setShowExternalSharingWarning($value)
    {
        $this->setProperty("ShowExternalSharingWarning", $value, true);
    }
    /**
     * A dictionary 
     * object that lists the display name and the id of the SharePoint simplified 
     * roles (edit, view).
     * @return KeyValueCollection
     */
    public function getSimplifiedRoles()
    {
        if (!$this->isPropertyAvailable("SimplifiedRoles")) {
            return null;
        }
        return $this->getProperty("SimplifiedRoles");
    }
    /**
     * A dictionary 
     * object that lists the display name and the id of the SharePoint simplified 
     * roles (edit, view).
     * @var KeyValueCollection
     */
    public function setSimplifiedRoles($value)
    {
        $this->setProperty("SimplifiedRoles", $value, true);
    }
    /**
     * Boolean 
     * that indicates whether or not the object to share supports ACL 
     * propagation.
     * @return bool
     */
    public function getSupportsAclPropagation()
    {
        if (!$this->isPropertyAvailable("SupportsAclPropagation")) {
            return null;
        }
        return $this->getProperty("SupportsAclPropagation");
    }
    /**
     * Boolean 
     * that indicates whether or not the object to share supports ACL 
     * propagation.
     * @var bool
     */
    public function setSupportsAclPropagation($value)
    {
        $this->setProperty("SupportsAclPropagation", $value, true);
    }
    /**
     * The URL 
     * pointing to the containing SPWeb object.
     * @return string
     */
    public function getWebUrl()
    {
        if (!$this->isPropertyAvailable("WebUrl")) {
            return null;
        }
        return $this->getProperty("WebUrl");
    }
    /**
     * The URL 
     * pointing to the containing SPWeb object.
     * @var string
     */
    public function setWebUrl($value)
    {
        $this->setProperty("WebUrl", $value, true);
    }
    /**
     * Contains 
     * information about the sharing state of a shareable object.
     * @return ObjectSharingInformation
     */
    public function getObjectSharingInformation()
    {
        if (!$this->isPropertyAvailable("ObjectSharingInformation")) {
            $this->setProperty("ObjectSharingInformation", new ObjectSharingInformation($this->getContext(), new ResourcePath("ObjectSharingInformation", $this->getResourcePath())));
        }
        return $this->getProperty("ObjectSharingInformation");
    }
    /**
     * @return SharePointSharingSettings
     */
    public function getSharePointSettings()
    {
        if (!$this->isPropertyAvailable("SharePointSettings")) {
            $this->setProperty("SharePointSettings", new SharePointSharingSettings($this->getContext(), new ResourcePath("SharePointSettings", $this->getResourcePath())));
        }
        return $this->getProperty("SharePointSettings");
    }
    /**
     * @return bool
     */
    public function getEnforceIBSegmentFiltering()
    {
        if (!$this->isPropertyAvailable("EnforceIBSegmentFiltering")) {
            return null;
        }
        return $this->getProperty("EnforceIBSegmentFiltering");
    }
    /**
     * @var bool
     */
    public function setEnforceIBSegmentFiltering($value)
    {
        $this->setProperty("EnforceIBSegmentFiltering", $value, true);
    }
    /**
     * @return array
     */
    public function getSiteIBSegmentIDs()
    {
        if (!$this->isPropertyAvailable("SiteIBSegmentIDs")) {
            return null;
        }
        return $this->getProperty("SiteIBSegmentIDs");
    }
    /**
     * @var array
     */
    public function setSiteIBSegmentIDs($value)
    {
        $this->setProperty("SiteIBSegmentIDs", $value, true);
    }
}