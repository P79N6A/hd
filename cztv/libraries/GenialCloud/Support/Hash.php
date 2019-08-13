<?php
namespace GenialCloud\Support;


interface Hash {

    public static function checkUser($user, $password);

    public static function encrypt($secret, $salt='');

    public static function check($hashedPassword, $secret, $salt='');

}