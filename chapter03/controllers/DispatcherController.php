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
