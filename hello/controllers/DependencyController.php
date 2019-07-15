<?php

namespace Multiphalcon\Hello\Controllers;

class DependencyController extends \Phalcon\Mvc\Controller{
    public function index1Action(){
        $di = $this->getDI();

        //get service that is not set at hello module (at module chapter03)
        //Service 'chapter03_service' wasn't found in the dependency injection container
        //$di->get('chapter03_service');

        //get service that set at service.php
        $di->get('application_service');

    }

    public function researchAction(){
        echo '<br>=============researchAction==============Start</br>';
            echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        echo '<br>=============researchAction==============End</br>';
    }

    public function forwardAction(){
        echo '<br>=============researchAction==============Start</br>';
            $previousAction = $this->dispatcher->getPreviousActionName(); //index8
            $previousControl = $this->dispatcher->getPreviousControllerName(); //dispatcher
            $lastController = $this->dispatcher->getLastController(); //dispatcher obj

            echo '<pre>'; 
                print_r($lastController);
            echo '</pre>';

            echo '<h3 style="color:red">'.__METHOD__.'</h3>';
        echo '<br>=============researchAction==============End</br>';
    }
}
