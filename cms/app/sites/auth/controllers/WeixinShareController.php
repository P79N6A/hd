<?php

/*
 * Created by NetBeans IDE
 * User: JCL
 * Date: 2016/10/26
 * Time: 9:00
 */
class WeixinShareController extends WeiXinBaseController {

    const JSAPI_TICKET_KEY = "weixin_jsapi_key";

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
  
    /*
     * 
     *分享函数 转门提供H5用
     *       */

    function shareAction() {
                
		$callback = !empty(Request::getQuery('callback')) ? Request::getQuery('callback'):"";
                
		$callback = preg_match("/^[a-zA-Z0-9_?-]+$/", $callback) ? $callback : "";
		$url = !empty(Request::getQuery('url'))?strip_tags(trim(Request::getQuery('url'))):"";
		
		if($callback){
			$ret = $this->getSignPackage($url);
			$ret['success'] = true;
			echo $callback.'('.json_encode($ret).')';
			exit;
		}
		echo $callback.'('.json_encode(array('success'=>false)).')';
		exit;
    }
        
    public function getSignPackage($url) {
		$jsapiTicket = $this->getJsApiTicket(); 
                
                $timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId" => $this->app_id,
			"nonceStr" => $nonceStr,
			"timestamp" => $timestamp,
			"url" => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
    }
    
    private function getJsApiTicket() {
		// jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
		$data =json_decode(RedisIO::get(self::JSAPI_TICKET_KEY.$this->app_id));
		if ($data->expire_time < time()) {
                    //getAccessToKen
                    $accessToken = parent::getAccessToKen();
                    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                    $res = json_decode(F::curlRequest($url));
                    $ticket = $res->ticket;
                    if ($ticket) {
			$data->expire_time = time() + 7000;
			$data->jsapi_ticket = $ticket;
			RedisIO::set(self::JSAPI_TICKET_KEY.$this->app_id, json_encode($data));
			}
		} else {
			$ticket = $data->jsapi_ticket;
		}

		return $ticket;
    }
    
    private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
    }
    
    



}