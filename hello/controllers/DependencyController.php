<?php

//namespace Application\Controllers;

class DependencyController extends \Phalcon\Mvc\Controller{
    public function indexAction(){
        echo "application - DependencyController - indexAction ";

        //register service
        $di = $this->getDI();

        // echo "<pre>";
        //     print_r($di);
        // echo "</pre>";

        //set service
        // $di->set("index_service",function($name, $color){
        //     echo "hello index_service";
        //     echo '<h3 style="color:red"> Name:'.$name.'</h3>';
        //     echo '<h3 style="color:red"> Color:'.$color.'</h3>';
        // });

        // $di->set("index_service", 'Application\Vendor\Xyzcom\Service\UserService');        

        $di->set("index_service", function(){
            return new UserService('index-facebook','index-yahoo');
        });

        //$di->set("index_service", new UserService('index-facebook','index-yahoo'));
        $di->set("index_service",[
            "className" => 'Application\Vendor\Xyzcom\Service\UserService',
            'arguments' => [
                [
                    "type" => "parameter",
                    "value" => "index-facebook",
                ],
                [
                    "type" => "parameter",
                    "value" => "index-yahoo",
                ]
            ]
        ]);




        //call service
        //$di->get("index_service",["zendvn","brown"]);
        //$di["index_service"] = 'Application\Vendor\Xyzcom\Service\UserService';
        // $di["index_service"] = function(){
        //     return new UserService('index-facebook','index-yahoo');
        // }
        //$di->getIndex_service;
        $user = $di->get("index_service",[
            'index-facebook',
            'index-yahoo',            
        ]);
        $user->showInfo();
        echo '<h3 style="color:red"> Color:'.$user->showInfo.'</h3>';
        echo "<pre>";
            print_r($user);
        echo "</pre>";


    }


    public function index3Action(){
        //register
        $di = $this->getDI();
        $di->set("index3_service", 'Application\Vendor\Xyzcom\Service\UserService');

    }
}
