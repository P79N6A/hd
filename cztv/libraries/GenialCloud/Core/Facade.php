<?php

namespace GenialCloud\Core;

use Phalcon\Cli\Console\Exception;

trait Facade {

    protected static $core = null;

    private function __construct() {
    }

    public static function setCore(callable $core) {
        self::$core = $core;
    }

    protected static function initCore() {
        if(is_callable(self::$core)) {
            self::$core = call_user_func(self::$core);
        }
    }

    public static function __callStatic($name, $params) {
        self::initCore();
        if(!self::$core) {
            throw new Exception('Invalid '.__CLASS__.' component.');
        } elseif(method_exists(self::$core, $name)) {
            return call_user_func_array([self::$core, $name], $params);
        } else {
            throw new Exception('Undefined '.__CLASS__.' method '.$name);
        }
    }

}