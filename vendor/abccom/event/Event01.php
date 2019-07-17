<?php
namespace Multiphalcon\Vendor\Abccom\Event;
use \Phalcon\Events\EventsAwareInterface;

class Event01 implements EventsAwareInterface {
    protected $_eventManager;

    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager) {
        $this->_eventManager = $eventsManager;
    }

    public function getEventsManager() {
        return $this->_eventManager;
    }

    public function showInfo() {
        $this->_eventManager->fire('abc:beforeShow', $this);
        echo '<h1>Content of showInfo</h1>';
        $this->_eventManager->fire('abc:afterShow', $this);
    }
}