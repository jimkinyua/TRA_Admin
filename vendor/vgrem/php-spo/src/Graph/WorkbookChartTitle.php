<?php

/**
 * Generated by phpSPO model generator 2020-05-26T22:05:50+00:00 
 */
namespace Office365\Graph;

use Office365\Runtime\ClientObject;
use Office365\Runtime\ResourcePath;
class WorkbookChartTitle extends ClientObject
{
    /**
     * @return bool
     */
    public function getOverlay()
    {
        if (!$this->isPropertyAvailable("Overlay")) {
            return null;
        }
        return $this->getProperty("Overlay");
    }
    /**
     * @var bool
     */
    public function setOverlay($value)
    {
        $this->setProperty("Overlay", $value, true);
    }
    /**
     * @return string
     */
    public function getText()
    {
        if (!$this->isPropertyAvailable("Text")) {
            return null;
        }
        return $this->getProperty("Text");
    }
    /**
     * @var string
     */
    public function setText($value)
    {
        $this->setProperty("Text", $value, true);
    }
    /**
     * @return bool
     */
    public function getVisible()
    {
        if (!$this->isPropertyAvailable("Visible")) {
            return null;
        }
        return $this->getProperty("Visible");
    }
    /**
     * @var bool
     */
    public function setVisible($value)
    {
        $this->setProperty("Visible", $value, true);
    }
    /**
     * @return WorkbookChartTitleFormat
     */
    public function getFormat()
    {
        if (!$this->isPropertyAvailable("Format")) {
            $this->setProperty("Format", new WorkbookChartTitleFormat($this->getContext(), new ResourcePath("Format", $this->getResourcePath())));
        }
        return $this->getProperty("Format");
    }
}