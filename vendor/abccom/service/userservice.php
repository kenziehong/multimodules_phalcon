<?php
namespace Multiphalcon\Vendor\Abccom\Service;

class UserService{
    protected $_facebook;
    protected $_yahoo;

    public function __construct($_facebookA = "default facebookA", $_yahooA = "default yahooA")
    {
        $this->_facebook = $_facebookA;
        $this->_yahoo = $_yahooA;
    }

    public function setFace($newFace){
        $this->_facebook = $newFace;
    }

    public function showInfo(){
        echo __METHOD__;
    }
}