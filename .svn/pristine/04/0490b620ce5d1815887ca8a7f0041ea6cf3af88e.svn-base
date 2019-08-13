<?php

use GenialCloud\Core\Facade;

/**
 * Class RedisIO
 *
 * @method static bool connect($host, $port = 6379, $timeout = 0.0)
 * @method static bool open($host, $port = 6379, $timeout = 0.0)
 * @method static bool pconnect($host, $port = 6379, $timeout = 0.0)
 * @method static null close()
 * @method static bool popen($host, $port = 6379, $timeout = 0.0)
 * @method static bool setOption($name, $value)
 * @method static int getOption($name)
 * @method static string ping()
 * @method static mixed client($command, $arg = '')
 * @method static mixed slowlog($command)
 * @method static bool expire($key, $expires)
 * @method static bool expireat($key, $timestamp)
 * @method static bool psetex($key, $ttl, $value)
 * @method static array|bool sScan($key, $iterator, $pattern = '', $count = 0)
 * @method static array|bool scan($iterator, $pattern = '', $count = 0)
 * @method static array|bool zScan($key, $iterator, $pattern = '', $count = 0)
 * @method static array hScan($key, $iterator, $pattern = '', $count = 0)
 * @method static string|bool get($key)
 * @method static bool set($key, $value, $timeout = 0)
 * @method static bool setex($key, $ttl, $value)
 * @method static bool setnx($key, $value)
 * @method static int del($key1, $key2 = null, $key3 = null)
 * @method static int delete($key1, $key2 = null, $key3 = null)
 * @method static \Redis multi()
 * @method static null exec()
 * @method static null discard()
 * @method static null watch($key)
 * @method static null unwatch()
 * @method static null subscribe($channels, $callback)
 * @method static bool exists($key)
 * @method static int incr($key)
 * @method static int decr($key)
 * @method static bool hSet($key, $hashKey, $value)
 * @method static bool hMset($key, $hashKeys)
 * @method static string hGet($key, $hashKey)
 * @method static int hLen($key)
 * @method static int hDel($key, $hashKey1, $hashKey2 = null, $hashKeyN = null)
 * @method static array HGetAll($key)
 * @method static array hMGet($key, $hashKeys)
 * @method static array hKeys($key)
 * @method static array hVals($key)
 * @method static bool hExists($key, $hashKey)
 * @method static int zCard($key)
 * @method static int zSize($key)
 * @method static float zScore($key, $member)
 * @method static int zAdd($key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null)
 * @method static int zRem($key, $member1, $member2 = null, $memberN = null)
 * @method static int zDelete($key, $member1, $member2 = null, $memberN = null)
 * @method static array zRange($key, $start, $end, $withscore = null)
 * @method static array zRevRange($key, $start, $end, $withscore = null)
 * @method static array zRangeByScore($key, $start, $end, array $options = array())
 * @method static array zRevRangeByScore($key, $start, $end, array $options = array())
 * @method static int sAdd($key, $value1, $value2 = null, $valueN = null)
 * @method static int sRem($key, $member1, $member2 = null, $memberN = null)
 * @method static bool sMove($srcKey, $dstKey, $member)
 * @method static bool sIsMember($key, $value)
 * @method static int sCard($key)
 * @method static string|bool sPop($key)
 * @method static string|bool sRandMember($key)
 * @method static array sInter($key1, $key2, $keyN = null)
 * @method static int sInterStore($dstKey, $key1, $key2, $keyN = null)
 * @method static array sUnion($key1, $key2, $keyN = null)
 * @method static int sUnionStore($dstKey, $key1, $key2, $keyN = null)
 * @method static array sDiff($key1, $key2, $keyN = null)
 * @method static int sDiffStore($dstKey, $key1, $key2, $keyN = null)
 * @method static array sMembers($key)
 */
class RedisIO {

    use Facade;

    /**
     * key name
     * @param string $key
     * @param string $id
     * @return string
     */
    public static function kn($key, $id) {
        return sprintf($key, $id);
    }

    /**
     * @param $key
     * @param $id
     */
    public static function hGetAllById($key, $id) {
        self::hGetAll(self::kn($key, $id));
    }

    /**
     * 获取新的score
     * @return float
     */
    public static function getScore() {
        return microtime(true);
    }

}