<?php

/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/4/28
 * Time: 14:14
 */
class OauthController extends SsoBaseController {
    private $_oauthSource = array(
        1 => 'sina',
        2 => 'qq',
        3 => 'weixin',
    );

    public function indexAction() {
        if ($_SERVER['HTTP_HOST'] != 'dev.sso.cztv.com') {
            exit('502');
        }
    }


//==================================================SINA PC=============================================================
    //SINA 初始化
    public function sinaInit() {
        $this->oauth_sina = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_sina');
        require_once APP_PATH . 'libraries/oauth/sina/sina.class.php';
        //$this->sina = new SaeTOAuthV2( SINA_AKEY , SINA_SKEY);
        $this->sina = new SaeTOAuthV2($this->oauth_sina['akey'], $this->oauth_sina['skey']);
    }

    //SINA 第一步
    public function sinaAction() {
        $this->sinaInit();
        $from = $this->request->getQuery('from', null, '');

        $userModel = new User();
        $userModel->handleURLWhitelist($from);

        if (!empty($from)) {
            //$callback = SINA_CALLBACK . '?from=' . $from;
            $callback = WEB_HOST . $this->oauth_sina['callback'] . '?from=' . $from;
        } else {
            $callback = WEB_HOST . $this->oauth_sina['callback'];
        }

        $url = $this->sina->getAuthorizeURL($callback);
        $this->response->redirect($url, true);
    }

    //SINA 第二步
    public function sinacallbackAction() {
        $type = 1;
        $this->sinaInit();
        $from = $this->request->getQuery('from', null, '');
        $code = $this->request->getQuery('code', null, '');
        //$sina_key = $this->sina->getAccessToken('code',array('code'=>$code,'redirect_uri'=>SINA_CALLBACK));
        $sina_key = $this->sina->getAccessToken('code', array('code' => $code, 'redirect_uri' => WEB_HOST . $this->oauth_sina['callback']));
        //跳到回第一步
        if (!is_array($sina_key) or !isset($sina_key['access_token'], $sina_key['uid'])) {
            $this->response->redirect(WEB_HOST . '/oauth/sina', true);
        }
        $oauth_token = $sina_key['access_token'];
        $oauth_user_id = $sina_key['uid'];//sina的用户ID

        //获取用户信息
        $oauth_user_info = $this->sina->get('https://api.weibo.com/2/users/show.json', array('access_token' => $oauth_token, 'uid' => $oauth_user_id));
        $userModel = new User();
        if (empty($oauth_user_info)) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'sinacallback', array($oauth_user_id));
            $this->response->redirect(WEB_HOST . '/oauth/sina', true);
        }
        $gender = $oauth_user_info['gender'] == 'f' ? 2 : ($oauth_user_info['gender'] == 'm' ? 1 : 0);
        //跳到回第一步
        if (!isset($oauth_user_info['name'])) {
            $this->response->redirect(WEB_HOST . '/oauth/sina', true);
        }
        //开始绑定
        $sina['oauth_user_id'] = $oauth_user_id;
        $sina['oauth_user_email'] = '';
        $nickname = $this->handleOauthNickname('sina_', $oauth_user_id, $oauth_user_info['name']);
        $sina['oauth_user_nickname'] = $nickname;
        $this->session->set('sina', $sina);


        $UserSocialsModel = new UserSocials();
        $oplateUserInfo = $UserSocialsModel->getOAuthUser($oauth_user_id, $type);


        if (empty($oplateUserInfo)) {
            //第一次认证
            //头像剪裁
            /**
             * SINA头像
             * http://tp2.sinaimg.cn/2422489825/180/5618794917/1 180*180
             * 新蓝头像尺寸
             * 298*298,200*200,70*70,50*50
             */
            $oauth_avatar = $this->saveAvatar($oauth_user_info['avatar_large']);
            $oauth_avatar = cdn_url('image', $oauth_avatar);

            $flag = $this->insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token);
            if ($flag) {
                //授权登录同时发送微博消息
                $title = '……';
                $url = 'http://tv.cztv.com';
                $content = '我在新蓝网发现很多好剧哦，' . $title . '，大家快来追剧吧，还有消息提醒呢~' . $url;
                $flag = $this->sina->post('statuses/update', array('access_token' => $sina_key['access_token'], 'status' => $content));
                //用户绑定
                $ssouid = $this->bind($type, $oauth_user_id, $nickname, $oauth_avatar, $from, $gender);
                if ($ssouid > 0) {
                    $userinfo = $userModel->getUserByID($ssouid);
                    $this->goLogin($userinfo, $type, 1, $from);
                } else {
                    exit("sina用户连接入库失败，sina维护中。。");
                }
            } else {
                exit("sina用户连接入库失败，sina维护中。。");
            }
        } else if (!empty($oplateUserInfo) && empty($oplateUserInfo['uid'])) {
            $ssouid = $this->bind($type, $oauth_user_id, $nickname, $oauth_avatar = '', $from, $gender);
            if ($ssouid > 0) {
                $userSocialsModel = new UserSocials();
                $bindflag = $userSocialsModel->bindOAuthUser($oauth_user_id, $type, $ssouid);
                $userinfo = $userModel->getUserByID($ssouid);
                $this->goLogin($userinfo, $type, 1, $from);
            } else {
                exit("sina用户连接入库失败，sina维护中。。");
            }
        } else {
            $this->loginOauth($oplateUserInfo['uid'], $type, $oauth_token, $oauth_user_id, $from);
        }

    }

    //===============================================QQ PC==============================================================
    /**
     * @desc QQ 初始化
     * @version 2015-06-04
     *
     */
    public function qqInit() {
        require_once APP_PATH . 'libraries/oauth/qq/qq.class.php';
        $this->qq = new QQ();
    }

    //QQ 第一步
    public function qqAction() {
        $this->qqInit();
        $from = $this->request->getQuery('from', null, '');
        $userModel = new User();
        $from = $userModel->handleURLWhitelist($from);
        $url = $this->qq->getAuthorizeURL($from);
        $this->response->redirect($url, true);
    }

    //QQ 第二步
    public function qqcallbackAction() {
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

        $oauth_token = $qq_key['access_token'];
        $oauth_user_id = $qq_key['openid'];

        //获取qq用户信息
        $oauth_user_info = $this->qq->getUserInfo($oauth_token, $oauth_user_id);
        if (empty($oauth_user_info)) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'qqcallback', array($oauth_user_id));
            $this->response->redirect(WEB_HOST . '/oauth/qq', true);
        }
        $gender = $oauth_user_info['gender'] == '女' ? 2 : ($oauth_user_info['gender'] == '男' ? 1 : 0);

        //开始绑定
        $qq['oauth_user_id'] = $oauth_user_id;
        $qq['oauth_user_email'] = '';
        $nickname = $this->handleOauthNickname('qq_', $oauth_user_id, $oauth_user_info['nickname']);
        $qq['oauth_user_nickname'] = $nickname;
        $this->session->set('qq', $qq);

        $userSocialsModel = new UserSocials();
        $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);

        if (empty($oplateUserInfo)) {//第一次认证
            $flag = $this->insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token, $qq_key['refresh_token']);
            if ($flag) {
                $oauth_avatar = $this->saveAvatar($oauth_user_info['figureurl_qq_2']);
                $oauth_avatar = cdn_url('image', $oauth_avatar);
                //用户绑定
                $ssouid = $this->bind($type, $oauth_user_id, $nickname, $oauth_avatar, $from, $gender);
                if ($ssouid > 0) {
                    //注册成功，添加分享说说
                    $title = '……';
                    $url = 'http://tv.cztv.com';
                    $comment = '我在新蓝网发现很多好剧哦' . $title . '，大家快来追剧吧，还有消息提醒呢~';
                    $rs = $this->qq->pushData(array('oauth_token' => $qq_key['access_token'], 'openid' => $qq_key['openid']), $title, $url, $comment, 101);
                    //用户登录
                    $userinfo = $userModel->getUserByID($ssouid);
                    $this->goLogin($userinfo, $type, 1, $from);
                } else {
                    exit("qq用户连接入库失败，qq维护中。。");
                }
            } else {
                exit("qq用户连接入库失败，qq维护中。。");
            }
        } else if (!empty($oplateUserInfo) && empty($oplateUserInfo['uid'])) {
            $ssouid = $this->bind($type, $oauth_user_id, $nickname, $oauth_avatar = '', $from, $gender);
            if ($ssouid > 0) {
                $bindflag = $userSocialsModel->bindOAuthUser($oauth_user_id, $type, $ssouid);
                $userinfo = $userModel->getUserByID($ssouid);
                $this->goLogin($userinfo, $type, 1, $from);
            } else {
                exit("qq用户连接入库失败，qq维护中。。");
            }
        } else {
            $this->loginOauth($oplateUserInfo['uid'], $type, $oauth_token, $oauth_user_id, $from);
        }
    }

//==============================================weixin PC===============================================================
    //weixin 初始化
    public function weixinInit() {
        require_once APP_PATH . 'libraries/oauth/weixin/weixin.class.php';
        $this->weixin = new Weixin();
    }

    //weixin 第一步
    public function weixinAction() {
        $this->weixinInit();
        $from = $this->request->getQuery('from', null, '');
        $userModel = new User();
        $from = $userModel->handleURLWhitelist($from);
        $url = $this->weixin->getAuthorizeURL($from);
        $this->response->redirect($url, true);
    }

    //weixin 第二步
    public function weixincallbackAction() {
        $type = 3;
        $this->weixinInit();
        $weixin_key = $this->weixin->callBack();
        $from = $this->request->getQuery('from', null, '');
        $userModel = new User();
        //跳到回第一步
        if (!is_array($weixin_key) or $weixin_key == false) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'weixincallback_empty_weixinkey', array());
            $this->response->redirect(WEB_HOST . '/oauth/weixin', true);
        }

        $oauth_token = $weixin_key['access_token'];

        //获取qq用户信息
        $oauth_user_info = $this->weixin->getUserInfo($oauth_token, $weixin_key['openid']);
        if (empty($oauth_user_info)) {
            $userModel->signSysLog('sso', 'oauthinfo_error', 'sso', 'weixincallback', array($weixin_key['openid']));
            $this->response->redirect(WEB_HOST . '/oauth/weixin', true);
        }
        //同一用户，对同一个微信开放平台下的不同应用，unionid是相同的
        $oauth_user_id = $oauth_user_info['unionid'];
        //开始绑定
        $weixin['oauth_user_id'] = $oauth_user_id;
        $weixin['oauth_user_email'] = '';
        $nickname = $this->handleOauthNickname('weixin_', $oauth_user_id, $oauth_user_info['nickname']);
        $weixin['oauth_user_nickname'] = $nickname;
        $this->session->set('weixin', $weixin);

        $userSocialsModel = new UserSocials();
        $oplateUserInfo = $userSocialsModel->getOAuthUser($oauth_user_id, $type);

        if (empty($oplateUserInfo)) {//第一次认证
            $flag = $this->insertUserConnect($oauth_user_id, $nickname, $type, $oauth_token, $weixin_key['refresh_token']);
            if ($flag) {
                $oauth_avatar = $this->saveAvatar($oauth_user_info['headimgurl']);
                $oauth_avatar = cdn_url('image', $oauth_avatar);
                //用户绑定
                $ssouid = $this->bind($type, $oauth_user_id, $nickname, $oauth_avatar, $from);
                if ($ssouid > 0) {
                    //用户登录
                    $userinfo = $userModel->getUserByID($ssouid);
                    $this->goLogin($userinfo, $type, 1, $from);
                } else {
                    exit("微信用户连接入库失败，微信维护中。。");
                }
            } else {
                exit("微信用户连接入库失败，微信维护中。。");
            }
        } else if (!empty($oplateUserInfo) && empty($oplateUserInfo['uid'])) {
            $ssouid = $this->bind($type, $oauth_user_id, $nickname, $oauth_avatar = '', $from);
            if ($ssouid > 0) {
                $bindflag = $this->model('Oauth')->bindOAuthUser($oauth_user_id, $type, $ssouid);
                $userinfo = $this->model('User')->getUserByID($ssouid);
                $this->goLogin($userinfo, $type, 1, $from);
            } else {
                exit("微信用户连接入库失败，微信维护中。。");
            }
        } else {
            $this->loginOauth($oplateUserInfo['uid'], $type, $oauth_token, $oauth_user_id, $from, $weixin_key['refresh_token']);
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
					window.opener.User.loginSuccess();
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
}