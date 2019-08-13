<?php

/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/5/5
 * Time: 10:23
 */
class My {
    protected static $ssourl = 'http://sso.cztv.com/api/';

    /**
     * @desc 由sso_token获取用户ID
     * @return int|string
     */
    public static function CurrentUserUid() {
        $user_id = $sso_tk = "";

        if (!empty($_REQUEST['sso_tk'])) {
            //非web端（移动或tv）
            $sso_tk = trim($_REQUEST['sso_tk']);
        } elseif (!empty($_COOKIE['sso_tk'])) {
            //web端，通过cookie走
            $sso_tk = trim($_COOKIE['sso_tk']);
        } elseif (!empty($_SERVER['HTTP_SSOTK'])) {
            //移动端新方法
            $sso_tk = trim($_SERVER['HTTP_SSOTK']);
        }

        if (empty($sso_tk)) {
            return $user_id;
        }
        //$user_id = Cola_Com::cache('_cache')->get('usertoken:'.md5($sso_tk));
        $user_id = RedisIO::get('usertoken:' . md5($sso_tk));
        if (empty($user_id)) {
            $token_info = self::Curl(self::$ssourl . 'checkTicket/tk/' . $sso_tk . '/need_expire/1');
            $token_info = json_decode($token_info, true);
            $user_id = isset($token_info['bean']['result']) ? intval($token_info['bean']['result']) : null;
            if (!empty($user_id) && !empty($token_info['expire'])) {
                $expire = ($token_info['expire'] > 30 * 86400) ? 30 * 86400 : $token_info['expire'];
                RedisIO::set('usertoken:' . md5($sso_tk), $user_id, $expire);
            }
        }
        return $user_id;
    }

    /**
     * @desc 从sso_token从获取用户全部信息
     * @return array
     */
    public static function CurrentUserBaseInfo() {
        $userinfo = array();
        $sso_tk = "";

        if (!empty($_REQUEST['sso_tk'])) {
            //非web端（移动或tv）
            $sso_tk = trim($_REQUEST['sso_tk']);
        } elseif (!empty($_COOKIE['sso_tk'])) {
            //web端，通过cookie走
            $sso_tk = trim($_COOKIE['sso_tk']);
        } elseif (!empty($_SERVER['HTTP_SSOTK'])) {
            //移动端新方法
            $sso_tk = trim($_SERVER['HTTP_SSOTK']);
        }
        if (empty($sso_tk)) {
            return $userinfo;
        }

        $tkinfo = self::Curl(self::$ssourl . 'checkTicket/tk/' . $sso_tk . '/need_expire/1?all=1');
        $tkinfo = json_decode($tkinfo, true);
        if (!empty($tkinfo['bean']['ssouid']) || !empty($tkinfo['expire'])) {
            $userinfo = $tkinfo['bean'];
            $userinfo += self::getUserProfile($userinfo['uid']);
        }
        return $userinfo;
    }

    /**
     * @desc curl http 请求
     * @param $destURL
     * @param string $paramStr
     * @param string $flag
     * @param string $name
     * @param string $password
     * @return mixed
     */
    public static function Curl($destURL, $paramStr = '', $flag = 'get', $name = '', $password = '') {
        if (!extension_loaded('curl')) {
            exit('php_curl.dll');
        }
        $curl = curl_init();
        if ($flag == 'post') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $paramStr);
        }
        curl_setopt($curl, CURLOPT_URL, $destURL);
        curl_setopt($curl, CURLOPT_TIMEOUT, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($name) && !empty($password)) {
            curl_setopt($curl, CURLOPT_USERPWD, "{$name}:{$password}");
        }
        $str = curl_exec($curl);

        // 失败重试一次
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            $str = curl_exec($curl);
        }
        // 失败重试一次
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            $str = curl_exec($curl);
        }
        curl_close($curl);
        return $str;
    }

    /**
     * @desc 获取用户扩展信息
     * @param $uid
     * @return array
     */
    public static function getUserProfile($uid) {
        $userinfo = array();
        $pinfo = self::Curl(self::$ssourl . 'getUserProfileByID?uid=' . $uid);
        $pinfo = json_decode($pinfo, true);
        if (!empty($pinfo['bean']['uid'])) {
            $userinfo = $pinfo['bean'];
        }
        return $userinfo;
    }


    /**
     * @desc 用户ID获取用户基础信息
     * @param int $uid
     * @return array
     */
    public static function FullUserInfoByUid($uid) {
        $userInfo = array();
        if (empty($uid)) {
            return $userInfo;
        }
        $ssoInfo = self::Curl(self::$ssourl . 'getUserByID/uid/' . $uid . '/dlevel/total');
        $ssoInfo = json_decode($ssoInfo, true);
        if (!empty($ssoInfo['bean']['uid'])) {
            $userInfo = $ssoInfo['bean'];
        }
        return $userInfo;
    }
}