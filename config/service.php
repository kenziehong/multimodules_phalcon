<?php

$di = new \Phalcon\Di\FactoryDefault();
$di->set('router', function(){
    return require_once APPLICATION_PATH . '/config/router.php';
});


