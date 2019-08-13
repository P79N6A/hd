<?php

class NeteaseSMS extends SMS {
    private $_appkey;
    private $_appsecrect;
    private $_tempid;
    private $_bodyparams;

    public function __construct($phone, $body, $suffix, array $confgparams) {
        parent::__construct($phone, $body, $suffix, $confgparams);
        $this->setConfigInfo($confgparams);
    }

    private function setConfigInfo($params) {
        if (array_key_exists('appkey', $params) &&
            array_key_exists('appsecret', $params) &&
            array_key_exists('tempid', $params) &&
            array_key_exists('bodyparams', $params) &&
            is_array($params['bodyparams'])
        ) {
            $this->_appkey = $params['appkey'];
            $this->_appsecrect = $params['appsecret'];
            $this->_tempid = intval($params['tempid']);
            $this->_bodyparams = $params['bodyparams'];
        }

    }


    public function SendSms() {
        $api = new NeteaseServerAPI($this->_appkey, $this->_appsecrect);
        $phones = array($this->getPhone());
        return $api->sendSMSTemplate($this->_tempid, $phones, json_encode($this->_bodyparams));
    }


}




