<?php

/**
 * Updated By PHP Office365 Generator 2020-08-17T19:25:17+00:00 16.0.20405.12007
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
/**
 * Specifies 
 * the information for a document library on 
 * a site 
 * (2).
 */
class DocumentLibraryInformation extends ClientValue
{
    /**
     * Absolute 
     * Url of the document library.
     * @var string
     */
    public $AbsoluteUrl;
    /**
     * @var bool
     */
    public $FromCrossFarm;
    /**
     * Identifies 
     * the modified date of the document library.
     * @var string
     */
    public $Modified;
    /**
     * Identifies 
     * a friendly display for the modified date of the document library.
     * @var string
     */
    public $ModifiedFriendlyDisplay;
    /**
     * Identifies 
     * the server-relative 
     * URL of the document library.
     * @var string
     */
    public $ServerRelativeUrl;
    /**
     * Identifies 
     * the title of the document library.
     * @var string
     */
    public $Title;
    /**
     * @var string
     */
    public $Id;
}