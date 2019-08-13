<?php use GenialCloud\Core\Facade;
/**
 *
 * @class Config
 *
 * @method static bool offsetExists($index)
 * @method static string offsetGet($index)
 * @method static mixed offsetSet($index, $value)
 * @method static Phalcon\Config merge(Config $config)
 * @method static int count()
 *
 */
class Config {

    use Facade;

    /**
     * @param $name
     * @param null $default
     * @return \Phalcon\Config
     */
    public static function get($name, $default=null) {
        self::initCore();
        if(isset(self::$core->$name)) {
            return self::$core->$name;
        }
        return $default;
    }

}