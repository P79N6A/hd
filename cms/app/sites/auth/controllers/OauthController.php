<?php

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2016/9/28
 * Time: 14:17
 */
class OauthController extends SsoBaseController {

    protected $cookie_key = 'H5_USERINFO';  //用户信息
    protected $cache_key = 'weiXinAccessToken';      //token键名
    protected $app_id;
    protected $app_secret;
    protected $callback;
    protected $channel_id;

    //初始化方法
    public function initialize()
    {
        parent::initialize();
        $this->crossDomain();
    }

    private $_oauthSource = array(
        1 => 'sina',
        2 => 'qq',
        3 => 'weixin',
    );


//==================================================SINA PC=============================================================
    //SINA 初始化
    public function sinaInit() {
        $this->oauth_sina = Setting::getByChannel($this->channel_id, 'oauth_sina');
        if(!$this->oauth_sina){
            $this->_json("Channel id Empty!");
        }
        require_once APP_PATH . 'libraries/oauthtown/sina/sina.class.php';
        $this->sina = new SaeTOAuthV2($this->oauth_sina['akey'], $this->oauth_sina['skey']);
    }

    //SINA 第一步
    public function sinaAction() {
        $from = $this->request->getQuery('from', null, '');
        $callbackurl = $this->request->getQuery('callbackurl', 'string');
        $channel_id = $this->request->getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;
        $this->sinaInit();
        if (!empty($from)) {
            $callback = WEB_HOST . $this->oauth_sina['callback'] . '?from=' . $from;
        }elseif(!empty($callbackurl)){
            $callback = WEB_HOST . $this->oauth_sina['callback'] . '?channel_id=' . $channel_id . '&callbackurl='.$callbackurl;

        } else {
            $callback = WEB_HOST . $this->oauth_sina['callback'];
        }

        $url = $this->sina->getAuthorizeURL($callback);
        $this->response->redirect($url, true);
    }

    //SINA 第二步
    public function sinacallbackAction() {

        $channel_id = $this->request->getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;

        $type = 1;
        $this->sinaInit();
        $code = $this->request->getQuery('code', null, '');
        $sina_key = $this->sina->getAccessToken('code', array('code' => $code, 'redirect_uri' => WEB_HOST . $this->oauth_sina['callback']));
        //跳到回第一步
        if (!is_array($sina_key) or !isset($sina_key['access_token'], $sina_key['uid'])) {
            $this->response->redirect(WEB_HOST . '/oauth/sina', true);
        }
        //获取初步授权信息
        if(isset($sina_key['access_token']) && isset($sina_key['uid'])) {
            $oauth_token = $sina_key['access_token'];
            $oauth_user_id = $sina_key['uid'];//sina的用户ID
            $oauth_user_info = $this->sina->get('https://api.weibo.com/2/users/show.json', array('access_token' => $oauth_token, 'uid' => $oauth_user_id));
            $oauth_user_info['nickname'] = $oauth_user_info['name'];
            $oauth_user_info['username'] = "weibo_" . $oauth_user_id;
            $oauth_user_info['openid'] = $oauth_user_id;
            $oauth_user_info['refresh_token'] = '';
            $oauth_user_info['sns_token'] = '';
            $oauth_user_info['sns_id'] = $oauth_user_info['idstr'];
            $oauth_user_info['avatar'] = $oauth_user_info['avatar_large'];
            $oauth_user_info['sns_type'] = 3;
            $oauth_user_info['from'] = 3;
            $oauth_user_info['callbackurl'] = Request::getQuery("callbackurl", "string", isset($_SERVER['HTTP_REFERER']));
            $oauth_user_info['channel_id'] = Request::getQuery('channel_id', 'int', 3);
            $oauth_user_info['email'] = "";
            $oauth_user_info['mobile'] = "";
            $oauth_user_info = array_merge($oauth_user_info, $sina_key);
            //授权成功后处理数据
            $this->isAuth($oauth_user_id, $oauth_user_info);
        } else {
            //echo "ok";
            $this->_json([], 403, "Oauth fail!");
        }


    }

    //===============================================QQ PC==============================================================
    /**
     * @desc QQ 初始化
     *
     *
     */
    public function qqInit() {
        require_once APP_PATH . 'libraries/oauthtown/qq/qq.class.php';
        $this->qq = new QQ();
        $this->qq->channel_id = $this->channel_id;

    }

    //QQ 第一步
    public function qqAction() {
        $from = $this->request->getQuery('from', null, '');
        $callbackurl = $this->request->getQuery('callbackurl', 'string');
        $channel_id = $this->request->getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;
        $this->qqInit();
        $url = $this->qq->getAuthorizeURL($from, $callbackurl, $channel_id);
        $this->response->redirect($url, true);
    }

    //QQ 第二步
    public function qqcallbackAction() {
        $channel_id = $this->request->getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;
        $type = 2;
        $this->qqInit();

        $qq_key = $this->qq->callBack();
        $from = $this->request->getQuery('from', null, '');
        $userModel = new User();
        //跳到回第一步
        if (!is_array($qq_key) or $qq_key == false) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'qqcallback_empty_qqkey', array());
            $this->response->redirect(WEB_HOST . '/oauth/qq', true);
        }
        if(isset($qq_key['access_token']) && isset($qq_key['openid'])) {
            //构建sina用户信息
            $oauth_token = $qq_key['access_token'];
            $oauth_user_id = $qq_key['openid'];
            $oauth_user_info = $this->qq->getUserInfo($oauth_token, $oauth_user_id);
            $oauth_user_info['access_token'] = $qq_key['access_token'];
            $oauth_user_info['refresh_token'] = $qq_key['refresh_token'];
            $oauth_user_info['openid'] = $qq_key['openid'];
            $oauth_user_info['sns_type'] = 2;
            $oauth_user_info['from'] = 2;
            $oauth_user_info['callbackurl'] = Request::getQuery("callbackurl", "string", isset($_SERVER['HTTP_REFERER'])?:"");
            $oauth_user_info['username'] = "qq_" . $oauth_user_id;
            $oauth_user_info['sns_token'] = $oauth_user_info['access_token'];
            $oauth_user_info['sns_id'] = $oauth_user_info['openid'];
            $oauth_user_info['avatar'] = $oauth_user_info['figureurl_qq_1'];
            $oauth_user_info['channel_id'] = Request::getQuery('channel_id', 'int', 3);
            $oauth_user_info['email'] = "";
            $oauth_user_info['mobile'] = "";
            $oauth_user_info = array_merge($oauth_user_info, $qq_key);
            //授权成功处理数据
            $this->isAuth($oauth_user_id, $oauth_user_info);
        } else {
            $this->_json([], 403, "Oauth fail");
        }


    }

//==============================================weixin PC===============================================================
    //weixin 初始化
    public function weixinInit() {
        require_once APP_PATH . 'libraries/oauthtown/weixin/weixin.class.php';
        $this->weixin = new Weixin();
        $this->weixin->channel_id = $this->channel_id;
    }

    //weixin 第一步
    public function weixinAction() {
        $from = $this->request->getQuery('from', null, '');
        $callbackurl = $this->request->getQuery('callbackurl', 'string');
        $channel_id = $this->request->getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;
        $this->weixinInit();
        $url = $this->weixin->getAuthorizeURL($from, $callbackurl, $channel_id);
        $this->response->redirect($url, true);
    }

    //weixin 第二步
    public function weixincallbackAction() {

        $channel_id = $this->request->getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;

        $type = 3;
        $this->weixinInit();
        $weixin_key = $this->weixin->callBack();
        $from = $this->request->getQuery('from', null, '');
        //跳到回第一步
        if (!is_array($weixin_key) or $weixin_key == false) {
            $this->response->redirect(WEB_HOST . '/oauth/weixin', true);
        }
        if (isset($weixin_key['access_token']) && isset($weixin_key['openid'])) {//获取微信用户信息
            $oauth_token = $weixin_key['access_token'];
            $oauth_user_info = $this->weixin->getUserInfo($oauth_token, $weixin_key['openid']);
            $oauth_user_id = $weixin_key['openid'];
            $oauth_user_info['openid'] = $weixin_key['openid'];
            $oauth_user_info['access_token'] = $weixin_key['access_token'];
            $oauth_user_info['sns_type'] = 3;
            $oauth_user_info['from'] = 3;
            $oauth_user_info['callbackurl'] = Request::getQuery("callbackurl","string", isset($_SERVER['HTTP_REFERER'])?:"");
            $oauth_user_info['username'] = "weixin_" . $oauth_user_id;
            $oauth_user_info['refresh_token'] = '';
            $oauth_user_info['sns_token'] = '';
            $oauth_user_info['sns_id'] = $oauth_user_info['openid'];
            $oauth_user_info['avatar'] = $oauth_user_info['headimgurl'];
            $oauth_user_info['channel_id'] = Request::getQuery('channel_id', 'int', 3);
            $oauth_user_info['email'] = "";
            $oauth_user_info['mobile'] = "";
            //去新地址
            $this->isAuth($oauth_user_id, $oauth_user_info);
        } else {
            $this->_json([], 403, "Oauth fail");
        }


    }

//=================================================SINA APP=============================================================
    public function appssosinaAction() {
        $type = 1;
        $this->sinaInit();
        isset($_SESSION['plat']) && $plat = $_SESSION['plat'];
        empty($plat) && $plat = $_GET['plat'];
        $clientIp = $this->request->getQuery('clientip', null, '');
        $equipType = $this->request->getQuery('equipType', null, ''); //设备类型
        $equipID = $this->request->getQuery('equipID', null, ''); //设备ID
        $softID = $this->request->getQuery('softID', null, ''); //软件版本

        $oauth_token = $this->request->getQuery('access_token');
        $oauth_user_id = $this->request->getQuery('uid');//sina的用户ID
        // dlevel：返回用户信息级别：basic=>基本信息  expand=>返回用户扩展信息 total=>返回全部信息，默认为basic
        $dlevel = $this->request->getQuery('dlevel', 'total');
        //获取用户信息
        $oauth_user_info = $this->sina->get('https://api.weibo.com/2/users/show.json', array('access_token' => $oauth_token, 'uid' => $oauth_user_id));
        $gender = $oauth_user_info['gender'] == 'f' ? 2 : ($oauth_user_info['gender'] == 'm' ? 1 : 0);
        $userModel = new User();
        //跳到回第一步(重新进行授权)
        if (!isset($oauth_user_info['name'])) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'appssosina', array($oauth_user_id));
            $initArr['bean'] = array();
            $initArr['status'] = 0;
            $initArr['errorCode'] = 1001;
            $initArr['message'] = '登录失败，sina异常，获取不到新浪用户信息';
            $initArr['sso_tk'] = '';
            echo json_encode($initArr);
            exit;
        }
        //开始绑定
        $sina['oauth_user_id'] = $oauth_user_id;
        $sina['oauth_user_email'] = '';
        $nickname = $this->handleOauthNickname('sina_', $oauth_user_id, $oauth_user_info['name']);
        $sina['oauth_user_nickname'] = $nickname;
        $this->session->set('sina', $sina);

        $userSocialsModel = new UserSocials();
        $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);
        if (empty($oplateUserInfo)) {//第一次认证
            $oauth_avatar = $this->saveAvatar($oauth_user_info['avatar_large']);
            $oauth_avatar = cdn_url('image', $oauth_avatar);
            $flag = $this->insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token);
            if ($flag) {
                $ssouid = $this->bindapp($type, $plat, $oauth_user_id, $nickname, $oauth_avatar, $equipType, $equipID, $softID, $clientIp, $gender);
                //跳到用户绑定页面
                $userInfo = $userModel->getUserByID($ssouid, 1, $dlevel);
                if (!empty($userInfo)) {
                    $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);
                    unset($oplateUserInfo['token']);
                    unset($oplateUserInfo['refresh_token']);
                    $userInfo['user_connect'] = $oplateUserInfo;
                    if (empty($userInfo['user_connect']['avatar'])) {
                        $userInfo['user_connect']['avatar'] = $oauth_user_info['profile_image_url'];
                    }
                    unset($userInfo['password']);
                    unset($userInfo['salt']);
                    $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                    $initArr['bean'] = $userInfo;
                    $initArr['status'] = 1;
                    $initArr['errorCode'] = 0;
                    $initArr['sso_tk'] = $sso_tk;
                    $initArr['message'] = '登录成功';
                } else {
                    $initArr['bean'] = array();
                    $initArr['status'] = 0;
                    $initArr['errorCode'] = 1001;
                    $initArr['message'] = '登录失败，账号异常，没有绑定新蓝网账号';
                    $initArr['sso_tk'] = '';
                }
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssosina', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
                echo json_encode($initArr);
                exit;
            } else {
                $initArr['bean'] = array();
                $initArr['status'] = 0;
                $initArr['errorCode'] = 1001;
                $initArr['message'] = '登录失败，第三方账号入库失败';
                $initArr['sso_tk'] = '';
                echo json_encode($initArr);
                exit;
            }
        } else {
            $userInfo = $userModel->getUserByID($oplateUserInfo['uid'], 1, $dlevel);
            if (!empty($userInfo)) {
                $re = $userSocialsModel->findFirst(array("conditions" => "open_id='{$oauth_user_id}' and type='{$type}'"));
                $re->save(array('token' => $oauth_token));
                unset($oplateUserInfo['token']);
                unset($oplateUserInfo['refresh_token']);
                $userInfo['user_connect'] = $oplateUserInfo;
                if (empty($userInfo['user_connect']['avatar'])) {
                    $userInfo['user_connect']['avatar'] = $oauth_user_info['profile_image_url'];
                }
                unset($userInfo['password']);
                unset($userInfo['salt']);
                $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                $initArr['bean'] = $userInfo;
                $initArr['status'] = 1;
                $initArr['errorCode'] = 0;
                $initArr['message'] = '登录成功';
                $initArr['sso_tk'] = $sso_tk;
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssosina', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
            } else {
                // 如果没有绑定第三方登录，自动绑定一个新蓝网账号
                $oauth_avatar = "";
                $ssouid = $this->bindapp($type, $plat, $oauth_user_id, $nickname, $oauth_avatar, $equipType, $equipID, $softID, $clientIp, $gender);
                $userInfo = $userModel->getUserByID($ssouid, 1, $dlevel);
                if (!empty($userInfo)) {
                    $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                    $initArr['bean'] = $userInfo;
                    $initArr['status'] = 1;
                    $initArr['errorCode'] = 0;
                    $initArr['message'] = '登录成功';
                    $initArr['sso_tk'] = $sso_tk;
                    $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssosina', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
                } else {
                    $initArr['bean'] = array();
                    $initArr['status'] = 0;
                    $initArr['errorCode'] = 1001;
                    $initArr['message'] = '登录失败，账号异常，没有绑定新蓝网账号';
                    $initArr['sso_tk'] = '';
                }
            }
            echo json_encode($initArr);
            exit;
        }
        exit;
    }

//========================================================QQ APP========================================================
    public function appssoqqAction() {
        $type = 2;
        isset($_SESSION['plat']) && $plat = $_SESSION['plat'];
        if (empty($plat)) {
            $plat = $this->request->getQuery('plat', null, '');
        }
        $clientIp = $this->request->getQuery('clientip', null, '');
        $equipType = $this->request->getQuery('equipType', null, ''); //设备类型
        $equipID = $this->request->getQuery('equipID', null, ''); //设备ID
        $softID = $this->request->getQuery('softID', null, ''); //软件版本

        $this->qqInit();
        $oauth_token = $this->request->getQuery('access_token');
        $oauth_user_id = $this->request->getQuery('openid');//qq的用户ID'
        if ($plat == 'news') {//蓝新闻
            $oauth_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_qq');
            //$appkey = $this->request->getQuery('appkey', null, QQ_AKEY);//qq应用id
            $appkey = $this->request->getQuery('appkey', null, $oauth_qq['akey']);//qq应用id
        } else {//蓝TV
            $oauth_tv_app_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_app_qq');
            //$appkey = $this->request->getQuery('appkey', null, QQ_TV_APP_AKEY);//qq应用id
            $appkey = $this->request->getQuery('appkey', null, $oauth_tv_app_qq['akey']);//qq应用id
        }

        // dlevel：返回用户信息级别：basic=>基本信息  expand=>返回用户扩展信息 total=>返回全部信息，默认为basic
        $dlevel = $this->request->getQuery('dlevel', null, 'total');
        //获取qq用户信息
        $oauth_user_info = $this->qq->getOauthUserInfo($oauth_token, $oauth_user_id, $appkey);
        $gender = $oauth_user_info['gender'] == '女' ? 2 : ($oauth_user_info['gender'] == '男' ? 1 : 0);
        $userModel = new User();
        //跳到回第一步
        if (!isset($oauth_user_info['nickname'])) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'appssoqq', array($oauth_user_id));
            $initArr['bean'] = array();
            $initArr['status'] = 0;
            $initArr['errorCode'] = 1001;
            $initArr['message'] = '登录失败，qq异常，获取不到qq用户信息';
            $initArr['sso_tk'] = '';
            echo json_encode($initArr);
            exit;
        }

        //开始绑定
        $qq['oauth_user_id'] = $oauth_user_id;
        $qq['oauth_user_email'] = '';
        $nickname = $this->handleOauthNickname('qq_', $oauth_user_id, $oauth_user_info['nickname']);
        $qq['oauth_user_nickname'] = $nickname;
        $this->session->set('qq', $qq);

        $userSocialsModel = new UserSocials();
        $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);
        if (empty($oplateUserInfo)) {//第一次认证
            $flag = $this->insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token);
            if ($flag) {
                $oauth_avatar = $this->saveAvatar($oauth_user_info['figureurl_qq_2']);
                $oauth_avatar = cdn_url('image', $oauth_avatar);
                //跳到用户绑定页面
                $ssouid = $this->bindapp($type, $plat, $oauth_user_id, $nickname, $oauth_avatar, $equipType, $equipID, $softID, $clientIp, $gender);
                $userInfo = $userModel->getUserByID($ssouid, 1, $dlevel);
                if (!empty($userInfo)) {
                    $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);
                    unset($oplateUserInfo['token']);
                    unset($oplateUserInfo['refresh_token']);
                    $userInfo['user_connect'] = $oplateUserInfo;
                    if (empty($userInfo['user_connect']['avatar'])) {
                        $userInfo['user_connect']['avatar'] = $oauth_user_info["figureurl_qq_2"];
                    }
                    unset($userInfo['password']);
                    unset($userInfo['salt']);
                    $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                    $initArr['bean'] = $userInfo;
                    $initArr['status'] = 1;
                    $initArr['errorCode'] = 0;
                    $initArr['message'] = '登录成功';
                    $initArr['sso_tk'] = $sso_tk;
                } else {
                    $initArr['bean'] = array();
                    $initArr['status'] = 0;
                    $initArr['errorCode'] = 1001;
                    $initArr['message'] = '登录失败，账号异常，没有绑定新蓝网账号';
                    $initArr['sso_tk'] = '';
                }
                //加登录日志'
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoqq', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoqqq', array(json_encode($initArr)));
                echo json_encode($initArr);
                exit;
            } else {
                $initArr['bean'] = array();
                $initArr['status'] = 0;
                $initArr['errorCode'] = 1001;
                $initArr['message'] = '登录失败，第三方账号入库时失败';
                $initArr['sso_tk'] = '';
                echo json_encode($initArr);
                exit;
            }
        } else {
            $userInfo = $userModel->getUserByID($oplateUserInfo['uid'], 1, $dlevel);
            if (!empty($userInfo)) {
                $re = $userSocialsModel->findFirst(array("conditions" => "open_id='{$oauth_user_id}' and type='{$type}'"));
                $re->save(array('token' => $oauth_token));
                unset($oplateUserInfo['token']);
                unset($oplateUserInfo['refresh_token']);
                $userInfo['user_connect'] = $oplateUserInfo;
                if (empty($userInfo['user_connect']['avatar'])) {
                    $userInfo['user_connect']['avatar'] = $oauth_user_info["figureurl_qq_2"];
                }
                unset($userInfo['password']);
                unset($userInfo['salt']);
                $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                $initArr['bean'] = $userInfo;
                $initArr['status'] = 1;
                $initArr['errorCode'] = 0;
                $initArr['message'] = '登录成功';
                $initArr['sso_tk'] = $sso_tk;
                //加登录日志
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoqq', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
            } else {

                $ssouid = $this->bindapp($type, $plat, $oauth_user_id, $nickname, '', $equipType, $equipID, $softID, $clientIp, $gender);
                $userInfo = $userModel->getUserByID($ssouid, 1, $dlevel);
                if (!empty($userInfo)) {
                    $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                    $initArr['bean'] = $userInfo;
                    $initArr['status'] = 1;
                    $initArr['errorCode'] = 0;
                    $initArr['message'] = '登录成功';
                    $initArr['sso_tk'] = $sso_tk;
                    //加登录日志
                    $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoqq', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
                } else {
                    $initArr['bean'] = array();
                    $initArr['status'] = 0;
                    $initArr['errorCode'] = 1001;
                    $initArr['message'] = '登录失败，账号异常，没有绑定新蓝网账号';
                    $initArr['sso_tk'] = '';
                }
            }
            echo json_encode($initArr);
            exit;
        }
        exit;
    }

//===================================================weixin APP=========================================================
    public function appssoweixinAction() {
        $type = 3;
        $plat = $this->request->getQuery('plat', null, '');
        $clientIp = $this->request->getQuery('clientip', null, '');
        $equipType = $this->request->getQuery('equipType', null, ''); //设备类型
        $equipID = $this->request->getQuery('equipID', null, ''); //设备ID
        $softID = $this->request->getQuery('softID', null, ''); //软件版本

        $this->weixinInit();
        $oauth_token = $this->request->getQuery('access_token');
        $openid = $this->request->getQuery('openid');//qq的用户ID'
        // dlevel：返回用户信息级别：basic=>基本信息  expand=>返回用户扩展信息 total=>返回全部信息，默认为basic
        $dlevel = $this->request->getQuery('dlevel', null, 'total');
        //获取微信用户信息
        $oauth_user_info = $this->weixin->getUserInfo($oauth_token, $openid);

        $userModel = new User();
        //跳到回第一步
        if (!isset($oauth_user_info['nickname'])) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'appssoweixin', array($openid));
            $initArr['bean'] = array();
            $initArr['status'] = 0;
            $initArr['errorCode'] = 1001;
            $initArr['message'] = '登录失败，微信异常，获取不到微信用户信息';
            $initArr['sso_tk'] = '';
            echo json_encode($initArr);
            exit;
        }
        //同一用户，对同一个微信开放平台下的不同应用，unionid是相同的
        $oauth_user_id = $oauth_user_info['unionid'];

        //开始绑定
        $nickname = $this->handleOauthNickname('weixin_', $oauth_user_id, $oauth_user_info['nickname']);
        $userSocialsModel = new UserSocials();
        $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);
        //第一次认证
        if (empty($oplateUserInfo)) {
            $flag = $this->insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token);
            if ($flag) {
                $oauth_avatar = $this->saveAvatar($oauth_user_info['headimgurl']);
                $oauth_avatar = cdn_url('image', $oauth_avatar);
                //跳到用户绑定页面
                $ssouid = $this->bindapp($type, $plat, $oauth_user_id, $nickname, $oauth_avatar, $equipType, $equipID, $softID, $clientIp);
                $userInfo = $userModel->getUserByID($ssouid, 1, $dlevel);
                if (!empty($userInfo)) {
                    /*
                    //开通第三方账号 添加积分
                    if (in_array($plat, array('tv', 'mobile_tv', 'mobile_ca', 'letvpc', 'letv_box_tv'))) {
                        $flag = $this->model('User')->addUserScore($ssouid, $plat);
                    }
                    */
                    $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);
                    unset($oplateUserInfo['token']);;
                    unset($oplateUserInfo['refresh_token']);
                    $userInfo['user_connect'] = $oplateUserInfo;
                    if (empty($userInfo['user_connect']['avatar'])) {
                        $userInfo['user_connect']['avatar'] = $oauth_user_info["headimgurl"];
                    }
                    unset($userInfo['password']);
                    unset($userInfo['salt']);
                    $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                    $initArr['bean'] = $userInfo;
                    $initArr['status'] = 1;
                    $initArr['errorCode'] = 0;
                    $initArr['message'] = '登录成功';
                    $initArr['sso_tk'] = $sso_tk;
                } else {
                    $initArr['bean'] = array();
                    $initArr['status'] = 0;
                    $initArr['errorCode'] = 1001;
                    $initArr['message'] = '登录失败，账号异常，没有绑定新蓝网账号';
                    $initArr['sso_tk'] = '';
                }
                //加登录日志
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoweixin', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
                echo json_encode($initArr);
                exit;
            } else {
                $initArr['bean'] = array();
                $initArr['status'] = 0;
                $initArr['errorCode'] = 1001;
                $initArr['message'] = '登录失败，第三方账号入库时失败';
                $initArr['sso_tk'] = '';
                echo json_encode($initArr);
                exit;
            }
        } else {
            $userInfo = $userModel->getUserByID($oplateUserInfo['uid'], 1, $dlevel);
            if (!empty($userInfo)) {
                $re = $userSocialsModel->findFirst(array("conditions" => "open_id='{$oauth_user_id}' and type='{$type}'"));
                $re->save(array('token' => $oauth_token));
                unset($oplateUserInfo['token']);
                unset($oplateUserInfo['refresh_token']);
                $userInfo['user_connect'] = $oplateUserInfo;
                if (empty($userInfo['user_connect']['avatar'])) {
                    $userInfo['user_connect']['avatar'] = $oauth_user_info["headimgurl"];
                }
                unset($userInfo['password']);
                unset($userInfo['salt']);
                $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                $initArr['bean'] = $userInfo;
                $initArr['status'] = 1;
                $initArr['errorCode'] = 0;
                $initArr['message'] = '登录成功';
                $initArr['sso_tk'] = $sso_tk;
                //加登录日志
                $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoweixin', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
            } else {
                $ssouid = $this->bindapp($type, $plat, $oauth_user_id, $nickname, $oauth_avatar = '', $equipType, $equipID, $softID, $clientIp);
                $userInfo = $userModel->getUserByID($ssouid, 1, $dlevel);
                if (!empty($userInfo)) {
                    $sso_tk = $userModel->ssotk($userInfo['uid'], true, $plat);
                    $initArr['bean'] = $userInfo;
                    $initArr['status'] = 1;
                    $initArr['errorCode'] = 0;
                    $initArr['message'] = '登录成功';
                    $initArr['sso_tk'] = $sso_tk;
                    //加登录日志
                    $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'user/appssoweixin', array($userInfo['uid'], $plat, $type, $equipType, $equipID, $softID, $clientIp));
                } else {
                    $initArr['bean'] = array();
                    $initArr['status'] = 0;
                    $initArr['errorCode'] = 1001;
                    $initArr['message'] = '登录失败，账号异常，没有绑定新蓝网账号';
                    $initArr['sso_tk'] = '';
                }
            }
            echo json_encode($initArr);
            exit;
        }
        exit;
    }
    //==========================END========================

    /**
     * @desc 过滤生成新昵称
     * @param $prefix
     * @param $oauth_user_id
     * @param $nickname
     * @return mixed|string
     */
    private function handleOauthNickname($prefix, $oauth_user_id, $nickname) {
        $nickname = empty($nickname) ? $prefix . $oauth_user_id : preg_replace('/[^0-9a-zA-Z\x{4e00}-\x{9fa5}]+/u', '', $nickname);
        /**过滤后，如果字符长度为0，则直接生成新昵称
         *过滤后，如果字符长度为1～3，则补充下划线(_）至四位
         *过滤后，如果字符长度长于24，则截取前24位
         */
        $strlen = mb_strlen($nickname, 'UTF8');
        switch ($strlen) {
            case 1:
                $nickname = $nickname . '___';
                break;
            case 2:
                $nickname = $nickname . '__';
                break;
            case 3:
                $nickname = $nickname . '_';
                break;
        }
        if ($strlen > 24) {
            $nickname = mb_substr($nickname, 0, 24);
        }
        if (preg_match('/^[0-9]+$/', $nickname)) {
            $nickname = '_' . $nickname;
        }
        if ($strlen > 24) {
            $nickname = mb_substr($nickname, 0, 24);
        }
        return $nickname;
    }

    /**
     * @desc 第三方用户头像上传方法
     * @param $avatarSource
     * @return null|string 头像尺寸 298*298,200*200,70*70,50*50
     */
    private function saveAvatar($avatarSource) {
        if (empty($avatarSource)) {
            return null;
        }
        $oauth_avatar = '';
        $userModel = new User();
        //$source_img = $userModel->saveAvatarFile($avatarSource, 1);//返回临时图片地址
        //if (!empty($source_img))
        // {
        $oauth_avatar = $userModel->createUploadAvatar($avatarSource);
        //}
        return $oauth_avatar;
    }

    /**
     * @desc 插入授权用户的信息
     * @param $oauth_user_id
     * @param $nickname
     * @param $type
     * @param $oauth_token
     * @param string $oauth_refresh_token
     * @return mixed
     */
    private function insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token, $oauth_refresh_token = '') {
        $data = array(
            'channel_id' => LETV_CHANNEL_ID,
            'open_id' => $oauth_user_id,
            'nickname' => $nickname,
            'type' => $type,
            'token' => $oauth_token,
            'refresh_token' => $oauth_refresh_token,
            'uid' => 0,
            'from' => '',
            'bind_uid'=>0,
            'partition_by' => User::getHashTable($oauth_user_id),
        );
        $UserSocialsModel = new UserSocials();
        $flag = $UserSocialsModel->passport_insertData($data);
        return $flag;
    }

    /**
     * @desc 第三方用户绑定
     * @param $type
     * @param $oauth_user_id
     * @param $oauth_nickname
     * @param string $avatar
     * @param string $plat
     * @param int $gender
     * @return int
     */
    private function bind($type, $oauth_user_id, $oauth_nickname, $avatar = '', $plat = '', $gender = 0) {
        $userModel = new User();
        $ip = $userModel->getClientIp();
        //检查第三方来源
        if (!in_array($type, array(1, 2, 3))) {
            $userModel->signSysLog('sso', 'bindOauthError', 'bind', 'oauth', array($ip, $type, $oauth_user_id, 202));
            return 202;
        }
        $oauthArr = $this->_oauthSource;
        $oauth_type_name = $oauthArr[$type];
        //第三方id不能为空
        if (empty($oauth_user_id)) {
            $userModel->signSysLog('sso', 'bindOauthError', 'bind', 'oauth', array($ip, $type, $oauth_user_id, 201));
            return 201;
        }
        //设置用户名
        $oauth_username = $oauth_type_name . '_' . $oauth_user_id;
        $oauth_username = $userModel->getUsernameOauth($oauth_username);
        //注册绑定
        $ipstatus = $userModel->stopUserRegByIp($ip);

        $UserSocialsModel = new UserSocials();
        if ($ipstatus) {
            $UserSocialsModel->delOAuthUser($oauth_user_id, $type);
            $userModel->signSysLog('sso', 'bindOauthError', 'bind', 'oauth', array($ip, $type, $oauth_user_id, 'stopip'));
            exit("你今天注册用户过多，请稍后再试。");//限制一天内同一ip注册量大于2000
        }
        $password = time();
        $data = array(
            'username' => $oauth_username,
            'password' => $password,
            'regist_ip' => $ip,
            'nickname' => $oauth_nickname,
            'status' => '1',
            'regist_service' => 'my',
            'gender' => $gender,
        );

        if (!empty($avatar)) {
            $data['avatar'] = $avatar;
        }
        $ssouid = $userModel->regUser($data);

        if ($ssouid > 0) {
            $bindflag = $UserSocialsModel->bindOAuthUser($oauth_user_id, $type, $ssouid);
            if (!$bindflag) {
                $userModel->signSysLog('sso', 'bindOauthError', 'bind', 'oauth', array($ip, $type, $oauth_user_id, 'bindfail', $ssouid));
            }
        } else {
            $UserSocialsModel->delOAuthUser($oauth_user_id, $type);
            $userModel->signSysLog('sso', 'bindOauthError', 'bind', 'oauth', array($ip, $type, $oauth_user_id, 'regfail'));
        }

        if ($ssouid > 0) {
            if (!empty($plat) && $plat == 'news') {
                $userModel->signSysLog('sso', 'clientOauthRegist', 'sso', 'user/clientOauthRegist', array($ssouid, $plat, $type, $equipType = '', $equipID = '', $softID = ''));
            } elseif (!empty($plat)) {
                $userModel->signSysLog('sso', 'OauthRegist', 'sso', 'user/oauth', array($ssouid, $type, $plat));
            } else {
                $userModel->signSysLog('sso', 'OauthRegist', 'sso', 'user/oauth', array($ssouid, $type));
            }
            $userModel->signSysLog('sso', 'UserActionLog', 'register', 'oauth', array($ip, $type, $oauth_user_id, $ssouid, $plat));
        }
        return $ssouid;
    }

    /**
     * @desc 第三方处理登录
     * @param $userinfo
     * @param $type
     * @param int $firstLogin
     * @param string $from
     */
    public function goLogin($userinfo, $type, $firstLogin = 0, $from = '') {
        $loginname = $userinfo['username'];
        //$loginpwd  = $userinfo['pwd'];
        $userModel = new User();
        $userModel->LoginSsoCookie($userinfo, $type, 'my', 'true', true, '', $firstLogin);
        /////////#########添加登录日志###########///////////////////////////
        if ($from == "news") {
            $userModel->signSysLog('sso', 'clientOauthLogin', 'sso', 'oauth/login', array($userinfo['uid'], $from, $type, $equipType = '', $equipID = '', $softID = ''));
        } else {
            $userModel->signSysLog('sso', 'oauthLogin', 'sso', 'oauth/login', array($userinfo['uid'], $type, $from));
        }
        $userModel->signSysLog('sso', 'UserActionLog', 'login', 'oauth', array($userModel->getClientIp(), $type, $userinfo['uid'], $from));
        ///////////////////////////////////////////////////////
        //更新最后登录时间和ip
        header('Content-type:text/html;charset=utf-8');
        echo '<script type="text/javascript">
          	document.domain = "cztv.com";
            if (window.opener) {
         		try {
					window.opener.loginSuccess();
					window.opener=null;
					window.open("","_self");
					window.close();
				} catch(e) {
					window.opener=null;
					window.open("","_self");
					window.close();
				}
			} else {
				try {
					if ("cef_closeLoginWindow" in window) {
                        window.cef_closeLoginWindow();
                    } else {
                        window.external.closeLoginWindow();
                    }
				} catch(e) {

				}
			}
         </script>';
    }

    /**
     * @desc 没有绑定则进绑定页，已绑定则登录
     * @param $ssouid
     * @param $type
     * @param $oauth_token
     * @param $oauth_token_secret
     * @param $oauth_user_id
     * @param string $from
     * @param string $oauth_refresh_token
     */
    public function loginOauth($ssouid, $type, $oauth_token, $oauth_user_id, $from = '', $oauth_refresh_token = '') {
        //跳到用户绑定页面
        if ($ssouid <= 0) {
            $this->response->redirect(WEB_HOST . '/oauth/bind?type=' . $type);
        }

        //更新开放平台akey与skey与oauth_refresh_token
        $userSocialsModel = new UserSocials();
        $partition_by = User::getHashTable($oauth_user_id);
        $data = array('token' => $oauth_token, 'refresh_token' => $oauth_refresh_token, 'partition_by' => $partition_by);
        $userSocials = $userSocialsModel->findFirst(array("conditions" => "type='{$type}' and open_id='{$oauth_user_id}'"));
        if ($userSocials) {
            $userSocials->save($data);
        }
        //开始登录
        $userModel = new User();
        $userinfo = $userModel->getUserByID($ssouid, 1, 'expand');
        if (empty($userinfo)) {
            $Tiptitle = '新蓝网提醒您';
            $Tipmsg = '无此用户,或已被禁用，点击<a href="http://www.cztv.com" class="under blu">这里</a>回首页!';
            View::setMainView('tip/tip.php');
            View::setVars(compact('Tiptitle', 'Tipmsg'));
            exit;
        } else {
            if ($userinfo['status'] == '1') {
                $this->goLogin($userinfo, $type, 0, $from);
            } elseif ($userinfo['status'] == '2') {//邮箱未激活
                $userModel = new User();
                if ($userModel->checkEmailIfSendEmail($userinfo['email'])) {
                    if ($userModel->checkEmailIfSendEmail($userinfo['email'])) {
                        $mailModel = new Mail();
                        $mailModel->sendActiveEmail($userinfo['email'], $userinfo['uid'], $userinfo['registService']);
                        $userModel->setSemdEmailTimes($userinfo['email']);
                        $Tiptitle = '新蓝网提醒您';
                        $Tipmsg = '请到<a href="' . Plugin_Util::getEmailUrl($userinfo['email']) . '" class="under blu">' . $userinfo['email'] . '</a>激活邮箱,激活成功后可登录。';
                        View::setMainView('tip/tip.php');
                        View::setVars(compact('Tiptitle', 'Tipmsg'));;
                    } else {
                        $Tiptitle = '新蓝网提醒您';
                        $Tipmsg = '邮箱未激活，每个邮箱每天只能发送5封邮件';
                        View::setMainView('tip/tip.php');
                        View::setVars(compact('Tiptitle', 'Tipmsg'));
                    }
                }
                exit;
            } else {//账号被管理员禁掉了
                $Tiptitle = '新蓝网提醒您';
                $Tipmsg = '账号被管理员禁掉了，点击<a href="http://www.cztv.com" class="under blu">这里</a>回首页!';
                View::setMainView('tip/tip.php');
                View::setVars(compact('Tiptitle', 'Tipmsg'));
                exit;
            }
        }
    }

    /**
     * @desc 移动客户端第三方账号绑定
     * @param $type
     * @param $plat
     * @param $oauth_user_id
     * @param $oauth_nickname
     * @param string $avatar
     * @param string $equipType
     * @param string $equipID
     * @param string $softID
     * @param string $clientIp
     * @param int $gender
     * @return mixed
     */
    public function bindapp($type, $plat, $oauth_user_id, $oauth_nickname, $avatar = '', $equipType = '', $equipID = '', $softID = '', $clientIp = '', $gender = 0) {
        if (!session_id()) session_start();

        $type = isset($type) ? intval($type) : '';//1=>新浪, 2=>QQ, 3=>微信
        $userModel = new User();
        if (!in_array($type, array(1, 2, 3))) {
            $userModel->signSysLog('sso', 'bindOauthError', 'bindapp', 'oauth', array($clientIp, $type, $oauth_user_id, 202));
            exit("此平台未开放");
        }
        $oauthArr = $this->_oauthSource;
        $oauth_type_name = $oauthArr[$type];
        //第三方用户ID
        if (empty($oauth_user_id)) {
            $userModel->signSysLog('sso', 'bindOauthError', 'bindapp', 'oauth', array($clientIp, $type, $oauth_user_id, 'emptyoauthid'));
            exit("第三方用户ID为空,请重新登录");
        }
        $oauth_username = $oauth_type_name . '_' . $oauth_user_id;
        //判断第三方昵称是否重复，重复则重新生成
        $oauth_username = $userModel->getUsernameOauth($oauth_username);
        //注册绑定
        $ip = $userModel->stopUserRegByIp($clientIp);
        $userSocialsModel = new UserSocials();
        if ($ip) {
            $userSocialsModel->delOAuthUser($oauth_user_id, $type);
            $userModel->signSysLog('sso', 'bindOauthError', 'bindapp', 'oauth', array($clientIp, $type, $oauth_user_id, 'stopip'));
            exit("你今天注册用户过多，请稍后再试。");
        }
        $password = time();
        if (!empty($avatar)) {
            $ssouid = $userModel->regUser(array('username' => $oauth_username, 'password' => $password, 'regist_ip' => $ip, 'nickname' => $oauth_nickname, 'avatar' => $avatar, 'status' => '1', 'regist_service' => 'my', 'gender' => $gender));
        } else {
            $ssouid = $userModel->regUser(array('username' => $oauth_username, 'password' => $password, 'regist_ip' => $ip, 'nickname' => $oauth_nickname, 'status' => '1', 'regist_service' => 'my', 'gender' => $gender));
        }
        if ($ssouid) {
            $bindflag = $userSocialsModel->bindOAuthUser($oauth_user_id, $type, $ssouid);
            if (!$bindflag) {
                $userModel->signSysLog('sso', 'bindOauthError', 'bindapp', 'oauth', array($clientIp, $type, $oauth_user_id, 'bindfail', $ssouid));
            }
            $userModel->signSysLog('sso', 'clientOauthRegist', 'sso', 'user/clientOauthRegist', array($ssouid, $plat, $type, $equipType, $equipID, $softID));
            $userModel->signSysLog('sso', 'UserActionLog', 'register', 'oauth', array($clientIp, $type, $oauth_user_id));
        } else {
            $userSocialsModel->delOAuthUser($oauth_user_id, $type);
            $userModel->signSysLog('sso', 'bindOauthError', 'bindapp', 'oauth', array($clientIp, $type, $oauth_user_id, 'regfail'));
        }
        return $ssouid;
    }

    /**
     * 创建用户
     * @param $channel_id
     * @param $input
     * @return int
     */
    protected static function createUser_bak($channel_id, $input) {


        //生成唯一uid
        $uid = self::createUserId($channel_id, $input);

        //获取登入表分区
        $mobileAuthTable = Users::getHashTable($input['mobile']);
        //设置用户绑定uid zhanghaiquan:bind_uid
        self::createUserLogin($channel_id, $input, $uid, $mobileAuthTable);



        $userBaseinfoTable = Users::getHashTable($uid);
        list($user, $input) = self::createUsers($channel_id, $input, $uid, $data, $userBaseinfoTable);
        if ($uid && isset($input['sns_token']) && isset($input['sns_type'])) {
            self::createUserSocials($channel_id, $input, $uid);
        }
        return  $user->uid;
    }

    /**
     * 授权成功
     */
    public function isAuth($oauth_user_id,$userinfo){
        if(empty($userinfo['openid']) || empty($userinfo['channel_id'])){
            exit ("Openid and channel_id Not empty!");
        }
        //定义用户前缀
        $town_prefix = "town" . $userinfo['channel_id'] . "_";
        //构建用户昵称
        $nickname = $userinfo['nickname'] . "_" . $userinfo['channel_id'] . "_" . $userinfo['openid'];
        //先判断用户是否存在
        if(Userid::checkNickname($nickname)){
            $channel_id =  $userinfo['channel_id'];
            $open_id = $userinfo['openid'];
            $username = $userinfo['username'];
            $partition_by = Users::getHashTable($userinfo['openid']);
            $callbackurl = $userinfo['callbackurl'];
            //var_dump($userinfo);die;
            $socials = UserSocials::getUserSocials($channel_id, $open_id, $partition_by);
            $userinfo = Users::getUsersByUid($socials['uid']);
            $userinfo['socials'] = $socials;
            $token = md5($oauth_user_id);
            //过滤不需要的字段
            unset($userinfo['partition_by']);
            unset($userinfo['access_token']);
            unset($userinfo['refresh_token']);
            unset($userinfo['scope']);
            unset($userinfo['expires_in']);
            unset($userinfo['created_at']);
            unset($userinfo['created_at']);
            unset($userinfo['socials']['id']);
            unset($userinfo['socials']['partition_by']);
            RedisIO::set($token, json_encode($userinfo), 7200);
            $url = $callbackurl .'?token=' . $token;
            header('Location:' . $url);
            exit;
        } else {
            //创建uid
            $userid = new userid();
            $userinfo['uid'] = $userid->createUserId($nickname,$town_prefix . $userinfo['username']);

            //插入用户详细信息
            if($userinfo['uid']){
                $users = new Users();
                //插入用户详细信息
                $create_users  = $users->createUsers($userinfo);
                //插入第三方用户详细信息
                $create_socials = UserSocials::createUserSocials($userinfo);
                //处理好信息后设置redis
                if($create_users && $create_socials){
                    //过滤过段
                    unset($users->partition_by);
                    unset($users->access_token);
                    unset($users->refresh_token);
                    unset($users->scope);
                    unset($users->expires_in);
                    unset($users->created_at);
                    unset($create_socials->id);
                    unset($create_socials->partition_by);
                    $token = md5($oauth_user_id);
                    $users->socials = $create_socials;
                    RedisIO::set($token, json_encode($users), 7200);
                    $url = $userinfo['callbackurl'] .'?token=' . $token;
                    header('Location:' . $url);
                    exit;
                } else {
                    $this->_json([], 401, "create users or socials fail");
                }
            } else {
                $this->_json([], 401, "Create userid fail!");
            }

        }


    }

    /**
     * 绑定手机
     */
    public function bindMobileAction(){
        $token = Request::getPost('token','string');
        $userinfo = RedisIO::get($token);
        $userinfo = json_decode($userinfo,true);
        $open_id = $userinfo['socials']['open_id'];
        //赋值
        $input['mobile'] = Request::getPost('mobile','int');
        $input['password'] = Request::getPost('password','int');
        $input['channel_id'] = Request::getPost('channel_id','int');
        $input['email'] = 0;
        $input['nickname'] = $input['mobile'];
        $input['username'] = $input['mobile'];
        $input['regist_ip'] = Users::getClientIp();

        $code = Request::getPost("code","int");
        //手机短信验证
        if(!$code = VerifyCode::validate($input['mobile'],$code)){
            $this->_json([],403,"code error");
        }
        //手机短信验证
        if(!$token || !$input['mobile'] || !$input['password'] || !$input['channel_id']){
            $this->_json([],403,"parms error");
        }

        //创建用户uid
        $userlogin = Userlogin::query()
            ->andCondition("loginname" ,$input['mobile'])
            ->andCondition("channel_id",$input['channel_id'])
            ->first();
        if(!$userlogin){
            $userId = new Userid();
            //用户前缀
            $town_prefix = "town".$input['channel_id']."_";
            $input['uid'] = $userId->createUserid($input['mobile'] . "_" . $input['channel_id'],$town_prefix . $input['mobile']);

            //插入用户登入表
            if(!Userlogin::createUserLogin($input)){
                $this->_json([],401,"create userlogin fail");
            }
        } else{
            $input['uid'] = $userlogin->uid;
            //如果存在就更新密码
            $userlogin = Userlogin::findFirst("uid = " . $input['uid']);
            $cdkey = str_random();
            $userlogin->password = Hash::encrypt($input['password'], $cdkey);
            $userlogin->salt = $cdkey;
            $userlogin->save();
        }


        //插入用户信息表
        $users = new Users();
        if($users->createUsers($input)){
            $UserSocials = UserSocials::query()
                ->andCondition('open_id',$open_id)
                ->andCondition('channel_id',$input['channel_id'])
                ->andCondition('partition_by',Users::getHashTable($open_id))
                ->first();
            if($UserSocials) {
                $UserSocials->bind_uid = $input['uid'];
                if ($UserSocials->save()) {
                    //更新用户信息
                    $users->socials = $UserSocials;
                    RedisIO::set($token,json_encode($users));
                    $this->_json($UserSocials);
                }
            } else {
                $this->_json([],404,"UserSocials not found");
            }
        } else {
            $this->_json([],401,'create fail');
        }
    }

    /**
     * 存入token
     * @param $oauth_user_id
     * @param $oauth_user_info
     */
    protected function goNewUrl($oauth_user_id, $oauth_user_info)
    {
        $token = md5($oauth_user_id);
        RedisIO::set($token, json_encode($oauth_user_info), 3600);
        $url = WEB_HOST . '/oauth/isAuth?token=' . $token;
        header('Location:' . $url);
        exit;
    }

    /**
     * 获取用户信息地址
     */
    public function getUserInfoAction(){
        $token = Request::getQuery('token','string');
        $userinfo = json_decode(RedisIO::get($token),true);
        if($userinfo){
            $this->_json($userinfo);
        } else {
            $this->_json([], 404, "Invalid token");

        }
    }


    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = "success")
    {
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

    /**
     * 测试方法
     */
    public function bindAction(){
        $token = Request::getQuery("token",'string');
        echo $token;
    }

    /**
     * 发送手机验证码
     */
    public function sendCodeAction(){
        $mobile = Request::getPost('mobile','int');
        if($mobile) {
            $code = VerifyCode::send($mobile);
            if ($code) {
                $this->_json($code);
            } else {
                $this->json([], 405, "sendMobile fail!");
            }
        } else {
            $this->_json([],404,"mobile not empty");
        }
    }

    /**
     * 校验手机短信
     */
    public function checkCodeAction(){
        $mobile = Request::getPost('mobile','int');
        $code = Request::getPost('code','int');
        var_dump(VerifyCode::validate($mobile,$code));
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

        if(false !== strpos($host,'cztv')) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

    }

    /**
     * 手机注册
     */
    public function regMobileAction(){
        $mobile = Request::getPost("mobile","int");
        $channel_id = Request::getPost("channel_id","int");
        $password = Request::getPost("password","int");
        $code = Request::getPost("code","int");
        $partition_by = Users::getHashTable($mobile);

        $userLogin = Userlogin::hasMobile($mobile, $channel_id, $partition_by);
        if(!$code = VerifyCode::validate($mobile,$code)){
            $this->_json([],403,"code error");
        }
        if(!$userLogin){
            //用户前缀
            $town_prefix = "town" . $channel_id . "_";
            $userId = new Userid();
            $input['uid'] = $userId->createUserid($mobile . "_" . $channel_id, $town_prefix . $mobile);
            $input['mobile'] = $mobile;
            $input['password'] = $password;
            $input['channel_id'] = $channel_id;
            $input['email'] = "";
            $input['nickname'] = $mobile;
            $input['username'] = "mobile_" . $mobile;
            //插入用户登入表
            if(Userlogin::createUserLogin($input)){
                $users = new users();
                if($users->createUsers($input)){
                    $users->token = md5($input['mobile']);
                    RedisIO::set(md5($input['mobile']),json_encode($users), 7200);
                    $this->_json($users);
                } else {
                    $this->_json([],401,"create users fail");
                }
            } else {
                $this->_json([],401,"create userlogin fail");
            }

        } else {
            $this->_json([], 403, "mobile already exists");
        }
    }

    /**
     * 手机登入
     */
    public function loginMobileAction(){
        $mobile = Request::getPost("mobile","int");
        $password = Request::getPost("password","string");
        $channel_id = Request::getPost("channel_id","int");
        $verify = Request::getPost("verify","string",0);
        if(!$res = $this->verifyValidate($verify)) {
            $this->_json([], '400', '验证码错误');
        }
        $user = Users::apiGetUserByMobile($channel_id, $mobile);
        if(!$user) {
            $this->_json([], '401', '手机号码不存在');
        }
        if(!Hash::check($user['password'], $password, $user['salt'])) {
            $this->_json([], '402', '账号密码错误');
        }
        $id = $user['uid'];

        if(!$user['status']) {
            $this->_json([], '403', 'not status');
        } else {
            $info = Users::getUserInfo($id);
            $info['token'] = md5($mobile);
            RedisIO::set(md5($mobile),json_encode($info),7200);
            $this->_json($info);
        }


    }

    /**
     * 重设置帐号密码
     */
    public function restPwdAction(){
        $channel_id = Request::getPost('channel_id','int');
        $mobile = Request::getPost('mobile','int');
        $pwd = Request::getPost('password','string');
        //$verify = Request::getPost('verify','string');
        $code = Request::getPost("code","int");

        //验证码
        /*if(!$res = $this->verifyValidate($verify)) {
            $this->_json([], '400', '验证码错误');
        }*/
        //校验码
        if(!$code = VerifyCode::validate($mobile,$code)){
            $this->_json([],403,"code error");
        }

        $res = Users::restPassword($channel_id,$mobile,$pwd);
        if($res){
            $this->_json([]);
        } else {
            $this->_json([],4006,'重置失败');
        }

    }

    /**
     * 校验验证码
     * @return bool|int
     */
    private function verifyValidate($captchaCode) {
        if($captchaCode===null){
            return true;
        }
        $captcha = new XmasCaptcha();
        return $captcha->check($captchaCode);
    }

    /**
     * 验证码
     */
    public function verifyAction() {
        $captcha = new XmasCaptcha();
        $captcha->generateCookie();
        exit;
    }

    /**
     * 检手机号是存已经注册
     */
    public function checkMobileAction(){
        $mobile = Request::getpost('mobile','int',0);
        $channel_id = Request::getPost('channel_id','int',0);
        if($mobile || $channel_id){
            $res = Userlogin::query()
                ->andCondition('loginname',$mobile)
                ->andCondition('channel_id',$channel_id)
                ->first();
                if($res){
                    $this->_json(array('status'=>1));
                } else {
                    $this->_json(array('status'=>0));
                }
        } else {
            $this->_json([], 403, 'mobile not empty!');
        }
    }

    /**
     * 获取微信ToKen
     * @return Ambigous <boolean, mixed>
     */
    protected function getAccessToKen()
    {
        $urlAccessToken = 'http://192.168.138.36:8080/applet-inner-api/inner/getPublicAccessToken';
        $jsonAccessToken = F::curlRequest($urlAccessToken);
        $dataAccessToken = json_decode($jsonAccessToken, true);
        if ($dataAccessToken['code'] == 200) {
            $accessToken = $dataAccessToken['data']['accessToken'];
        } else {
            error_log('获取accesstoken失败:'.$dataAccessToken['msg']);
            $accessToken = false;
        }
        return $accessToken;
    }


    /**
     * 授权作用域userinfo
     */
    public function weixinWapAction()
    {
        $code = Request::getQuery('code', 'string');
        $channel_id = Request::getQuery('channel_id', 'int');
        $this->channel_id = $channel_id;
        $zgltv_weixin = Setting::getByChannel($this->channel_id, 'zgltv_wechat');
        $this->app_id = $zgltv_weixin['app_id'];
        $this->app_secret = $zgltv_weixin['app_secret'];
        $this->callback = $zgltv_weixin['callback'];
        //通过code获取openID
        if (!$code) {
            $callbackurl = Request::getQuery('callbackurl','string');
            $redirect_url = $this->callback . '/Oauth/weixinWap?channel_id=' . $channel_id . '&callbackurl=' . $callbackurl;
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_userinfo&state=' . time() . '#wechat_redirect';
            header('Location:' . $url);
            exit;
        } else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
            //TODO 统一外网服务接口
            $authInfo = F::curlRequest($url);
            $authInfo = json_decode($authInfo, true); //转为数组
            $userinfo = $this->getUserInfo($authInfo);
            $oauth_user_info = array_merge($userinfo,$authInfo);
            //获取qq用户信息
            $oauth_user_id = $oauth_user_info['openid'];
            $oauth_user_info['sns_type'] = 4;
            $oauth_user_info['from'] = 4;
            $oauth_user_info['callbackurl'] = Request::getQuery("callbackurl","string", isset($_SERVER['HTTP_REFERER'])?:"");
            $oauth_user_info['username'] = "weixinwap_" . $oauth_user_id;
            $oauth_user_info['sns_token'] = $oauth_user_info['access_token'];
            $oauth_user_info['sns_id'] = $oauth_user_info['openid'];
            $oauth_user_info['avatar'] = $oauth_user_info['headimgurl'];
            $oauth_user_info['channel_id'] = Request::getQuery('channel_id', 'int', 3);
            $oauth_user_info['email'] = "";
            $oauth_user_info['mobile'] = "";
            //授权成功处理数据
            $this->isAuth($oauth_user_id, $oauth_user_info);

        }
    }


    /**
     * 获取用户信息
     */
    protected function getUserInfo($authInfo)
    {
        //获取用户openid
        $openid = $authInfo['openid'];
        $accessToken = $authInfo['access_token'];
        if ($openid && $accessToken) {
            //TODO 统一外网服务接口
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid ."&lang=zh_CN";
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true);
            return $userInfo;
        } else {
            return false;
        }
    }



}