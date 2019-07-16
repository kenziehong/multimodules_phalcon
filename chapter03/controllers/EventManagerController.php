<?php
namespace Multiphalcon\Chapter03\Controllers;

use Phalcon\Events\Manager;
class EventManagerController extends \Phalcon\Mvc\Controller {
    public function indexAction() {
        //echo __METHOD__;

        $eventManager = new Manager();

        //Invalid event type event
        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event - doing 01</h3>';
        });

        $eventManager->attach('event:02', function(){
            echo '<h3 style="color:red">event - doing 02</h3>';
        });

        $eventManager->fire('event:01', $this); //source $this
        $eventManager->fire('event:02', $this); //source $this
        
    }

    public function index2Action() {
        //echo __METHOD__;

        $eventManager = new Manager();

        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event 01 - doing 01</h3>';
        });

        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event 01 - doing 02</h3>';
        });

        $eventManager->attach('event:02', function(){
            echo '<h3 style="color:red">event 02 - doing 01</h3>';
        });

        $eventManager->attach('event:02', function(){
            echo '<h3 style="color:red">event 02 - doing 02</h3>';
        });

        $eventManager->fire('event:01', $this); //source $this
        $eventManager->fire('event:02', $this); //source $this
        
    }
}