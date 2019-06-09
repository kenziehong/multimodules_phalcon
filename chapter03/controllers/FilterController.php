<?php
namespace Multiphalcon\Chapter03\Controllers;
use Multiphalcon\Vendor\Abccom\Filter\AddLink;
use Multiphalcon\Vendor\Abccom\Filter\RemoveCircumflex;

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

    //custom filter
    public function index4Action(){
        echo __FUNCTION__;
        echo '<hr>';

        //create custom filter
        $this->filter->add('lenght5', function($input){
            $len = strlen($input);
            if($len>5){
                $input = substr($input, 0, 5);
            }
            return $input;

        });

        //call custom filter
        $input = 'Nguyen Van A';
        $output = $this->filter->sanitize($input, 'lenght5');
        echo 'Input: '.$input;
        echo '<hr>';
        echo 'Ouput: '.$output;

    }

    //custom addLink
    public function index5Action(){
        //$addLink = new AddLink();
        $this->filter->add('addLink', new AddLink([
            'keyword'=> 'zendvn',
            'link' => 'http://abc.com',
        ]));

        $input = "Zendvn trung tam dao tao chuyen nghiep, zendvn luon co cac khoa hoc bo ich";
        $output = $this->filter->sanitize($input, 'addLink');
        echo 'Input: '.$input;
        echo '<hr>';
        echo 'Ouput: '.$output;
    }

    //custom remove circumflex
    public function index6Action(){
        $this->filter->add('rmcircumflex', new RemoveCircumflex());

        $input = "Zendvn là một trung tâm đào tạo chuyên nghiệp, zendvn luôn có các khóa học bổ ích";
        $output = $this->filter->sanitize($input, 'rmcircumflex');
        echo 'Input: '.$input;
        echo '<hr>';
        echo 'Ouput: '.$output;
    }
}