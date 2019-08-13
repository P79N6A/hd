<?php

/**
 * 微信基础类v1.0
 * @author fang
 *
 */
class WeiXinBaseController extends BaseController
{

    protected $cookie_key = 'H5_USERINFO';  //用户信息
    protected $cache_key = 'weiXinAccessToken';      //token键名
    protected $app_id;
    protected $app_secret;
    protected $callback;
    protected $lh_url;
    protected $lh_url_unsubscribe;
    protected $channel_id;

    public function initialize()
    {
        parent::initialize();
        $this->channel_id = intval(Request::getQuery("channel_id",'int',LETV_CHANNEL_ID));
        $this->checkChannel($this->channel_id);
        $zgltv_weixin = Setting::getByChannel($this->channel_id, 'zgltv_wechat');

        $this->app_id = $zgltv_weixin['app_id'];
        $this->app_secret = $zgltv_weixin['app_secret'];
        $this->callback = $zgltv_weixin['callback'];
        $this->lh_url = $zgltv_weixin['lh_url'];
        $this->lh_url_unsubscribe = $zgltv_weixin['lh_url_unsubscribe'];

    }

    protected function checkChannel($channelId) {
        $channel_info = Channel::getOneChannel($channelId);
        if(!($channel_info&&$channel_info->status==1)) {
            $resp = json_encode(array('code' => 403, 'msg' => "Site Forbidden", 'data' => 'Not Found'));
            if ($callback = Request::get('callback')) {
                echo htmlspecialchars($callback) . "({$resp});";
            } else {
                echo $resp;
            }
            exit;
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
     * 判断是否关注
     * @param string $openid
     * @return boolean
     */
    protected function isSubscribe($openid)
    {
        $accessToken = $this->getAccessToKen();
        if ($accessToken) {
            //TODO 统一外网服务接口
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
            $cbtvInfoJson = F::curlRequest($url);
            $cbtvInfoJson = json_decode($cbtvInfoJson, true);

            if (isset($cbtvInfoJson['subscribe'])) {
                return $cbtvInfoJson['subscribe'];
            } else if( 40001 == $cbtvInfoJson['errcode']){
                $this->updateToKen();
            } else {
                $this->_json($cbtvInfoJson);
            }
        } else {
            $this->_json([], 4007, "accessToken false");
        }
    }


    /**
     * 强制更新token
     */
    protected function updateToKen()
    {
        //TODO 统一外网服务接口
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
     * 备用
     */
    public function updateTokenAction(){
        $accessToken = $this->updateToKen();
        var_dump($accessToken);
    }

    /**
     * 代理curl
     * @param unknown $url
     * @param unknown $data
     * @return mixed
     */
    public function curl_login($url, $data)
    {
        $login = curl_init();
        curl_setopt($login, CURLOPT_TIMEOUT, 30);
        curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($login, CURLOPT_URL, $url);
        curl_setopt($login, CURLOPT_POST, TRUE);
        curl_setopt($login, CURLOPT_POSTFIELDS, $data);

        curl_setopt($login, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($login, CURLOPT_SSL_VERIFYHOST, FALSE);

        ob_start();
        return curl_exec($login);
        ob_end_clean();
        curl_close($login);
        unset($login);
    }

    /**
     * 设置默认站点
     * (non-PHPdoc)
     * @see BaseController::defaultDomainCheck()
     */
    protected function defaultDomainCheck($host)
    {
        $this->domain_id = 6;
        $this->channel_id = 1;
        return true;
    }


}




