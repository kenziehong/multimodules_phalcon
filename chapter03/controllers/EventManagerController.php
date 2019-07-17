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

    
    public function index3Action() {
        $eventManager = new Manager();
        $listener01 = function() {
            echo '<h3 style="color:red">event 01 - doing 01</h3>';
        };

        $eventManager->attach('event:01', $listener01);

        $eventManager->fire('event:01', $this); //source $this
        
    }

    public function index4Action() {
        $eventManager = new Manager();
        $eventManager->enablePriorities(true);

        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event 01 - doing 01</h3>';
        }, 20);

        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event 01 - doing 02</h3>';
        }, 30);

        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event 01 - doing 03</h3>';
        }, 10);

        $eventManager->attach('event:01', function(){
            echo '<h3 style="color:red">event 01 - doing 04</h3>';
        }, 100);

        if($eventManager->arePrioritiesEnabled() == true) {
            echo "enabled";

        } else {
            echo "disabled";
        }

        $eventManager->fire('event:01', $this); //source $this
        
    }

    public function index5Action() {
        $eventManager = new Manager();
        $data = [
            'name' => 'phalcon',
            'city' => 'hcm',
        ];

        //receive
        $eventManager->attach('event:02', function($event, $obj, $data){
            echo '<h3 style="color:red">event 02 - doing 01</h3>';

            //type event
            $event->setType('abc');
            echo $event->getType();
            
            // echo '<pre>';
            //     print_r($event->getSource());
            // echo '</pre>';

            $event->setData(['dataChange' => 'data-change']);
            echo '<pre>';
                print_r($event->getData());
            echo '</pre>';
        });

        //send 
        $eventManager->fire('event:02', $this, $data);
    }
}