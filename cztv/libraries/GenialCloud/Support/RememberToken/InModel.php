<?php
namespace GenialCloud\Support\RememberToken;

trait InModel {

    use RememberToken;

    public static function getByToken() {
        $token = self::fetchRememberToken();
        if($token) {
            return static::query()->andCondition('remember_token', $token)->first();
        }
        return false;
    }

    public static function saveToken($user_id, $token) {
        static::findFirst($user_id)->update(['remember_token' => $token]);
    }

}