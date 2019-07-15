<?php
namespace Multiphalcon\Chapter03\Controllers;

//Lesson 105
//get info about current index, controller, module
//get param 
//forward to other apache_get_version
//give important event
class DispatcherController extends \Phalcon\Mvc\Controller{

    public function indexAction(){
        echo '<br>=============indexAction==============Start</br>';
        echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        echo '<br>=============indexAction==============End</br>';
        //show include detailAction
        $this->dispatcher->forward([
            'action' => 'detail',

        ]);
    }

    public function index2Action(){
        echo '<br>=============index2Action==============Start</br>';
        echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        echo '<br>=============inde2xAction==============End</br>';
        //show include detailAction
        $this->dispatcher->forward([
            'action' => 'detail',
            'params' => [
                'name' => 'zendvn',
                'color' => 'green',
            ],

        ]);
    }

    public function index3Action(){
        echo '<br>=============index3Action==============Start</br>';
        echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        echo '<br>=============inde3xAction==============End</br>';
        //show include detailAction
        $this->dispatcher->forward([
            'controller' => 'dependency',
            'action' => 'research',
            'params' => [
                'name' => 'zendvn',
                'color' => 'green',
            ],

        ]);

        $this->dispatcher->forward([
            'namespace' => 'Multiphalcon\Hello\Controllers',
            'controller' => 'dependency',
            'action' => 'research',
            'params' => [
                'name' => 'zendvn',
                'color' => 'green',
            ],

        ]);
    }

    public function index5Action(){
        echo '<br>=============index5Action==============Start</br>';
        $this->dispatcher->setParam('school', 'A Chau');    
        $this->dispatcher->setParam('class', '12');  
        $this->dispatcher->setParams([
            'abc' => 'abc',
            'def' => 123,
        ]);
        
        //!view file of this action will show view file of detailAction
        $this->dispatcher->forward([
            'action' => 'detail',
        ]);  

        echo '<br>=============index5Action==============End</br>';

    }

    public function index7Action(){

        //get name 
        $module = $this->dispatcher->getModuleName();
        $controller = $this->dispatcher->getControllerName();
        $action = $this->dispatcher->getActionName(); //index7
        $activeMethod = $this->dispatcher->getActiveMethod();//index7Action
        $classController = $this->dispatcher->getControllerClass();//Multiphalcon\Chapter03\Controllers\DispatcherController
        $namespace = $this->dispatcher->getNamespaceName(); //Multiphalcon\Chapter03\Controllers
        $defaultNamespace = $this->dispatcher->getDefaultNamespace(); //Multiphalcon\Chapter03\Controllers

        //get object
        $activeController = $this->dispatcher->getActiveController();
        $activeMethod = $this->dispatcher->getActiveMethod();

        //get Factory Default
        $di = $this->dispatcher->getDI();


        echo '<h3 style="color:red">'.$module.'</h3>';
        echo '<h3 style="color:red">'.$controller.'</h3>';
        echo '<h3 style="color:red">'.$action.'</h3>';
        echo '<h3 style="color:red">'.$activeMethod.'</h3>'; 
        echo '<h3 style="color:red">'.$namespace.'</h3>'; 

        // echo '<pre>';
        //     print_r($activeController);
        // echo '</pre>';

    }

    public function index8Action() {
        echo '<br>=============index8Action==============Start</br>';
        echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        
        $this->dispatcher->forward([
            'namespace' => 'Multiphalcon\Hello\Controllers',
            'controller' => 'dependency',
            'action' => 'forward',
            ]);
            
            echo '<br>=============index8Action==============End</br>';
        }
        
        public function index9Action() {
            echo '<br>=============index9Action==============Start</br>';
                echo '<h3 style="color:red">'.__METHOD__.'</h3>';
                //$this->dispatcher->dispatch(); //vong lap vo tan

                //A dependency injection container is required to access the 'response' service
                $dependencyInjection = $this->getDI();
                $dispatcher = new \Phalcon\Mvc\Dispatcher();
                $dispatcher->setDI($dependencyInjection);

                $dispatcher->setActionName('detail');
                $dispatcher->setControllerName('dispatcher');
                $dispatcher->setModuleName('chapter04');

                //DispatcherController handler class cannot be loaded
                $dispatcher->setNamespaceName('Multiphalcon\Chapter03\Controllers');

                //it is inside index9, dif with forward - next to index9
                $dispatcher->dispatch();


                // echo '<pre>';
                //     print_r($dispatcher);
                // echo '</pre>';
            
            echo '<br>=============index9Action==============End</br>';
            
    }



    public function detailAction(){
        echo '<br>=============detailAction==============Start</br>';
            echo '<h3 style="color:red">'.__METHOD__.'</h3>';
            
            $params = $this->dispatcher->getParams();
            $name = $this->dispatcher->getParam('name', 'upper', 'default name');
            $color = $this->dispatcher->getParam('abc', null, 'default color');
            echo '<pre>';
                print_r($params);
            echo '</pre>';
            
            echo '<h3 style="color:red">'.$name.'</h3>';
            echo '<h3 style="color:red">'.$color.'</h3>';

        echo '<br>=============detailAction==============End</br>';

    }

}
