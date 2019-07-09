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
