<?php
namespace Multiphalcon\Hello;

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
            'Multiphalcon\Hello\Controllers' => APPLICATION_PATH . '/hello/controllers',
            'Multiphalcon\Hello\Models' => APPLICATION_PATH . '/hello/models',
        ])->register();

        $loader->registerClasses([
            "Application\Vendor\Xyzcom\Grouprouter\Groupchapter"  => APPLICATION_PATH."/application/vendor2/xyzcom/grouprouter.php",
        ])->register();
        
        
    }

	/**
	 * Registers services related to the module
	 */
	public function registerServices(\Phalcon\DiInterface $dependencyInjector = null){
        $dependencyInjector->set('dispatcher', function(){
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace('Multiphalcon\Hello\Controllers');
            return $dispatcher;

        });

        $dependencyInjector->set('view', function(){
            $view =  new \Phalcon\Mvc\View();
            $view->setViewsDir(APPLICATION_PATH . '/hello/views');
            return $view;

        });

    }

}