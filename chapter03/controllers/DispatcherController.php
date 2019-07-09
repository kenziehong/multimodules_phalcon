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

    public function detailAction(){
        echo '<br>=============detailAction==============Start</br>';
        echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        echo '<br>=============detailAction==============End</br>';

    }

}
