<?php
$loader = new \Phalcon\Loader();

$loader->registerClasses([
    'Multiphalcon\Vendor\Abccom\Filter\AddLink'=> APPLICATION_PATH . '/vendor/abccom/filter/AddLink.php',
    'Multiphalcon\Vendor\Abccom\Filter\RemoveCircumflex'=> APPLICATION_PATH . '/vendor/abccom/filter/RemoveCircumflex.php',
]);
    
$loader->registerNamespaces([
    'Multiphalcon\Vendor\Abccom\Service'=> APPLICATION_PATH . '/vendor/abccom/service',
]);

$loader->register();
