<?php
namespace Multiphalcon\Vendor\Abccom\Helper;

use Phalcon\Tag;
class Zendhelper extends Tag {

    //echoo $this->tag->getTitle()
    public function hello($name) {
        echo 'hello'.$name;
    }

}
