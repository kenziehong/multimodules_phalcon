<?php
namespace Multiphalcon\Chapter03\Controllers;

use Multiphalcon\Vendor\Abccom\Helper\Zendhelper;

class HelperController extends \Phalcon\Mvc\Controller{

    //view helper
    public function index7Action(){
        new Zendhelper();
        
    }

}