<?php
/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/4/29
 * Time: 9:37
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

require_once(APP_PATH . 'libraries/Plugin/Util.php');

class User extends Model {
    //private $avatar = '/cztv/tmp/avatar/';//第三方头像存放目录
    private $_mcEnable = true;//是否缓存
    private $_mcPrefix_auth = 'BAD_COMMENTS::';//审核系统 用户屏蔽
    private $avatar = "D:\CZTV\cms\upload\avater\\";//TEST TODO
    protected $sso_reg_user_key = 'sso::reg:ip:sum:';//单个ip下注册用户的数量
    protected $_sso_reg_limit = 'sso::reg::limit:';//用户中心用户注册频次限制
    protected $sso_uid_key = 'sso::uid:';//基础信息
    protected $_sso_send_email_times_key = 'sso::sendemail:';//发送邮件次数限制
    protected $_sso_mobile_check_times_key = 'sso::mobilecheck:';//手机存在检查次数限制
    protected $_sso_email_check_times_key = 'sso::emailcheck:';//邮箱存在检查次数限制

    protected $_sso_reset_code_key = 'sso::authcode:';//手机重置密码验证码 客户端使用
    protected $sso_user_login_error_times = 'sso::user::login::error::times::';//用户登录错误次数

    const SIGNSYSLOGNG = false;
    const sso_auth_code_key = 'sso::authcode:';//手机注册验证码 客户端使用
    protected $_sso_block_counter_key = 'sso::bcounter:';//防刷计数器key值
    protected $userlogin_table = 'userlogin';
    protected $sso_active_email_link = 'sso::activeemail:';//用户激活邮件链接合法性验证
    protected $_sso_info_email_key = 'sso::info::email:';//用户基础信息
    protected $_sso_info_mobile_key = 'sso::info::mobile:';//用户基础信息
    protected $_sso_info_nickname_key = 'sso::info::nickname:';//用户基础信息
    protected $sso_loginname_login_is_limited = 'sso::loginname::login::is::limited::'; // loginname 是登录限制
    protected $sso_ip_login_is_limited = 'sso::ip::login::is::limited::'; // ip登录成功次数
    protected $sso_ip_login_success_times = 'sso::ip::login::success::times::'; // ip登录成功次数
    protected $sso_loginname_login_error_times = 'sso::loginname::login::error::times::';  // loginname 登录失败次数
    protected $sso_ip_login_error_times = 'sso::ip::login::error::times::';//ip登录错误次数
    protected $_sso_send_msg_times_key = 'sso::sendmsg:';//发送手机短信次数限制
    protected $_sso_client_send_msg_times_key = 'sso::clientsendmsg:';//单个平台发送手机短信次数
    protected $sso_ip_reg_is_limited = 'sso::ip::reg::is::limited::'; // ip注册限制
    protected $sso_ip_reg_success_times = 'sso::ip::reg::success::times::'; // ip注册成功次数


    //token平台来源，token前缀
    private $__platform = array(
        'test_p' => '999',//提供给各个客户端测试专用
        'def' => '101',//default
        'www' => '102',//PC
        'web' => '103',//m站
        'app' => '104',//app
        'tv' => '105',//中国蓝TV tv端
        'news' => '106',//中国蓝新闻客户端
    );

    /**
     * @desc 检查手机号格式是否合法
     * @version 2015-06-01
     * @param string $mobile
     */
    public function checkMobile($mobile) {
        if (empty($mobile)) {
            return false;
        }
        $phone_list = RedisIO::get('phone_list');
        $phone_list = json_decode($phone_list, true);
        if (empty($phone_list)) {
            $phone_list = PhoneList::find()->toArray();
            RedisIO::set('phone_list', json_encode($phone_list), '3600');
        }
        foreach ($phone_list as $val) {
            $tt = strpos($mobile, $val['phone_num']);
            if ($tt === 0) return true;
        }
        if (empty($phone_list)) {
            //正则表达式:中国大陆手机号
            if (preg_match('/^(1[3|4|5|7|8]|00886|0064)\d{9}$/', $mobile)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @desc 检查jsonp callback
     * @version 2015-06-01
     * @param string $jsonpcallback
     * @return boolean|number
     */
    public function checkJsonCallBackPara($jsonpcallback = '') {
        if (!is_string($jsonpcallback)) {
            return false;
        }
        return preg_match('/^[0-9a-zA-Z_]+$/u', $jsonpcallback);
    }

    /**
     * @desc 对防刷计数器减一，配合blockCounter函数，当操作失败时，对已经加了的计数器做减一操作
     * @version 2015-06-02
     * @param unknown $key
     */
    public function decrCounter($key) {
        $key = $this->_sso_block_counter_key . $key;
        //$cnt = RedisIO::increment($key, -1);
        $cnt = RedisIO::get($key);
        RedisIO::set($key, $cnt - 1);
    }


    /**
     * @desc 防刷的计数器，每调用一次加1，如果计数器大于$limit 就返回false
     * @version 2015-06-01
     * @param
     * $key 对应的防刷key
     * $limit 限制次数
     * $expire 过期时间（秒），默认false明天
     */
    public function blockCounter($key, $limit, $expire = false) {
        $key = $this->_sso_block_counter_key . $key;
        date_default_timezone_set('Asia/Shanghai');
        //$cnt = RedisIO::increment($key);//为某一个已存在的键对应的值进行加1（实际上是加法运算， 默认加1）,成功返回1，失败返回false
        //如果没有过期时间传入，则默认为自然日的第二天
        if (false === $expire) $expire = strtotime(date("Y-m-d",time()))+86400-time();
        $cnt = RedisIO::get($key);
        if (false === $cnt) {
            RedisIO::set($key, 1, $expire);
            $cnt = 1;
        } else {
            RedisIO::set($key, ++$cnt, $expire);
        }

        if ($cnt > $limit) return false;
        return true;
    }

    /**
     * @desc 客户端调用存储手机注册激活码
     * @param
     * $mobile 手机号码
     * $plat 平台
     * $action 行为
     * $cacheTime 缓存时间默认86400秒
     * $code_key 默认手机注册验证码key【客户端使用】
     */
    public function storeClientAuthCode($mobile, $plat, $action, $cacheTime = 86400, $code_key = self::sso_auth_code_key) {
        if (empty($mobile) || empty($plat) || empty($action)) {
            return false;
        }
        $cacheKey = $code_key . $mobile . $plat . $action;
        $code = RedisIO::get($cacheKey);
        $this->signSysLog('sso', 'msgdebug', 'sso', 'test', array("get:", $cacheKey, var_export($code, true)));
        if (empty($code)) {
            //$code = rand(100000, 999999);由于新的短信接口只支持4位数
            $code = rand(1000, 9999);
            //确保同一个手机在同一个平台上的同一个操作的激活码是唯一的
            $flag = RedisIO::set($cacheKey, $code, $cacheTime);
            $this->signSysLog('sso', 'msgdebug', 'sso', 'test', array("set", $cacheKey, "code", $code, "exp", $cacheTime, "ret", var_export($flag, true)));
            if ($flag) {
                return $code;
            } else {
                return false;
            }
        } else {
            //重新设置缓存时间
            $flag = RedisIO::set($cacheKey, $code, $cacheTime);
            if ($flag) {
                return $code;
            } else {
                return false;
            }
        }
    }


    /**
     * @desc 检查手机号是否存在
     * @version 2015-06-01
     * @param
     * $mobile 手机号
     * $iscount true检测是否存在，false返回uid,loginname,status
     */
    public function checkMobileExists($mobile, $iscount = true) {
        if (empty($mobile)) {
            return true;
        }
        try {
            $partition_by = User::getHashTable($mobile);
            $mobile = addslashes($mobile);
            if ($iscount) {
                $flag = Userlogin::checkMobile($mobile, $partition_by);
                return !empty($flag);
            } else {
                $r = Userlogin::checkMobile($mobile, $partition_by);
                $r = $r ? $r->toArray() : $r;
                !empty($r['loginname']) && $r['mobile'] = $r['loginname'];

                return $r;
            }
        } catch (Exception $e) {
            return true;
        }
    }


    /**
     * @desc 检查手机激活码
     * @param string $mobile
     * @param string $plat
     * @param string $action
     * @param string $code
     * @param string $code_key
     * @return boolean
     */
    public function checkClientAuthCode($mobile, $plat, $action, $code, $code_key = self::sso_auth_code_key) {
        if (empty($mobile) || empty($plat) || empty($action) || empty($code)) {
            return false;
        }
        $res = $this->getClientAuthCode($mobile, $plat, $action, $code_key);

        $this->signSysLog('sso', 'msgdebug', 'sso', 'test', array("check", $code_key . $mobile . $plat . $action, "code", $code, "res", $res));
        if (empty($res)) {
            return false;
        } else {
            if ($res != $code) {
                return false;
            } else {
                $this->clearClientAuthCode($mobile, $plat, $action, $code_key);
                return true;
            }
        }
    }

    /*
     * 敏感词过滤方法，暂时未完成
     */
    public function nicknameFilter($content) {//TODO
        return false;
    }

    /**
     * @desc 根据手机号码获得随机昵称
     * @version 2015-06-01
     * @param string $mobile
     * @return boolean|Ambigous <string, mixed>
     */
    public function getNameByMobile($mobile) {
        if (empty($mobile)) {
            return false;
        }
        $len = strlen($mobile);
        if ($len == 11) {//中国大陆手机号
            $nickname = preg_replace("/(\d{4})(\d{3})(\d{4})/", "$1xxx$3", $mobile);
        } else if ($len == 12) {
            //006586995835
            $nickname = preg_replace("/(\d{7})(\d{3})(\d{2})/", "$1xxx$3", $mobile);
        } else if ($len == 13) {
            //0064212618328
            $nickname = preg_replace("/(\d{7})(\d{3})(\d{3})/", "$1xxx$3", $mobile);
        } else if ($len == 14) {
            //00886918901515
            $nickname = preg_replace("/(\d{8})(\d{3})(\d{3})/", "$1xxx$3", $mobile);
        }
        if ($this->checkNicknameExists($nickname)) $nickname = $nickname . '_' . rand(100, 999);
        return $nickname;
    }

    /**
     * @desc 检查邮箱是否存在
     * @version 2015-06-07
     * @param unknown $email
     * @param string $iscount
     * @return boolean|number|unknown
     */
    public function checkEmailExists($email, $iscount = true) {
        if (empty($email)) {
            return true;
        }
        try {
            $partition_by = User::getHashTable($email);
            $email = addslashes($email);
            if ($iscount) {
                $flag = Userlogin::checkMobile($email, $partition_by);
                return !empty($flag);
            } else {
                $r = Userlogin::checkMobile($email, $partition_by);
                $r = $r ? $r->toArray() : $r;
                !empty($r['loginname']) && $r['mobile'] = $r['loginname'];
                return $r;
            }
        } catch (Exception $e) {
            return true;
        }
    }


    public function checkPwd($pwd) {
        //return preg_match('~^[a-z|0-9|\~\!\@\#\$\%\^\&\*\(\)\[\]\{\}\:\;\"\'\,\.\<\>\?\/\_\\\+\=\-]{6,20}$~is', $pwd);
        // return preg_match('/^\w\W{6,20}$/is', $pwd);
        return preg_match('/^[^&]{6,20}$/is', $pwd);
    }

    /**
     * @desc 更新用户密码
     * @version 2015-06-09
     * @param sting $sso_tk
     * @param string $pwd
     * @param string $oldpwd
     * @throws Exception
     * @return mixed
     */
    public function updateUserPassword($sso_tk, $pwd, $oldpwd) {
        if (!($sso_tk && $pwd && $oldpwd)) {
            throw new Exception('参数错误');
        }

        $url = 'http://sso.cztv.com/api/modifyPwd';
        $data = array(
            'tk' => $sso_tk,
            'oldpwd' => $oldpwd,
            'newpwd' => $pwd
        );
        $response = curl_request($url, 'post', $data);
        $response = json_decode($response, true);
        if (empty($response)) {
            throw new Exception('请求超时');
        }

        return $response;
    }

    /**
     * @desc 更新用户信息
     * @version 2015-06-09
     * @param array $userinfo
     * @throws Exception
     * @return mixed
     */
    public function updateUserinfo(array $userinfo) {
        $url = 'http://sso.cztv.com/api/updateUserInfo?';
        foreach ($userinfo as $key => $value) {
            $url .= "{$key}={$value}&";
        }
        $url = substr($url, 0, -1);
        $response = curl_request($url, 'get', array(), 5, true);
        $response = json_decode($response, true);
        if (empty($response)) throw new Exception ('请求超时');
        return $response;
    }

    public function getUserByIdAll($uid) {
        if (empty($uid)) {
            return false;
        }
        $users_partition_by = self::getHashTable($uid);
        $data = Users::findFirst("uid={$uid} and partition_by={$users_partition_by}");
        $data = $data ? $data->toArray() : $data;
        $data['email'] == $data['uid'] && $data['email'] = '';
        $data['mobile'] == $data['uid'] && $data['mobile'] = '';
        //获取用户的授权信息
        if (!empty($data['email'])) {
            $userlogin_partition_by = self::getHashTable($data['email']);
            $loginname = $data['email'];
        } elseif (!empty($data['mobile'])) {
            $userlogin_partition_by = self::getHashTable($data['mobile']);
            $loginname = $data['mobile'];
        } elseif (!empty($data['username'])) {
            list($pre) = explode('_', $data['username']);
            if (in_array($pre, array(1 => 'sina', 2 => 'qq', 3 => 'weixin'))) {
                $userlogin_partition_by = '';
            } else {
                $userlogin_partition_by = self::getHashTable($data['username']);
                $loginname = $data['username'];
            }
        }
        if (!empty($userlogin_partition_by) || $userlogin_partition_by === '') {
            $loginname = addslashes($loginname);
            $dataAuth = Userlogin::findFirst("loginname='{$loginname}' and partition_by={$userlogin_partition_by}");
            $dataAuth = $dataAuth ? $dataAuth->toArray() : $dataAuth;
            if (!empty($dataAuth)) {
                !empty($dataAuth['password']) && $data['pwd'] = $dataAuth['password'];
                !empty($dataAuth['salt']) && $data['cdkey'] = $dataAuth['salt'];
            }
        }
        return $data;
    }

    public function setCookie($name, $value, $domain = 'cztv.com', $time = 0) {
        if (empty($name) || empty($value)) {
            return false;
        }
        header('P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"');//p3p
        Cookie::set($name, $value, $time, '/', null, $domain, false); //设置cookie
        Cookie::send();
    }


    /**
     * @desc 邮箱激活邮件下发
     * @version 2015-06-02
     * @param string $email
     * @param string $uid
     * @param string $from
     * @param string $next_action
     * @return Ambigous <boolean, mixed>
     */
    public function sendActiveEmail($email, $uid, $from = "", $next_action = "") {
        $verify = Plugin_Util::authcode($uid, 'ENCODE', ACTIVE_EMAIL_KEY, 3600 * 24);//1天有效
        $verify = urlencode(base64_encode($verify));
        $url = 'http://sso.cztv.com/user/activeEmail/verify/' . $verify;
        if (!empty($_GET['next_action'])) {
            $next_action = $_GET['next_action'];
        }
        !empty($from) && $url .= '?from=' . $from;
        if (isset($next_action) && !empty($next_action) && empty($from)) {
            $url .= '?next_action=' . trim(urlencode($next_action));
        } elseif (isset($next_action) && !empty($next_action) && !empty($from)) {
            $url .= '&next_action=' . trim(urlencode($next_action));
        }
        $infoStr = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $infoStr .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $infoStr .= '<head>';
        $infoStr .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $infoStr .= '<title>用户中心</title>';
        $infoStr .= '</head>';
        $infoStr .= '<body>';
        $infoStr .= '<p style="margi-top:35px;margin-left:25px;">尊敬用户，您好！</p>';
        $infoStr .= '<p style="margin-left:25px;font-size:14px; color:#444444; font-family:\'微软雅黑\';">请点击以下链接进行邮箱激活</p>';
        $infoStr .= '<p style="margin:20px 0 20px 25px;"><a href="' . $url . '" style="display:block; width:180px; height:43px; overflow:hidden; font-family:\'微软雅黑\'; line-height:43px; text-align:center; font-size:16px; background-color:#419ce3; border:1px solid #419ce3; color:#ffffff;text-decoration:none;" title="马上验证邮箱">马上验证邮箱</a></p>';
        $infoStr .= '<p style="margin-left:25px;font-size:14px; color:#444444; font-family:\'微软雅黑\';">如果您无法点击以上链接，请复制以下网址到浏览器里直接打开：：<a href="' . $url . '" style="color:#529bef; font-size:14px; text-decoration:underline;">' . $url . '</a></p>';
        $infoStr .= '<p style="margin-left:25px;color:#c6c6c6; font-family:\'微软雅黑\'; font-size:14px;">这是一封自动发送的邮件,请不要直接回复</p>';
        $infoStr .= '</body>';
        $infoStr .= '</html>';
        return Plugin_Util::send_mail($email, '中国蓝TV账号激活', $infoStr);
    }


    /**
     * @desc 检查激活用户邮箱链接是否第一次点击
     * @version 2015-06-07
     * @param unknown $uid
     * @return boolean
     */
    public function checkActiveEmailLink($uid) {
        if (empty($uid)) {
            return false;
        }
        $cacheKey = $this->sso_active_email_link . $uid;
        $cacheValue = RedisIO::get($cacheKey);
        //第一次点击链接
        if (!$cacheValue) {
            RedisIO::set($cacheKey, $uid, 86400);
            return true;
        } else {
            //不是第一次链接
            return false;
        }
    }


    /**
     * @desc 清空手机激活码
     * @version 2015-06-01
     * @param string $mobile
     * @param string $plat
     * @param string $action
     * @param string $code_key
     * @return boolean
     */
    public function clearClientAuthCode($mobile, $plat, $action, $code_key = self::sso_auth_code_key) {
        if (empty($mobile) || empty($plat) || empty($action)) {
            return false;
        }
        $cacheKey = $code_key . $mobile . $plat . $action;
        RedisIO::delete($code_key . ':times' . $mobile . $plat . $action);
        return RedisIO::delete($cacheKey);
    }


    /**
     * @desc 获取手机激活码
     * @version 2015-06-01
     * @param string $mobile
     * @param string $plat
     * @param string $action
     * @param string $code_key
     * @return boolean|string
     */
    public function getClientAuthCode($mobile, $plat, $action, $code_key = self::sso_auth_code_key) {
        if (empty($mobile) || empty($plat) || empty($action)) {
            return false;
        }
        $cacheKey = $code_key . $mobile . $plat . $action;
        $checkTimesKey = $code_key . ':times' . $mobile . $plat . $action;
        $checkTimes = RedisIO::get($checkTimesKey);
        if (!$checkTimes) {
            RedisIO::set($checkTimesKey, 1, 86400);
        } elseif ($checkTimes < 3) {
            //RedisIO::increment($checkTimesKey);
            $num = RedisIO::get($checkTimesKey);
            RedisIO::set($checkTimesKey, $num + 1, 86400);
        } else {
            RedisIO::delete($checkTimesKey);
            RedisIO::delete($cacheKey);
            return '';
        }
        return RedisIO::get($cacheKey);
    }


    //更改用户状态  0:被禁掉 1:正常 2:邮箱未激活
    public function modifyStatus($uid, $status) {
        if (empty($uid)) {
            return true;
        }
        $userBaseinfoTable = User::getHashTable($uid);
        try {
            Users::modifyStatus($uid, $userBaseinfoTable, $status);
            $userBase = Users::getUserByUid($uid);
            if (empty($userBase)) {
                return false;
            }
            if (!empty($userBase->email)) {
                $emailAuthTable = User::getHashTable($userBase->email);
                Userlogin::modifyStatus($uid, $emailAuthTable, $status);
            }
            if (!empty($userBase->mobile)) {
                $mobileAuthTable = User::getHashTable($userBase->mobile);
                Userlogin::modifyStatus($uid, $mobileAuthTable, $status);
            }
        } catch (Exception $e) {

        }
        $this->_clearUserInfoCache($uid);

        /**
         * 兼容旧流程，数据双写
         */
//        $sqlOld = "update userinfo SET status = '" . $status . "' where uid = '" . $uid . "'";
//        try {
//            $this->_passportdb->sql($sqlOld);
//        } catch (Exception $e) {
//
//        }

        return true;
    }


    /**
     * @desc 退出 清除cookie
     * @version 2015-06-07
     */
    public function LoginOutSsoCookie() {
        header('P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"');//p3p
        $time = time() - 3600 * 24 * 30;
        Cookie::set('sso_tk', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('ssouid', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('sso_nickname', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('sso_picture', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('m', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('utype', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('lfrom', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('flogin', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('casflag', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('u', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('ui', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('j-sync', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('loginname', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('sso_icon', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('baidu_uid', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('baidu_url', '', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('first_login', '', time() - 86400 * 30 * 6, '/', null, '.cztv.com', false);
        Cookie::set('csrf', '', time() - 86400 * 30 * 6, '/', null, '.cztv.com', false);
        Cookie::send();
    }


    // 验证跳转链接，防止跳转漏洞
    public function checkNextAction($url) {
        if (empty($url)) {
            return false;
        }
        $urlArr = parse_url($url);
        $host_arr = explode('.', $urlArr['host']);
        $c_host = count($host_arr);
        if (count($host_arr) >= 3) {
            $host = $host_arr[$c_host - 2] . '.' . $host_arr[$c_host - 1];
        } else {
            $host = $urlArr['host'];
        }
        $whiteList = array(
            'cztv.com' => 1,
        );
        return isset($whiteList[$host]);
    }

    /**
     * @desc 跨平台用户登录userkey
     * @version 2015-06-03
     * @param unknown $tk
     * @param unknown $key
     * @param string $encode
     * @param number $time
     * @return Ambigous <string, mixed>
     */
    public function platUserkey($tk, $key, $encode = true, $time = 600) {
        if ($encode) {
            return Plugin_Util::authcode($tk, 'ENCODE', $key, $time);//有效期10分钟
        } else {
            return Plugin_Util::authcode($tk, 'DECODE', $key, $time);//有效期10分钟
        }
    }

    /**
     * @desc 用户请求来源处理方法
     * @param $from
     * @return array|mixed|string
     */
    public function handleURLWhitelist($from) {
        if (empty($from)) {
            $from = 'my';
        } elseif ($from != 'my') {
            $fromList = array(
                'www.cztv.com' => 'www',
                'sso.cztv.com' => 'sso',
                'm.cztv.com' => 'web',
            );
            if (strpos($from, '.com') > 0) {
                $from = substr($from, 7);
                $from = explode('/', $from);
                $from = $fromList[$from[0]];
                $from = !empty($from) ? $from : 'my';//处理来源白名单
            } else {
                return $from;
            }
        }
        return $from;
    }

    //记录日志
    public function signSysLog($floder, $file, $operator, $source, $message) {
        if (!SIGNSYSLOGNG) {
            return false;
        }
        try {
            $property['datetime'] = date("Y-m-d H:i:s"); //设置日志时间
            $property['clientip'] = $_SERVER['REMODE_ADDR']; //设置IP
            $property['operator'] = $operator; //设置操作者
            $property['source'] = $source; //设置来源
            $property['message'] = implode('&', json_encode($message)); //设置日志内容
            $directory = '/tmp/' . $floder . '/' . $file . '.log';// /tmp/sso/ /tmp/api /tmp/my/ /tmp/favorites/
            error_log(json_encode($property) . "\r\n", 3, $directory);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @desc 保存图片
     * @param $url 保存的链接
     * @param int $method
     * @return bool|string 保存的字节数，失败则返回 false
     */
    public function saveAvatarFile($url, $method = 1) {
        // mime 和 扩展名 的映射
        $mimes = array(
            'GIF' => 'gif',
            'JPEG' => 'jpg',
            'PNG' => 'png',
        );
        // 如果符合我们要的类型
        $fileName = time() . rand(1000, 9999);
        // 获取数据并保存
        if ($method) {
            $ch = curl_init();
            $timeout = 1;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        //判断文件类型
        $type = $this->getpictype($img);
        if (!isset($mimes[$type])) {
            return false;
        }
        $ext = $mimes[$type];
        $file = $this->avatar . $fileName . '_bak.' . $ext;
        $fp2 = @fopen($file, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);
        if ($ext == 'gif') {
            $i = imagecreatefromgif($file);
            $file_png = $this->avatar . $fileName . '_bak.png';
            imagepng($i, $file_png);
            unlink($file);
            return $file_png;
        }
        return $file;
    }

    /**
     * @desc 获取图片的类型
     * @param $content
     * @return bool|string
     */
    public function getpictype($content) {
        if ($content{0} . $content{1} == "\x89\x50") {
            return 'PNG';
        } else if ($content{0} . $content{1} == "\xff\xd8") {
            return 'JPEG';
        } else if ($content{0} . $content{1} . $content{2} == "\x47\x49\x46") {
            if ($content{4} == "\x37" || $content{4} == "\x39") {
                return 'GIF';
            } else {
                return false;
            }
        } else if ($content{0} . $content{1} == "\x42\x4d") {
            return 'BMP';
        }
        return false;
    }

    /**
     * @desc 上传头像，剪裁
     * @param $img 原图url,远程图片地址
     * @return mixed
     */
    public function createUploadAvatar($img) {
        try {
            /*
            $prefix = "6/xianghuimg";
            $ext = substr(strrchr($img, '.'), 1);
            $filename = pathinfo($img)['filename'] . '.' . $ext;
            $path = httpcopy($img, APP_PATH . "..\\tasks\\tmp\\" . $filename, 120);
            */
            $ext = substr(strrchr($img, '.'), 1);
            $filename = pathinfo($img)['filename'] . '.' . $ext;
            $path = httpcopy($img, APP_PATH . '../tasks/tmp/' . $filename, 120);

            $avatar = Img::upload($path);
            if (!empty($avatar['file'])) {
                $result = $avatar['file'];
            }
            unlink($path);
            return $result;
        } catch (Exception $e) {

        }
    }

    /**
     * @desc 获取客户端ip公用方法 HTTP_LEPROXY_FORWARDED_FOR ： 小运营商代理ip
     * @return mixed
     */
    public function getClientIp() {
        if (isset($_SERVER['HTTP_LEPROXY_FORWARDED_FOR']) && !empty($_SERVER['HTTP_LEPROXY_FORWARDED_FOR'])) {
            $clientIp = $_SERVER['HTTP_LEPROXY_FORWARDED_FOR'];
        } else {
            $clientIp = $this->_getIP();
            //$clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }

    /**
     * 2016-07-08 饶佳添加
     * @desc 获取客户端真实IP
     * @return mixed
     */
    public function _getIP() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * @desc 限制一天内同一ip注册量达2000个
     * @param null $ip
     * @return bool
     */
    public function stopUserRegByIp($ip = NULL) {
        empty($ip) && $ip = $this->getClientIp();
        $cacheKey = $this->sso_reg_user_key . $ip;
        $sum = RedisIO::get($cacheKey);
        $sum = intval($sum);
        if ($sum > 2000) {
            return true;
        } else {
            $date1 = date('H:i:s');
            $date2 = date('23:59:59');
            $ttl = strtotime($date2) - strtotime($date1);
            if ($ttl == 0) {
                $ttl = 5;
            }
            RedisIO::set($cacheKey, $sum + 1, $ttl);
            return false;
        }
    }

    /**
     * @desc 第三方用户绑定，生成用户名，重复绑定避免用户名重复
     * @param $username
     * @param string $username_source
     * @return string
     */
    public function getUsernameOauth($username, $username_source = '') {
        $user = $this->checkUsernameExists($username, false);
        if (empty($user)) {
            return $username;
        }
        empty($username_source) && $username_source = $username;
        $username = $username_source . '_' . rand(111, 999);
        return $this->getUsernameOauth($username, $username_source);
    }

    /**
     * @desc 检查用户名是否存在， 只能校验用户自命名的用户名，不能校验系统自动生成的用户名，
     * 如：第三方用户名，cztv_ 开始的用户名,注：第三方用户登录用户名命名需要进一步验证
     * @param $username
     * @param string $iscount
     * @return bool|int
     */
    public function checkUsernameExists($username, $iscount = 'true') {
        if (empty($username)) {
            return true;
        }
        try {
            if ($iscount) {
                $result = Userid::query()
                    ->columns("count(1) as total")
                    ->andWhere("username=:username:")
                    ->bind(array('username' => $username,))
                    ->execute()
                    ->toArray();
                return $result[0]['total'];
            } else {
                $result = Userid::query()
                    ->columns("uid")
                    ->andWhere("username=:username:")
                    ->bind(array('username' => $username,))
                    ->execute()
                    ->toArray();
                if (count($result)) {
                    return $result[0]['uid'];
                } else {
                    return 0;
                }
            }
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * @desc 用户注册
     * @param $data
     * @param bool $ruleFlag
     * @return bool
     */
    public function regUser($data, $ruleFlag = true) {
        try {
            $flag = $flagEmail = $flagMobile = true;
            //检查注册接口请求频次
            if (isset($data['email']) && !empty($data['email'])) {
                $flagEmail = $this->checkRegLimit($data['email']);
            }
            if (isset($data['mobile']) && !empty($data['mobile'])) {
                $flagMobile = $this->checkRegLimit($data['mobile']);
            }
            $flag = ($flagEmail && $flagMobile);
            //请求过于频繁，阻止注册
            if (!$flag) {
                return false;
            }
            $username = isset($data['username']) ? trim($data['username']) : uniqid('cztv_') . rand(0, 100);
            $infotmp = substr(md5(time() . rand(10000, 99999)), 8, 10);
            //用户uid生成
            $nickname = isset($data['nickname']) && !empty($data['nickname']) ? trim($data['nickname']) : '';
            if (!$this->checkNickname($nickname)) {
                $nickname = '';
            }
            if (!empty($nickname) && $this->checkNicknameExists($nickname)) {
                $nickname = $nickname . '_' . rand(100, 999);
            }
            empty($nickname) && $nickname = $infotmp;
            $dataUid = array('nickname' => $nickname, 'username' => $username);
            $useridModel = new Userid();
            $uid = $useridModel->saveGetId($dataUid);

            if ($uid <= 0) {
                return false;
            }
            //更新默认昵称
            if ($nickname == $infotmp) {
                $useridModel = Userid::findFirst(array("conditions" => array("uid='{$uid}'")));
                $useridModel->save(array('nickname' => 'user' . $uid));
            }
            $cdkey = $this->createCdkey();
            $pwd = isset($data['pwd']) ? $this->pwdcode($data['pwd'], $cdkey, true) : "";
            $status = isset($data['status']) ? $data['status'] : '1';
            //注册用户授权信息入库
            //设置用户绑定uid zhanghaiquan:bind_uid
            $userloginModel = new Userlogin();
            if (isset($data['email']) && !empty($data['email'])) {
                $emailAuthTable = $this->getHashTable($data['email']);
                $dataAuth = array(
                    'uid' => $uid,
                    'channel_id' => isset($data['channel_id']) ? $data['channel_id'] : LETV_CHANNEL_ID,
                    'loginname' => $data['email'],
                    'password' => $pwd,
                    'salt' => $cdkey,
                    'type' => Userlogin::LOGIN_TYPE_EMAIL,
                    'bind_uid' => 0,
                    'status' => $status,
                    'partition_by' => $emailAuthTable,
                );
                $userloginModel->save($dataAuth);
            }
            if (isset($data['mobile']) && !empty($data['mobile'])) {
                $mobileAuthTable = $this->getHashTable($data['mobile']);
                $dataAuth = array(
                    'uid' => $uid,
                    'channel_id' => isset($data['channel_id']) ? $data['channel_id'] : LETV_CHANNEL_ID,
                    'loginname' => $data['mobile'],
                    'password' => $pwd,
                    'salt' => $cdkey,
                    'type' => Userlogin::LOGIN_TYPE_MOBILE,
                    'bind_uid' => 0,
                    'status' => $status,
                    'partition_by' => $mobileAuthTable,
                );
                $userloginModel->save($dataAuth);
            }
            //用户基本信息入库
            $userBaseinfoTable = $this->getHashTable($uid);
            $row = array();
            $row['uid'] = $uid;
            $row['channel_id'] = isset($data['channel_id']) ? $data['channel_id'] : LETV_CHANNEL_ID;
            $row['username'] = $username;
            $row['status'] = $status;//0=>被管理员屏蔽,1=>正常,2=>邮箱未激活
            $row['gender'] = isset($data['gender']) ? $data['gender'] : '0';//0保密,1男,2女
            $row['qq'] = '';
            $row['regist_ip'] = isset($data['regist_ip']) ? $data['regist_ip'] : $this->getClientIp();
            $row['created_at'] = time();
            $row['updated_at'] = time();
            $row['nickname'] = $nickname;
            $row['regist_service'] = isset($data['regist_service']) ? $data['regist_service'] : 'my';
            $row['email'] = isset($data['email']) && !empty($data['email']) ? $data['email'] : ''; // $uid;
            $row['mobile'] = isset($data['mobile']) && !empty($data['mobile']) ? $data['mobile'] : ''; //$uid;
            $row['avatar'] = isset($data['avatar']) ? $data['avatar'] : 'http://i1.letvimg.com/img/201207/30/tx298.png,http://i0.letvimg.com/img/201207/30/tx200.png,http://i0.letvimg.com/img/201207/30/tx70.png,http://i3.letvimg.com/img/201207/30/tx50.png';
            $row['partition_by'] = $userBaseinfoTable;
            $usersModel = new Users();
            $usersModel->save($row);
            return $uid;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @desc 检查注册频次，同一个手机号或者邮箱 5秒种之内只能请求一次注册接口
     * @param $key
     * @return bool
     */
    public function checkRegLimit($key) {
        $cachekey = $this->_sso_reg_limit . $key;
        $cacheData = RedisIO::get($cachekey);
        if (!empty($cacheData)) {
            return false;
        } else {
            RedisIO::set($cachekey, 1, 5);
            return true;
        }
    }

    /**
     * @desc 检查昵称格式是否合法
     * @param $nickname
     * @return bool
     */
    public function checkNickname($nickname) {
        if (empty($nickname)) {
            return false;
        }
        $len = mb_strlen($nickname, 'UTF8');
        if (($len >= 4 && $len <= 32) && preg_match('/^[0-9a-zA-Z\x{4e00}-\x{9fa5}_]+$/u', $nickname) && !preg_match('/^_+$/', $nickname) && !preg_match('/^[0-9]+$/', $nickname)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 检查昵称是否存在
     * @param $nickname
     * @param string $iscount
     * @return bool|int
     */
    public function checkNicknameExists($nickname, $iscount = 'true') {
        if (empty($nickname)) {
            return true;
        }
        try {
            if ($iscount) {
                $result = Userid::query()
                    ->columns("count(1) as total")
                    ->andWhere("nickname=:nickname:")
                    ->bind(array('nickname' => $nickname,))
                    ->execute()
                    ->toArray();
                return $result[0]['total'];
            } else {
                $result = Userid::query()
                    ->columns("uid")
                    ->andWhere("nickname=:nickname:")
                    ->bind(array('nickname' => $nickname,))
                    ->execute()
                    ->toArray();
                if (count($result)) {
                    return $result[0]['uid'];
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * 获取分表表格名称，改写只返回hash值
     * @param unknown $table
     * @param unknown $userid
     * @return string
     */
    public static function getHashTable($userid) {
        $userid = strtolower($userid);
        $str = crc32($userid);
        if ($str < 0) {
            $hash = "0" . substr(abs($str), 0, 1);
        } elseif ($str < 10) {
            $hash = "0" . $str;
        } else {
            $hash = substr($str, (strlen($str) - 2));
        }
        return $hash;
    }

    /**
     * @desc 用户注册时，分配密码加密key，一个用户一个key
     * @return string
     */
    public function createCdkey() {
        $str = 'abcde012fghij345klmno678pqrstuvw9xyz';
        $rndstr = '';    //用来存放生成的随机字符串
        for ($i = 0; $i < 8; $i++) {
            $rndcode = rand(0, 35);
            $rndstr .= $str[$rndcode];
        }
        return $rndstr;
    }

    /**
     * @desc 密码加密
     * @param $pwd
     * @param $cdkey
     * @param bool $md5
     * @return bool|string
     */
    public function pwdcode($pwd, $cdkey, $md5 = false) {
        if (empty($pwd)) {
            return false;
        }
        if($md5&&self::is_md5($pwd)) $md5 = false;
        //新密码做md5加密
        $md5 && $pwd = md5($pwd);
        $pwd = substr($pwd, 7, 16);
        $pwd = md5($pwd . $cdkey);
        return $pwd;
    }

    function is_md5($password) {
        return preg_match("/^[a-z0-9]{32}$/", strtolower($password));
    }

    /**
     * @desc 根据uid获取用户信息
     * @param $uid
     * @param int $iscache
     * @param string $dlevel
     * @return array|bool
     */
    public function getUserByID($uid, $iscache = 1, $dlevel = 'basic') {
        try {
            $uid = (int)$uid;
            empty($dlevel) && $dlevel = 'basic';
            $cacheKey = $this->sso_uid_key . $uid;
            $iscache && $data = RedisIO::get($cacheKey);
            $data = json_decode($data, true);
            $data = array();//TODO
            if (!is_array($data) || empty($data) || !$iscache) {
                $userBaseinfoTable = $this->getHashTable($uid);
                $data = Users::query()
                    ->andWhere("uid = :uid:")
                    ->andWhere("partition_by = :partition_by:")
                    ->bind(array('uid' => $uid, 'partition_by' => $userBaseinfoTable))
                    ->execute()
                    ->toArray();
                if (!empty($data)) {
                    $data = $data[0];
                }
                if (isset($data['uid']) && $data['uid'] > 0) {
                    unset($data['password']);
                    unset($data['salt']);
                    RedisIO::set($cacheKey, json_encode($data), 86400 * 3);
                }
            }
            if (empty($data)) {
                return $data;
            }
            //如果头像为空，则赋值默认头像地址
            empty($data['avatar']) && $data['avatar'] = 'http://i1.letvimg.com/img/201207/30/tx298.png,http://i0.letvimg.com/img/201207/30/tx200.png,http://i0.letvimg.com/img/201207/30/tx70.png,http://i3.letvimg.com/img/201207/30/tx50.png';

            $data['email'] == $data['uid'] && $data['email'] = '';
            $data['mobile'] == $data['uid'] && $data['mobile'] = '';
            switch ($dlevel) {
                case 'basic':
                    $return = array(
                        'uid' => $data['uid'],
                        'username' => $data['username'],
                        'status' => $data['status'],
                        'nickname' => $data['nickname'],
                        'email' => $data['email'],
                        'mobile' => $data['mobile'],
                        'picture' => $data['avatar'],
                        'lastModifyPwdTime' => $data['last_modify_pwd_time'],
                    );
                    break;
                case 'expand':
                    $return = array(
                        'uid' => $data['uid'],
                        'username' => $data['username'],
                        'status' => $data['status'],
                        'nickname' => $data['nickname'],
                        'email' => $data['email'],
                        'mobile' => $data['mobile'],
                        'picture' => $data['avatar'],
                        'lastModifyPwdTime' => $data['last_modify_pwd_time'],
                        'gender' => $data['gender'],
                        'name' => $data['realname'],
                        'registTime' => $data['created_at'],
                        'registIp' => $data['regist_ip'],
                        'registService' => $data['regist_service'],
                    );
                    break;
                case 'total':
                    $return = array(
                        'uid' => $data['uid'],
                        'channel_id' => $data['channel_id'],
                        'username' => $data['username'],
                        'status' => $data['status'],
                        'nickname' => $data['nickname'],
                        'email' => $data['email'],
                        'mobile' => $data['mobile'],
                        'picture' => $data['avatar'],
                        'qq' => $data['qq'],
                        'birthday' => $data['birthday'],
                        'province' => $data['province'],
                        'city' => $data['city'],
                        'birthday' => $data['birthday'],
                        'lastModifyPwdTime' => $data['last_modify_pwd_time'],
                        'gender' => $data['gender'],
                        'name' => $data['realname'],
                        'registTime' => $data['created_at'],
                        'registIp' => $data['regist_ip'],
                        'registService' => $data['regist_service'],
                    );
                    break;
                default:
                    break;

            }
            return $return;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @desc 登录 种cookie
     * @param $userinfo
     * @param string $utype
     * @param string $from
     * @param string $memberme
     * @param bool $logFlag
     * @param string $loginname
     * @param int $firstlonginflag
     * @return bool
     */
    public function LoginSsoCookie($userinfo, $utype = '0', $from = 'my', $memberme = 'false', $logFlag = true, $loginname = '', $firstlonginflag = 0) {
        header('P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"');//p3p
        if ($memberme == 'true') {
            $time = time() + 3600 * 24 * 365 * 10;//cookie永不过期
        } else {
            $time = 0;
        }
        $sso_tk = $this->ssotk($userinfo['uid'], true, $from);
        $nickname = empty($userinfo['nickname']) ? $userinfo['username'] : $userinfo['nickname'];
        Cookie::set('m', $userinfo['username'], $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('sso_tk', $sso_tk, $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('sso_nickname', $nickname, $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('casflag', '1', $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('ssouid', $userinfo['uid'], $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('loginname', $loginname, $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('loginnamecookie', $loginname, time() + 86400 * 30 * 6, '/', null, '.cztv.com', false);
        $picture = $userinfo['picture'];
        $picture = explode(',', $picture);
        $picture = isset($picture['2']) ? $picture['2'] : 'http://i1.letvimg.com/user/201208/03/f_tx30.png';//取第三张图
        Cookie::set('sso_picture', $picture, $time, '/', null, '.cztv.com', false); //设置cookie
        Cookie::set('sso_icon', $userinfo['picture'], $time, '/', null, '.cztv.com', false); //设置cookie
        if ($utype > 0) {
            Cookie::set('utype', $utype, $time, '/', null, '.cztv.com', false);//设置cookie
        }
        if (!empty($from)) {
            Cookie::set('lfrom', $from, $time, '/', null, '.cztv.com', false);//设置cookie
        }
        if ($firstlonginflag) {
            Cookie::set('first_login', 1, $time, '/', null, '.cztv.com', false);//设置cookie
        }

        $_user = array();
        $_user['uid'] = $userinfo['uid'];
        $_user['nickname'] = $userinfo['username'];
        $_user['email'] = ($userinfo['uid'] != $userinfo['email']) ? $userinfo['email'] : '';
        $_user['name'] = $userinfo['username'];
        $_user['ssouid'] = $userinfo['uid'];
        $u = base64_encode(json_encode($_user));
        Cookie::set('u', $u, $time, '/', null, '.cztv.com', false); //设置cookie
        $uinfo = Plugin_Util::authCodePtv(json_encode($_user), 'ENCODE', PTV_LOGIN);//加密cookie
        Cookie::set('ui', $uinfo, $time, '/', null, '.cztv.com', false); //设置cookie
        //csrfcookie统一在message里处理，这个不能去掉，去掉会导致网站csrf失效，比如评论发不出去
        if (!isset($_COOKIE['csrf']) || empty($_COOKIE['csrf'])) {
            $csrf = substr(md5(uniqid()), rand(0, 12), 20);
            Cookie::set('csrf', $csrf, 0, '/', null, '.cztv.com', false);
        }
        Cookie::send();
        return true;
    }

    /**
     * @desc 用户认证票 :用户登录改造，修改ssotk格式
     * @param $ssoid
     * @param bool $encode
     * @param null $plat
     * @param bool $need_expire
     * @return array|bool|mixed|string
     */
    public function ssotk($ssoid, $encode = true, $plat = null, $need_expire = false) {
        if ((!is_string($ssoid) && !is_int($ssoid)) || empty($ssoid)) {
            return false;
        }
        $key = LOGIN_KEY;
        if ($encode) {//加密
            //设置tk前缀 标记登录平台来源
            if (isset($plat) && isset($this->__platform[$plat])) {
                $tk_plat = $this->__platform[$plat];
            } else {
                $tk_plat = $this->__platform['def'];
            }
            //token格式升级，记录token生产时间 1、pc web端格式升级
            if (in_array($tk_plat, array('102', '103', '105', '106', '999'))) {
                $ssoid .= '|' . time();
            }
            if (in_array($tk_plat, array('102', '103', '105', '106'))) {
                //pc web tv永久有效
                $letk = Plugin_Util::authcode($ssoid, 'ENCODE', $key);
            } else if ($tk_plat == '999') {
                $letk = Plugin_Util::authcode($ssoid, 'ENCODE', $key, 3600);//1个小时有效
            } else {
                $letk = Plugin_Util::authcode($ssoid, 'ENCODE', $key, 3600 * 24 * 30);//30天有效
            }

            $letk = $tk_plat . $letk;
            return $letk;
        } else {//解密
            /**
             * token经过三次格式升级
             * 1、最初token格式为简单uid加密后信息，加密串经过两步处理：1、先base64_encode，2、后urlencode
             * 2、多平台登录标识记载，添加token前缀，加密串经过两步处理：1、先base64_encode，2、后urlencode
             * 3、添加token生产时间记载，uid+生产时间 统一加密，加密串不经过任何处理
             */
            $tk_plat = substr($ssoid, 0, 3);
            //判断token格式，是否为最新格式的token
            if (is_numeric($tk_plat)) {
                $letk = substr($ssoid, 3);//3位以后为加密token
                if (in_array($tk_plat, array('102', '103', '105'))) {
                    //pc web tv永久有效
                    $tickt_info = Plugin_Util::authcode($letk, 'DECODE', $key, null, $need_expire);
                } elseif ($tk_plat == '999') {
                    $tickt_info = Plugin_Util::authcode($letk, 'DECODE', $key, 3600, $need_expire);
                } else {
                    $tickt_info = Plugin_Util::authcode($letk, 'DECODE', $key, 3600 * 24 * 30, $need_expire);//30天有效
                }
                //需要获取token过期时间
                if ($need_expire && isset($tickt_info['ssoid']) && $tickt_info['ssoid'] > 0) {
                    $expire = $tickt_info['expire']; //过期时间
                    $tickt_info = $tickt_info['ssoid']; //uid
                }
                //uid token生产时间
                $temp = explode('|', $tickt_info);
                if (!empty($temp)) {
                    $uid = $temp[0];
                    $create_time = (isset($temp[1]) ? $temp[1] : '');
                }
                //list($uid, $create_time) = explode('|', $tickt_info);
                $userInfo = $this->getUserByID($uid);

                //如果用户被封禁，返回false
                if (isset($userInfo['status']) && $userInfo['status'] == '0') {
                    $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($uid, $userInfo['status']));
                    return false;
                }

                //验证用户是否修改过密码，以及依据token生产时间，确认格式是否正确
                if (in_array($tk_plat, array('102', '103', '105', '999'))) {
                    //校验生产时间与修改密码时间的先后
                    //修改过密码
                    if (!empty($userInfo['lastModifyPwdTime']) && (empty($create_time) || $create_time < $userInfo['lastModifyPwdTime'])) {
                        //早于修改密码时间之前生成的token全部过期
                        $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($ssoid, $userInfo['lastModifyPwdTime']));
                        return false;
                    }
                }
                if ($need_expire) {

                    if (isset($expire)) $uid = array('ssoid' => $uid, 'expire' => $expire);
                }
                return $uid;
            } else {
                $letk = base64_decode(urldecode($ssoid));
                $letk_hold = $letk;//保留tk格式，拥挤兼容旧版
                $tk_plat = substr($letk, 0, 3);//截取前三位平台标识
                $letk = substr($letk, 3);//3位以后为加密token
                if (in_array($tk_plat, array('102', '103', '105'))) {
                    //pc web tv永久有效
                    $uid = Plugin_Util::authcode($letk, 'DECODE', $key, null, $need_expire);
                } else {
                    $uid = Plugin_Util::authcode($letk, 'DECODE', $key, 3600 * 24 * 30, $need_expire);//30天有效
                }
                if ($need_expire && isset($uid['ssoid']) && $uid['ssoid'] > 0) {
                    $expire = $uid['expire'];
                    $uid = $uid['ssoid'];
                }
                //$uid 大于0 用户token校验成功
                if ($uid > 0) {
                    $userInfo = $this->getUserByID($uid);

                    //如果用户被封禁，返回false
                    if ($userInfo['status'] == '0') {
                        $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($uid, $userInfo['status']));
                        return false;
                    }
                    //首先对接web端、PC端，验证用户是否修改过密码
                    if (in_array($tk_plat, array('102', '103', '105'))) {
                        //校验生产时间与修改密码时间的先后
                        //修改过密码
                        if (!empty($userInfo['lastModifyPwdTime'])) {
                            //早于修改密码时间之前生成的token全部过期
                            $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($ssoid, $userInfo['lastModifyPwdTime']));
                            return false;
                        }
                    }
                    if ($need_expire) {
                        $uid = array('ssoid' => $uid, 'expire' => $expire);
                    }
                    return $uid;
                }
                return Plugin_Util::authcode($letk_hold, 'DECODE', $key, 3600 * 24 * 30, $need_expire);//30天有效 */
            }
        }
    }

    /**
     * @desc 检查邮箱是否可以发送邮件，每天不超过五次
     * @param $email
     * @return bool
     */
    public function checkEmailIfSendEmail($email) {
        if (empty($email)) {
            return false;
        }
        $times = $this->getSendEmailTimes($email);
        if ($times >= 50) {
            return false;
        }
        return true;
    }

    /**
     * @desc 获取邮箱每天发送邮件次数
     * @param $email
     * @return null
     */
    public function getSendEmailTimes($email) {
        if (empty($email)) {
            return null;
        }
        $cacheKey = $this->_sso_send_email_times_key . $email;
        $times = RedisIO::get($cacheKey);
        return $times;
    }

    /**
     * @desc 设置每天每个邮箱最多可以发送几条邮件
     * @param $email
     * @return null
     */
    public function setSemdEmailTimes($email) {
        if (empty($email)) {
            return null;
        }
        $cacheKey = $this->_sso_send_email_times_key . $email;
        $times = RedisIO::get($cacheKey);
        $time = strtotime(date('Y-m-d')) + 86400 - time();
        if ($times > 0) {
            RedisIO::set($cacheKey, $times + 1, $time);
        } else {
            RedisIO::set($cacheKey, 1, $time);
        }
    }


    /**
     * @desc 检查是否可以查询邮件存在，ip每天不超过十次
     * @param $email
     * @return bool
     */
    public function checkIfCheckMobile($ip) {
        $times = $this->getMobileCheckTimes($ip);
        if ($times >= 10) {
            return false;
        }
        return true;
    }

    /**
     * @desc 获取手机存在查询次数
     */
    public function getMobileCheckTimes($ip) {
        $cacheKey = $this->_sso_mobile_check_times_key . $ip;
        $times = RedisIO::get($cacheKey);
        return $times;

    }

    /**
     * @desc 设置每天每个IP最多可查手机存在次数
     */
    public function setMobileCheckTimes($ip) {
        $cacheKey = $this->_sso_mobile_check_times_key . $ip;
        $times = RedisIO::get($cacheKey);
        $time = strtotime(date('Y-m-d')) + 86400 - time();
        if ($times > 0) {
            RedisIO::set($cacheKey, $times + 1, $time);
        } else {
            RedisIO::set($cacheKey, 1, $time);
        }


    }

    /**
     * @desc 检查是否可以查询邮件存在，ip每天不超过十次
     * @param $email
     * @return bool
     */
    public function checkIfCheckEmail($ip) {
        $times = $this->getEmailCheckTimes($ip);
        if ($times >= 10) {
            return false;
        }
        return true;
    }

    /**
     * @desc 获取邮箱存在查询次数
     */
    public function getEmailCheckTimes($ip) {
        $cacheKey = $this->_sso_email_check_times_key . $ip;
        $times = RedisIO::get($cacheKey);
        return $times;

    }

    /**
     * @desc 设置每天每个IP最多可查邮箱存在次数
     */
    public function setEmailCheckTimes($ip) {
        $cacheKey = $this->_sso_email_check_times_key . $ip;
        $times = RedisIO::get($cacheKey);
        $time = strtotime(date('Y-m-d')) + 86400 - time();
        if ($times > 0) {
            RedisIO::set($cacheKey, $times + 1, $time);
        } else {
            RedisIO::set($cacheKey, 1, $time);
        }

    }

    /**
     * @desc api接口错误信息返回
     * @param $errorCode
     * @param array $bean
     * @param bool $seeArr
     * @param string $jsonp
     */
    public function errorShow($errorCode, $bean = array(), $jsonp = '') {
        //错误定义
        $error = array(
            403 => '没有权限执行',
            500 => '服务器错误',
            1000 => '参数不正确',
            1001 => '用户不存在',
            1002 => '登录名或密码不正确',
            1003 => '用户已被管理员屏蔽了',
            1004 => '用户邮箱未激活',
            1005 => '邮箱格式不正确',
            1006 => '邮箱不存在',
            1007 => '邮件下发失败，服务忙',
            1008 => '旧密码不正确',
            1009 => '参数不正确或密码少于6位',
            1010 => '邮箱已存在请更换邮箱',
            1011 => '手机格式不正确',
            1012 => '手机已存在',
            1013 => '昵称已存在',
            1014 => 'sso票不正确或已过期',
            1015 => '等级不存在',
            1016 => '您这个第三方账号已经绑定了其他乐视账号，换个别的吧',
            1017 => '密钥不正确',
            1018 => '成长值超过了日限额',
            1019 => '成长值太大了',
            1020 => 'token不正确或已过期',
            1021 => '激活码已经过期或者手机号码错误',
            1022 => '激活码错误',
            1023 => '请先绑定邮箱或者手机号后，再解绑第三方账号',
            1024 => '第三方用户不存在',
            1025 => '请先绑定了邮箱，再解绑手机号',
            1026 => '每个手机号每天最多能够发送5条短信',
            1027 => '每个邮箱每天最多能发送5封邮件',
            1028 => '第三方账号授权失败',
            1029 => '第三方用户与token不匹配',
            1030 => '该第三方账号已经注册过',
            1031 => '你今天注册用户过多，请稍后再试',
            1032 => '图片上传失败',
            1033 => '上传头像不得超过1M',
            1034 => '图片格式不支持',
            1035 => '开放平台应用id已经被占用',
            1036 => '开放平台应用不存在',
            1037 => '该ip请求过于频繁',
            1038 => '该账号请求过于频繁',
            1039 => '每个ip每天最多注册20个账号',
            1040 => '获取第三方用户信息失败',
            1041 => 'access_token过期，第三方用户需要重新授权',
        );
        $result = array(
            'bean' => $bean,
            'status' => empty($errorCode) ? 1 : 0,
            'errorCode' => $errorCode,
            'message' => isset($error[$errorCode]) ? $error[$errorCode] : '',
            'responseType' => 'json'
        );
        if (!empty($jsonp)) {
            echo $jsonp . '(' . json_encode($result) . ')';
            exit;
        }
        echo json_encode($result);
        exit;
    }

    /**
     * @desc 修改头像
     * @param $photo
     * @param $uid
     * @return bool
     */
    public function photoReset($photo, $uid) {
        if (empty($uid)) {
            return true;
        }

        if ($photo == '') {
            $photo = 'http://i1.letvimg.com/img/201207/30/tx298.png,http://i0.letvimg.com/img/201207/30/tx200.png,http://i0.letvimg.com/img/201207/30/tx70.png,http://i3.letvimg.com/img/201207/30/tx50.png';
        }
        try {
            $partition_by = $this->getHashTable($uid);
            $usersModel = new Users();
            $userInfo = $usersModel->findFirst(array("conditions" => "uid='{$uid}' and partition_by='{$partition_by}'"));
            if ($userInfo) {
                $userInfo->save(array('avatar' => $photo));
            }
        } catch (Exception $e) {
        }
        $this->_clearUserInfoCache($uid);
        return true;
    }

    /**
     * @desc 清空用户信息缓存
     * @param $uid
     * @param array $userinfo
     * @return null
     */
    private function _clearUserInfoCache($uid, $userinfo = array()) {
        if (empty($uid)) {
            return null;
        }
        RedisIO::delete($this->sso_uid_key . $uid);
        if (!empty($userinfo)) {
            !empty($userinfo['email']) && RedisIO::delete($this->_sso_info_email_key . $userinfo['email']);
            !empty($userinfo['mobile']) && RedisIO::delete($this->_sso_info_mobile_key . $userinfo['mobile']);
            !empty($userinfo['nickname']) && RedisIO::delete($this->_sso_info_nickname_key . $userinfo['nickname']);
        }
    }

    /**
     * 获取当前登录用户信息
     *
     * @return array
     */
    public function getCurrentUserinfo($lang = 'zh') {
        $userInfo1 = My::CurrentUserBaseInfo();
        $userInfo2 = My::FullUserInfoByUid(My::CurrentUserUid());
        $userInfo = array_merge($userInfo1, $userInfo2);

        return $userInfo;
    }

    public function getVerifyCode(array $array) {
        $str = implode('&', $array);
        return md5('cztv' . $str);
    }

    /**
     * @desc 根据条件检索用户信息
     * @param $cond 检索条件
     * @param $value 检索条件取值
     * @param string $dlevel
     * @return array|bool
     */
    public function getUserByCond($cond, $value, $dlevel = 'basic') {
        if (empty($cond) || !in_array($cond, array('username', 'email', 'mobile', 'nickname')) || empty($value)) {
            return array();
        }
        $value = addslashes($value);
        empty($dlevel) && $dlevel = 'basic';
        $cacheKey = '_sso_info_' . $cond . '_key' . $value;
        $uid = RedisIO::get($cacheKey);
        if (empty($uid)) {
            try {
                switch ($cond) {
                    case 'email':
                    case 'mobile':
                        $cond = 'loginname';
                        $partition_by = $this->getHashTable($value);
                        $uid = Userlogin::query()
                            ->columns("uid")
                            ->andWhere("loginname = :loginname:")
                            ->andWhere("partition_by = :partition_by:")
                            ->bind(array('loginname' => $value, 'partition_by' => $partition_by))
                            ->execute()
                            ->toArray();
                        $uid = (isset($uid[0])) ? $uid[0]['uid'] : 0;
                        break;
                    case 'nickname':
                    case 'username':
                        $uid = Userid::query()
                            ->columns("uid")
                            ->andWhere("{$cond} = :{$cond}:")
                            ->bind(array("{$cond}" => $value))
                            ->execute()
                            ->toArray();
                        $uid = (isset($uid[0])) ? $uid[0]['uid'] : 0;
                        break;
                    default:
                        break;
                }
                //扩展信息
                if ($uid > 0) {
                    RedisIO::set($cacheKey, $uid, 86400 * 3);
                }
            } catch (Exception $e) {
                return array();
            }
        }
        $userinfo = $this->getUserByID($uid, 1, $dlevel);
        if (!empty($userinfo)) {
            return $userinfo;
        }
        return array();
    }


    /**
     * @desc 修改密码
     * @version 2015-06-09
     * @param string $pwd
     * @param int $uid
     * @return boolean
     */
    public function passwordReset($pwd, $uid) {
        try {
            if (empty($pwd) || empty($uid)) {
                return false;
            }
            $partition_by = $this->getHashTable($uid);
            $userBase = Users::findFirst(array("conditions" => "uid='{$uid}' and partition_by='{$partition_by}'"));

            if (empty($userBase)) {
                return false;
            }

            $cdkey = $this->createCdkey();
            $pwd = $this->pwdcode($pwd, $cdkey, true);
            //更新邮箱鉴权信息
            if (!empty($userBase->email)) {
                $partition_by = $this->getHashTable($userBase->email);
                $userlogin = Userlogin::checkMobile($userBase->email, $partition_by);
                if ($userlogin) {
                    $userlogin->password = $pwd;
                    $userlogin->salt = $cdkey;
                    $userlogin->save();
                }
            }

            //更新手机鉴权信息
            if (!empty($userBase->mobile)) {
                $partition_by = $this->getHashTable($userBase->mobile);
                $userlogin = Userlogin::checkMobile($userBase->mobile, $partition_by);
                if ($userlogin) {
                    $userlogin->password = $pwd;
                    $userlogin->salt = $cdkey;
                    $userlogin->save();
                }
            }

            //更新用户名鉴权信息
            /*
             没有使用用户名登录的---章海泉
            if (!empty($userBase['username']))
            {
                $loginname = $userBase['username'];
                list($pre) = explode('_', $loginname);
                if (!in_array($pre, array(1=>'sina', 2=>'qq', 3=>'weixin', 4=>'cztv')))
                {
                    $usernameAuthTable = $this->getHashTable($this->user_auth_table, $userBase['username']);
                    $sql = "UPDATE ".$usernameAuthTable." SET pwd='".$pwd."', cdkey='".$cdkey."' WHERE uid = '".$uid."'";
                    $this->_passportdb->sql($sql);
                }
            }
            */

            $userBase->last_modify_pwd_time = time();

//            $this->cache('_usercache')->delete($this->sso_uid_key.$uid);
            /**
             * 兼容老数据
             */
            //$sqlOld   = "update userinfo SET pwd='" . $pwd . "', cdkey='" . $cdkey . "', lastModifyPwdTime='" . time() . "' where uid = '" . $uid . "'";
            //$this->_passportdb->sql($sqlOld);
            return true;
        } catch (Exception $e) {
            return false;
        }

    }


    /**
     * @desc 获取某个登录名的用户的登录错误信息
     * @version 2015-06-03
     * @param string $loginname
     * @param string $get
     * @param number $times
     * @return boolean|unknown
     */
    public function userLoginErrorInfo($loginname, $get = true, $times = 0) {
        if (empty($loginname)) {
            return false;
        }
        $user_login_error_times = $this->sso_user_login_error_times . $loginname;
        if ($get) {
            $data['login_error_times'] = RedisIO::get($user_login_error_times);//登录错误次数
            return $data;
        } else {
            if ($times < 5) {
                RedisIO::get($user_login_error_times, $times, 600);
            } else {
                RedisIO::get($user_login_error_times, $times, 86400);
            }
            return true;
        }
    }

    /**
     * @desc 用户登录
     * @param $row
     * @param $uid
     * @return bool
     */
    public function userLogin($loginname, $password, $registService = 'my', $ismd5 = 0) {
        try {
            if (empty($loginname)) {
                return false;
            }
            $partition_by = $this->getHashTable($loginname);
            $loginname = addslashes($loginname);

            $userauth = Userlogin::query()
                ->andWhere("loginname = :loginname:")
                ->andWhere("partition_by = :partition_by:")
                ->bind(array('loginname' => $loginname, 'partition_by' => $partition_by))
                ->execute()
                ->toArray();
            $userauth = isset($userauth[0]) ? $userauth[0] : null;
            //用户不存在，退出
            if (empty($userauth)) {
                return false;
            }
            if (!empty($userauth['salt'])) {
                if ($ismd5 == 1) {
                    $password = $this->pwdcode($password, $userauth['salt']);
                } else {
                    $password = $this->pwdcode($password, $userauth['salt'], true);
                }
            }

            //验证登录
            //登录成功
            if ($password == $userauth['password']) {
                //返回用户绑定uid zhanghaiquan:bind_uid
                if(isset($userauth['bind_uid'])&&$userauth['bind_uid']>0) {
                    $userinfo = $this->getUserByID($userauth['bind_uid']);
                }
                else {
                    $userinfo = $this->getUserByID($userauth['uid']);

                }
                return $userinfo;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @desc 修改用户信息
     * @param $row
     * @param $uid
     * @return bool
     */
    public function modifyUserInfo($row, $uid) {
        //基础表
        if (empty($row) || empty($uid)) {
            return false;
        }
        $basedata = array();
        $partition_by = $this->getHashTable($uid);
        $userinfo = $this->getUserByID($uid);

        //更新用户基础信息表
//        if (isset($row['email'])) $basedata['email'] = empty($row['email']) ? $uid : $row['email'];
//        if (isset($row['mobile'])) $basedata['mobile'] = empty($row['mobile']) ? $uid : $row['mobile'];
        if (isset($row['email'])) $basedata['email'] = empty($row['email']) ? '' : $row['email'];
        if (isset($row['mobile'])) $basedata['mobile'] = empty($row['mobile']) ? '' : $row['mobile'];
        if (isset($row['lastModifyPwdTime'])) $basedata['last_modify_pwd_time'] = $row['lastModifyPwdTime'];
        if (isset($row['gender'])) $basedata['gender'] = $row['gender'];
        if (isset($row['qq'])) $basedata['qq'] = $row['qq'];
        if (isset($row['birthday'])) $basedata['birthday'] = $row['birthday'];
        if (isset($row['nickname'])) $basedata['nickname'] = addslashes($row['nickname']);
        if (isset($row['province'])) $basedata['province'] = $row['province'];
        if (isset($row['city'])) $basedata['city'] = $row['city'];
        if (isset($row['name'])) $basedata['realname'] = $row['name'];//追加内容，原来没有
        if (!empty($basedata)) {
            try {
                $rs = Users::findFirst(array("conditions" => "uid='{$uid}' and partition_by='{$partition_by}'"));
                if ($rs) {
                    $rs->save($basedata);
                }
            } catch (Exception $e) {
            }
        }
        //更新用户昵称
        if (!empty($row['nickname'])) {
            try {
                $rs = Userid::findFirst(array("conditions" => "uid='{$uid}'"));
                if ($rs) {
                    $rs->save(array('nickname' => $row['nickname']));
                }
            } catch (Exception $e) {
            }
        }
        $this->_clearUserInfoCache($uid, $userinfo);
        return true;
    }

    /**
     * @desc 验证百度推广活动sign
     * @version 2015年8月14日
     */
    public function checkSign($mobile, $actid, $day = 90, $app_secret = INVITE_KEY) {
        $params = array();//所有提交参数的字典
        $params['user'] = $mobile;
        $params['actid'] = $actid;
        $params['day'] = $day;
        $params['app_secret'] = $app_secret;//加上授权码
        ksort($params);//按照key进行字典升序排序

        //$params_string=http_build_query($params);
        $params_string = "";
        foreach ($params as $key => $v) {
            $params_string .= ("" != $params_string) ? "&" . $key . '=' . urlencode($v) : $key . '=' . urlencode($v);
        }

        //base64加密，再进行32位md5加密，得到sign值
        $sign = md5(base64_encode($params_string));
        return $sign;
    }

    /**
     * @desc 执行百度推广活动vip信息更新操作
     * @version 2015年8月14日
     */
    public function baidu2vip($mobile, $actid, $day) {
        $partition_by = $this->getHashTable($mobile);
        $userlogin = Userlogin::checkMobile($mobile, $partition_by);
        if ($userlogin['uid'] <= 0) {
            $return['errorcode'] = 1;
            $return['error_msg'] = '用户信息不存在，请先注册';
            return $return;
        }
        $cacheKey = 'is_vip_' . $uid['uid'];

        RedisIO::delete($cacheKey);
        $isvip = $this->is_vip($uid['uid']);
        if ($isvip['uid'] > 0) {
            $return['errorcode'] = 1;
            $return['error_msg'] = 'vip信息已存在';
            return $return;

        }
        $uid = $this->getUserByID($userlogin['uid']);
        if ($uid['uid'] > 0) {
            $begin = strtotime("now");//会员开始时间
            $end = strtotime("+90 day");//会员到期时间
            $data = array(
                'uid' => $uid['uid'],
                'isvip' => 1,
                'param1' => $actid,
                'param2' => $begin,
                'param3' => $end
            );
            $re = false;
            //$re = $this->_passportdb->insert($data, 'user_profile');
            if ($re) {
                return true;
            }
        }
        return false;
    }


    /**
     * @desc 判断是否是vip用户
     * @version 2015年8月19日
     */
    public function is_vip($uid) {
        $return = array();
        if (empty($uid)) {
            $return['uid'] = '';
            $return['isvip'] = '';
            $return['timeline'] = '';
            return $return;
        }
        $cacheKey = 'is_vip_' . $uid;
        $return = RedisIO::get($cacheKey);
        if (empty($return)) {
            $data = $this->getUserByID($uid);
            if ($data['uid'] > 0) {
                $return['uid'] = isset($data['uid']) ? $data['uid'] : '';
                $return['isvip'] = isset($data['isvip']) ? $data['isvip'] : '';
                $return['timeline'] = isset($data['param3']) ? $data['param3'] : '';
                RedisIO::set($cacheKey, $return, 60 * 5);
                return $return;
            } else {
                $return['uid'] = '';
                $return['isvip'] = '';
                $return['timeline'] = '';
                RedisIO::set($cacheKey, $return, 60 * 5);
                return $return;
            }
        } else {
            return $return;
        }
    }

    /**
     * @desc  单ip登录是否限制
     * @param $ip
     * return boolean
     */
    public function ipLoginLimited($ip, $get = true, $expire = 86400) {
        if (empty($ip)) {
            return true;
        }
        try {
            $key = $this->sso_ip_login_is_limited . $ip;
            if ($get) {
                $limited = RedisIO::get($key);
                return $limited == 1;
            } else {
                return RedisIO::set($key, 1, $expire);
            }
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @desc 账号登录限制
     * @param loginname
     * @return boolean
     */
    public function loginnameLimited($loginname, $get = true, $expire = 86400) {
        if (empty($loginname)) {
            return true;
        }
        $key = $this->sso_loginname_login_is_limited . $loginname;
        if ($get) {
            $limited = RedisIO::get($key);
            return $limited == 1;
        } else {
            return RedisIO::set($key, 1, $expire);
        }
    }

    //检查客户端登录来源是否合法
    public function checkPlat($plat, $get = false) {
        if ($get) {
            return $this->__platform[$plat];
        }
        return isset($this->__platform[$plat]);
    }

    public function getUserProfileByID($uid, $iscache = 1) {
        return $this->getUserByID($uid, 1, 'total');
    }

    /**
     * @desc ip登录成功次数记录
     * @version 2015-06-03
     * @param string $ip
     * @param string $get
     * @param number $expire
     * @return boolean|unknown|Ambigous <boolean, number>
     */
    public function ipLoginSuccesInfo($ip, $get = true, $expire = 86400) {
        if (empty($ip)) {
            return false;
        }
        $ip_login_success_times = $this->sso_ip_login_success_times . $ip;
        if ($get) {
            $data['times'] = RedisIO::get($ip_login_success_times);//登录错误次数
            return $data;
        } else {
            return $this->increCount($ip_login_success_times, $expire);
        }
    }

    /**
     * @desc memcache 计数器，如果存在则+1，否则根据超时时间设置为1
     * @param $key
     * @param expire
     * @return boolean
     */
    function increCount($key, $expire) {
        $value = RedisIO::get($key);
        if ($value && $value >= 1) {
            return RedisIO::incr($key);
//            $num = RedisIO::get($key);
//            return RedisIO::set($key, $num + 1);
        } else {
            RedisIO::set($key, '1', $expire);
            return 1;
        }
    }

    /**
     * 账号登录失败次数记录
     * @param $loginname
     * @param string $get
     * @return boolean|unknown
     */
    public function loginnameLoginErrorInfo($loginname, $get = true, $expire = 86400) {
        if (empty($loginname)) {
            return false;
        }
        $key = $this->sso_loginname_login_error_times . $loginname;
        if ($get) {
            $data['times'] = RedisIO::get($key);//登录错误次数
            return $data;
        } else {
            return $this->increCount($key, $expire);
        }
    }

    /**
     * ip 登录失败次数记录
     * @param $loginname
     * @param string $get
     * @return boolean|unknown
     */
    public function ipLoginErrorInfo($ip, $get = true, $expire = 86400) {
        if (empty($ip)) {
            return false;
        }
        $key = $this->sso_ip_login_error_times . $ip;
        if ($get) {
            $data['times'] = $this->memGet($key);//登录错误次数
            return $data;
        } else {
            return $this->increCount($key, $expire);
        }
    }

    /**
     * @desc 根据key从memcache中获取数据
     * @param $key
     * @return boolean
     */
    public function memGet($key) {
        if (empty($key)) return NULL;
        $value = RedisIO::get($key);
        return $value;
    }

    /**
     * @desc 验证验证码方法 废弃！！！
     * @version 2015-06-05
     * @param unknown $verifyKey
     * @param unknown $verify
     * @param string $captchaId
     * @return boolean
     */
    public function checkVerify($verifyKey, $verify, $captchaId = '') {
        if (empty($verify)) {
            setcookie('captchaId', '');
            setcookie('captchaValue', time());
            return false;
        }
        if (empty($captchaId)) {
            $captchaId = $_COOKIE['captchaId'];
        }
        if ($_COOKIE['captchaId'] == md5(VERIFY_KEY) && $_COOKIE["captchaValue"] == $verify) {
            setcookie('captchaId', '');
            setcookie('captchaValue', time());
            return true;
        }
        setcookie('captchaId', '');
        setcookie('captchaValue', time());
        return false;
    }

    /**
     * 检查该手机号是否还可以发送短信
     */
    public function checkMobieIfSendMsg($mobile) {
        if (empty($mobile)) {
            return false;
        }
        $times = $this->getSendMsgTimes($mobile);
        //每天每个手机号最多可以发送5条短信
        if ($times > 5) {
            return false;
        }
        return true;
    }

    /**
     * 获取手机每天发送短信次数
     */
    public function getSendMsgTimes($mobile) {
        if (empty($mobile)) {
            return null;
        }
        $cacheKey = $this->_sso_send_msg_times_key . $mobile;

        $times = RedisIO::get($cacheKey);
        return $times;
    }

    /**
     * 获取单个平台一段时间内发送短信次数
     */
    public function getClientSendMsgTimes($mobile, $plat, $action) {
        if (empty($mobile)) {
            return null;
        }
        $cacheKey = $this->_sso_client_send_msg_times_key . $mobile . $plat . $action;

        $times = RedisIO::get($cacheKey);

        return $times;
    }

    /**
     * 设置每天发送短信次数限制
     * 每个自然天每个手机号最多可以发送3条短信
     */
    public function setSendMsgTimes($mobile) {
        if (empty($mobile)) {
            return null;
        }
        $cacheKey = $this->_sso_send_msg_times_key . $mobile;
        $times = RedisIO::get($cacheKey);
        $time = strtotime(date('Y-m-d')) + 86400 - time();
        if ($times > 0) {
            RedisIO::incr($cacheKey);
        } else {
            RedisIO::set($cacheKey, 1, $time);
        }
    }

    /**
     * 设置单个平台每天发送短信次数限制
     * @param mobile
     * @param expire存储时长
     */
    public function setClientSendMsgTimes($mobile, $plat, $action, $expire) {
        if (empty($mobile)) {
            return null;
        }
        $cacheKey = $this->_sso_client_send_msg_times_key . $mobile . $plat . $action;
        $times = RedisIO::get($cacheKey);
        if ($times > 0) {
            RedisIO::incr($cacheKey);
        } else {
            RedisIO::set($cacheKey, 1, $expire);
        }
    }

    /**
     * @desc  单ip注册是否限制
     * @param $ip
     * return boolean
     */
    public function ipRegLimited($ip, $get = true, $expire = 86400) {
        if (empty($ip)) {
            return true;
        }
        $key = $this->sso_ip_reg_is_limited . $ip;
        if ($get) {
            $limited = RedisIO::get($key);
            return $limited == 1;
        } else {
            return RedisIO::set($key, 1, $expire);
        }
    }

    /**
     * ip登录注册次数记录
     * @param unknown $ip
     * @param string $get
     * @return boolean|unknown
     */
    public function ipRegSuccesInfo($ip, $get = true, $expire = 86400) {
        if (empty($ip)) {
            return false;
        }
        $ip_reg_success_times = $this->sso_ip_reg_success_times . $ip;
        if ($get) {
            $data['times'] = RedisIO::get($ip_reg_success_times);//登录错误次数
            return $data;
        } else {
            return $this->increCount($ip_reg_success_times, $expire);
        }
    }

    /**
     * @desc 通过传Token获取用户登录信息
     * @param string $sso_tk
     * @return array
     */
    public function isLoginNoCookie($sso_tk = '') {
        if (empty($sso_tk)) {
            return array();
        }
        $userinfo = array();
        $tkinfo = $this->__requestSSO($sso_tk);
        if (!empty($tkinfo) && is_array($tkinfo) && isset($tkinfo['bean']) && !empty($tkinfo['bean']) && is_array($tkinfo['bean'])) {
            $info = $tkinfo['bean'];
            if (!empty($info) && is_array($info) && isset($info['uid']) && isset($info['username']) && isset($info['nickname'])) {
                $userinfo['uid'] = 0;
                $userinfo['nickname'] = $info['nickname'];
                $userinfo['email'] = $info['email'];
                $userinfo['name'] = $info['username'];
                $userinfo['ssouid'] = $info['uid'];
            }
        }
        return $userinfo;
    }

    /**
     * @desc 调用SSO接口判断用户登录Token是否有效.
     * @param string $sso_tk
     * @return array|mixed
     */
    private function __requestSSO($sso_tk = '') {
        if (empty($sso_tk)) {
            return array();
        }

        $tkinfo = self::checkTicket($sso_tk);

        if (!empty($tkinfo)) {
            $tkinfo = json_decode($tkinfo, true);
        } else {
            $tkinfo = array();
        }
        return $tkinfo;
    }

    var $initArr = array(
        'bean' => '',
        'action' => '',
        'responseType' => 'json',
        'status' => '0',
        'errorCode' => '0',
        'message' => ''
    );

    /**
     * 直接将sso原有请求接口变为此方法私有方法
     * @desc
     * 验证用户tk是否邮箱，检查用户的登录状态
     * @param
     * tk : 用户登录token
     * need_expire ： 是否需要过期时间： 0 => 不需要， 1=> 需要
     * need_profile ： 是否需要用户扩展信息：0 => 不需要， 1=> 需要
     */
    private function checkTicket($tk , $need_expire = 1 , $need_profile = 1) {
        $this->initArr['action'] = 'checkTicket';

        if (empty($tk)) {
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '参数不正确';
            return json_encode($this->initArr);
        }
        //验证是否需要返回token的过期时间
        if (isset($need_expire) && !empty($need_expire)) {
            $need_expire = true;
        } else {
            $need_expire = false;
        }

        //验证token有效性
        $sso_tk = self::ssotk($tk, false, null, $need_expire);
        //如果需要过期时间，返回数组
        if ($need_expire) {
            isset($sso_tk['expire']) && $expire = $sso_tk['expire'];
            isset($sso_tk['ssoid']) && $sso_tk = $sso_tk['ssoid'];
        }

        if ($sso_tk > 0) {
            $this->initArr['status'] = '1';
            //取用户的扩展信息
            if ($need_profile) {
                $userProfile = self::getUserByID($sso_tk, 1, 'total');
            } else {
                $userProfile = array();
            }
            if (isset($_GET['all'])) {
                $userinfo = self::getUserByID($sso_tk);
                $userinfo["ssouid"] = $sso_tk;
                $this->initArr['bean'] = array_merge($userinfo, $userProfile);
            } else {
                $this->initArr['bean'] = array_merge(array("result" => $sso_tk), $userProfile);
            }
            if ($need_expire) {
                if (isset($expire)) $this->initArr['expire'] = $expire;
            }
        } else {
            $this->initArr['errorCode'] = '1014';
            $this->initArr['message'] = 'sso票不正确或已过期';
        }
        return json_encode($this->initArr);
    }

    /**
     * @desc curl
     * @param $url
     * @param bool $post_data
     * @param int $timeout
     * @return bool|mixed
     */
    private function ___curl($url, $post_data = false, $timeout = 1) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post_data) {

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        return $output;
    }

    /**
     * @desc 登录信息
     * @return array
     */
    public function isLogin() {
        $userinfo = array();
        if (isset($_COOKIE['sso_tk']) && !empty($_COOKIE['sso_tk'])) {
            $tkinfo = $this->__requestSSO($_COOKIE['sso_tk']);
            if (!empty($tkinfo) && is_array($tkinfo) && isset($tkinfo['bean']) && !empty($tkinfo['bean']) && is_array($tkinfo['bean'])) {
                $info = $tkinfo['bean'];
                if (!empty($info) && is_array($info) && isset($info['uid']) && isset($info['username']) && isset($info['nickname'])) {
                    $userinfo['uid'] = 0;
                    $userinfo['nickname'] = $info['nickname'];
                    $userinfo['email'] = $info['email'];
                    $userinfo['name'] = $info['username'];
                    $userinfo['ssouid'] = $info['uid'];
                }
            }
        }
        return $userinfo;
    }

    /**
     * @desc 获取用户信息
     * @param $ssouidStr
     * @return array
     */
    public function getSsoUserInfoArr($ssouidStr) {
        $return = array();
        $uids = explode(",", $ssouidStr);
        $memCacheList = array();
        $notHitCacheList = array();
        if (!empty($uids)) {
//            $apiUids = implode(",", $uids);
//            /*sso接口中调取*/
//            $url = "http://sso.cztv.com/api/getUserByIdList?uidlist=$apiUids&from=vcm";
//            $post_data = false;
//            $timeout = 2;
//            $userInfo = $this->___curl($url, $post_data, $timeout);
//            $userInfo = @json_decode($userInfo, true);

            $userInfo = $this->getUserByIdList((array)$uids);

            if (is_array($userInfo) && !empty($userInfo)) {
                $userInfoArr = $userInfo;
                foreach ($userInfoArr as $val) {
                    $memCacheList[$val['uid']] = $val;
                    $notHitCacheList[$val['uid']] = $val;
                }
            }
        }
        $return = $memCacheList;
        unset($memCacheList);
        unset($userInfoArr, $userInfo);
        return $return;
    }

    /**
     * @desc ip登录错误信息处理
     * @param $ip
     * @param bool $get
     * @param int $times
     * @return bool
     */
    public function ipLoginInfo($ip, $get = true, $times = 0) {
        if (empty($ip)) {
            return false;
        }
        $ip_login_error_times = $this->sso_ip_login_error_times . $ip;
        if ($get) {
            $data['login_error_times'] = RedisIO::get($ip_login_error_times);//登录错误次数
            return $data;
        } else {
            if ($times < 10) {
                RedisIO::set($ip_login_error_times, $times, 600);
            } else {
                RedisIO::set($ip_login_error_times, $times, 86400);
            }
            return true;
        }
    }

    /**
     * @desc 查看IP禁言信息
     * @param $ip
     * @return int|string
     */
    public function checkUserIpAuth($ip) {
        $cacheId = $this->_mcPrefix_auth . 'IP::' . $ip;
        $retMc = RedisIO::get($cacheId);
        $ret = 0;
        if ($this->_mcEnable && !empty($retMc)) {
            $ret = 1;
        } else {
            $blockip = AuditCommentBlockip::findFirst(array("conditions" => "ip='{$ip}'"));
            if (!empty($blockip) && !empty($blockip->id) && $blockip->id) {
                $ret = 1;
                RedisIO::set($cacheId, $ret);
            }
        }
        return $ret;
    }

    /**
     * @desc 查看用户禁言信息
     * @param $ssouid
     * @return int
     */
    public function checkUserAuth($ssouid) {
        $ret = 0;
        if (empty($ssouid)) {
            return $ret;
        }
        $cacheId = $this->_mcPrefix_auth . $ssouid;
        $retMc = RedisIO::get($cacheId);
        if ($this->_mcEnable && !empty($retMc)) {
            $ret = 1;
        } else {
            $res = AuditBanComments::findFirst(array("conditions" => "ssoid='{$ssouid}'"));
            if (!empty($res) && !empty($res->id) && $res->id) {
                $ret = 1;
                RedisIO::set($cacheId, $ret);
            }
        }
        return $ret;
    }

    /**
     * 2016/7/3饶佳修改
     * 缓存获取方法，数据库和内存比较
     *
     * @desc 批量获取用户信息 /去掉返回的多余的用户信息
     * @param $uids
     * @param int $iscache
     * @return array
     */
    public function getUserByIdList($uids, $iscache = 1) {
        if (empty($uids) || !is_array($uids)) {
            return array();
        }
        $uids = array_values($uids);
        $data = array();
        if ($iscache) {
            $uid_keys = array();
            foreach ((array)$uids as $uid) {
                $uid_keys[] = $this->sso_uid_key . $uid;
                $data[] = json_decode(RedisIO::get($this->sso_uid_key . $uid), true);
            }

            if (!empty($data)) {
                $data = array_values($data);
                //得到查询条件中缓存中已有keys值
                foreach ((array)$data as $da) {
                    $uid_exis[] = $da['uid'];
                }
                //得到查询条件里缓存中没有的keys
                if (!empty($uid_exis)) $uids = array_diff($uids, (array)$uid_exis);
            }

        }

        //如果1.根据条件得到的用户信息为空，或2.存在缓存中没有keys，或3.不使用缓存
        if (empty($data) || !$iscache || !empty($uids)) {
            $dataDB = array();
            foreach ((array)$uids as $key => $uid) {
                if (empty($uid)) {
                    continue;
                }
                $dataDB[] = $this->getUserByID($uid);
            }
            //数据缓存
            $data = array_merge((array)$dataDB, (array)$data);
        }

        foreach ((array)$data as $key => $da) {
            if (!empty($da['uid'])) {
                $da['mobile'] == $da['uid'] && $data[$key]['mobile'] = '';
                $da['email'] == $da['uid'] && $data[$key]['email'] = '';
                if (empty($da['picture'])) {
                    if (!empty($da['avatar']))
                        $data[$key]['picture'] = str_replace('hudong-cztv.oss-cn-hangzhou.aliyuncs.com','ohudong.cztv.com',$da['avatar']);
                    else
                        $data[$key]['picture'] = 'http://i1.letvimg.com/img/201207/30/tx298.png,http://i0.letvimg.com/img/201207/30/tx200.png,http://i0.letvimg.com/img/201207/30/tx70.png,http://i3.letvimg.com/img/201207/30/tx50.png';
                }
            } else {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * @desc 更新$uid的头像设置
     * @version 2015-06-09
     * @param string $sso_tk
     * @param array $icons
     * @throws Exception
     * @return mixed
     */
    public function updateUserIcon($sso_tk, array $icons) {
        if (empty($sso_tk)) {
            throw new Exception('参数错误');
        }
        $icons = implode(',', $icons);
        if (empty($icons)) {
            throw new Exception('参数错误');
        }
        $url = 'http://sso.cztv.com/api/modifyPhoto';
        $data = array(
            'tk' => $sso_tk,
            'photo' => $icons
        );
        $response = curl_request($url, 'post', $data, 5, true);
        $response = json_decode($response, true);
        if (empty ($response)) {
            throw new Exception('请求超时');
        }

        return $response;
    }
}
