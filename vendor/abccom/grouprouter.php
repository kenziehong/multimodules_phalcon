<?php

namespace Application\Vendor\Xyzcom\Grouprouter;

class Groupchapter extends \Phalcon\Mvc\Router\Group{
    public function initialize(){
        $this->setPaths([
            "controller" => "router",
        ]);

        $this->setPrefix("/groupapplication");

        $this->add("/edit",[
            "action" => "edit",
        ]);

        $this->add("/update",[
            "action" => "update",
        ]);
         


    }

}