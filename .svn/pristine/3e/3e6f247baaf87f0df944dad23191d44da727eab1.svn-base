<?php

class HfiveController extends \PublishBaseController {

	public $appid = 'wxb80bcbf68dd70ce6';
	public $app_secret = 'f320b683b1689e443915842d3a18d055';
	public $cookie_key = 'H5_USERINFO';
	public $cache_key = 'H5_CACHE';

	public function luhanAction(){

		
	    
	    $h5_userinfo_json =  Cookie::get($this->cookie_key);
	    $h5_userinfo = json_decode($h5_userinfo_json, true);
		$code = Request::get('code');
		if(!$code){

			$r = $this->router;
			$controller = $r->getControllerName();
			$action = $r->getActionName();

	        $redirect_url = Request::getHttpHost() . '/' . $controller . '/' . $action;
	        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
	        $url .= $this->appid;
	        $url .= '&redirect_uri=';
	        $url .= urlencode($redirect_url);
	        $url .= '&response_type=code&scope=snsapi_base&state=';
	        $url .= time();
	        $url .= '#wechat_redirect';
        	header('Location:' . $url);
    	}

    	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
	    $userInfo = json_decode(file_get_contents($url), true);
	    $openid = isset($userInfo['openid']) ? $userInfo['openid'] : '';


        //是否展现正常内容
	    $showFlag = false;
	    if ($h5_userinfo && $h5_userinfo['openid'] == $openid && $h5_userinfo['subscribe'] == 1) {
	        $showFlag = true;
	    } else if ($code) {
	        //获取access_token
            $urlAccessToken = 'http://192.168.138.36:8080/applet-inner-api/inner/getPublicAccessToken';
            $jsonAccessToken = F::curlRequest($urlAccessToken);
            $dataAccessToken = json_decode($jsonAccessToken, true);
            if ($dataAccessToken['code'] == 200) {
                $accessToken = $dataAccessToken['data']['accessToken'];
            } else {
                error_log('获取accesstoken失败:'.$dataAccessToken['msg']);
                exit;
            }
	        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
	        $h5_userinfo_json = json_decode(file_get_contents($url), true);
	        if (!isset($h5_userinfo_json['subscribe'])) {
	            if ($h5_userinfo_json['errcode'] == 40003) {
	                $showFlag = false;
	            } else {
	                $showFlag = true;
	            }
	        } else if ($h5_userinfo_json['subscribe'] == 1) {
	            $h5_userinfo = array(
	                'subscribe' => $h5_userinfo_json['subscribe'],
	                'openid' => $h5_userinfo_json['openid']
	            );
	            Cookie::set($this->cookie_key, json_encode($cbtvInfo), time() + 300, '/', 'cztv.com' );
        		Cookie::send();
	            $showFlag = true;
	        }
	    }

        View::setVar('showFlag',$showFlag);
    }


}