<?php
namespace Multiphalcon\Chapter03\Controllers;

class ShowController extends \Phalcon\Mvc\Controller{
    public function indexAction(){
        
        $arrInfo = [
            'name'    =>'hong',
            'age'     =>28,
            'address' =>'vungtau'
        ];
        
        //header('Content-Type: application/json');
        return json_encode($arrInfo);

    }

    public function index1Action(){

    }

    public function index2Action(){

    }

    public function index3Action(){

    }
}