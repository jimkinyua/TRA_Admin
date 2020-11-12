<?php

/**
 * Generated by phpSPO model generator 2020-05-26T22:10:14+00:00 
 */
namespace Office365\Graph;

use Office365\Runtime\ClientValue;
class IosNotificationSettings extends ClientValue
{
    /**
     * @var string
     */
    public $BundleID;
    /**
     * @var string
     */
    public $AppName;
    /**
     * @var string
     */
    public $Publisher;
    /**
     * @var bool
     */
    public $Enabled;
    /**
     * @var bool
     */
    public $ShowInNotificationCenter;
    /**
     * @var bool
     */
    public $ShowOnLockScreen;
    /**
     * @var bool
     */
    public $BadgesEnabled;
    /**
     * @var bool
     */
    public $SoundsEnabled;
}