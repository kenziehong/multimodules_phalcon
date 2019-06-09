<?php
namespace Multiphalcon\Hello\Controllers;

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
}