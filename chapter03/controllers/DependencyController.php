<?php
namespace Multiphalcon\Chapter03\Controllers;

use Multiphalcon\Vendor\Abccom\Service\UserService;

class DependencyController extends \Phalcon\Mvc\Controller{
    public function indexAction(){
        echo "application - DependencyController - indexAction ";

        //create object to register service 
        $di = $this->getDI();
        
        echo "<pre>";
            print_r($di);
        echo "</pre>";
        
        //set service and only use in this action
        $di->set("index_service",function(){
            echo "hello index_service";
        });

        //call service
        //Cách 1
        //$di->get("index_service");

        //Cách 2
        //$di["index_service"]);
        
        //Cách 3
        $di->getIndex_service;

    }
    
    public function index1Action(){
        $di = $this->getDI();
        $di->set("index_service",function($name, $color){
            echo "hello index_service";
            echo '<h3 style="color:red"> Name:'.$name.'</h3>';
            echo '<h3 style="color:red"> Color:'.$color.'</h3>';
        });
        
        $di->get("index_service",["zendvn","brown"]);
    }    

    public function index2Action(){
        $di = $this->getDI();

        //Cách 1
        // $di->set("index_service", function(){
        //     return new UserService('index-facebook','index-yahoo');
        // });

        //Cách 2
        //$di->set("index_service", 'Multiphalcon\Vendor\Abccom\Service\UserService');

        //Cách 3
        //$di["index_service"] = 'Multiphalcon\Vendor\Abccom\Service\UserService';

        //Cách 4
        $di->set("index_service", new UserService('index-facebook','index-yahoo'));

        //Cách 5
        // $di->set("index_service",[
        //     "className" => 'Multiphalcon\Vendor\Abccom\Service\UserService',
        //     'arguments' => [
        //         [
        //             "type" => "parameter",
        //             "value" => "index-facebook",
        //         ],
        //         [
        //             "type" => "parameter",
        //             "value" => "index-yahoo",
        //         ]
        //     ]
        // ]);
    
        $user = $di->get("index_service",[
            'index-facebook',
            'index-yahoo',            
        ]);

        //$user->showInfo();
        echo '<h3 style="color:red"> Color:'.$user->showInfo().'</h3>';
        echo "<pre>";
            print_r($user);
        echo "</pre>";
     
    }

    public function index3Action(){
        $di = $this->getDI();
        $services = $di->getServices();

        // echo "<pre>";
        //     print_r($services);
        // echo "</pre>";

        $di->set("index_service",[
            "className" => 'Multiphalcon\Vendor\Abccom\Service\ExampleService',
            'arguments' => [
                [
                    "type" => "service",
                    "name" => "response",
                ],
            ]
        ]);

        $di->get('index_service');

    }

    public function index4Action(){
        $di = $this->getDI();

        $di->set("index_service", function(){
            return new UserService();
        }, true);

        $user1 = $di->get('index_service');
        $user1->setFace('newFace');

        $user2 = $di->get('index_service');

        echo "<pre>";
            print_r($user1);
        echo "</pre>";

        echo "<pre>";
            print_r($user2);
        echo "</pre>";
    }
                
}
