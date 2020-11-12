<?php

/**
 * Updated By PHP Office365 Generator 2020-04-25T20:43:59+00:00 16.0.20008.12009
 */
namespace Office365\OutlookServices;


use Office365\Runtime\ResourcePath;
class EventMessage extends OutlookEntity
{
    /**
     * @var MeetingMessageType
     */
    public $MeetingMessageType;
    /**
     * @return Event
     */
    public function getEvent()
    {
        if (!$this->isPropertyAvailable("Event")) {
            $this->setProperty("Event", new Event($this->getContext(), new ResourcePath("Event", $this->getResourcePath())));
        }
        return $this->getProperty("Event");
    }
}