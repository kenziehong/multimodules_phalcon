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
}
