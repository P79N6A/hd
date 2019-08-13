<?php

class JZSMS extends SMS {
    private $_account;
    private $_password;
    const Account = 'jzyy902';
    const Password = '135790';
    const Url = "http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl";
    const Encode = 'UTF-8';

    public function __construct($phone, $body, $suffix, array $confgparams) {
        parent::__construct($phone, $body, $suffix, $confgparams);
        $this->setConfigInfo($confgparams);
    }


    private function setConfigInfo($params) {
        if (array_key_exists('account', $params) &&
            array_key_exists('password', $params)
        ) {
            $this->_account = $params['account'];
            $this->_password = $params['password'];
        }
    }


    public function SendSms() {
        $client = new SoapClient(self::Url, array('encoding' => self::Encode));

        $param1 = array(
            'account' => $this->_account,
            'password' => $this->_password,
            'destmobile' => $this->getPhone(),
            'msgText' => $this->getBody() . $this->getSuffix());
        //接口方法。
        $result = $client->sendBatchMessage($param1);
        //将XML数据转换成数组
        return $result;
    }

}