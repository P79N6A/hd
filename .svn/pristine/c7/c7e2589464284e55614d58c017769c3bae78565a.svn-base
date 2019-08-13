<?php

class SmsController extends SMSBaseController
{
    private $mobile                 = '';
    private $arrParam               = array();
    private $template_id            = 0;
    private $content                = '';
    private $sms_scene_config       = array();      //短信应用场景配置信息
    private $sms_channel_config     = array();      //短信通道配置信息
    private $sms_template_config    = array();      //短信模板配置信息

    public function indexAction()
    {
        echo 'ok';
        die();
    }

    /*
     * @desc 发送短信接口GET方式
     * @param uname 短信用户名
     * @param pwd   密码
     * @param appid 短信应用场景id
     * @param mobile 短信接收手机号码
     * */
    public function sendSmsAction()
    {
        $channel = $this->pick_channel();
        switch($channel)
        {
            case 'netease':
                $this->neteaseSender($this->mobile,$this->template_id,$this->arrParam);
                break;
            case 'surfing':
                $this->esurfingSender($this->mobile,$this->template_id,$this->arrParam);
                break;
            case 'jz':
                $this->JZSender($this->mobile,$this->content);
                break;
        }
    }



    private function checkPassword()
    {
        $scene_id = Request::getQuery('scene_id','int',0);
    }


    /*
     * @desc 选择短信通道
     * @author 冯固
     * @date 2016-6-8
     * @return 返回短信通道netease:网易短信,esurfing:天翼短信,jz:建周短信
     * */
    private function pick_channel()
    {
        //TODO 获取短信通道




    }









    public function testAction()
    {
//        $mobile = '13989452011';
//        $tydata = array(
//            'appid' => '398350640000043874',
//            'appsecrect' => '681926f6c1d626be7f28ad0e4968edb9',
//            'tempid' => '91550748'
//        );
//        $tysmsclient = new EsurfingSMS($mobile, '', '', $tydata);
//        $param['param1'] = SMS::generateCode();
//        //$param['param2'] = 30;
//        $tysmsclient->setParam($param);
//        var_dump($tysmsclient->SendSms());
//
//        $neteasedata = array(
//            'appkey' => 'a77e75f8dd48e5267518d7381554f277',
//            'appsecret' => 'c5e0e5c6a586',
//            'tempid' => 6312,
//            'bodyparams' => array('中国蓝', '15')
//        );
//
//        $neteaseclient = new NeteaseSMS($mobile, '', '', $neteasedata);
//        var_dump($neteaseclient->SendSms());
//
//        $jzdata = array(
//            'account' => 'jzyy902',
//            'password' => '135790'
//        );
//        $body = '您的验证码是52545';
//        $suffix = "【建周科技】";
//        $jzclient = new JZSMS($mobile, $body, $suffix, $jzdata);
//
//        var_dump($jzclient->SendSms());
    }
    
    



}