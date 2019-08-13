<?php namespace GenialCloud\Helper;

class Validator {

    public static function cnMobile($attribute, $value, $parameters) {
        return preg_match('#^1[3|4|5|7|8]\d{9}$#', $value);
    }

}