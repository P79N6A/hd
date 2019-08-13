<?php
/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/4/29
 * Time: 9:37
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SsoUser extends Model {
    const SIGNSYSLOGNG = false;
    const sso_auth_code_key = 'sso::authcode:';//手机注册验证码 客户端使用
    protected $_sso_block_counter_key = 'sso::bcounter:';//防刷计数器key值
    protected $userlogin_table = 'userlogin';
    protected $sso_uid_key = 'sso::uid:';//基础信息
    protected $sso_reg_user_key = 'sso::reg:ip:sum:';
    protected $sso_active_email_link = 'sso::activeemail:';//用户激活邮件链接合法性验证
    protected $_sso_info_email_key = 'sso::info::email:';//用户基础信息
    protected $_sso_info_mobile_key = 'sso::info::mobile:';//用户基础信息
    protected $_sso_info_nickname_key = 'sso::info::nickname:';//用户基础信息
    //token平台来源，token前缀
    private $__platform = array(
        /**
         * test
         * 提供给各个客户端测试专用
         */
        'test_p' => '999',
        /**
         *  默认
         */
        'def' => '101',//default

        /**
         * 网站端
         */
        'www' => '102',//PC
        'web' => '103',//m站
        'app' => '104',//app

        /**
         * TV端
         */
        'tv' => '105',//中国蓝TV tv端

        /**
         * 移动端
         */
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
        //正则表达式:中国大陆手机号
        if (preg_match('/^(1[3|4|5|7|8]|00886|0064)\d{9}$/', $mobile)) {
            return true;
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
     * @desc 防刷的计数器，每调用一次加1，如果计数器大于$limit 就返回false
     * @version 2015-06-01
     * @param
     * $key 对应的防刷key
     * $limit 限制次数
     * $expire 过期时间（秒），默认false明天
     */
    public function blockCounter($key, $limit, $expire = false) {
        $key = $this->_sso_block_counter_key . $key;
        $cnt = MemcacheIO::increment($key);//为某一个已存在的键对应的值进行加1（实际上是加法运算， 默认加1）,成功返回1，失败返回false
        if (false === $expire) $expire = strtotime('tomorrow');
        if (false === $cnt) {
            MemcacheIO::set($key, 1, $expire);
            $cnt = 1;
        }
        if ($cnt > $limit) return false;
        return true;
    }

    /**
     * @desc 对防刷计数器减一，配合blockCounter函数，当操作失败时，对已经加了的计数器做减一操作
     * @version 2015-06-02
     * @param unknown $key
     */
    public function decrCounter($key) {
        $key = $this->_sso_block_counter_key . $key;
        $cnt = MemcacheIO::increment($key, -1);
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
        $code = MemcacheIO::get($cacheKey);
        $this->signSysLog('sso', 'msgdebug', 'sso', 'test', array("get:", $cacheKey, var_export($code, true)));
        if (empty($code)) {
            //$code = rand(100000, 999999);由于新的短信接口只支持4位数
            $code = rand(1000, 9999);
            //确保同一个手机在同一个平台上的同一个操作的激活码是唯一的
            $flag = MemcacheIO::set($cacheKey, $code, $cacheTime);
            $this->signSysLog('sso', 'msgdebug', 'sso', 'test', array("set", $cacheKey, "code", $code, "exp", $cacheTime, "ret", var_export($flag, true)));
            if ($flag) {
                return $code;
            } else {
                return false;
            }
        } else {
            //重新设置缓存时间
            $flag = MemcacheIO::set($cacheKey, $code, $cacheTime);
            return $code;
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
     * @version 2015-06-01
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

    /**
     * @desc 获取手机激活码
     * @version 2015-06-01
     * @param string $mobile
     * @param string $plat
     * @param string $action
     * @param string $code_key
     * @return boolean|string
     */
    public function getClientAuthCode($mobile, $plat, $action, $code_key = UserModel::sso_auth_code_key) {
        if (empty($mobile) || empty($plat) || empty($action)) {
            return false;
        }
        $cacheKey = $code_key . $mobile . $plat . $action;
        $checkTimesKey = $code_key . ':times' . $mobile . $plat . $action;
        $checkTimes = MemcacheIO::get($checkTimesKey);
        if (!$checkTimes) {
            MemcacheIO::set($checkTimesKey, 1, 86400);
        } elseif ($checkTimes < 3) {
            MemcacheIO::increment($checkTimesKey);
        } else {
            MemcacheIO::delete($checkTimesKey);
            MemcacheIO::delete($cacheKey);
            return '';
        }
        return MemcacheIO::get($cacheKey);
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
        MemcacheIO::delete($code_key . ':times' . $mobile . $plat . $action);
        return MemcacheIO::delete($cacheKey);
    }

    /*
     * 敏感词过滤方法，暂时未完成
     */
    public function nicknameFilter($content) {
        return true;
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
        if ($len == 11)//中国大陆手机号
        {
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

    public function setCookie($name, $value, $domain = 'cztv.com', $time = 0) {
        if (empty($name) || empty($value)) {
            return false;
        }
        header('P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"');//p3p
        Cookie::set($name, $value, $time, '/', $domain); //设置cookie
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
        return Plugin_Util::send_mail($email, '中国蓝TV帐号激活', $infoStr);
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
        $cacheValue = MemcacheIO::get($cacheKey);
        //第一次点击链接
        if (!$cacheValue) {
            MemcacheIO::set($cacheKey, $uid, 86400);
            return true;
        } else {
            //不是第一次链接
            return false;
        }
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

//            if (!empty($userBase->username)) {
//                $loginname = $userBase->username;
//                list($pre) = explode('_', $loginname);
//                if (!in_array($pre, array(1=>'sina',2=>'renren',3=>'qq',4=>'msn',5=>'kaixin',6=>'feixin',8=>'tianya', 9=>'alipay', 10=>'udb', 11 => 'baidu',12=>'weixin',13=>'letv'))) {
//                    $usernameAuthTable = User::getHashTable($userBase->username);
//                    $sql   = "UPDATE " . $usernameAuthTable . " SET status = '" . $status . "' WHERE uid = '" . $uid . "'";
//                    $this->_passportdb->sql($sql);
//                }
//            }
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
//        Cola::loadClass('Plugin_Util', ROOT_PATH);
        $time = time() - 3600 * 24 * 30;
        Cookie::set('sso_tk', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('ssouid', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('sso_nickname', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('sso_picture', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('m', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('utype', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('lfrom', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('flogin', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('casflag', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('u', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('ui', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('j-sync', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('loginname', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('sso_icon', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('baidu_uid', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('baidu_url', '', $time, '/', '.cztv.com'); //设置cookie
        Cookie::set('first_login', '', time() - 86400 * 30 * 6, '/', '.cztv.com');
        Cookie::set('csrf', '', time() - 86400 * 30 * 6, '/', '.cztv.com');
    }

    /**
     * @desc 同步登录公用方法 TODO
     * @version 2015-06-07
     * @param unknown $uid
     * @param string $next_action
     */
    public function sysLogin($uid, $next_action = '') {
        $memberme = 1;
        $sso_tk = $this->ssotk($uid, true, 'web');
        $sso_tk = urldecode($sso_tk);
        $sign = md5($memberme . $sso_tk . SSOSYSLOGINSTORE);
        $url = 'http://api.letvstore.com/tvos/xsite_sso?memberme=' . $memberme . '&sso_tk=' . $sso_tk . '&sign=' . $sign;
        echo "<iframe style='display:none' src='" . $url . "' onload='ifmload()'></iframe>";
        //乐视致新新登录流程
        $userkey = $this->platUserkey($uid, HD_KEY);
        if ($memberme) {
            $expire_type = 3;
        } else {
            $expire_type = 0;
        }
        if (SERVER_LOCATION == 'hongkong') {
            $shopurl = 'http://hk.authentication.shop.letv.com';
        } else {
            $shopurl = 'http://authentication.shop.letv.com';
        }
        $url = $shopurl . "/api/web/query/leTVLoginSimplify.json?USERKEY=" . $userkey . "&EXPIRE_TYPE=" . $expire_type;
        echo "<iframe style='display:none' src='{$url}' onload='ifmload()'></iframe>";
        //登录成功之后需要跳转
        if (!empty($next_action)) {
            echo "<script type=\"text/javascript\">";
            echo "var loadcnt = 0;";
            echo "function ifmload(){loadcnt++;if(loadcnt >= 2){redirect();}}";
            echo "function redirect(){location.href=\"{$next_action}\";}";
            echo "setTimeout(\"redirect()\", 5000);";
            echo "</script>";
        } else {
            //登录成功之后不需要跳转
            echo "<script type=\"text/javascript\">";
            echo "var loadcnt = 0;";
            echo "function ifmload(){loadcnt++;}";
            echo "</script>";
        }
    }

    /**
     * @desc 用户认证票 :用户登录改造，修改ssotk格式
     * @version 2015-06-03
     * @param unknown $ssoid
     * @param string $encode
     * @param string $plat
     * @param string $need_expire
     * @return boolean|string|Ambigous <multitype:, multitype:multitype: unknown >|multitype:Ambigous <multitype:, multitype:unknown multitype:unknown  , multitype:unknown , unknown> Ambigous <multitype:unknown , unknown> |Ambigous <string, mixed>
     */
    public function ssotk($ssoid, $encode = true, $plat = null, $need_expire = false) {
        if ((!is_string($ssoid) && !is_int($ssoid)) || empty($ssoid)) {
            return false;
        }
        $key = LOGIN_KEY;
        $mUser = new User();
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
                list($uid, $create_time) = explode('|', $tickt_info);//uid token生产时间
                $userInfo = $mUser->getUserByID($uid);

                //如果用户被封禁，返回false
                if ($userInfo['status'] == '0') {
                    $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($uid, $userInfo['status']));
                    return false;
                }

                //验证用户是否修改过密码，以及依据token生产时间，确认格式是否正确
                if (in_array($tk_plat, array('102', '103', '105', '999'))) {
                    //校验生产时间与修改密码时间的先后
                    //修改过密码
                    if (!empty($userInfo['lastModifyPwdTime']) && (empty($create_time) || $create_time < $userInfo['lastModifyPwdTime'])) {
                        //早于修改密码时间之前生成的token全部过期
                        $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($ssoid, $userInfo['lastModifyPwdTime'], $create_time));
                        return false;
                    }
                }
                if ($need_expire) {
                    $uid = array('ssoid' => $uid, 'expire' => $expire);
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
                    $userInfo = $mUser->getUserByID($uid);

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
                            $this->signSysLog('sso', 'checkTicketError', 'api', 'checkTicket', array($ssoid, $userInfo['lastModifyPwdTime'], $create_time));
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
            $property['message'] = implode('&', $message); //设置日志内容
            $directory = '/tmp/' . $floder . '/' . $file . '.log';// /tmp/sso/ /tmp/api /tmp/my/ /tmp/favorites/
            error_log(json_encode($property) . "\r\n", 3, $directory);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 清空用户信息缓存
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
}