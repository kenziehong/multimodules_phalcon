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

    //depend on a place for setting services (module, controller, action) that we will be allow to use them
    //service.php => use for all modules
    //module.php => use for this module
    //controller-action => use for this action
    //! if we have same_service(the same name) at 3 places, it priorities at lowest place - Lesson 104
    //Lesson 102
    public function index9Action(){
        
        //create DI
        $di = $this->getDI();

        //set service
        $di->set('index9_service', function(){
            echo '<h3 style="color:red">index9_service -- dependencyController -- </h3>';
        });

        //get service that set at this action
        $di->get('index9_service');

        //get service that set at module chapter03
        $di->get('chapter03_service');

        //get service that set at service.php
        $di->get('application_service');


    }

    public function index10Action(){
        //create DI
        $di = $this->getDI();
        
        //get service that is not set at this action
        #Service 'index9_service' wasn't found in the dependency injection container
        $di->get('index9_service');

        //get service that set at module chapter03
        $di->get('chapter03_service');

    }

    //Lesson 106
    public function index13Action(){
        $di = $this->getDI();
        $service = $di->getService('setting_service');
        $name = $service->getName();
        $define = $service->getDefinition();

        //change define
        $service->setDefinition(function(){
            return new UserService();
        });

        //many obj can share properties
        $service->setShared(true);
        
        $obj = $service->resolve(); //obj belong to class Multiphalcon\Vendor\Abccom\Service\ExampleService before (User after)
        $obj2 = $service->resolve(); //obj belong to class Multiphalcon\Vendor\Abccom\Service\ExampleService before (User after)
        $obj->setFace('index13-facebook');


        // echo '<pre>';
        //     print_r($service);
        // echo '</pre>';

        // echo '<h3 style="color:red">Name of Service: '.$name.'</h3>';
        
        // echo '<pre>';
        //     print_r($define);
        // echo '</pre>';

        echo '<pre>';
            print_r($obj);
        echo '</pre>';

        echo '<pre>';
            print_r($obj2);
        echo '</pre>';

        // Phalcon\Di\Service Object --!!! object belongs to Phalcon\Di\Service
        // (
        //     [_name:protected] => setting_service 
        //     [_definition:protected] => Closure Object --!!!a function has passed at setting this service
        //         (
        //         )

        //     [_shared:protected] => 
        //     [_resolved:protected] => 
        //     [_sharedInstance:protected] => 
        // )

    }

}
