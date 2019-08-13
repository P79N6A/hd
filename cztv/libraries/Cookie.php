<?php

use GenialCloud\Core\Facade;

/**
 * Class Cookie
 *
 * @method static \Phalcon\Http\Response\CookiesInterface useEncryption($useEncryption)
 * @method static bool isUsingEncryption()
 * @method static \Phalcon\Http\Response\CookiesInterface set($name, $value = null, $expire = 0, $path = "/", $secure = null, $domain = null, $httpOnly = null)
 * @method static \Phalcon\Http\Cookie get($name)
 * @method static bool has($name)
 * @method static bool delete($name)
 * @method static bool send()
 * @method static \Phalcon\Http\Response\CookiesInterface reset()
 */
class Cookie {
    use Facade;
    
    public static function getValue($key){
        return self::get($key)->getValue();
    }
}