<?php
namespace Multiphalcon\Chapter03;

/**
 * Phalcon\Mvc\ModuleDefinitionInterface
 *
 * This interface must be implemented by class module definitions
 */

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface{

    /**
	 * Registers an autoloader related to the module
	 */
    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null){
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces([
            'Multiphalcon\Chapter03\Controllers' => APPLICATION_PATH . '/chapter03/controllers',
            'Multiphalcon\Chapter03\Models' => APPLICATION_PATH . '/chapter03/models',
        ])->register();

        $loader->registerClasses([
            "Application\Vendor\Xyzcom\Grouprouter\Groupchapter"  => APPLICATION_PATH."/application/vendor2/xyzcom/grouprouter.php",
        ])->register();
        
        
    }

	/**
	 * Registers services related to the module
	 */
	public function registerServices(\Phalcon\DiInterface $dependencyInjector = null){

        #set service dispatcher
        $dependencyInjector->set('dispatcher', function(){
            //auto echo, dont need to call
            echo 'hello dispatcher';
            /** 
            01-beforeDispatchLoop       yes
            02-beforeDispatch           yes
            03-beforeExecuteRoute       yes
            04-initialize               no
            05-afterExecuteRoute        no
            06-beforeNotFoundAction     yes
            07-beforeException          yes
            08-afterDispatch            yes
            09-afterDispatchLoop        no
            */

            $eventManager = new \Phalcon\Events\Manager();

            //set event type, handler
            $eventManager->attach('dispatch:beforeDispatchLoop', function($event, $dispatcher){
                echo '<h3 style="color:red">01 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:beforeDispatch', function($event, $dispatcher){
                echo '<h3 style="color:red">02 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:beforeExecuteRoute', function($event, $dispatcher){
                echo '<h3 style="color:red">03 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:afterExecuteRoute', function($event, $dispatcher){
                echo '<h3 style="color:red">05 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:beforeNotFoundAction', function($event, $dispatcher){
                echo '<h3 style="color:red">06 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:beforeException', function($event, $dispatcher){
                echo '<h3 style="color:red">07 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:afterDispatch', function($event, $dispatcher){
                echo '<h3 style="color:red">08 - '.$event->getType().'</h3>';
            });

            $eventManager->attach('dispatch:afterDispatchLoop', function($event, $dispatcher){
                echo '<h3 style="color:red">09 - '.$event->getType().'</h3>';
            });

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace('Multiphalcon\Chapter03\Controllers');

            //executive event
            $dispatcher->setEventsManager($eventManager);

            return $dispatcher;

        });

        //set service view
        $dependencyInjector->set('view', function(){
            $view =  new \Phalcon\Mvc\View();
            $view->setViewsDir(APPLICATION_PATH . '/chapter03/views');
            return $view;

        });

        //set service chapter03, use for this module, all action belong to it
        $dependencyInjector->set('chapter03_service', function(){
            echo '<h3 style="color:red">chapter03_service -- Module.php -- chapter04 --</h3>';
        });

    }

}