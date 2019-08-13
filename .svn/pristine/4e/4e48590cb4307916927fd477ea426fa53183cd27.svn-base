<?php

use GenialCloud\Core\Facade;

/**
 * @class Session
 *
 * @method static start()
 * @method static setOptions($options)
 * @method static array getOptions()
 * @method static mixed get($index, $defaultValue = null)
 * @method static set($index, $value)
 * @method static bool has($index)
 * @method static bool remove($index)
 * @method static string getId()
 * @method static bool isStarted()
 * @method static bool destroy()
 */
class Session {

    /**
     * @var string
     */
    protected static $csrf_token='';

    use Facade;

    /**
     * @return string
     */
    public static function makeCsrfToken() {
        $token = md5(str_random());
        self::set('_token', $token);
        self::$csrf_token = $token;
        return $token;
    }

    /**
     * @param $input
     * @return bool
     */
    public static function verifyCsrfToken($input) {

        $token = self::get('_token', '');
        self::remove('_token');

        return $input && $token == $input;

    }

    /**
     * @return string
     */
    public static function getCsrfToken() {
        return self::$csrf_token;
    }

}