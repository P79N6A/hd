<?php

/*
 Author: liangyunfei
 Email: liangyunfei@yfcloud.com
 */

!defined('AUTH_DEBUG') and define('AUTH_DEBUG', false);

class YfAuth {

    private $access_key;
    private $secrect_key;

    public function __construct($access_key, $secrect_key) {
        $this->access_key = $access_key;
        $this->secrect_key = $secrect_key;
    }

    //加密函数
    public function encrypt($unsigned_data) {
        $signed_data = hash_hmac('sha1', $unsigned_data, $this->secrect_key);
		$this->_debug('HmacSHA1签名字符串', $unsigned_data);
        $signed_data = base64_encode($signed_data);
		$this->_debug('Base64编码字符串', $unsigned_data);
        return $signed_data;
    }

    //获取AccessToken
    public function getAccessToken($url='', $body=array()) {
        if (empty($body)) {
            $unsigned_data = $url . "\n";
        } else {
            $body = json_encode($body, JSON_UNESCAPED_SLASHES);
            $unsigned_data = implode("\n", array($url, $body));
        }
		
		$this->_debug('待签名的字符串', $unsigned_data);
        $token = $this->encrypt($unsigned_data);
        return implode(":", array($this->access_key, $token));
    }
	
	//debug
	private function _debug($k, $v) {
		if (AUTH_DEBUG) {
			echo "[AUTH DEBUG] {$k}: {$v} \r\n";
		}
	}
}
