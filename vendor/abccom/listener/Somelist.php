<?php
namespace Multiphalcon\Vendor\Abccom\Listener;

class Somelist {
    public function beforeShow() {
        echo '<h3 style="color:red">before show info</h3>';
    }
    
    public function afterShow() {
        echo '<h3 style="color:red">after show info</h3>';
        
    }
}