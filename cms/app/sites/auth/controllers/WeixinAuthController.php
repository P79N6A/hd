<?php

/**
 * Created by PhpStorm.
 * User: fang
 * Date: 2016/8/18
 * Time: 15:38
 */
class WeixinAuthController extends WeiXinBaseController {

    public function initialize()
    {
        parent::initialize();
        $this->crossDomain();
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

        $root_domain = "";
        if (!empty($host)) {
            $root_domain = $this->getUrlToDomain($host);
        }
        //跨域白名单
        $domains = array(
            "cztv.com",
            "cztvcloud.com",
            "xianghunet.com",
            "szttkk.com",
            "zjbtv.com",
            "sybtv.com",
            "txnews.com.cn",
            "qz123.com",
            "zjxcw.com",
            "yysee.net",
            "cncico.com"
        );
        if (in_array($root_domain, $domains)) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

    }

    /**
     * 取得根域名
     * @param type $domain 域名
     * @return string 返回根域名
     */
    protected function getUrlToDomain($domain)
    {
        $re_domain = '';
        $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
        $array_domain = explode(".", $domain);
        $array_num = count($array_domain) - 1;
        if(!$array_num){
            return "";
        }
        if ($array_domain[$array_num] == 'cn') {
            if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
                $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            } else {
                $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            }
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
        return $re_domain;
    }

    /**
     * 授权接口
     */
    public function authUserInfoAction(){
        $data_id = Request::getQuery('data_id', 'int');
        $callBackUrl = Request::getQuery('callbackurl');
        $authUserInfo = RedisIO::get("authUserInfo:" . $data_id);

        if($authUserInfo){
            $data['oauth'] = 1;
            $data['url'] = "http://ssohudong.cztv.com/weixin_auth/index?data_id={$data_id}&callbackurl=" . urldecode($callBackUrl);
        } else {
            $data['oauth'] = 0;
            $data['url'] = "";
        }
        $this->_json($data);
    }

    /**
     * 关注接口
     */
    public function subscribeAction(){
        $data_id = Request::getQuery('data_id', 'int');
        $subscribe = RedisIO::get("subscribe:" . $data_id);
        $this->_json($subscribe);
    }

    /**
     * 获取用户是否关注
     */
    public function getUserInfoAction()
    {
        $data_id = Request::getQuery('data_id', 'int');
        $token = Request::getQuery('token','string');
        
        if(RedisIO::exists($token)){
            $userinfo = json_decode(RedisIO::get($token),true);
            $openId = $userinfo['openid'];
        } else {
            $this->_json([],4009,'invalid token');
        }

        //是否关注
        $isSubscribe = $this->isSubscribe($openId);
        //关注地址
        $isSubUrl = RedisIO::get("isSubUrl:" . $data_id);
        //未关注地址
        $noSubUrl = RedisIO::get("noSubUrl:" . $data_id);
        //投票接口限制微信时使用，统一登入完成前的临时方案
        if(!RedisIO::get('interaction::vote::upwork::' . $openId)) {
            RedisIO::set('interaction::vote::upwork::' . $openId, 1);
        }
        //构建返回值
        $data = array(
            'isSubscribe'=>$isSubscribe,
            'isSubUrl'=>$isSubUrl,
            'noSubUrl'=>$noSubUrl,
            'userinfo'=>$userinfo

        );
        $this->_json($data);
    }


    /**
     * 授权首页
     */
    public function indexAction(){
        $data_id = Request::getQuery('data_id', 'int');
        if(!isset($data_id)){
            echo "data_id no empty!";
            exit;
        }
        $callBackUrl = Request::getQuery('callbackurl');

        $redirect_url = $this->callback . "/weixin_auth/index?channel_id=".$this->channel_id."&data_id={$data_id}&callbackurl=" . $callBackUrl;

        $authInfo = $this->_authUserInfo($redirect_url);

        $userinfo = $this->getUserInfo($authInfo);

        if($callBackUrl){
            $url = $callBackUrl;
        } else {
            $url = RedisIO::get("isSubUrl:" . $data_id);
        }

        //生成token
        $token = md5($userinfo['openid'] . time());
        //设置token有效期
        RedisIO::set($token,json_encode($userinfo),7000);

        header('Location:' . $url . "?data_id={$data_id}&token={$token}");
        exit;

    }



    /**
     * 用户授权备用
     */
    public function authuserAction()
    {
        //煤资ID
        $data_id = Request::getQuery('data_id', 'int');
        //回调地址
        $redirect_url = $this->callback . '/weixin_auth/index?';
        //是否要授权
        $authUserInfo = RedisIO::get("authUserInfo:" . $data_id);
        //是否要关注
        $subscribe = RedisIO::get("subscribe:" . $data_id);
        //关注地址
        $isSubCallBackUrl = RedisIO::get('callback_is_url:' . $data_id);
        //非关注地址
        $noSubCallBackUrl = RedisIO::get('callback_no_url:' . $data_id);
        //授权逻辑
        if ($authUserInfo) {
            //获取用户授权信息
            $authInfo = $this->_authUserInfo($redirect_url);
            //获取到授权
            if($authInfo) {
                //是否要关注选项
                if($subscribe){
                    $openid = $authInfo['openid'];
                    $isSubscribe = $this->isSubscribe($openid);
                    if($isSubscribe){
                        $url = $isSubCallBackUrl;

                    } else {
                        $url = $noSubCallBackUrl;

                    }

                } else {
                    $url = $isSubCallBackUrl;
                }
                header('Location:' . $url);
                exit;

            } else {
                echo "get authInfo error";
            }
        } else {
            $url = $noSubCallBackUrl;
            header('Location:' . $url);
            exit;
        }


    }

    /**
     * 获取openId
     */
    private function _openId($redirect_url)
    {
        $code = Request::getQuery('code', 'string');
        //通过code获取openID
        if (!$code) {
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_base&state=' . time() . '#wechat_redirect';
            header('Location:' . $url);
            exit;
        } else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
            //TODO 统一外网服务接口
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true); //转为数组
            return $userInfo['openid'];
        }
    }


    /**
     * 通过code获取openid
     */
    public function getOpenIdByCode()
    {
        $code = Request::getQuery('code', 'string');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
        //TODO 统一外网服务接口
        $authInfo = F::curlRequest($url);
        $authInfo = json_decode($authInfo, true); //转为数组
        return $authInfo;
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


    /**
     * 关注授权作用域
     */
    protected function _authBase_bak($redirect_url)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_base&state=' . time() . '#wechat_redirect';
        header('Location:' . $url);
        exit;
    }

    /**
     * 授权作用域
     */
    protected function _authUserInfo_bak($redirect_url)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_userinfo&state=' . time() . '#wechat_redirect';
        header('Location:' . $url);
        exit;
    }


    /**
     * 授权作用域userinfo
     */
    private function _authUserInfo($redirect_url)
    {
        $code = Request::getQuery('code', 'string');
        //通过code获取openID
        if (!$code) {
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_userinfo&state=' . time() . '#wechat_redirect';
            header('Location:' . $url);
            exit;
        } else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
            //TODO 统一外网服务接口
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true); //转为数组
            return $userInfo;
        }
    }

    /**
     * 授权作用域userbase
     */
    private function _authUserBase($redirect_url)
    {
        $code = Request::getQuery('code', 'string');
        //通过code获取openID
        if (!$code) {
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->app_id . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&scope=snsapi_base&state=' . time() . '#wechat_redirect';
            header('Location:' . $url);
            exit;
        } else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->app_id . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
            //TODO 统一外网服务接口
            $userInfo = F::curlRequest($url);
            $userInfo = json_decode($userInfo, true); //转为数组
            return $userInfo['openid'];
        }
    }

    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = "success")
    {
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }




}
