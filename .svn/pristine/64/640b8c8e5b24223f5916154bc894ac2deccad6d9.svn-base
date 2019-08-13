<?php
namespace GenialCloud\Support\RememberToken;

use \RedisIO;

trait InRedis {

    use RememberToken;

    protected static $rememberTokenExpires = 2592000;

    public static function getByToken() {
        $token = self::fetchRememberToken();
        if($token) {
            $id = RedisIO::get('_rmbtkn:'.$token);
            if($id) {
                return self::find($id);
            }
        }
        return false;
    }

    public static function saveToken($user_id, $token) {
        RedisIO::set('_rmbtkn:'.$token, $user_id, self::$rememberTokenExpires);
    }

}