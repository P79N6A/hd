<?php
namespace GenialCloud\Support\RememberToken;

use \Request;
use \Cookie;

trait RememberToken {

    public static function fetchRememberToken() {
        $token = false;
        if(Cookie::has('remember_token')) {
            $token = Cookie::get('remember_token');
        }
        if(!$token) {
            $token = Request::get('remember_token');
        }
        return $token;
    }

}