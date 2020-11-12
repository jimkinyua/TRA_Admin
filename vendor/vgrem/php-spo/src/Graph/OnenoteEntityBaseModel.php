<?php

/**
 * Generated by phpSPO model generator 2020-05-26T22:10:14+00:00 
 */
namespace Office365\Graph;

use Office365\Runtime\ClientObject;
use Office365\Runtime\ResourcePath;
class OnenoteEntityBaseModel extends ClientObject
{
    /**
     * @return string
     */
    public function getSelf()
    {
        if (!$this->isPropertyAvailable("Self")) {
            return null;
        }
        return $this->getProperty("Self");
    }
    /**
     * @var string
     */
    public function setSelf($value)
    {
        $this->setProperty("Self", $value, true);
    }
}