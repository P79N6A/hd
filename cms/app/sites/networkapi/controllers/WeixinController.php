<?php

/**
 * @RoutePrefix("/weixin")
 */
class WeixinController extends ApiBaseController
{
    public $cache_key = 'H5_CACHE';      //token键名
    protected $app_id;
    protected $app_secret;
    protected $callback;

    public function initialize()
    {
        parent::initialize();
        //数据库读取微信配置参数
        $zgltv_weixin = Setting::getByChannel(LETV_CHANNEL_ID, 'zgltv_wechat');
        $this->app_id = $zgltv_weixin['app_id'];
        $this->app_secret = $zgltv_weixin['app_secret'];
        $this->callback = $zgltv_weixin['callback'];

    }

    /**
     * @Get("/token")
     */
    public function getTokenAction()
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
        echo $accessToken;
        exit;
    }


}

?>