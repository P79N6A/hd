<?php

/**
 * @RoutePrefix("/user")
 */
class UserController extends SsoBaseController {
    var $initArr = array(
        'bean' => '',
        'action' => '',
        'responseType' => 'json',
        'status' => '0',
        'errorCode' => '0',
        'message' => ''
    );

    public function initialize() {
        if(!preg_match('/^[0-9a-zA-Z_]+$/u', $_GET['callback'])) unset($_GET['callback']);
    }

    /**
     * @Get("/mobileRegCode/mobile/{mobile:-?[0-9]+}")
     * @param int $mobile
     * @return json
     */
    public function mobileRegCodeAction() {
        $mobile = $this->getVar('mobile');
        $times = Request::getQuery('times', 'int', 10);
        $mUser = new User();
        $clientIp = $mUser->getClientIp();

        if (!is_string($mobile) || !$mUser->checkMobile($mobile)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '401', $mobile, $clientIp, ''));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '401';
                $this->initArr['message'] = '手机号不正确';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('401');
        }

        //冻结每个手机号，至少60秒才能操作一次
        if (!$mUser->blockCounter($mobile . "freeze", 1, 60)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '408', $mobile, $clientIp, ''));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '408';
                $this->initArr['message'] = '冻结手机号：至少隔60秒才能请求第二次';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('408');
        }

        //每个手机号，一天只能发5条
        if (!$mUser->blockCounter($mobile, 5)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '406', $mobile, $clientIp, ''));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '406';
                $this->initArr['message'] = '每个手机号一天只能发5条';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('406');
        }
        //每个IP，一天只能发20条
        if (!$mUser->blockCounter($clientIp, 20)) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '407', $mobile, $clientIp, ''));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '407';
                $this->initArr['message'] = '每个IP一天只能发20条';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('407');
        }
        $code = $mUser->storeClientAuthCode($mobile, $plat = 'web', $action = 'reg', 600);
        //手机激活码存储失败
        if (!$code) {
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '500', $mobile, $clientIp, ''));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '500';
                $this->initArr['message'] = '手机激活码存储失败';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('500');
        }
        $messagemodel = new MessageModel();
        $flag = $messagemodel->mobileSend($mobile, $code, $times);
        if ($flag) {
            $logdata = array('mobile' => $mobile, 'msg' => '【中国蓝TV】您的效验码是' . $code . '。如非本人操作，请忽略本短信。', 'from' => 'my', 'ip' => $mUser->getClientIp(), 'time' => date('Y-m-d H:i:s'));
            //$mUser->saveMessageLog($logdata);//导致502错误，没有使用，注释
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '200', $mobile, $clientIp, $code));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '1';
                $this->initArr['errorCode'] = '200';
                $this->initArr['message'] = '发送成功';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('200');
        } else {
            $mUser->decrCounter($mobile);
            $mUser->decrCounter($clientIp);
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/mobileRegCode', array(date('Y-m-d H:i:s'), '403', $mobile, $clientIp, ''));
            if (isset($_GET['callback'])) {
                $this->initArr['bean'] = array();
                $this->initArr['action'] = 'mobileRegCode';
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '403';
                $this->initArr['message'] = '发送失败';
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            exit('403');
        }
    }

    public function checkMobileExistsAction() {
        $mobile = $this->getvar('mobile');
        $jsonp = Request::getQuery('jsonp');
        $mUser = new User();
        $clientIp = $mUser->getClientIp();
        if(!$mUser->checkIfCheckMobile($clientIp)) {
            $this->initArr['message'] = "请求过于频繁";
            $this->initArr['bean'] = array("result" => 0);
            $this->initArr['action'] = 'checkMobileExists';
            $this->initArr['status'] = '1';
            echo json_encode($this->initArr);
            exit;
        }
        $flag = $mUser->checkMobileExists($mobile, false);
        $mUser->setMobileCheckTimes($clientIp);
        if (!empty($flag['status'])) $flag = array('status' => $flag['status']);
        $this->initArr['bean'] = array("result" => $flag);
        $this->initArr['action'] = 'checkMobileExists';
        $this->initArr['status'] = '1';
        $this->initArr['message'] = ($flag) ? '手机号已存在' : '手机号不存在';
        //jsonp格式
        if (!empty($jsonp) && $mUser->checkJsonCallBackPara($jsonp)) {
            echo $jsonp . '(' . json_encode($this->initArr) . ')';
            exit;
        }
        //json格式
        echo json_encode($this->initArr);
    }

    public function checkPhoneNumExistsAction() {
        $mobile = Request::getPost('mobile');
        if (!$mobile || !preg_match('/^(1[3|4|5|7|8]|00886|0064)\d{9}$/', $mobile)){
            $this->_jsonzgltv( 1 , [] , 2001, '手机号不存在或格式错误' );
        }
        $mUser = new User();
//        $clientIp = $mUser->getClientIp();
//        if(!$mUser->checkIfCheckMobile($clientIp)) {
//            $this->_jsonzgltv( 1 , [] , 2002, '请求过于频繁' );
//        }
        $flag = $mUser->checkMobileExists($mobile, false);
//        $mUser->setMobileCheckTimes($clientIp);

        if ($flag && isset($flag['status']) && $flag['status'] == 1) {
            $this->_jsonzgltv( 1 , [] , 2003, '用户已存在' );
        }else{
            $this->_jsonzgltv( 1 , [] , 200, '验证通过' );
        }
    }

    protected function _jsonzgltv($channel_id, $data, $code = 200, $msg = "success", $aleradyarray=false) {
        if($channel_id== 1 ) {
            header('Content-type: application/json');
            $listdata = [];
            if($data!=[]) $listdata[] = $data;
            if($aleradyarray) $listdata = $data;
            echo json_encode([
                'alertMessage' => "数据获取成功",
                'state' => ($code==200)?0:$code,
                'message' => $msg,
                'content' => ['list'=>$listdata],
            ]);
            exit;
        }
        else {
            $this->_json($data, $code, $msg);
        }
    }

    public function checkNicknameExistsAction() {
        $url = Request::getQuery();
        $a = explode('/', $url['_url']);
        $index = array_search('nickname', $a);
        $nickname = $a[$index + 1];
        $jsonp = Request::getQuery('jsonp');
        $mUser = new User();
        $flag = $mUser->checkNicknameExists($nickname, false);
        if ($flag) $flag = array('status' => 1);
        $this->initArr['bean'] = array("result" => $flag);
        $this->initArr['action'] = 'checkNicknameExists';
        $this->initArr['status'] = '1';
        $this->initArr['message'] = ($flag) ? '用户名已存在' : '用户名不存在';
        //jsonp格式
        if (!empty($jsonp) && $mUser->checkJsonCallBackPara($jsonp)) {
            echo $jsonp . '(' . json_encode($this->initArr) . ')';
            exit;
        }
        //json格式
        echo json_encode($this->initArr);
    }

    /**
     * @desc 用户注册——手机注册，POST方式传递参数
     * @version 2015-06-01
     * @param
     * mobile
     * password
     * nickname
     * regcode
     * callback
     * referrer
     */
    public function mobileRegSubmitAction() {
        $mobile = Request::getPost('mobile');
        $password = Request::getPost('password');
        $nickname = Request::getPost('nickname');
        $regcode = Request::getPost('regcode');//手机激活码
        $callback = Request::getPost('callback', 'string', '');
        $from = Request::getPost('referrer', 'string', '');//注册来源
        empty($from) && !empty($_COOKIE['sso_reg_refer']) && $from = $_COOKIE['sso_reg_refer'];
        $mUser = new User();
        $clientIp = $mUser->getClientIp();
        $from = $mUser->handleURLWhitelist($from);

        //验证手机号格式
        if (!$mUser->checkMobile($mobile)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, '403', $from));
            redirect('http://sso.cztv.com/user/registcallback/err/403/callback/' . $callback);
            exit;
        }
        //验证手机号是否存在
        if ($mUser->checkMobileExists($mobile)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, '404', $from));
            redirect('http://sso.cztv.com/user/registcallback/err/404/callback/' . $callback);
            exit;
        }
        //验证昵称是否存在
        if ($mUser->checkNicknameExists($nickname)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, '402', $from));
            redirect('http://sso.cztv.com/user/registcallback/err/402/callback/' . $callback);
            exit;
        }
        //检查短信激活码
        if (!$mUser->checkClientAuthCode($mobile, $plat = 'web', $action = 'reg', $regcode)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, '401', $from));
            redirect('http://sso.cztv.com/user/registcallback/err/401/callback/' . $callback);
            exit;
        }

        //限制一天内同一ip注册量达1000个
        if ($mUser->stopUserRegByIp()) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, '501', $from));
            redirect('http://sso.cztv.com/user/registcallback/err/501/callback/' . $callback);
            exit;
        }
        //用户注册
        //if (empty($nickname) || $mUser->nicknameFilter($nickname))//敏感词过滤未完成
        //{
        //    $nickname = $mUser->getNameByMobile($mobile);
        //}

        $uid = $mUser->regUser(array('pwd' => $password, 'mobile' => $mobile, 'nickname' => $nickname, 'registService' => $from));
        if ($uid) {
            $userinfo = $mUser->getUserByID($uid);
            //设置登录状态
            $mUser->LoginSsoCookie($userinfo, 101, 'sso', 'true', true, $userinfo['mobile'], 1);
            //用户注册统计日志
            $mUser->signSysLog('sso', 'mobileRegSuccessV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, $uid));
            $mUser->signSysLog('sso', 'regist', 'sso', 'user/regist', array($uid, $from, 'mobile', $mobile, $from, $equipType = '', $equipID = '', $softID = ''));
            //用户行为日志
            $mUser->signSysLog('sso', 'UserActionLog', 'register', 'mobileRegSubmit', array($clientIp, $from, 'mobile', $uid, $mobile, $regcode));
            //清理来源cookie
            Cookie::set('sso_reg_refer', '', 0, '/', null, '.cztv.com'); //设置cookie

            session_start();
            unset($_SESSION['mobilereg']);
            $_SESSION['mobileokuid'] = $uid;
            $_SESSION['mobileoktmp'] = $mobile;
            redirect('http://sso.cztv.com/user/registcallback/err/200/callback/' . $callback);
            exit;
        } else {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'mobile', $mobile, $clientIp, '405', $from));
            redirect('http://sso.cztv.com/user/registcallback/err/405/callback/' . $callback);
            exit;
        }
    }

    public function checkEmailExistsAction() {
        $email = $this->getvar('email');
        $jsonp = Request::getQuery('jsonp');
        $mUser = new User();
        $clientIp = $mUser->getClientIp();
        if(!$mUser->checkIfCheckEmail($clientIp)) {
            $this->initArr['message'] = "请求过于频繁";
            $this->initArr['bean'] = array("result" => 0);
            $this->initArr['action'] = 'checkEmailExists';
            $this->initArr['status'] = '1';
            echo json_encode($this->initArr);
            exit;
        }
        $flag = $mUser->checkEmailExists($email, false);
        $mUser->setEmailCheckTimes($clientIp);
        if (!empty($flag['status']))
            $flag = array('status' => $flag['status']);
        $this->initArr['bean'] = array("result" => $flag);
        $this->initArr['action'] = 'checkEmailExists';
        $this->initArr['status'] = '1';
        $this->initArr['message'] = ($flag) ? '邮箱已存在' : '邮箱不存在';
        //jsonp格式
        if (!empty($jsonp) && $mUser->checkJsonCallBackPara($jsonp)) {
            $this->initArr['responseType'] = 'jsonp';
            echo $jsonp . '(' . json_encode($this->initArr) . ')';
            exit;
        }
        //json格式
        echo json_encode($this->initArr);
    }

    public function getCodeAction($num = '4', $w = '60', $h = '20') {
        session_start();
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $code .= rand(0, 9);
        }
        //4位验证码也可以用rand(1000,9999)直接生成
        //将生成的验证码写入session，备验证时用
        setcookie('captchaId', session_id());
        setcookie('captchaValue', $code);
        //$_SESSION['captchaId'] = session_id();
        //$_SESSION['captchaValue'] = $code;

        //创建图片，定义颜色值
        header("Content-type: image/PNG");
        $im = imagecreate($w, $h);
        $black = imagecolorallocate($im, 0, 0, 0);
        $gray = imagecolorallocate($im, 200, 200, 200);
        $bgcolor = imagecolorallocate($im, 255, 255, 255);
        //填充背景
        imagefill($im, 0, 0, $gray);

        //画边框
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $black);

        //随机绘制两条虚线，起干扰作用
        $style = array($black, $black, $black, $black, $black,
            $gray, $gray, $gray, $gray, $gray
        );
        imagesetstyle($im, $style);
        $y1 = rand(0, $h);
        $y2 = rand(0, $h);
        $y3 = rand(0, $h);
        $y4 = rand(0, $h);
        imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
        imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);

        //在画布上随机生成大量黑点，起干扰作用;
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
        }
        //将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
        $strx = rand(3, 8);
        for ($i = 0; $i < $num; $i++) {
            $strpos = rand(1, 6);
            imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
            $strx += rand(8, 12);
        }
        ob_clean();
        imagepng($im);//输出图片
        imagedestroy($im);//释放图片所占内存
    }

    /**
     * @desc https 邮件注册提交地址
     * @version 2015-06-02
     * @param
     * email
     * password
     * nickname
     * regcode
     * callback
     * from
     * referrer
     */
    public function emailRegSubmitAction() {
        $email = Request::getPost('email');
        $password = Request::getPost('password');
        $nickname = Request::getPost('nickname');
        $regcode = Request::getPost('regcode');//图片验证码
        $callback = Request::getPost('callback', 'string', '');
        $from = Request::getPost('from', 'string', '');//注册来源
        $next_action = Request::getPost('referrer', 'string', '');//该参数为放在激活邮件中的 next_action，即激活成功后跳转到的地址
        $mUser = new User();
        $clientIp = $mUser->getClientIp();
        session_start();
        // from 为后加参数，加入next_action 判断以兼容旧版js
        if ($next_action == '') {
            $next_action = 'http://sso.cztv.com';
        }
        empty($from) && !empty($_COOKIE['sso_reg_refer']) && $from = $_COOKIE['sso_reg_refer'];
        $from = $mUser->handleURLWhitelist($from);
        //检查验证码
        if ($_COOKIE['captchaId'] != session_id() || $_COOKIE["captchaValue"] != $regcode) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '401', $from));
            $mUser->signSysLog('sso', 'verifyError', 'emailReg', 'user/register', array(date('Y-m-d H:i:s'), $regcode, $_COOKIE['emailreg']));
            redirect("http://sso.cztv.com/user/registcallback/err/401/callback/" . $callback);
            exit;
        }

        if (!$mUser->checkPwd($password)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '407', $from));
            redirect("http://sso.cztv.com/user/registcallback/err/407/callback/" . $callback);
            exit;
        }
        if (!preg_match('~^\S+@\S+\.[a-z]{2,5}$~is', $email)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '403', $from));
            redirect("http://sso.cztv.com/user/registcallback/err/403/callback/" . $callback);
            exit;
        }
        if ($mUser->checkEmailExists($email)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '404', $from));
            redirect("http://sso.cztv.com/user/registcallback/err/404/callback/" . $callback);
            exit;
        }
        //验证昵称格式，以及是否重复
        if ($mUser->checkNicknameExists($nickname)) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '402', $from));
            redirect("http://sso.cztv.com/user/registcallback/err/402/callback/" . $callback);
            exit;
        }

        //限制一天内同一ip注册量大于1000
        if ($mUser->stopUserRegByIp()) {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '501', $from));
            redirect("http://sso.cztv.com/user/registcallback/err/501/callback/" . $callback);
            exit;
        }

        if (empty($nickname) || $mUser->nicknameFilter($nickname))//TODO
        {
            $nickname = substr($email, 0, strpos($email, '@'));
        }
        //用户注册
        $row = array('pwd' => $password, 'email' => $email, 'registIp' => $clientIp, 'nickname' => $nickname, 'status' => '2', 'registService' => $from);

        $uid = $mUser->regUser($row);
        if ($uid) {
            //用户注册统计日志
            $mUser->signSysLog('sso', 'emailRegSuccessV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, $uid));
            //用户行为日志
            $mUser->signSysLog('sso', 'UserActionLog', 'register', 'emailRegSubmit', array($clientIp, $from, 'email', $uid, $email, $regcode));
            $mUser->signSysLog('sso', 'regist', 'sso', 'user/regist', array($uid, $from, 'email', $email));
            unset($_SESSION['regcode']);
            $mUser->setCookie('emailoktmp', $email);
            $mUser->setCookie('nextactiontmp', $next_action);
            if ($mUser->checkEmailIfSendEmail($email)) {
                $mailModel = new Mail();
                $mailModel->sendActiveEmail($email, $uid, $from, $next_action);
                $mUser->setSemdEmailTimes($email);
            }
            //清理来源cookie
            Cookie::set('sso_reg_refer', '', 0, '/', null, '.cztv.com'); //设置cookie
            redirect("http://sso.cztv.com/user/registcallback/err/200/callback/" . $callback);
            exit;
        } else {
            $mUser->signSysLog('sso', 'registSourceActionV2', 'sso', 'user/register', array(date('Y-m-d H:i:s'), 'email', $email, $clientIp, '405', $from));
            redirect("http://sso.cztv.com/user/registcallback/err/405/callback/" . $callback);
            exit;
        }
    }

    /**
     * @desc 激活邮件地址 http://sso.cztv.com/user/activeEmail/verify/xx 每个激活邮件只能点击一次
     * @version 2015-06-02
     * @param
     * varify
     * deviceID
     */
    public function activeEmailAction() {
        require_once APP_PATH . 'libraries/Plugin/Util.php';
        $verify = $this->getVar('verify');
        if (!is_string($verify)) {
            $verify = '';
        }
        $mUser = new User();
        $uid = base64_decode(urldecode($verify));
        $uid = Plugin_Util::authcode($uid, 'DECODE', ACTIVE_EMAIL_KEY);
        $uid = intval($uid);
        $legal = true;
        //检查该链接是否使用过
        if ($uid) {
            $legal = $mUser->checkActiveEmailLink($uid);
        }
        if ($uid && $legal) {
            $userinfo = $mUser->getUserByID($uid);
            $mUser->modifyStatus($uid, '1');
            $mUser->LoginOutSsoCookie();
            $mUser->LoginSsoCookie($userinfo, 100, 'sso', 'true', true, $userinfo['email'], 1);
            $from = $this->getPara('from', '');
            $ip = $mUser->getClientIp();
            $mUser->signSysLog('sso', 'activeEmail', 'sso', 'user/activeEmail', array(date('Y-m-d H:i:s'), $uid, $ip, $from));
            $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/activeEmail', array(date('Y-m-d H:i:s'), $uid, $ip, $from));
            //记录用户行为日志
            $mUser->signSysLog('sso', 'UserActionLog', 'activeEmail', 'activeEmail', array($ip, 'my', $uid));
            $next_action = Plugin_Util::removeXss($this->request->getQuery('next_action'));
            //用户中心页面直接跳转首页
            if (empty($next_action) || strpos($next_action, 'sso.cztv.com/user')) {
                $next_action = "http://tv.cztv.com";
            }
            $this->response->redirect($next_action);
        } else {
            exit('<center>对不起,链接已失效，点击<a href="http://tv.cztv.com" class="under blu">这里</a>回首页！</center>');
        }
    }

    private function getPara($key = null, $default = null) {
        if (empty($key)) {
            return Request::getQuery($key);
        }
        return htmlspecialchars(Request::getQuery($key));
    }

    /**
     * @desc 弹出登录页面
     * @version 2015-06-19
     */
    public function indexAction() {
        require_once APP_PATH . 'libraries/Plugin/Util.php';
        $next_action = Plugin_Util::removeXss($this->request->get('next_action'));
        /**
         * 如果来源是http://sso.cztv.com/?next_action=http://shop.cztv.com/，则不跳转
         * shop商城有独立的登录页，但是有可能从注册页直接跳转到sso的登录页
         */
        if (isset($next_action) && strripos($next_action, 'shop.cztv.com')) {
            $userModel = new User();
            $userModel->LoginOutSsoCookie();//清cookie
        }
        if (!empty($_COOKIE['loginnamecookie'])) {
            $this->view->loginnamecookie = $_COOKIE['loginnamecookie'];
        }
        $this->view->next_action = urlencode($next_action);
        $this->view->pick('login/index');
    }

    /**
     * @desc 登录注册页面
     * @version 2015-06-19
     */
    public function loginAction() {
        $this->view->pick('login/login');
    }

    /**
     * 找回密码展示页
     * @param
     * next_action : 回跳地址
     */
    public function backpwdAction() {
        require_once APP_PATH . 'libraries/Plugin/Util.php';
        $next_action = Plugin_Util::removeXss($this->request->get('next_action'));
        $this->view->next_action = urlencode($next_action);
        $this->view->pick('backpwd/back_pwd');
    }

    /**
     * 找回密码展示页wap
     * @param  BY JCL 2016年2月23日15:05:02
     * next_action : 回跳地址
     */
    public function backpwdwapAction()
    {
        require_once APP_PATH . 'libraries/Plugin/Util.php';
        $next_action = Plugin_Util::removeXss($this->request->get('next_action'));
        $this->view->next_action = urlencode($next_action);
        $this->view->pick('backpwd/back_pwd_wap');
    }



    /**TODO
     * @desc 获取登录错误次数，控制验证码是否显示
     * @version 2015-06-30
     * @param unknown $loginname
     */
    public function getUserLoginErrorInfoAction($loginname) {
        //用户名登录错误信息
        $mUser = new User();
        $errorInfo = $mUser->userLoginErrorInfo($loginname);
        $this->initArr['bean'] = array('result' => $errorInfo);
        $this->initArr['action'] = 'getUserLoginErrorInfo';
        $this->initArr['status'] = '1';
        $this->initArr['errorCode'] = '0';
        $this->initArr['message'] = '用户名登录错误次数';
        if (isset($_GET['callback'])) {
            echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
            exit;
        } else {
            echo json_encode($this->initArr);
            exit;
        }
    }


    /**
     * @desc 用户登录提交地址[https登录post地址]
     * @version 2016-05-07
     * @param
     * loginname
     * password
     * memberme
     * callback
     * verify
     * next_action
     */
    public function loginsubmitAction() {
        session_start();
        $loginname = Request::getPost('loginname', 'string', '');
        $password = Request::getPost('password', 'string', '');
        $memberme = Request::getPost('memberme', 'string', false);
        $callback = Request::getPost('callback', 'string', '');
        $verify = Request::getPost('verify', 'string', '');//验证码
        $next_action = Request::getPost('next_action', 'string', '');
        $from = Request::getPost('referrer', 'string', '');//注册来源

        $next_action = htmlspecialchars_decode($next_action);
        $next_action = urlencode($next_action);
        $mUser = new User();

        $ip = $mUser->getClientIp();//获取客户端ip

        empty($from) && $from = $_SERVER['HTTP_REFERER'];
        $from = $mUser->handleURLWhitelist($from);//处理来源白名单
        //验证码
        //用户名登录错误信息
        $user_login_error_info = $mUser->userLoginErrorInfo($loginname);

        if (!empty($verify) && $user_login_error_info && ($_COOKIE['captchaId'] != session_id() || $_COOKIE["captchaValue"] != $verify)) {

            $mUser->signSysLog('sso', 'verifyError', 'login', 'user/loginsubmit', array(date('Y-m-d H:i:s'), $verify, $_COOKIE['errorLogin']));
            redirect('http://sso.cztv.com/user/logincallback/err/406/callback/' . $callback);
            exit;
        }

        $userinfo = $mUser->userLogin($loginname, $password);//验证登录密码，并获取个人信息
        $ajaxArr = array('flag' => '401', 'username' => '');
        if (!empty($userinfo)) {
            if (preg_match('~^\S+@\S+\.[a-z]{2,5}$~is', $loginname)) {
                $utype = 100;
                $logintype = 'email';
            } else if ($mUser->checkMobile($loginname)) {
                $utype = 101;
                $logintype = 'mobile';
            }
            //判断是否为新用户第一次登录
            $userProfile = $mUser->getUserProfileByID($userinfo['uid']);
            empty($userProfile) ? $firstlonginflag = 1 : $firstlonginflag = 0;
            //登录成功
            if ($userinfo['status'] == '1' && $mUser->LoginSsoCookie($userinfo, $utype, $from, $memberme, $logFlag = true, $loginname, $firstlonginflag)) {
                // 记录ip登录成功次数：
                $mUser->ipLoginSuccesInfo($ip, false, 600);
                //更新最后登录时间和IP
                if (empty($userProfile)) {
                    $mUser->updateUserProfile($userinfo['uid'], time(), $ip);
                }
                //记录登录日志
                //登录统计日志
                $mUser->signSysLog('sso', 'login', 'sso', 'user/login', array($userinfo['uid'], $from, $ip));
                //用户行为日志
                $mUser->signSysLog('sso', 'UserActionLog', 'login', 'loginsubmit', array($ip, $from, $logintype, $userinfo['uid'], $loginname, $verify));
                $userkey = $mUser->platUserkey($userinfo['uid'], HD_KEY);
                redirect('http://sso.cztv.com/user/logincallback/code/' . $userkey . '/callback/' . $callback . '?next_action=' . $next_action);
                exit;
            } else if ($userinfo['status'] == '0') {//用户已被管理员屏蔽了！
                $mUser->signSysLog('sso', 'loginSourceAction', 'sso', 'user/login', array(date('Y-m-d H:i:s'), $loginname, $ip, '402'));
                redirect('http://sso.cztv.com/user/logincallback/err/402/callback/' . $callback);
                exit;
            } else if ($userinfo['status'] == '2') {//邮箱未激活
                //检查邮箱是否可以发送邮件，每天不超过五次
                if ($mUser->checkEmailIfSendEmail($userinfo['email'])) {
                    $mailModel = new Mail();
                    $mailModel->sendActiveEmail($userinfo['email'], $userinfo['uid'], $userinfo['registService']);
                    $mUser->setSemdEmailTimes($userinfo['email']);
                }
                $mUser->signSysLog('sso', 'loginSourceAction', 'sso', 'user/login', array(date('Y-m-d H:i:s'), $loginname, $ip, '403'));
                redirect('http://sso.cztv.com/user/logincallback/err/403/callback/' . $callback);
                exit;
            }
        } else {
            //设置用户登录错误信息
            if (empty($user_login_error_info)) {
                $mUser->userLoginErrorInfo($loginname, false, 1);
            } else {
                $mUser->userLoginErrorInfo($loginname, false, $user_login_error_info['login_error_times'] + 1);
            }
            //设置ip登录错误信息
            if (empty($ip_login_error_info)) {
                $mUser->ipLoginInfo($ip, false, 1);
            } else {
                $mUser->ipLoginInfo($ip, false, $ip_login_error_info['login_error_times'] + 1);
            }
            $mUser->signSysLog('sso', 'loginSourceAction', 'sso', 'user/login', array(date('Y-m-d H:i:s'), $loginname, $ip, '401'));
            //账号或密码不正确
            redirect('http://sso.cztv.com/user/logincallback/err/401/callback/' . $callback);
            exit;
        }
        $mUser->signSysLog('sso', 'loginSourceAction', 'sso', 'user/login', array(date('Y-m-d H:i:s'), $loginname, $ip, $ajaxArr['flag']));
        redirect('http://sso.cztv.com/user/logincallback/err/' . $ajaxArr['flag'] . '/callback/' . $callback);
        exit;
    }

    /**
     * @desc 登录回调地址
     */
    public function logincallbackAction() {
        $code = $this->getVar('code', '');
        $err = $this->getVar('err', '');
        $next_action = $this->request->getQuery('next_action', null, '');
        $callback = $this->getVar('callback', '');
        $mUser = new User();
        if (!$mUser->checkJsonCallBackPara($callback)) {
            exit;
        }
        //登录错误
        if (isset($err) && !empty($err)) {
            $ajaxArr = array('flag' => $err, 'username' => '');
            echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($ajaxArr) . ")}catch(e){}</script>";
            exit;
        }
        //没有错误码
        $ajaxArr = array('flag' => '401', 'username' => '');
        if (isset($code) && !empty($code)) {
            $uid = $mUser->platUserkey($code, HD_KEY, false);
            $uid > 0 && $useriofo = $mUser->getUserByID($uid);
            if (!empty($useriofo)) {
                $ajaxArr['flag'] = '200';
                $ajaxArr['username'] = $useriofo['username'];
                $ajaxArr['next_action'] = $next_action;
            }
        }
        echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($ajaxArr) . ")}catch(e){}</script>";
        exit;
    }

    /**
     * @desc 邮箱找回密码，获取验证码，修改密码
     * @version 2015-06-05
     * @param
     * email 邮箱
     * callback 传递此参数返回jsonp格式
     */
    public function backpwdemailAction() {
        session_start();
        if (Request::isPost()) {
            $email = Request::getPost('email');
            $callback = isset($_POST['callback'])?$_POST['callback']:"";
            $callback = preg_match('/^[0-9a-zA-Z_]+$/u', $callback) ? $callback : "";
            $mUser = new User();
            if (!preg_match('~^\S+@\S+\.[a-z]{2,5}$~is', $email)) {
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '0';
                    $this->initArr['errorCode'] = '403';
                    $this->initArr['message'] = '邮箱格式不正确';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('403');
            }
            $userinfo = $mUser->checkEmailExists($email, false);
            if (empty($userinfo)) {
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '0';
                    $this->initArr['errorCode'] = '404';
                    $this->initArr['message'] = '此邮箱尚未注册';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('404');
            }
            if ($userinfo['status'] != 1) {
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '0';
                    $this->initArr['errorCode'] = '406';
                    $this->initArr['message'] = '该用户被禁用';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('406');
            }
            if (!$mUser->checkEmailIfSendEmail($email)) {
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '0';
                    $this->initArr['errorCode'] = '405';
                    $this->initArr['message'] = '超过次数限制，请明天再试';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('405');
            }

            $code = $mUser->storeClientAuthCode($email, $plat = 'web', $action = 'reg');
            //验证码存储失败
            if (!$code) {
                $mUser->signSysLog('sso', 'UserLog', 'sso', 'user/backpwdemail', array(date('Y-m-d H:i:s'), '500', $email, $_SERVER ["HTTP_CLIENT_IP"], ''));
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '0';
                    $this->initArr['errorCode'] = '500';
                    $this->initArr['message'] = '邮箱验证码存储失败';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('500');
            }

            $mailModel = new Mail();
            $flag = $mailModel->sendBackpwdEmail($email, $userinfo['uid'], 'web', $code);//TODO
            if ($flag) {
                $_SESSION['backpwdemailtmp'] = $email;
                $mUser->setSemdEmailTimes($email);
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '1';
                    $this->initArr['errorCode'] = '200';
                    $this->initArr['message'] = '发送成功';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('200');
            } else {
                if ($callback) {
                    $this->initArr['bean'] = array();
                    $this->initArr['action'] = 'backpwdemail';
                    $this->initArr['status'] = '0';
                    $this->initArr['errorCode'] = '402';
                    $this->initArr['message'] = '发送失败';
                    echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($this->initArr) . ")}catch(e){}</script>";
                    exit;
                }
                exit('402');
            }
        }
    }

    /**
     * 饶佳修改,增加sql和XSS防注入攻击
     * @desc 用户反馈
     * @version 2015-06-17
     */
    public function feedbackAction() {
        $feedback = [];
        $feedback['contact'] = Request::getQuery('contact', 'int');
        if (!$feedback['contact']) {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'feedback';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '403';
            $this->initArr['message'] = '请输入正确的联系方式';
            if (isset($_GET['callback'])) {
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            } else {
                echo json_encode($this->initArr);
                exit;
            }
        }
        $feeback_str = Request::getQuery('feedback');
        $feeback_str = $this->filter_it($feeback_str);
        $feedback['feedback'] = $feeback_str;

        if (!$feedback['feedback']) {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'feedback';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '404';
            $this->initArr['message'] = '请输入正确的反馈内容';
            if (isset($_GET['callback'])) {
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            } else {
                echo json_encode($this->initArr);
                exit;
            }
        }

        $mUser = new User();
        $feedback['uid'] = isset($_COOKIE['ssouid']) ? intval($_COOKIE['ssouid']) : (isset($_GET['uid']) ? intval($_GET['uid']) : null);
        $feedback['ip'] = $mUser->getClientIp();
        $feedback['addtime'] = date('Y-m-d H:i:s', time());
        $feedback['channel_id'] = LETV_CHANNEL_ID;
        $userFeedbackModel = new UserFeedback();
        if ($userFeedbackModel->save($feedback)) {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'feedback';
            $this->initArr['status'] = '1';
            $this->initArr['errorCode'] = '0';
            $this->initArr['message'] = '反馈成功';
            if (isset($_GET['callback'])) {
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            } else {
                echo json_encode($this->initArr);
                exit;
            }
        } else {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = 'feedback';
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '500';
            $this->initArr['message'] = '反馈失败';
            if (isset($_GET['callback'])) {
                echo $_GET['callback'] . '(' . json_encode($this->initArr) . ')';
                exit;
            } else {
                echo json_encode($this->initArr);
                exit;
            }
        }
    }


    //退出
    public function loginOutAction() {
        $mUser = new User();
        $mUser->LoginOutSsoCookie();
        //get $url
        $next_action = Request::getQuery('next_action', 'string', '');
        if (empty($next_action) || $mUser->checkNextAction($next_action)) {
            $next_action = 'http://tv.cztv.com';
        }
        redirect($next_action);
    }

    /**
     * @desc 验证码验证接口
     * @version 2015-09-14
     */
    public function checkGetCodeAction() {
        $regcode = Request::getPost('regcode');//图片验证码
        $callback = Request::getPost('callback', 'string', '');
        $from = Request::getPost('from', 'string', 'www');//注册来源
        $next_action = Request::getPost('referrer', 'string', '');//该参数为放在激活邮件中的 next_action，即激活成功后跳转到的地址

        session_start();
        // from 为后加参数，加入next_action 判断以兼容旧版js
        if ($next_action == '') {
            $next_action = 'http://sso.cztv.com';
        }
        empty($from) && !empty($_COOKIE['sso_reg_refer']) && $from = $_COOKIE['sso_reg_refer'];

        $mUser = new User();
        $from = $mUser->handleURLWhitelist($from);
        if ($_COOKIE['captchaId'] != session_id() || $_COOKIE["captchaValue"] != $regcode) {
            setcookie('captchaId', '');
            setcookie('captchaValue', time());
            $mUser->signSysLog('sso', 'verifyError', 'emailReg', 'user/register', array(date('Y-m-d H:i:s'), $regcode, $_COOKIE['captchaValue']));
            redirect("http://sso.cztv.com/user/registcallback/err/401/callback/" . $callback);
            exit;
        }
        setcookie('captchaId', '');
        setcookie('captchaValue', time());
        redirect("http://sso.cztv.com/user/registcallback/err/1/callback/" . $callback);
        exit;
    }


    //注册回调地址
    public function registcallbackAction() {

        $url = Request::getQuery();
        $a = explode('/', $url['_url']);
        $index = array_search('err', $a);
        $err = $a[$index + 1];
        $index = array_search('callback', $a);
        $callback = $a[$index + 1];
        $next_action = Request::getQuery('next_action', 'string', '');
        $mUser = new User();
        if (!$mUser->checkJsonCallBackPara($callback)) {
            exit;
        }
        $ajaxArr = array('flag' => $err, 'username' => '');
        !empty($next_action) && $ajaxArr['next_action'] = urldecode($next_action);
        echo "<script type=\"text/javascript\">try{document.domain='cztv.com';window.parent." . $callback . "(" . json_encode($ajaxArr) . ")}catch(e){}</script>";
        exit;
    }

    /**
     * @desc 根据手机验证码重置用户密码，原接口在api下有ip验证，改用此接口
     * @version 2015-07-14
     * @param
     * code：验证码
     * mobile：手机号
     * plat：平台
     * pwd：密码
     */
    public function resetPwdByCodeAction() {
        $code = Request::getQuery('code', 'string', '');
        $mobile = Request::getQuery('mobile', 'string', '');
        $email = Request::getQuery('email', 'email', '');
        $plat = Request::getQuery('plat', 'string', 'web');
        $newpwd = Request::getQuery('pwd', 'string', '');

        $callback = isset($_GET['callback'])?$_GET['callback']:"";
        $callback = preg_match('/^[0-9a-zA-Z_]+$/u', $callback) ? $callback : "";
        $ACTION = 'resetPwdByCode';
        $userModel = new User();
        //兼容手机、邮箱
        $mobile_email = !empty($mobile) ? $mobile : $email;
        $check = $userModel->checkClientAuthCode($mobile_email, $plat, 'reg', $code);
        if ($check) {
            // 验证码有效
            $mobile_user = $userModel->getUserByCond('mobile', $mobile);
            $email_user = $userModel->getUserByCond('email', $email);
            $user = !empty($mobile_user) ? $mobile_user : $email_user;
            $uid = $user['uid'];
            if (empty($user) || empty($uid)) {
                // 错误处理
                $this->initArr['bean'] = array();
                $this->initArr['action'] = $ACTION;
                $this->initArr['status'] = '0';
                $this->initArr['errorCode'] = '10001';
                $this->initArr['message'] = '手机号为' . $mobile . '的用户不存在';
                if ($callback) {
                    echo $callback . '(' . json_encode($this->initArr) . ')';
                    exit;
                }
                echo json_encode($this->initArr);
                exit;
            } else {
                $userModel->passwordReset($newpwd, $uid);
                $this->initArr['bean'] = array();
                $this->initArr['action'] = $ACTION;
                $this->initArr['status'] = '1';
                $this->initArr['errorCode'] = '0';
                $this->initArr['message'] = '重置密码成功';
                if ($callback) {
                    echo $callback . '(' . json_encode($this->initArr) . ')';
                    exit;
                }
                echo json_encode($this->initArr);
                exit;
            }
        } else {
            $this->initArr['bean'] = array();
            $this->initArr['action'] = $ACTION;
            $this->initArr['status'] = '0';
            $this->initArr['errorCode'] = '10002';
            $this->initArr['message'] = '验证码无效';
            if ($callback) {
                echo $callback . '(' . json_encode($this->initArr) . ')';
                exit;
            }
            echo json_encode($this->initArr);
            exit;
        }
    }


}