<?php

/**
 * Updated By PHP Office365 Generator 2020-08-05T10:11:52+00:00 16.0.20315.12009
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
class ClassificationResult extends ClientValue
{
    /**
     * @var double
     */
    public $ConfidenceScore;
    /**
     * @var string
     */
    public $ContentTypeId;
    /**
     * @var array
     */
    public $Metas;
    /**
     * @var string
     */
    public $ModelId;
    /**
     * @var string
     */
    public $ModelVersion;
    /**
     * @var integer
     */
    public $RetryCount;
    /**
     * @var integer
     */
    public $RetentionLabelFlags;
    /**
     * @var string
     */
    public $RetentionLabelName;
}