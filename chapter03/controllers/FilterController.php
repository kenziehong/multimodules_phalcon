<?php
namespace Multiphalcon\Chapter03\Controllers;

class FilterController extends \Phalcon\Mvc\Controller{

    public function indexAction(){
        //echo "multiphalcon-module chapter03-filterController-indexAction";
        $this->view->disable();
        $input = '<b><>@#string example123<<>>>></b>';
        $output = $this->filter->sanitize($input, 'string');
        echo 'Input: '.$input;
        echo '<hr>';
        echo 'Ouput: '.$output;
        //@#string example123>>

    }

    public function index1Action(){
        $input = 'zend2@g\mail(.c(o)m';
        $output = $this->filter->sanitize($input, 'email');
        echo 'Input: '.$input;
        echo '<hr>';
        echo 'Ouput: '.$output;
        //zend2@gmail.com

    }

    public function index2Action(){
        $input = '<b><>@#sTring ExamPle123<<>>>></b>';
        $output = $this->filter->sanitize($input, 'upper');
        echo 'Input: '.$input;
        echo '<hr>';
        echo 'Ouput: '.$output;
        //<>@#STRING EXAMPLE123<<>>>>

    }

    //filter int, float, alphanum, striptags, trim, lower

    //filter in view
    public function index3Action(){
        $this->view->name = "Nguyen Van A";
    }
}