<?php namespace CZTVPush\GeTui;

define('GETUI_ROOT', APP_PATH . 'libraries/igetui3.3.2.1/');
require_once(GETUI_ROOT . 'IGt.Push.php');
require_once(GETUI_ROOT . 'igetui/IGt.AppMessage.php');
require_once(GETUI_ROOT . 'igetui/IGt.APNPayload.php');
require_once(GETUI_ROOT . 'igetui/template/IGt.BaseTemplate.php');
require_once(GETUI_ROOT . 'IGt.Batch.php');

class Sender {

    protected $url = 'http://sdk.open.api.igexin.com/apiex.htm';
    protected $appKey = '';
    protected $appId = '';
    protected $masterSecret = '';
    protected $cid = '';
    protected $deviceToken = '';
    protected $alias = '';

    const ANDROID_PUSH=1;
    const IOS_PUSH=2;

    /**
     * @var \IGeTui
     */
    protected $igt;

    public function __construct($appKey, $masterSecret, $appId) {
        $this->appKey = $appKey;
        $this->masterSecret = $masterSecret;
        $this->appId = $appId;
        $this->igt = new \IGeTui($this->url, $appKey, $masterSecret);
    }

    public function send($params, $client = null) {

        $title = get($params, 'title', '');
        $body = get($params, 'body', '');
        $data = json_encode($params['data'],JSON_UNESCAPED_UNICODE);
        
		$clientType = get($params, 'clientType', '');				// 推送至客户端类型
        $offlineExpireTime = get($params, 'offlineExpireTime', ''); // 离线时间值
        $template = $this->getTransmissionTemplate($title, $body, $data);
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime($offlineExpireTime);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        $message->set_appIdList(array($this->appId));
	
        if(($clientType & self::ANDROID_PUSH) > 0 && ($clientType & self::IOS_PUSH)>0 ){
        	$message->set_phoneTypeList(array('ANDROID', 'IOS'));
        }else{
	        if(($clientType & self::ANDROID_PUSH)>0) {
	        	$message->set_phoneTypeList(array('ANDROID'));
	        }
	        if(($clientType & self::IOS_PUSH)>0) {
	        	$message->set_phoneTypeList(array('IOS'));
	        }
        }
        $rep ="";
        if ($client) {
            $target = new \IGtTarget();
            $target->set_appId($this->appId);
            $target->set_clientId($client);
            $rep = $this->igt->pushMessageToSingle($message, $target);
        } else {
            $rep = $this->igt->pushMessageToApp($message);
        }
        return $rep;
    }

    public function stop($contentId) {
        $this->igt->stop($contentId);
    }

    protected function getTransmissionTemplate($title, $body, $data) {

        $template = new \IGtTransmissionTemplate();
        $template->set_appId($this->appId);
        $template->set_appkey($this->appKey);
        $template->set_transmissionType(2);
        $template->set_transmissionContent($data);

        $apn = new \IGtAPNPayload();
        $msg = new \DictionaryAlertMsg();
        $msg->body = $title;

        //IOS8.2 支持
//        $msg->title = $title;

        $msg->actionLocKey = "查看";
        $msg->locKey = $title;
        $msg->locArgs = array("locargs");
        $msg->launchImage = "launchimage";

        $apn->alertMsg = $msg;
        $apn->badge = 1;
        $apn->sound = "";
        $apn->add_customMsg("payload", $data);
        $apn->contentAvailable = 0;
        $apn->category = "ACTIONABLE";
        $template->set_apnInfo($apn);

        return $template;

    }

}