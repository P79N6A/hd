<?php
class Input {

    protected static $inputs = [];
    protected static $results = [];
    protected static $init = false;
    protected static $isResultsArray = false;

    /**
     * 初始化输入, 含有模型用于表单获取值
     * @param array $inputs
     * @param mixed $rs
     */
    public static function init(array $inputs, $results=[]) {
        if(!self::$init) {
            if(is_array($results)) {
                self::$isResultsArray = true;
            } elseif (!is_object($results)) {
                throw new \Phalcon\Mvc\View\Exception('R results must be an array or an object.');
            }
            self::$inputs = $inputs;
            self::$results = $results;
            self::$init = true;
        }
    }

    /**
     * 通过 init 输入获取值
     * @param string $name
     * @return mixed
     * @throws \Phalcon\Mvc\View\Exception
     */
    public static function fetch($name) {
        if(self::$init) {
            $r = null;
            if(isset(self::$inputs[$name])) {
                $r = self::$inputs[$name];
            } elseif (!self::$isResultsArray) {
                if(isset(self::$results->$name)) {
                    $r = self::$results->$name;
                }
            } elseif (isset(self::$results[$name])) {
                $r = self::$results[$name];
            }
            return $r;
        } else {
            throw new \Phalcon\Mvc\View\Exception('R inputs has not been initialized.');
        }
    }

    /**
     * @param $name
     * @param $equals
     */
    public static function checked($name, $equals) {
        $c = '';
        if(self::fetch($name) == $equals) {
            $c = 'checked="checked"';
        }
        return $c;
    }

    /**
     * @param $name
     * @param $equals
     */
    public static function selected($name, $equals, $strict=false) {
        $c = '';
        if($strict) {
            if(self::fetch($name) === $equals) {
                $c = 'selected="selected"';
            }
        } else {
            if(self::fetch($name) == $equals) {
                $c = 'selected="selected"';
            }
        }
        return $c;
    }

}