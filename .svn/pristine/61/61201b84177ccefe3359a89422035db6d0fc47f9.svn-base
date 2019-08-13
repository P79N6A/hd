<?php

use GenialCloud\Support\Hash as IHash;

class Hash implements  IHash {

    public static function encrypt($secret, $salt='', $md5=true) {
        if (empty($secret)) {
            return false;
        }
        if($md5&&self::is_md5($secret)) $md5 = false;
        $md5 && $secret = md5($secret);
        $secret = substr($secret, 7, 16);
        $secret = md5($secret . $salt);
        return $secret;
    }

    public static function is_md5($password) {
        return preg_match("/^[a-z0-9]{32}$/", strtolower($password));
    }

    public static function check($hashedPassword, $secret, $salt='') {
        return $hashedPassword == self::encrypt($secret, $salt, true);
    }

    public static function checkUser($user, $password) {
        return self::check($user->password, $password, $user->salt);
    }

    public static function createToken($id) {
        return sha1(Config::get('secret').$id);
    }

}