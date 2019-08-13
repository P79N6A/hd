<?php

class SMS {
    //短信验证码
    private $_code;
    //短信验证码长度
    private $_code_length;
    //短信验证码字符集
    private $_charset = "0123456789";
    //手机号码
    private $_phone;
    //发送主体
    private $_body;
    //尾缀
    private $_suffix;

    public function __construct($phone, $body, $suffix = '', $confgparams = array()) {
        $this->setPhone($phone);
        $this->setBody($body);
        $this->setSuffix($suffix);
        $this->setcode(4);
    }

    private function setBody($body) {
        $this->_body = $body;
    }

    protected function getBody() {
        return $this->_body;
    }

    private function setSuffix($suffix) {
        $this->_suffix = $suffix;
    }

    protected function getSuffix() {
        return $this->_suffix;
    }

    private function setPhone($phone) {
        if (preg_match('/^(1[0-9]{10})$/', $phone)) {
            $this->_phone = $phone;
        } else {
            $this->setError("phone error");
        }
    }

    private function setCode($codelength = 4) {
        $this->setCodeLength($codelength);
        $code = "";
        for ($i = 0; $i < $this->_code_length; $i++) {
            $code .= $this->_charset[rand(0, 9)];
        }
        $this->_code = $code;
    }

    private function setCodeLength($codelength) {
        $this->_code_length = intval($codelength) ? intval($codelength) : 4;

    }

    public function getCode() {
        return strval($this->_code);
    }

    protected function getPhone() {
        return strval($this->_phone);
    }

    protected function setError($error) {
        throw new Exception($error);
    }


    public static function generateCode($codelength = 4) {
        $code = "";
        $charsets = "0123456789";
        for ($i = 0; $i < $codelength; $i++) {
            $code .= $charsets[rand(0, 9)];
        }
        return $code;
    }

}