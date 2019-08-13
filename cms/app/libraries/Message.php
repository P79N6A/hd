<?php

class Message {
    //发送短信验证码平台
    const PLATFORM = "open.189.cn";
    //短信模板ID
    const TEMPLATE_ID_COMMON = "91004089";
    const TEMPLATE_DGS_ID_COMMON = "91550748";
    const TEMPLATE_BLIND_ID_COMMON = "91551852";
    const TEMPLATE_NOTICE_ID_COMMON = "91552039";
    const TEMPLATE_YearTaste_ID_COMMON = "91552232";

    //天翼平台accesstoken接口地址
    private $_url_access_token = "https://oauth.api.189.cn/emp/oauth2/v3/access_token";
    //天翼平台发送短信接口地址
    private $_url_send_message = "http://api.189.cn/v2/emp/templateSms/sendSms";
    private $_app_id = "398350640000043874";
    private $_app_secret = "681926f6c1d626be7f28ad0e4968edb9";
    //短信有效期，单位为分钟
    private $_expired;
    //短信验证码
    private $_code;
    //短信验证码长度
    private $_code_length;
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
    public function __construct($phone, $expired = 30, $code_length = 4) {
        $this->setPhone($phone);
        intval($expired) ? ($this->_expired = $expired) : ($this->setError("expired error"));
        intval($code_length) ? ($this->_code_length = $code_length) : ($this->setError("code_length error"));
        $this->setCode();
    }

    private function setPhone($phone) {
        if (preg_match('/^(1[0-9]{10})$/', $phone)) {
            $this->_phone = $phone;
        } else {
            $this->setError("phone error");
        }
    }

    private function setCode() {
        $code = "";
        for ($i = 0; $i < $this->_code_length; $i++) {
            $code .= $this->_charset[rand(0, 9)];
        }
        $this->_code = $code;
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
        $token_data = 'grant_type=client_credentials&app_id=' . $this->_app_id . '&app_secret=' . $this->_app_secret;
        $token = json_decode($this->curl_post($this->_url_access_token, $token_data), true);
        if (!$token || $token['res_code'] != 0) {
            $this->setError("get access token failed");
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

    public function sendDGSMessage() {
        $this->_template_id = self::TEMPLATE_DGS_ID_COMMON;
        $template_param = array();
        $template_param['param1'] = strval($this->_code);
        return $this->sendMessage($template_param);
    }

    public function sendBlindMessage($param1, $param2 ,$param3 ,$param4) {
        $this->_template_id = self::TEMPLATE_BLIND_ID_COMMON;
        $template_param = array();
        $template_param['param1'] = strval($param1);
        $template_param['param2'] = strval($param2);
        $template_param['param3'] = strval($param3);
        $template_param['param4'] = strval($param4);
        return $this->sendMessage($template_param);
    }

    public function sendNoticeMessage() {
        $this->_template_id = self::TEMPLATE_NOTICE_ID_COMMON;
        $template_param = array();

        return $this->sendMessage($template_param);
    }

    public function sendYearTasteMessage() {
        $this->_template_id = self::TEMPLATE_YearTaste_ID_COMMON;
        $template_param = array();

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
        $params['app_id'] = 'app_id=' . $this->_app_id;
        $params['access_token'] = 'access_token=' . $access_token;
        $params['acceptor_tel'] = 'acceptor_tel=' . $this->_phone;
        $params['template_id'] = 'template_id=' . $this->_template_id;
		if(count($template_param)) {		
            $params['template_param'] = 'template_param=' . json_encode($template_param);
		}
		else {
            $params['template_param'] = 'template_param={}';
		}
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
        return urlencode(base64_encode(hash_hmac('sha1', $params_str, $this->_app_secret, $raw_output = true)));
    }

    private function curl_post($url, $args) {
//        try {
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
//            $data = curl_exec($ch);
//            curl_close($ch);
//        } catch (Exception $ex) {
//            throw($ex);
//        }
        //TODO 统一外网服务接口
        $data = F::curlRequest($url, 'post', $args);
        return $data;
    }

    /**
     * @function 异常信息抛出
     * @author    应灵伟
     * @param string $error 异常信息
     * @version   1.0.0
     */
    private function setError($error) {
        throw new Exception($error);
    }

    /**
     * @function 发送验证码
     * @param $phonenumber
     * @return bool|string
     */
    public static function sendCode($phonenumber) {
        try {
            $codeMessage = new self($phonenumber);
            $code = $codeMessage->getCode();
            $rs = $codeMessage->sendCommonMessage();
            if (strtolower($rs['res_message']) == 'success') {
                return $code;
            } else {
                return '';
            }
        } catch (Exception $ex) {
            return '';
        }
    }

    public static function sendCodeDGS($phonenumber) {
        try {
            $codeMessage = new self($phonenumber, 30, 4);
            $code = $codeMessage->getCode();
            $rs = $codeMessage->sendDGSMessage();
            if (strtolower($rs['res_message']) == 'success') {
                return $code;
            } else {
                return '';
            }
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * @function 发送交友大会短信
     * @param $phonenumber
     * @return bool|string
     */
    public static function sendCodeBlind($phonenumber,$number,$arr) {
        try {
            $codeMessage = new self($phonenumber);
            if(isset($arr)&&!empty($arr)) {
                $rs = $codeMessage->sendBlindMessage($arr[0], $arr[1], $arr[2], $number . '。');
                if (strtolower($rs['res_message']) == 'success') {
                    return 'success';
                } else {
                    return '';
                }
            }
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * @function 20161108集团通知，模板已固定
     * @param $phonenumber
     * @return bool|string
     */
    public static function sendCodeNotice($phonenumber) {
        try {
            $codeMessage = new self($phonenumber);
            $rs = $codeMessage->sendNoticeMessage();
            if (strtolower($rs['res_message']) == 'success') {
                return 'success';
            } else {
                return '';
            }
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * @function 20170113年味儿活动通知，模板已固定
     * @param $phonenumber
     * @return bool|string
     */
    public static function sendYearTasteNotice($phonenumber) {
        try {
            $codeMessage = new self($phonenumber);
            $rs = $codeMessage->sendYearTasteMessage();
            if (strtolower($rs['res_message']) == 'success') {
                return 'success';
            } else {
                return '';
            }
        } catch (Exception $ex) {
            return '';
        }
    }
}