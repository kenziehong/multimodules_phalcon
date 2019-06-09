<?php
$loader = new \Phalcon\Loader();

$loader->registerClasses([
    'Multiphalcon\Vendor\Abccom\Filter\AddLink'=> APPLICATION_PATH . '/vendor/abccom/filter/AddLink.php'

]);

$loader->register();
