<?php

/**
 * 微信基础类v1.0
 * @author fang
 *
 */
const APP_ID = 'wxb80bcbf68dd70ce6';
const APP_SECRET = 'f320b683b1689e443915842d3a18d055';

class WeiXinBaseController extends BaseController{
	
	public $cookie_key = 'H5_USERINFO';  //用户信息
	public $cache_key = 'H5_CACHE';      //token键名
	
	/**
	 * 获取微信ToKen
	 * @return Ambigous <boolean, mixed>
	 */
	public function getAccessToKen(){
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
	 * @param unknown $openid
	 * @return boolean
	 */
	public function isSubscribe($openid){
		$accessToken = $this->getAccessToKen();
		//TODO 统一外网服务接口
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
//		$cbtvInfoJson = file_get_contents($url);
		$cbtvInfoJson = F::curlRequest($url);
		$cbtvInfoJson = json_decode($cbtvInfoJson,true);
		if (!isset($cbtvInfoJson['subscribe'])) {
			if ($cbtvInfoJson['errcode'] == 40003) {
				$showFlag = false;
			}else if($cbtvInfoJson['errcode'] == 40001){  //如果token过期了刷新
				$this->updateToKen();
				$showFlag = false;	
			} else {
				$showFlag = true;
			}
		} else if ($cbtvInfoJson['subscribe'] == 1) {
			$cbtvInfo = array(
					'subscribe' => $cbtvInfoJson['subscribe'],
					'openid' => $cbtvInfoJson['openid']
			);
			setcookie($this->cookie_key, json_encode($cbtvInfo), time() + 600, '/', 'cztv.com');
			$showFlag = true;
		}
		return $showFlag;
	}
	
	
	/**
	 * 强制更新token
	 */
	public function updateToKen(){
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
	 * 代理curl
	 * @param unknown $url
	 * @param unknown $data
	 * @return mixed
	 */
	public function curl_login($url,$data){
		$login = curl_init();
		curl_setopt($login, CURLOPT_TIMEOUT, 30);
		curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
	
		curl_setopt($login, CURLOPT_URL, $url);
		curl_setopt($login, CURLOPT_POST, TRUE);
		curl_setopt($login, CURLOPT_POSTFIELDS, $data);
	
		curl_setopt($login, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($login, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		ob_start();
		return curl_exec ($login);
		ob_end_clean();
		curl_close ($login);
		unset($login);
	}
	
	/**
	 * 设置默认站点
	 * (non-PHPdoc)
	 * @see BaseController::defaultDomainCheck()
	 */
    protected function defaultDomainCheck($host) {
		$this->domain_id =6;
		$this->channel_id = 1;
        return true;
    }

	
	/**
	 * 获取测试
	 */
	public function testAction(){       
		echo "is test";
	}
	
		
	
}




