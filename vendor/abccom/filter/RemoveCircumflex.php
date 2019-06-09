<?php
namespace Multiphalcon\Vendor\Abccom\Filter;

class RemoveCircumflex {

    public function filter($input){
        
        $patternA = '#(á|à|ã|ả|ạ|ắ|ằ|ẵ|ẳ|ặ|ấ|ầ|ẫ|ẩ|ậ)#imSU';
        $replacementA = 'a';
        $output = preg_replace($patternA, $replacementA, $input);
        
        $patternD = '#(ó|ò|õ|ỏ|ọ|ố|ồ|ỗ|ổ|ộ|ớ|ờ|ỡ|ở|ợ)#imSU';
        $replacementD = 'o';
        $output = preg_replace($patternD, $replacementD, $output);

        $patternO = '#(đ)#imSU';
        $replacementO = 'd';
        $output = preg_replace($patternO, $replacementO, $output);

        return $output;
    }
    

}