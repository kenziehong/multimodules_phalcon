<?php

$di = new \Phalcon\Di\FactoryDefault();
$di->set('router', function(){
    $router = new \Phalcon\Mvc\Router();
    //$router->setDefaultModule('hello');
    $router->setDefaultModule('chapter03');

    $router->setDefaults([
        "controller" => "index",
        "action"     => "index",

    ]);

    $router->handle();

    //ADD NEW URL
    $router->add('/test1api',[
        'module' => 'hello',
        'controller' =>'show',
        'action' =>'index'
    ]);

    //test1
    $router->add('/test1',[
        'module' => 'hello',
        'controller' =>'index',
        'action' =>'index'
    ]);
    
    // //xyz.com/controller_name/abc/def/action_name/y/m/d
    // //                 1            2          3        4            5
    $router->add('/:controller/abc/:action/([0-9]{4})/([0-9]){2}/([0-9]{2})',[
        'controller'    => 1,
        'action'        => 2,
        "year"          => 3,
        "month"         => 4,
        "day"           => 5,
    ]);

    $router->add('/param/{year:[0-9]{4}}/{month:[0-9]{2}}/{day:[0-9]{2}}/{name}/end',[
        'controller'    => "router",
        'action'        => "getparam",
    ]);
    
    //set hostname - not work???
    // $router->add('/hostname',[
    //     "controller" => "router",
    //     "action"     => "host",
    
    // ])->setHostname("eafgghdh.com");
    
    //set route just use for post method
    $router->addPost("/post-router",[
        "controller"   => "router",
        "action"       => "post",
    
    ]);   
    
    //set route just use for get method
    $router->addGet("/get-router",[
        "controller"   => "router",
        "action"       => "get",
    
    ]); 
    
    //allow to use get, post method 
    $router->add("/via-post",[
        "controller"   => "router",
        "action"       => "post",
    
    ])->via(["POST","GET"]);
    
    
    // //create group of routes
    // $application = new \Phalcon\Mvc\Router\Group([
    //     "controller" => "router",
    // ]);
    // $application->setPrefix("/groupapplication");
    // $application->add("/edit",[
    //     "action" => "edit",
    
    // ]);
    // $application->add("/update",[
    //     "action" => "update",
    
    // ]);
    // $router->mount($application);
    
    //$router->mount(new Groupchapter());
    
    
    //convert parameters of router
    $router->add("/product/{name}",[
        "controller"  => "router",
        "action"      =>"getparam",
    ])->convert("name", function($name){
        $filter = new \Phalcon\Filter();
        $afterconvert = $filter->sanitize($name, "int");
        return $afterconvert;
    });
    
    
    $router->removeExtraSlashes(true);

    return $router;

});


