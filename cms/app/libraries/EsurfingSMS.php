<?php

class EsurfingSMS extends SMS {
    //发送短信验证码平台
    const PLATFORM = "open.189.cn";
    //短信模板ID
    const TEMPLATE_ID_COMMON = "91004089";
    const TEMPLATE_DGS_ID_COMMON = "91550748";

    //天翼平台accesstoken接口地址
    private $_url_access_token = "https://oauth.api.189.cn/emp/oauth2/v3/access_token";
    //天翼平台发送短信接口地址
    private $_url_send_message = "http://api.189.cn/v2/emp/templateSms/sendSms";
    private $_app_id = "398350640000043874";
    private $_app_secret = "681926f6c1d626be7f28ad0e4968edb9";
    private $_tempid;
    private $_param;


    public function __construct($phone, $body, $suffix, array $confgparams) {
        parent::__construct($phone, $body, $suffix, $confgparams);
        $this->setConfigInfo($confgparams);
    }

    private function setConfigInfo($confparams) {
        if (array_key_exists('appid', $confparams) &&
            array_key_exists('appsecrect', $confparams) &&
            array_key_exists('tempid', $confparams)
        ) {
            $this->_app_id = $confparams['appid'];
            $this->_app_secret = $confparams['appsecrect'];
            $this->_tempid = $confparams['tempid'];
        }
    }

    public function setParam($arrparam) {
        if (is_array($arrparam)) {
            $this->_param = $arrparam;
        }
    }


    public function SendSms() {
        return $this->sendMessage($this->_param);
    }


    /**
     * @function 获取发送短信的access_token
     * @author    应灵伟
     * @version   1.0.0
     */
    private function getAccessToken() {
        $token_data = 'grant_type=client_credentials&app_id=' . $this->_app_id . '&app_secret=' . $this->_app_secret;
        $token = json_decode($this->curl_post($this->_url_access_token, $token_data), true);
        if (!$token || $token['res_code'] != 0) {
            $this->setError("get access token failed");
        }
        return $token['access_token'];
    }

    /**
     * @function 请求发送短信
     * @param array $template_param 短信模板中定义好的参数数组
     * @author    应灵伟
     * @version   1.0.0
     */
    private function sendMessage($template_param) {
        $access_token = $this->getAccessToken();
        $timestamp = date('Y-m-j G:i:s');
        $params = array();
        $params['app_id'] = 'app_id=' . $this->_app_id;
        $params['access_token'] = 'access_token=' . $access_token;
        $params['acceptor_tel'] = 'acceptor_tel=' . $this->getPhone();
        $params['template_id'] = 'template_id=' . $this->_tempid;
        $params['template_param'] = 'template_param=' . json_encode($template_param);
        $params['timestamp'] = 'timestamp=' . $timestamp;
        ksort($params);
        $params_str = join('&', $params);
        $sign = $this->getSign($params_str);
        $msg_data = $params_str . '&sign=' . $sign;
        $json = $this->curl_post($this->_url_send_message, $msg_data);
        return json_decode($json, true);
    }


    private function curl_post($url, $args) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            $data = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $ex) {
            throw($ex);
        }
        return $data;
    }

    /**
     * @function 获取签名算法值
     * @author    应灵伟
     * @version   1.0.0
     */
    private function getSign($params_str) {
        return urlencode(base64_encode(hash_hmac('sha1', $params_str, $this->_app_secret, $raw_output = true)));
    }

}