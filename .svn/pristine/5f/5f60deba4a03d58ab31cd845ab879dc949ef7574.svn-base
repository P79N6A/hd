<?php

use GenialCloud\Core\Facade;

/**
 * Class Url
 *
 * @method static setDI(\Phalcon\DiInterface $dependencyInjector)
 * @method static \Phalcon\DiInterface getDI()
 * @method static \Phalcon\Mvc\Url setBaseUri($baseUri)
 * @method static \Phalcon\Mvc\Url setStaticBaseUri($staticBaseUri)
 * @method static string getBaseUri()
 * @method static string getStaticBaseUri()
 * @method static \Phalcon\Mvc\Url setBasePath($basePath)
 * @method static string getBasePath()
 * @method static string get($uri = null, $args = null, $local = null, $baseUri = null)
 * @method static string getStatic($uri = null)
 * @method static string path($path = null)
 */
class Url {
    use Facade;

    public static function getRefererElse($uri = null, $args = null, $local = null, $baseUri = null) {
        if(!$url = Request::getHTTPReferer()) {
            $url = Url::get($uri, $args, $local, $baseUri);
        }
        return $url;
    }

    public static function fetch($name, $params) {
        $params['for'] = $name;
        return self::get($params);
    }

}