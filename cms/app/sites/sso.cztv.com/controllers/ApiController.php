<?php

/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/5/5
 * Time: 14:43
 */
class ApiController extends SsoBaseController {
    var $initArr = array(
        'bean' => '',
        'action' => '',
        'responseType' => 'json',
        'status' => '0',
        'errorCode' => '0',
        'message' => ''
    );

    /**
     * 获取用户的扩展信息
     */
    public function getUserProfileByIDAction() {
        $uid = $this->request->getQuery('uid');
        $dtype = $this->request->getQuery('dtype');
        $userModel = new User();
        if (empty($uid)) {
            $userModel->errorShow(1000, $bean = array());
        }
        $userProfile = $userModel->getUserByID($uid, 1, 'total');
        if (empty($userProfile)) {
            $userModel->errorShow(1000, $bean = array());
        }
        if (!empty($dtype) && isset($userProfile[$dtype])) {
            $bean = array(
                $dtype => $userProfile[$dtype],
            );
        } elseif (empty($dtype)) {
            $bean = $userProfile;
        } else {
            $userModel->errorShow(1000, $bean = array());
        }
        $userModel->errorShow(200, $bean);
    }

    /**
     * @desc 修改头像
     * tk : 用户登录token
     * photo : 头像
     */
    public function modifyPhotoAction() {
        if ($this->request->isPost() == true) {
            $tk = $this->request->getPost('tk', null, '');
            $photo = $this->request->getPost('photo', null, '');
        } else {
            $uid = intval($this->request->getQuery('uid', null, 0));
            $photo = $this->request->getQuery('photo', null, '');
        }

        $this->initArr['action'] = 'modifyPhoto';
        $userModel = new User();
        if (!empty($tk)) {
            $uid = $userModel->ssotk($tk, false);
            if (empty($uid)) {
                $userModel->errorShow('1020', array());
            }
        }
        if ($uid <= 0) {
            $userModel->errorShow('1000', array());
        } else {
            $userModel->photoReset($photo, $uid);
            $userModel->errorShow('0', array("result" => '1'));
        }
        $userModel->signSysLog('sso', 'UserLog', 'modifyPhoto', 'modifyPhoto', array($this->model('Role')->getClientIp(), $uid));
    }


    /**
     * @desc 修改密码接口
     * 请求示例: http://api.sso.letv.com/api/modifyPwd
     * @version 2015-06-09
     * @param
     * oldpwd:旧密码
     * newpwd：新密码
     * tk：用户登录验证token
     */
    function modifyPwdAction() {
        $oldpwd = $this->request->getPost('oldpwd', null, '');
        $newpwd = $this->request->getPost('newpwd', null, '');
        $tk = $this->request->getPost('tk', null, '');
        $this->initArr['action'] = 'modifyPwd';
        $mUser = new User();
        $uid = $mUser->ssotk($tk, false);
        if (empty($uid)) {
            $mUser->errorShow('1020', array());
            exit;
        }

        $mUser->signSysLog('sso', 'UserLog', 'modifyPwd', 'modifyPwd', array($mUser->getClientIp(), $uid));
        if ($uid <= 0 || empty($oldpwd) || empty($newpwd) || !$mUser->checkPwd($newpwd)) {
            $mUser->errorShow('1009', array());
            exit;
        }
        $arr = $mUser->getUserByIdAll($uid);
        if (!empty($arr['cdkey'])) {
            $pwd = $mUser->pwdcode($oldpwd, $arr['cdkey'], true);
        } else {
            $pwd = md5($oldpwd);
        }
        if (empty($arr) || $pwd != $arr['pwd'] || $arr['status'] != 1) {
            $mUser->errorShow('1008', array());
            exit;
        } else {
            $mUser->passwordReset($newpwd, $uid);
            $mUser->signSysLog('sso', 'UserActionLog', 'modifyPwd', 'modifyPwd', array($mUser->getClientIp(), $uid));
            $mUser->errorShow('0', array("result" => '1'));
            exit;
        }
    }

    /**
     * @desc
     * 验证用户tk是否邮箱，检查用户的登录状态
     * 请求示例：
     * http://sso.letv.com/api/checkTicket/tk/ZGJiMjAxZEk5dS9zOGJpMUFYWEV5ajBhZkpCMnVuVklGQjVkR1FTWEVUVVpKTzkyZjJ3
     * @param
     * tk : 用户登录token
     * need_expire ： 是否需要过期时间： 0 => 不需要， 1=> 需要
     * need_profile ： 是否需要用户扩展信息：0 => 不需要， 1=> 需要
     */
    public function checkTicketAction() {
        $this->initArr['action'] = 'checkTicket';
        $tk = $this->getVar('tk');
        $need_expire = $this->getVar('need_expire', '1');
        $need_profile = $this->getVar('need_profile', '1');
        if (empty($tk)) {
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '参数不正确';
            echo json_encode($this->initArr);
            exit;
        }
        //验证是否需要返回token的过期时间
        if (isset($need_expire) && !empty($need_expire)) {
            $need_expire = true;
        } else {
            $need_expire = false;
        }
        $userModel = new User();
        //验证token有效性
        $sso_tk = $userModel->ssotk($tk, false, null, $need_expire);
        //如果需要过期时间，返回数组
        if ($need_expire) {
            isset($sso_tk['expire']) && $expire = $sso_tk['expire'];
            isset($sso_tk['ssoid']) && $sso_tk = $sso_tk['ssoid'];
        }

        if ($sso_tk > 0) {
            $this->initArr['status'] = '1';
            //取用户的扩展信息
            if ($need_profile) {
                //if (isset($need_profile) && !empty($need_profile)) {
                $userProfile = $userModel->getUserByID($sso_tk, 1, 'total');
            } else {
                $userProfile = array();
            }
            if (isset($_GET['all'])) {
                $userinfo = $userModel->getUserByID($sso_tk);
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
        echo json_encode($this->initArr);
    }

    /**
     * @desc 修改用户信息
     * @param
     * uid : 用户id
     * gender：性别
     * qq ： 扣扣号
     * birthday ： 生日
     * nickname ： 昵称
     * province：省份
     * city ： 城市
     * realname ： 真实姓名
     *
     */
    function updateUserInfoAction() {
        $this->initArr['action'] = 'updateUserInfo';
        $uid = intval($this->request->getQuery('uid', null, ''));
        $gender = $this->request->getQuery('gender', null, '');
        $qq = $this->request->getQuery('qq', null, '');
        $birthday = urldecode($this->request->getQuery('birthday', null, ''));
        $nickname = urldecode($this->request->getQuery('nickname', null, ''));
        $province = urldecode($this->request->getQuery('province', null, ''));
        $city = urldecode($this->request->getQuery('city', null, ''));
        $name = urldecode($this->request->getQuery('name', null, ''));//realname
        //新增手机 [邮箱]更新字段，手机更新时验证手机的正确性checkcode
        $mobile = urldecode($this->request->getQuery('mobile', null, ''));//移动端绑定手机需求，仅仅是添加手机信息
        //校验验证码
        $checkCode = $this->request->getQuery('checkCode', null, '');
        $userModel = new User();
        if (!empty($checkCode) && !$userModel->checkClientAuthCode($mobile, 'web', $action = 'reg', $checkCode)) {
//            if (!empty($checkCode) && !$this->model("User")->checkClientAuthCode($mobile, 'web', $action = 'reg', $checkCode)) {
            $userModel->signSysLog('sso', 'UserLog', 'sso', 'api/updateUserInfo', array(date('Y-m-d H:i:s'), $mobile, $nickname, '1022', 'web', $checkCode));
            $this->initArr['errorCode'] = '1022';
            $this->initArr['message'] = '验证码错误';
            echo json_encode($this->initArr);
            exit;
        }
        if ($uid <= 0) {
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '用户ID不正确';
            echo json_encode($this->initArr);
            exit;
        }
        //昵称唯一性检查
        if (isset($nickname) && !empty($nickname)) {
            //过滤敏感词
            if (false && $userModel->nicknameFilter($nickname))//TODO 评论里有敏感词过滤，可以共用！！！
            {
                $this->initArr['errorCode'] = '1013';
                $this->initArr['message'] = '昵称含有敏感词，请重新修改';
                echo json_encode($this->initArr);
                exit;
            }
            //检查昵称是否存在
            $uidCheck = $userModel->getUserByCond('nickname', $nickname);

            if (!empty($uidCheck) && $uidCheck['uid'] != $uid) {
                $this->initArr['errorCode'] = '1013';
                $this->initArr['message'] = '昵称已存在';
                echo json_encode($this->initArr);
                exit;
            } else {
                $userModel->signSysLog('sso', 'UserLog', 'sso', 'api/updateUserInfo', array(date('Y-m-d H:i:s'), $mobile, $nickname, '0', 'web', $checkCode));
            }
        }
        $row = array();
        if (isset($_GET['gender'])) {
            $row['gender'] = $gender;
        }
        if (isset($_GET['qq'])) {
            $row['qq'] = $qq;
        }
        if (isset($_GET['birthday'])) {
            $row['birthday'] = $birthday;
        }
        if (isset($_GET['nickname'])) {
            $row['nickname'] = empty($nickname) ? 'user' . $uid : $nickname;
        }
        if (isset($_GET['province'])) {
            $row['province'] = $province;
        }
        if (isset($_GET['city'])) {
            $row['city'] = $city;
        }
        if (isset($_GET['name'])) {
            $row['name'] = $name;
        }
        if (isset($_GET['mobile']) && !empty($mobile)) {
            $row['mobile'] = $mobile;
        }

        $userModel->modifyUserInfo($row, $uid);//TODO
        $logdata['ip'] = $userModel->getClientIp();
        $logdata['uid'] = $uid;
        $logdata = array_merge((array)$logdata, (array)$row);
        $userModel->signSysLog('sso', 'UserActionLog', 'modifyUserInfo', 'updateUserInfo', array_values($logdata));
        $this->initArr['status'] = '1';
        $this->initArr['bean'] = array("result" => '1');
        echo json_encode($this->initArr);
        exit;
    }

    /**
     * 客户端登录 支持邮箱、手机、用户名登录
     * sso改造，登录成功之后，统一返回统一格式的ssotk，用以唯一登录标识
     * 请求示例：
     * http://api.sso.letv.com/api/clientLogin?loginname=woailetv2011&password=123456&registService=my
     * @author Xuhonglei@letv.com
     * @version 2013-02-21
     * @param
     * loginname:登录名，取值为手机号、邮箱和用户名
     * password:密码
     * plat:平台标识，取值为sso分配
     * registService:登录来源，基本被废弃，被plat取代
     * equipType:设备类型
     * equipID:设备ID
     * softID:软件版本
     * md5:密码是否经过md5处理 （1：经过处理 0：没经过处理）
     * need_ptvid:登录成功时候，是否需要返回ptvid，ptvid已经被废弃，直接返回uid （1:需要 0：不需要）
     * profile:是否需要返回扩展信息
     */
    public function clientLoginAction() {
        $loginname = Request::getPost('loginname', null, '');//登录名，取值为手机号、邮箱和用户名
        $password = Request::getPost('password', null, '');//密码
        $plat = Request::getPost('plat', null, ''); //平台标识，取值为sso分配
        $registService = Request::getPost('registService', null, 'my');//登录来源，基本被废弃，被plat取代
        $equipType = Request::getPost('equipType', null, ''); //设备类型
        $equipID = Request::getPost('equipID', null, ''); //设备ID
        $softID = Request::getPost('softID', null, ''); //软件版本
        $md5 = Request::getPost('md5', null, '0');//密码是否经过md5处理
        $need_ptvid = Request::getPost('need_ptvid', null, 0);//登录成功时候，是否需要返回ptvid，ptvid已经被废弃，直接返回uid
        $ip = Request::getPost('ip', null, '');//客户端ip
        $longitude = Request::getPost('longitude', null, '');//用户位置的经度
        $latitude = Request::getPost('latitude', null, '');//用户位置的纬度
        $cid = Request::getPost('cid', null, '');//手机基站的编号
        $lac = Request::getPost('lac', null, '');//手机基站区域码
        $imei = Request::getPost('imei', null, '');
        $mac = Request::getPost('mac', null, '');
        $deviceId = Request::getPost('deviceid', null, '');//设备号，如具体mac地址
        $licensetype = Request::getPost('licensetype', null, '');//牌照方名称
        $channel = Request::getPost('channel', null, '');//渠道
        $mUser = new User();
        if (!empty($ip)) {
            // 调研：10分钟内登录100次的ip记日志
            if (!$mUser->blockCounter($ip, 100, 600)) {
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1060', $equipType, $equipID, $softID));
            }
        }
        $seeArr = Request::getQuery('seeArr', null, false);
        if (empty($ip)) {
            $ip = $mUser->getClientIp();
            $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1063', $equipType, $equipID, $softID));
            $mUser->errorShow(1000, array(), $seeArr);
            exit;
        }

        // 单账号：10分钟登录失败30次  3分钟不能登录
        if ($mUser->loginnameLimited($loginname)) {
            $mUser->errorShow(1038, array(), $seeArr);
            exit;
        }

        // 判断ip是否被限制
        if (false && $mUser->ipLoginLimited($ip))//TODO
        {
            $mUser->errorShow(1037, array(), $seeArr);
            exit;
        }

        $this->initArr['action'] = 'clientLogin';

        //验证请求平台标识plat是否合法
        if (!$mUser->checkPlat($plat)) {
            $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1005', $equipType, $equipID, $softID));
            $this->initArr['errorCode'] = '1005';
            $this->initArr['message'] = '请求来源不合法！';
            //json格式
            echo json_encode($this->initArr);
            exit;
        }
        //验证客户登录
        if ($md5) {
            $userinfo = $mUser->userLogin($loginname, $password, $registService, '1');
        } else {
            $userinfo = $mUser->userLogin($loginname, $password, $registService);
        }
        if (!empty($userinfo)) {
            //是否加扩展信息
            if (isset($_REQUEST['profile'])) {
                $profileArr = $mUser->getUserProfileByID($userinfo['uid']);
                $userinfo = array_merge($userinfo, $profileArr);
            }

            //判断用户状态
            if ($userinfo['status'] == '1') {
                // 记录ip 10分钟内登录成功次数
                $ip_login_success_times = $mUser->ipLoginSuccesInfo($ip, false, 600);
                if ($ip_login_success_times >= 100) {
                    // 单ip：10分钟之内登录成功100次 则一个小时不能登录
                    $mUser->ipLoginLimited($ip, false, 3600);
                    $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1061', $equipType, $equipID, $softID));
                }
                $this->initArr['bean'] = $userinfo;
                //增加返回ssotk
                $this->initArr['tv_token'] = $mUser->ssotk($userinfo['uid'], true, $plat);
                $this->initArr['sso_tk'] = $this->initArr['tv_token'];
                $this->initArr['status'] = '1';
                //登录数统计 平台和登录方式
                $mUser->signSysLog('sso', 'clientLogin', 'sso', 'api/clientLogin', array($userinfo['uid'], $plat, $registService, $equipType, $equipID, $softID));
                if (preg_match('~^\S+@\S+\.[a-z]{2,5}$~is', $loginname)) {
                    $logintype = 'email';
                } elseif ($mUser->checkMobile($loginname)) {
                    $logintype = 'mobile';
                } else {
                    $logintype = 'username';
                }
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLogin', array($ip, $plat, $logintype, $userinfo['uid'], $loginname, $registService, $equipType, $equipID, $softID));
            } elseif ($userinfo['status'] == '0') {
                $this->initArr['errorCode'] = '1003';
                $this->initArr['message'] = '用户已被管理员屏蔽了！';
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1003', $equipType, $equipID, $softID));
            } elseif ($userinfo['status'] == '2') {
                $this->initArr['errorCode'] = '1004';
                $this->initArr['message'] = '用户邮箱未激活';
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1004', $equipType, $equipID, $softID));
            }
        } else {
            // 记录单帐号登录失败
            $loginname_error_times = $mUser->loginnameLoginErrorInfo($loginname, false, 600);
            if ($loginname_error_times >= 30) {
                // 10分钟登录失败30次  3分钟不能登录
                $mUser->loginnameLimited($loginname, false, 180);
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1065', $equipType, $equipID, $softID));
            }
            // 记录单ip登录30分钟内失败次数
            $ip_login_error_times = $mUser->ipLoginErrorInfo($ip, false, 1800);
            if ($ip_login_error_times >= 1000) {
                // 10分钟内登录失败1000次，一天不能登录
                $mUser->ipLoginLimited($ip, false);
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1062', $equipType, $equipID, $softID));
            }
            $this->initArr['errorCode'] = '1002';
            $this->initArr['message'] = '登录名或密码不正确';
            $mUser->signSysLog('sso', 'UserActionLog', 'login', 'clientLoginError', array($ip, $loginname, $plat, $need_ptvid, '1002', $equipType, $equipID, $softID));
        }
        $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientLogin', array(date('Y-m-d H:i:s'), $loginname, $plat, $need_ptvid, $this->initArr['errorCode'], $equipType, $equipID, $softID));
        //json格式
        echo json_encode($this->initArr);
    }

    /**
     * 根据uid获取用户信息
     * 请求示例：
     * http://api.sso.cztv.com/api/getUserByID/uid/7
     * @author xuhonglei@letv.com
     * @param
     * uid:用户id
     * need_ptvid:是否返回ptvid ，该id现在已经废弃，统一返回ssoid
     * dlevel：返回用户信息级别：basic=>基本信息  expand=>返回用户扩展信息 total=>返回全部信息，默认为basic
     */
    public function getUserByIDAction() {
        $uid = (int)$this->getVar('uid');
        $dlevel = $this->getVar('dlevel');
        $token = $this->getVar('token');

        $bean = array();
        $errorCode = 0;
        $mUser = new User();
        $uid_token = $mUser->ssotk($token, false);
        if (empty($uid)) {
            $errorCode = 1000;
        }elseif ($uid != $uid_token){
            $errorCode = 1002;
        } else {
            $userinfo = $mUser->getUserByID($uid, 1, $dlevel);
            //用户不存在
            if (empty($userinfo)) {
                $errorCode = 1001;
            } else {
                //!empty($need_ptvid) && $userinfo['ptv_uid'] = $userinfo['uid'];
                $bean = $userinfo;
            }
        }

        $mUser->errorShow($errorCode, $bean);
    }

    /**
     * 客户端发送手机短信接口：发送手机注册激活码
     */
    public function clientSendMsgAction() {
        $mobile = trim(Request::getQuery('mobile', 'string', ''));
        $auth = trim(Request::getQuery('auth', 'string', ''));
        $plat = trim(Request::getQuery('plat', 'string', ''));
        $action = trim(Request::getQuery('action', 'string', 'reg'));
        //$isCIBN = trim($this->get('isCIBN', 0));占不支持
        $captchaValue = trim(Request::getQuery('captchaValue', 'string', ''));
        $captchaId = trim(Request::getQuery('captchaId', 'string', ''));

        error_log(date("Y-M-d H:i:s") . ' | ' . $mobile . ' | request' . "\r\n", 3, "/tmp/code/{$mobile}.log");//TODO新增短信日志

        $key = CLIENTSENDMSG;
        $mUser = new User();
        // 如果有图片验证码，验证
        if (!empty($captchaValue) && !$mUser->checkVerify('', $captchaValue, $captchaId)) {
            //if($_COOKIE['captchaId'] != session_id() || $_COOKIE["captchaValue"]!= $captchaValue){
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'clientSendMsg';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '1027';
            $this->initArr['message'] = '图片验证码错误';
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '1027', $mobile, $plat, ''));
            echo json_encode($this->initArr);
            exit;
        }
        //验证手机号格式是否正确
        if (!$mUser->checkMobile($mobile)) {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'clientSendMsg';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '1011';
            $this->initArr['message'] = '手机号格式错误';
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '1011', $mobile, $plat, ''));
            echo json_encode($this->initArr);
            exit;
        }
        //验证签名
        if ($auth != md5($key . $mobile) || empty($plat)) {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'clientSendMsg';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '参数错误';
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '1000', $mobile, $plat, ''));
            echo json_encode($this->initArr);
            exit;
        }
        //冻结每个手机号，至少60秒才能操作一次
        if (!$mUser->blockCounter($mobile . "freeze", 1, 60)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '408', $mobile, $plat, ''));
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'clientSendMsg';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '408';
            $this->initArr['message'] = '冻结手机号：至少隔60秒才能请求第二次';
            echo json_encode($this->initArr);
            exit;
        }
        //每个手机号，一天只能发5条
        if (!$mUser->blockCounter($mobile, 5)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '406', $mobile, $plat, ''));
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'clientSendMsg';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '406';
            $this->initArr['message'] = '每个手机号一天只能发5条';
            echo json_encode($this->initArr);
            exit;
        }
        //验证手机是否有权限发送短信
        if (!$mUser->checkMobieIfSendMsg($mobile)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '1026', $mobile, $plat, ''));
            $mUser->errorShow(1026, $bean = array(), $seeArr = false);
            exit;
        }
        $cachetime = 600;
        $flag = $mUser->storeClientAuthCode($mobile, $plat, $action, $cachetime);

        if ($flag) {

            $messagemodel = new MessageModel();
            $flag = $messagemodel->mobileSend($mobile, $flag, 10);
            if ($flag) {
                $mUser->setSendMsgTimes($mobile);
                $mUser->setClientSendMsgTimes($mobile, $plat, $action, 600);
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'clientSendMsg';
                $this->initArr['status'] = '1';
                $this->initArr['errorCode'] = '0';
                $this->initArr['message'] = '手机激活码已经下发';
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '0', $mobile, $plat, $flag));
                echo json_encode($this->initArr);
                exit;
            } else {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'clientSendMsg';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '403';
                $this->initArr['message'] = '发送失败';
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '403', $mobile, $plat, $flag));
                echo json_encode($this->initArr);
                exit;
            }
        } else {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'clientSendMsg';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '500';
            $this->initArr['message'] = '手机激活码存储失败';
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/clientSendMsg', array(date('Y-m-d H:i:s'), '500', $mobile, $plat, ''));
            echo json_encode($this->initArr);
            exit;
        }
    }

    /**
     * 客户端用户注册接口
     * 请求地址：http://api.sso.letv.com/api/addUser
     * @param
     * email : 邮箱，邮箱和手机号不能同时为空
     * mobile ：手机号，邮箱和手机号不能同时为空
     * password ： 密码
     * nickname ：昵称
     * username ： 用户名：现在已经不提供用户名注册
     * gender ： 性别
     * registService ： 注册来源:现在该字段已经废弃
     * sendmail ： 是否发送激活邮件（只有邮箱注册才会处理该字段）
     * next_action ：邮件激活成功之后， 回跳地址
     * plat ： 平台标识
     * code ： 手机注册激活码（只有手机注册才处理该参数）
     * equipType ： 设备类型
     * equipID ： 设备ID
     * softID ： 软件版本
     */
    function addUserAction() {
        $email = urldecode(Request::getPost('email', null, ''));
        $mobile = Request::getPost('mobile', null, '');
        $password = urldecode(Request::getPost('password', null, ''));
        $nickname = urldecode(Request::getPost('nickname', null, ''));
        $username = urldecode(Request::getPost('username', null, ''));
        $gender = Request::getPost('gender', null, '0');//【0:保密 1:男 2:女】
        $registService = Request::getPost('registService', null, 'no');
        $sendmail = Request::getPost('sendmail', null, '');
        $deviceId = Request::getPost('deviceid', null, '');//乐视手机用户邮箱注册，激活成功之后消息回推标识，tv端使用则为设备号，如具体mac地址，cibn上报
        $next_action = Request::getPost('next_action', null, '');
        $plat = Request::getPost('plat', null, ''); //设备类型
        $code = Request::getPost('code', null, ''); //激活码
        $equipType = Request::getPost('equipType', null, ''); //设备类型
        $equipID = Request::getPost('equipID', null, ''); //设备ID
        $softID = Request::getPost('softID', null, ''); //软件版本
        $devId = Request::getPost('dev_id', null, '');
        $licensetype = Request::getPost('licensetype', null, '');//牌照方名称
        $channel = Request::getPost('channel', null, '');//渠道
        $clientIp = Request::getPost('clientip', null, '');//客户端ip
        $seeArr = Request::getPost('seeArr', null, false);
        $mUser = new User();

        if (empty($clientIp)) {
            $clientIp = $mUser->getClientIp();
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array($clientIp, $email, $mobile, $username, '1063', $plat, $equipType, $equipID, $softID, $code));
            $mUser->errorShow(1000, array(), $seeArr);
            exit;
        }

        // 判断ip是否已被限
        if ($mUser->ipRegLimited($clientIp)) {
            $mUser->errorShow(1039, array(), $seeArr);
            exit;
        }

        //如果没有昵称，通过手机号或邮箱自动生成昵称
        if (empty($nickname)) {
            if (!empty($mobile)) {
                $nickname = $mUser->getNameByMobile($mobile);
            }
            if (!empty($email)) {
                $nickname = substr($email, 0, strpos($email, '@'));
                if ($mUser->checkNicknameExists($nickname)) $nickname = $nickname . '_' . rand(100, 999);
            }
            if (!empty($username)) {
                $nickname = $username;
                if ($mUser->checkNicknameExists($nickname)) $nickname = $nickname . '_' . rand(100, 999);
            }
        }
        if (!$mUser->checkNickname($nickname)) $nickname = '';

        $this->initArr['action'] = 'addUser';
        //检查密码格式
        if (empty($password) || !$mUser->checkPwd($password)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, $password, '1000', $plat, $equipType, $equipID, $softID, $code));
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '参数不正确,密码不能为空或者密码格式不正确,密码规则：6-20位的英文、数字或符号';
            echo json_encode($this->initArr);
            exit;
        }

        //手机，邮箱不能同时为空
        if (empty($email) && empty($mobile)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1000', $plat, $equipType, $equipID, $softID, $code));
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '参数不正确,手机，邮箱不能同时为空';
            echo json_encode($this->initArr);
            exit;
        }

        //验证手机注册激活码
        if ((!empty($code) && empty($plat)) || (!empty($code) && empty($mobile))) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1000', $plat, $equipType, $equipID, $softID, $code));
            $this->initArr['errorCode'] = '1000';
            $this->initArr['message'] = '参数不正确,激活码不为空时，平台、手机也不能为空';
            echo json_encode($this->initArr);
            exit;
        }

        //用户名不为空，检查用户名格式、是否被注册
        if (!empty($username)) {
            if (!preg_match('~^[a-zA-Z]{1}[\w_]{5,49}$~is', $username)) {
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1020', $plat, $equipType, $equipID, $softID, $code));
                $this->initArr['errorCode'] = '1020';
                $this->initArr['message'] = '用户名格式不正确,请更换用户名';
                echo json_encode($this->initArr);
                exit;
            } elseif ($mUser->checkUsernameExists($username)) {
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1021', $plat, $equipType, $equipID, $softID, $code));
                $this->initArr['errorCode'] = '1021';
                $this->initArr['message'] = '用户名已存在,请更换用户名';
                echo json_encode($this->initArr);
                exit;
            }
        }

        //邮箱不为空，验证邮箱格式、检查邮箱是否被注册
        if (!empty($email)) {
            if (!preg_match('~^\S+@\S+\.[a-z]{2,5}$~is', $email)) {
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1005', $plat, $equipType, $equipID, $softID, $code));
                $this->initArr['errorCode'] = '1005';
                $this->initArr['message'] = '邮箱格式不正确';
                echo json_encode($this->initArr);
                exit;
            } else {
                if ($mUser->checkEmailExists($email)) {
                    $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1010', $plat, $equipType, $equipID, $softID, $code));
                    $this->initArr['errorCode'] = '1010';
                    $this->initArr['message'] = '邮箱已存在,请更换邮箱';
                    echo json_encode($this->initArr);
                    exit;
                }
            }
        }

        //手机不为空，验证手机号格式、是否被注册
        if (!empty($mobile)) {
            if (!$mUser->checkMobile($mobile)) {
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1011', $plat, $equipType, $equipID, $softID, $code));
                $this->initArr['errorCode'] = '1011';
                $this->initArr['message'] = '手机格式不正确';
                echo json_encode($this->initArr);
                exit;
            } else {
                if ($mUser->checkMobileExists($mobile)) {
                    $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1012', $plat, $equipType, $equipID, $softID, $code));
                    $this->initArr['errorCode'] = '1012';
                    $this->initArr['message'] = '手机已存在';
                    echo json_encode($this->initArr);
                    exit;
                }
            }
        }

        //昵称不为空,昵称不重复，昵称符合规则
        if (!empty($nickname) && ($mUser->checkNicknameExists($nickname) || !$mUser->checkNickname($nickname) || $mUser->nicknameFilter($nickname))) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1013', $plat, $equipType, $equipID, $softID, $code));
            $this->initArr['errorCode'] = '1013';
            $this->initArr['message'] = '昵称已存在或者格式不合法';
            echo json_encode($this->initArr);
            exit;
        }

        $status = empty($email) ? '1' : '2';//1正常，2邮箱未激活，邮箱需要激活
        /**
         * @auhtor xuhonglei@letv.com
         * @version 2013-10-31
         * 每个邮箱每天只能发送5封邮件
         */
        if (!empty($email) && $status == 2 && !$mUser->checkEmailIfSendEmail($email)) {
            $mUser->errorShow(1027, $bean = array(), $seeArr = false);
            exit;
        }

        //当传入code值时进行校验
        if (!empty($code)) {
            //校验验证码
            if (!$mUser->checkClientAuthCode($mobile, $plat, $action = 'reg', $code)) {
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '1022', $plat, $equipType, $equipID, $softID, $code));
                $this->initArr['errorCode'] = '1022';
                $this->initArr['message'] = '验证码错误';
                echo json_encode($this->initArr);
                exit;
            }
        }

        $arr = array(
            'registIp' => $clientIp,
            'mobile' => $mobile,
            'email' => $email,
            'pwd' => $password,
            'gender' => $gender,
            'nickname' => $nickname,
            'status' => $status,
            'registService' => $plat,
            'deviceId' => $deviceId,
            'licensetype' => $licensetype,
            'channel' => $channel
        );
        if (!empty($username)) {
            $arr['username'] = $username;
        }
        $uid = $mUser->regUser($arr);
        if ($uid) {
            // 注册成功，记录单ip每天注册数
            $ip_reg_success_info = $mUser->ipRegSuccesInfo($clientIp, false);
            if ($ip_reg_success_info >= 2000) {
                // 单个 ip 每天只能注册2000个
                $mUser->ipRegLimited($clientIp, false);
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array($clientIp, $email, $mobile, $username, '1064', $plat, $equipType, $equipID, $softID, $code));
            }
            $this->initArr['status'] = '1';
            $userinfo = $mUser->getUserByID($uid);
            $this->initArr['bean'] = array(
                'ssouid' => $uid,
                'username' => $userinfo['username'],
                'status' => $userinfo['status'],
            );
            if (!empty($email)) {
                $mUser->signSysLog('sso', 'regist', 'sso', 'client/addUser', array($uid, $registService, 'email', $email, $plat, $equipType, $equipID, $softID, $code));
                $mUser->signSysLog('sso', 'UserActionLog', 'register', 'addUser', array($clientIp, $plat, 'email', $uid, $email, $nickname, $registService, $sendmail, $equipType, $equipID, $softID, $code));
                //添加邮箱激活发送邮件流程
                if (!empty($sendmail) && $status == 2) {
                    empty($deviceId) && $mUser->sendActiveEmail($email, $uid, $registService, $next_action);
                    !empty($deviceId) && ($plat == 'letv_mobile' || $plat == 'mobile_tv'); //&& $this->model('Mail')->sendMobileActiveEmail($email, $uid, $deviceId);
                    $mUser->setSemdEmailTimes($email);
                }
            } elseif (!empty($mobile)) {
                $mUser->signSysLog('sso', 'regist', 'sso', 'client/addUser', array($uid, $registService, 'mobile', $mobile, $plat, $equipType, $equipID, $softID, $code));
                $mUser->signSysLog('sso', 'UserActionLog', 'register', 'addUser', array($clientIp, $plat, 'mobile', $uid, $mobile, $nickname, $registService, $sendmail, $equipType, $equipID, $softID, $code));

            } elseif (empty($email) && empty($mobile) && !empty($username)) {
                $mUser->signSysLog('sso', 'regist', 'sso', 'client/addUser', array($uid, $registService, 'username', $username, $plat, $equipType, $equipID, $softID, $code));
                $mUser->signSysLog('sso', 'UserActionLog', 'register', 'addUser', array($clientIp, $plat, 'username', $uid, $username, $nickname, $registService, $sendmail, $equipType, $equipID, $softID, $code));
            }
        } else {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'api/addUser', array(date('Y-m-d H:i:s'), $email, $mobile, $username, '500', $plat, $equipType, $equipID, $softID, $code));
            $this->initArr['errorCode'] = '500';
            $this->initArr['message'] = '注册时入库失败';
        }
        echo json_encode($this->initArr);
    }

    /**
     * 移动端上传头像
     */
    public function uploadAvatarAction() {
        $tmp_name = $_FILES['img']['tmp_name'];
        $tk = Request::getPost('tk', null, '');
        $plat = Request::getPost('plat', null, '');
        $User = new User();
        $uid = $User->ssotk($tk, false, $plat);
        if (empty($uid)) {
            $User->errorShow('1020', array());
            exit;
        }

        if (!empty($tmp_name) && file_exists($tmp_name)) {
            // mime 和 扩展名 的映射
            $mimes = array(
                'GIF' => 'gif',
                'JPEG' => 'jpg',
                'PNG' => 'png',
            );
            if (isset($_FILES['error']) && $_FILES['error'] != 0) {
                $User->errorShow(1032, array());
                exit;
            }
            // 图片大小不合格
            if (isset($_FILES['size']) && $_FILES['size'] > 5242880) {
                $User->errorShow(1033, array());
                exit;
            }
            //$fileName = time() . rand(1000,9999);
            $content = file_get_contents($tmp_name, NULL, NULL, 0, 4);
            $type = $User->getpictype($content);

            if (!empty($tmp_name) && file_exists($tmp_name)) {
                if (!isset($mimes[$type])) {
                    // 图片格式不支持
                    $User->errorShow(1034, array());
                    exit;
                }
                //$ext = $mimes[$type];
                //$file = '/tmp/' . $fileName . '.' . $ext;

                //if(!move_uploaded_file ( $tmp_name , $file )){
                //$User->errorShow(1032, array());
                //exit;
                //}
                unset($content);
                if (file_exists($tmp_name)) {
                    //$avatar_link = $User->createUploadAvatar($file);
                    $avatar = Img::upload($tmp_name);
                    if (!empty($avatar['file'])) {
                        $avatar_link = cdn_url('image', $avatar['file']);
                    }
                    unlink($tmp_name);
                    if (!empty($avatar_link)) {
                        $User->photoReset($avatar_link, $uid);
                        $User->errorShow('0', array('result' => $avatar_link));
                        exit;
                    } else {
                        $User->errorShow(1032, array());
                        exit;
                    }
                } else {
                    $User->errorShow(1032, array());
                    exit;
                }

            } else {
                $User->errorShow(1032, array());
                exit;
            }
        }
    }


    /**
     * @desc 百度推广活动接口
     * @version 2015年8月14日
     * @
     */
    public function baidu2vipAction() {
        $baidu2vip = [];
        $baidu2vip['user'] = Request::getPost('user');
        $baidu2vip['actid'] = Request::getPost('actid');
        $baidu2vip['day'] = Request::getPost('day');
        $baidu2vip['sign'] = Request::getPost('sign');


        $return['data'] = array();
        $return['errorcode'] = 0;
        $return['error_msg'] = '';
        $mUser = new User();

        //验证sign值
        $sign = $mUser->checkSign($baidu2vip['user'], $baidu2vip['actid'], $baidu2vip['day']);
        if ($baidu2vip['sign'] != $sign) {
            $return['errorcode'] = 1;
            $return['error_msg'] = 'sign值验证失败';
            echo json_encode($return);
            exit;
        }
        //执行vip信息插入更新
        $re = $mUser->baidu2vip($baidu2vip['user'], $baidu2vip['actid'], $baidu2vip['day']);
        if ($re && !is_array($re)) {
            $return['errorcode'] = 0;
            $return['error_msg'] = '';
            echo json_encode($return);
            exit;
        }
        if ($re && is_array($re)) {
            echo json_encode($re);
            exit;
        } else {
            $return['errorcode'] = 1;
            $return['error_msg'] = 'vip信息插入失败';
            echo json_encode($return);
            exit;
        }
    }

    /**
     * @desc 判断用户是否是vip
     * @version 2015年8月19日
     */
    public function isVipAction() {
        $uid = Request::getQuery('uid', 'int');
        if (empty($uid)) {
            $uid = isset($_COOKIE['ssouid']) ? $_COOKIE['ssouid'] : null;
        }
        $mUser = new User();
        $re = $mUser->is_vip($uid);
        echo json_encode($re);
    }


    /**
     * @desc 添加批量调用用户信息接口
     * uidlist : uid列表，多个uid以逗号分隔
     */
    public function getUserByIdListAction() {
        $uidlist = $this->request->getQuery('uidlist');
        $userModel = new User();
        if (empty($uidlist)) {
            $userModel->errorShow('1000', array());
            exit;
        }
        $uidlist = explode(',', $uidlist);
        $userinfo = $userModel->getUserByIdList((array)$uidlist);
        if (empty($userinfo)) {
            $errorCode = 1001;
            $bean = array();
        } else {
            $errorCode = 0;
            $bean = $userinfo;
        }
        $userModel->errorShow($errorCode, $bean);
    }

}