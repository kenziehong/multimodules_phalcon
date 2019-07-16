<?php
use Multiphalcon\Vendor\Abccom\Service\ExampleService;
use Multiphalcon\Vendor\Abccom\Helper\Zendhelper;

$di = new \Phalcon\Di\FactoryDefault();
$di->set('router', function(){
    return require_once APPLICATION_PATH . '/config/router.php';
});

//Lesson 103
//set at DI service.php which use for all modules
$di->set('application_service', function(){
    echo '<h3 style="color:red">application -- service.php</h3>';
});

//Lesson 106
$di->set('setting_service', function(){
    return new ExampleService();
});

// Lesson 116, can not access, because it has been registry in Module
// $di->set('dispatcher', function(){
//     echo 'hello dispatcher';
// });

//Lesson 73
$di->set('zendhelp', function(){
    return new Zendhelper();

});



