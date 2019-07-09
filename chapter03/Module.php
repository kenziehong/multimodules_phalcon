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
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace('Multiphalcon\Chapter03\Controllers');
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