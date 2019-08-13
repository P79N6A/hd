<?php


class VerifyCode {

    public static function send($mobile, $limit = 60, $expire_time = 1800) {
        $key = D::redisKey('smscode', $mobile);
        $data = json_decode(RedisIO::get($key), true);
        if (isset($data['time']) && (time() - $data['time'] <= $limit)) {
            return '';
        }
        $code = Message::sendCode($mobile);
        if ($code) {
            $value = ['mobile' => $mobile, 'time' => time(), 'code' => $code];
            RedisIO::SETEX($key, $expire_time, json_encode($value));
        }
        return $code;
    }

    /*
     * 大歌神短信
     * */
    public static function sendDGS($mobile, $limit = 60, $expire_time = 1800) {
        $key = D::redisKey('smscode', $mobile);
        $data = json_decode(RedisIO::get($key), true);
        if (isset($data['time']) && (time() - $data['time'] <= $limit)) {
            return '';
        }
        $code = Message::sendCodeDGS($mobile);
        if ($code) {
            $value = ['mobile' => $mobile, 'time' => time(), 'code' => $code];
            RedisIO::SETEX($key, $expire_time, json_encode($value));
        }
        return $code;
    }

    public static function validate($mobile, $code) {
        $key = D::redisKey('smscode', $mobile);
        $data = json_decode(RedisIO::get($key), true);
        return isset($data['code']) && $data['code'] == $code;
    }

    public static function remove($mobile) {
        $key = D::redisKey('smscode', $mobile);
        return RedisIO::del($key);
    }

}
