<?php
namespace Multiphalcon\Hello\Controllers;

class IndexController extends \Phalcon\Mvc\Controller{

    // not support in controller
    // public function __construct(){
        
    // }

    //like __contruct(){}
    // public function initialize(){
    //     echo '<div>'.__FUNCTION__.'</div>';
    // }
    
    
    public function indexAction(){
    }
    
    public function index1Action($name = 'John'){
        echo 'hello: '.$name; 
    }
    
    //pass a lot of variables
    public function index2Action($name, $age, $color){
        echo 'hello: '.$name;
        echo '<br>age: '.$age;
        echo '<br>color: '.$color;
    }
    
    public function index3Action(){
        echo 'multiphalcon - module hello - IndexController - index3Action';
        echo __FUNCTION__; 
        echo __FILE__;
        echo __DIR__;
        $this->view->disable();
    }

    public function index4Action(){
        $strName = "nguyen van a";
        $this->view->setVar("strControllerView", $strName);

        $arrInfo = [
        'name'    =>'hong',
        'age'     =>28,
        'address' =>'vungtau'
        ];

        $this->view->setVar("arrControllerView",$arrInfo);  
        $this->view->pick("temp/pickview");        
    }

    public function index5Action($type){
        if($type == "pickview"){
            $this->view->pick("temp/pickview");
        }
    }

    
}