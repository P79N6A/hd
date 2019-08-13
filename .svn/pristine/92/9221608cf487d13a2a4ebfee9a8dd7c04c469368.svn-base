<?php

use GenialCloud\Core\Facade;

/**
 * Class MemcacheIO
 *
 * @method static bool add(string $key, mixed $value, int $expiration=0)
 * @method static bool addByKey(string $server_key, string $key, mixed $value, int $expiration=0)
 * @method static bool addServer(string $host, int $port, int $weight = 0)
 * @method static bool addServers(array $servers)
 * @method static bool append(string $key, string $value)
 * @method static bool appendByKey(string $server_key, string $key, string $value)
 * @method static bool cas(float $cas_token, string $key, mixed $value, int $expiration=0)
 * @method static bool casByKey(float $cas_token, string $server_key, string $key, mixed $value, int $expiration=0)
 * @method static int decrement(string $key, int $offset = 1, int $initial_value = 0, int $expiry = 0)
 * @method static int decrementByKey(string $server_key, string $key, int $offset = 1, int $initial_value = 0, int $expiry = 0)
 * @method static bool delete(string $key, int $time = 0)
 * @method static bool deleteByKey(string $server_key, string $key, int $time = 0)
 * @method static bool deleteMulti(array $keys, int $time = 0)
 * @method static bool deleteMultiByKey(string $server_key, array $keys, int $time = 0)
 * @method static array fetch()
 * @method static array fetchAll()
 * @method static bool flush(int $delay = 0)
 * @method static mixed get(string $key, callable $cache_cb=null, float &$cas_token=null)
 * @method static array getAllKeys()
 * @method static mixed getByKey(string $server_key, string $key, callable $cache_cb=null, float &$cas_token=null)
 * @method static bool getDelayed(array $keys, bool $with_cas=false, callable $value_cb=null)
 * @method static bool getDelayedByKey(string $server_key, array $keys, bool $with_cas=false, callable $value_cb=null)
 * @method static mixed getMulti(array $keys, array &$cas_tokens=[], int $flags=0)
 * @method static array getMultiByKey(string $server_key, array $keys, string &$cas_tokens='', int $flags=0)
 * @method static mixed getOption(int $option)
 * @method static int getResultCode()
 * @method static string getResultMessage()
 * @method static array getServerByKey(string $server_key)
 * @method static array getServerList()
 * @method static array getStats()
 * @method static array getVersion()
 * @method static int increment(string $key, int $offset = 1, int $initial_value = 0, int $expiry = 0)
 * @method static int incrementByKey(string $server_key, string $key, int $offset = 1, int $initial_value = 0, int $expiry = 0)
 * @method static bool isPersistent()
 * @method static bool isPristine()
 * @method static bool prepend(string $key, string $value)
 * @method static bool prependByKey(string $server_key, string $key, string $value)
 * @method static bool quit()
 * @method static bool replace(string $key, mixed $value, int $expiration=0)
 * @method static bool replaceByKey(string $server_key, string $key, mixed $value, int $expiration=0)
 * @method static bool resetServerList()
 * @method static bool set(string $key, mixed $value, int $expiration=0)
 * @method static bool setByKey(string $server_key, string $key, mixed $value, int $expiration=0)
 * @method static bool setMulti(array $items, int $expiration=0)
 * @method static bool setMultiByKey(string $server_key, array $items, int $expiration=0)
 * @method static bool setOption(int $option, mixed $value)
 * @method static bool setOptions(array $options)
 * @method static null setSaslAuthData(string $username, string $password)
 * @method static bool touch(string $key, int $expiration=0)
 * @method static bool touchByKey(string $server_key, string $key, int $expiration=0)
 */
class MemcacheIO {

    use Facade;

    public static function snippet($key, $expires, $callback) {
        if((!$r = self::get($key)) || !open_cache()) {
            $r = $callback();
            self::set($key, $r, $expires);
        }
        return $r;
    }

    public static function partial($key, $expires, $partial, $params=null) {
        if((!$r = self::get($key)) || !open_cache()) {
            $r = View::getPartial($partial, $params);
            self::set($key, $r, $expires);
        }
        return $r;
    }

}