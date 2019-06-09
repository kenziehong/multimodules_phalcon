<?php

class RouterController extends Phalcon\Mvc\Controller{

    public function indexAction(){

    }
    public function index2Action(){

    }
    
    public function getparamAction(){
        $controller = $this->dispatcher->getControllerName();
        $action = $this->dispatcher->getActionName();
        $param = $this->dispatcher->getParams();
        $year = $this->dispatcher->getParam("year");
        $month = $this->dispatcher->getParam("month");
        $day = $this->dispatcher->getParam("day");
        $name = $this->dispatcher->getParam("name"); //,"upper"

        echo "Controller: ".$controller;
        echo "<hr>";
        echo "Action: ".$action;
        echo "<hr>";
        
        
        echo "<pre>";
        print_r($param);
        echo "</pre>";
        echo "<hr>";
        echo "Year: ".$year;
        echo "<hr>";
        echo "Month: ".$month;
        echo "<hr>";
        echo "Day: ".$day;
        echo "<hr>";
        echo "Name: ".$name;
    }
    
    public function hostAction(){
        echo __METHOD__;
    }

    public function postAction(){
        echo __METHOD__;
    }

    public function getAction(){
        echo __METHOD__;
    }

    public function editAction(){
        echo __METHOD__;
    }

    public function updateAction(){
        echo __METHOD__;
    }
}
