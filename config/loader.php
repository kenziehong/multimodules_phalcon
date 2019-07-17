<?php
$loader = new \Phalcon\Loader();

$loader->registerClasses([
    'Multiphalcon\Vendor\Abccom\Filter\AddLink'=> APPLICATION_PATH . '/vendor/abccom/filter/AddLink.php',
    'Multiphalcon\Vendor\Abccom\Filter\RemoveCircumflex'=> APPLICATION_PATH . '/vendor/abccom/filter/RemoveCircumflex.php',
]);
    
// Class 'Multiphalcon\Chapter03\Controllers\Event01' not found in C:\wamp64\www\abc.com\multimodule\chapter03\controllers\EventManagerController.php on line 226
$loader->registerNamespaces([
    'Multiphalcon\Vendor\Abccom\Listener'=> APPLICATION_PATH . '/vendor/abccom/listener',
    'Multiphalcon\Vendor\Abccom\Event'=> APPLICATION_PATH . '/vendor/abccom/event',
    'Multiphalcon\Vendor\Abccom\Helper'=> APPLICATION_PATH . '/vendor/abccom/helper',
    'Multiphalcon\Vendor\Abccom\Service'=> APPLICATION_PATH . '/vendor/abccom/service',
    'Multiphalcon\Hello\Controllers'=> APPLICATION_PATH . '/hello/controllers',
]);

$loader->register();
