<?php

class Auth {

    /**
     * 校验用到的模型名
     * @var string
     */
    protected static $authModel = 'Users';

    /**
     * 哈西
     * @var string
     */
    protected static $hasher = 'Hash';

    /**
     * 登陆令牌, 用于二次登陆
     * @var string
     */
    protected static $rememberToken = '';

    /**
     * 设置用户模型名
     * @param $name
     */
    public static function setAuthModel($name) {
        self::$authModel = $name;
    }

    /**
     * 设置Hash类名
     * @param $name
     */
    public static function setHasher($name) {
        self::$hasher = $name;
    }

    /**
     * 校验用户
     * @param array $data
     * @return array
     */
    protected static function validateUser(array $data) {
        /**
         * @var $authModel \Model
         */
        $authModel = self::$authModel;
        /**
         * @var $hasher \Hash
         */
        $hasher = self::$hasher;
        $query = $authModel::query();
        $password = $data['password'];
        unset($data['password']);
        foreach($data as $k => $v) {
            $query->andWhere("$k = :$k:", [$k => $v]);
        }
        $user = $query->execute()->getFirst();
        return [$user, !$user || !$hasher::checkUser($user, $password)];
    }

    /**
     * 校验并登陆
     * @param array $data
     * @param bool $is_remember
     * @param int $expires
     * @return bool
     */
    public static function attempt(array $data, $is_remember=false, $expires=2592000) {
        Event::fire('auth.attempt');
        list($user, $not_validated) = self::validateUser($data);
        if($not_validated) {
            return false;
        } else {
            if($is_remember) {
                $token = md5(uniqid(str_random()));
                /**
                 * @var $authModel \Model
                 */
                $authModel = self::$authModel;
                $authModel::saveToken($user->id, $token);
                Cookie::set('remember_token', $token, time() + $expires);
                Cookie::send();
            }
            return self::login($user);
        }
    }

    /**
     * 仅校验
     */
    public static function validate(array $data) {
        return !self::validateUser($data)[1];
    }

    /**
     * 登陆
     * @return bool
     */
    public static function login($user) {
        Event::fire('auth.login');
        Session::set('user', $user);
        setcookie('auth.login', 'admin', time() + 2700, '/', '.cztvcloud.com');
        setcookie('auth.login', 'admin' , time() + 2700, '/', '.cztv.com');
        return true;
    }

    /**
     * 退出
     */
    public static function logout() {
        Event::fire('auth.logout');
        Session::remove('user');
        Session::destroy();
        Cookie::set('remember_token', '', time()-690000);
        Cookie::send();
    }

    /**
     * 通过记住我来登陆
     */
    public static function viaRemember() {
        /**
         * @var $authModel \Model
         */
        $authModel = self::$authModel;
        $user = $authModel::getByToken();
        if($user) {
            return self::login($user);
        }
        return false;
    }

    /**
     * 是否已经登陆
     * @return bool
     */
    public static function check() {
        return Session::has('user') && Session::get('user');
    }

    /**
     * 获取用户数据
     */
    public static function user() {
        if(Session::has('user')) {
            return Session::get('user');
        } else {
            return null;
        }
    }

    /**
     * 返回用户ID
     */
    public static function id() {
        if($user = self::user()) {
            return $user->id;
        } else {
            return 0;
        }
    }

    /**
     * attempt 时候生成的二次登陆令牌
     * @return string
     */
    public static function token() {
        return self::$rememberToken;
    }

}