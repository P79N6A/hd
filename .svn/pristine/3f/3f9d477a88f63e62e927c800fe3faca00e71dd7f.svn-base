<?php

namespace GenialCloud\Helper;

class CnMessage {

    // 需配置重要参数
    protected $appId = "120369370000035989";
    protected $appSecret = "77e79f1927545c8ed5003ce639b6f830";
    protected $hashLen = 4;

    //发送短信验证码平台
    const PLATFORM = "open.189.cn";
    //短信模板ID
    const TEMPLATE_ID_COMMON = "91004089";
    //天翼平台accesstoken接口地址
    private $_url_access_token = "https://oauth.api.189.cn/emp/oauth2/v3/access_token";
    //天翼平台发送短信接口地址
    private $_url_send_message = "http://api.189.cn/v2/emp/templateSms/sendSms";
    //短信有效期，单位为分钟
    private $_expired = 30;
    //短信验证码
    private $_code;
    //短信验证码长度
    //短信验证码字符集
    private $_charset = "0123456789";
    //短信模板ID
    private $_template_id;
    //手机号码
    private $_phone;

    /**
     * @function 构造方法
     * @author    应灵伟
     * @param string $phone 发送验证码的手机号码
     * @param int $expired 短信有效期，默认30分钟
     * @param int $code_length 验证码长度，默认6位
     * @version   1.0.0
     */
    public function __construct(array $config) {
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    private function setPhone($phone) {
        if (preg_match('/^(1[0-9]{10})$/', $phone)) {
            $this->_phone = $phone;
        } else {
            $this->setError(400, "Mobile Number Error");
        }
    }

    private function setCode() {
        $this->_code = num_random($this->hashLen);
        return $this->_code;
    }

    public function getCode() {
        return strval($this->_code);
    }

    /**
     * @function 获取发送短信的access_token
     * @author    应灵伟
     * @version   1.0.0
     */
    private function getAccessToken() {
        $token_data = 'grant_type=client_credentials&app_id=' . $this->appId . '&app_secret=' . $this->appSecret;
        $token = json_decode($this->curl_post($this->_url_access_token, $token_data), true);
        if (!$token || $token['res_code'] != 0) {
            $this->setError(400,"Access Token Failed");
        }
        return $token['access_token'];
    }

    /**
     * @function 发送模板为CodeMessagePlugin::TEMPLATE_ID_COMMON验证码短信
     * @author    应灵伟
     * @version   1.0.0
     */
    public function sendCommonMessage() {
        $this->_template_id = self::TEMPLATE_ID_COMMON;
        $template_param = array();
        $template_param['param1'] = strval($this->_code);
        $template_param['param2'] = strval($this->_expired);

        return $this->sendMessage($template_param);
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
        $params['app_id'] = 'app_id=' . $this->appId;
        $params['access_token'] = 'access_token=' . $access_token;
        $params['acceptor_tel'] = 'acceptor_tel=' . $this->_phone;
        $params['template_id'] = 'template_id=' . $this->_template_id;

        $params['template_param'] = 'template_param=' . json_encode($template_param);
        $params['timestamp'] = 'timestamp=' . $timestamp;
        ksort($params);
        $params_str = join('&', $params);
        $sign = $this->getSign($params_str);
        $msg_data = $params_str . '&sign=' . $sign;
        $json = $this->curl_post($this->_url_send_message, $msg_data);
        return json_decode($json, true);
    }

    /**
     * @function 获取签名算法值
     * @author    应灵伟
     * @version   1.0.0
     */
    private function getSign($params_str) {
        return urlencode(base64_encode(hash_hmac('sha1', $params_str, $this->appSecret, $raw_output = true)));
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
     * @function 异常信息抛出
     * @author    应灵伟
     * @param string $error 异常信息
     * @version   1.0.0
     */
    private function setError($code, $msg) {
       throw new Exception($msg);
    }

    /**
     * @function 发送验证码
     * @param $mobile
     * @return bool|string
     */
    public  function sendCode($mobile) {
        try {
            $this->setPhone($mobile);
            $code = $this->setCode();
            $this->sendCommonMessage();
            return $code;
        } catch (Exception $ex) {
            return $ex;
        }
    }

}
