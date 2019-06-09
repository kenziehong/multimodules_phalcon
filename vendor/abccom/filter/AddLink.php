<?php
namespace Multiphalcon\Vendor\Abccom\Filter;

class AddLink {

    public $param = [
        'keyword' => null,
        'link'    => null,
    ];

    public function __construct($option){
        $this->param['keyword'] = $option['keyword'];
        $this->param['link'] = $option['link'];
    }

   
    public function filter($input){
        
        //Zendvn => <a href='http://abc.com'>abc.com</a>
        $pattern = '#'.$this->param['keyword'].'#imSU';
        $replacement = '<a href="'.$this->param['link'].'">abc.com</a>' ;
        $output = preg_replace($pattern, $replacement, $input);
        
        echo '<pre>';
            print_r($this->param);
        echo '</pre>';
        return $output;
    }
    

}