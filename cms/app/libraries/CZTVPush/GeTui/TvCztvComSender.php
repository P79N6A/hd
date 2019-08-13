<?php namespace CZTVPush\GeTui;

define('GETUI_ROOT', APP_PATH.'libraries/igetui3.3.2.1/');
require_once(GETUI_ROOT.'IGt.Push.php');
require_once(GETUI_ROOT.'igetui/IGt.AppMessage.php');
require_once(GETUI_ROOT.'igetui/IGt.APNPayload.php');
require_once(GETUI_ROOT.'igetui/template/IGt.BaseTemplate.php');
require_once(GETUI_ROOT.'IGt.Batch.php');
require_once(GETUI_ROOT.'igetui/template/notify/IGt.Notify.php');
require_once(GETUI_ROOT.'igetui/template/IGt.TransmissionTemplate.php');
/**
 * 中国蓝tv个推推送
 */
class TvCztvComSender {

	const LANTV_PUSH_NONE = 0;				// 默认为none，不发送
	const LANTV_PUSH_ALL = 1;				// 全推
	const LANTV_PUSH_LIST = 2;				// 列表推
	const LANTV_PUSH_SINGLE = 3;			// 单个推
	
    protected $url = 'http://sdk.open.api.igexin.com/apiex.htm';
    protected $appKey = '';
    protected $appId = '';
    protected $masterSecret = '';
    protected $cid = '';
    protected $deviceToken = '';
    protected $alias = '';
    protected $expireTime = 0;
    protected $iosSound = '';
    protected $androidSound = '';
    
    /**
     * @var \IGeTui
     */
    protected $igt;
    public function __construct($appKey, $masterSecret, $appId) {
        $this->appKey = $appKey;
        $this->masterSecret = $masterSecret;
        $this->appId = $appId;
        $this->igt = new \IGeTui($this->url, $appKey, $masterSecret);
        $this->expireTime = 2*3600*1000;
        $this->iosSound = "chinablue.wav";
        $this->androidSound = "1.wav";
    }
    
    /**
     * ios 推送
     * @param unknown $params 
     * @param string $client
     * @param unknown $type
     * @return Ambigous <multitype:, number, multitype:string , multitype:string unknown >
     */
    function sendToIos($params, $client = null, $type = self::LANTV_PUSH_NONE) {
        $title = get($params, 'title', '');
        $body = get($params, 'body', '');
        $data = json_encode($params['data'],JSON_UNESCAPED_UNICODE);    // 不转吗转换成json
        $template = $this->getTransmissionTemplateIosForTen($title, $body, $data);   // ios10 模板
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime($this->expireTime);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        $message->set_appIdList(array($this->appId));
        $message->set_phoneTypeList(array('IOS'));
        $result = $this->getuiPushMsg($template, $message, $client, $type, "IOS");
    	return $result;
    }
    
    /**
     * android 推送
     * @param unknown $params
     * @param string $client
     * @param unknown $type
     * @return Ambigous <multitype:, number, multitype:string , multitype:string unknown >
     */
    function sendToAndroid($params, $client = null, $type = self::LANTV_PUSH_NONE) {
    	$title = get($params, 'title', '');
    	$body = get($params, 'body', '');
    	$data = json_encode($params['data'],JSON_UNESCAPED_UNICODE);		// 不转吗转换成json
    	$template = $this->getTransmissionTemplateAndroid($title, $body, $data);
    	$message = new \IGtAppMessage();
    	$message->set_isOffline(true);
    	$message->set_offlineExpireTime($this->expireTime);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
    	$message->set_data($template);
    	$message->set_appIdList(array($this->appId));
    	$message->set_phoneTypeList(array('ANDROID'));
    	$result = $this->getuiPushMsg($template, $message, $client, $type, "Android");
    	return $result;
    }
    
    /**
     * 推送方式,分类发送
     * @param unknown $template 模板
     * @param unknown $message  消息体
     * @param unknown $client   客户单cid值
     * @param unknown $type     推送类型
     * @param unknown $mold     客户端类型:ios,android
     * @return Ambigous <multitype:, number, multitype:string , multitype:string unknown >
     */
    private function getuiPushMsg($template, $message, $client, $type, $mold) {
    	$rep = array();
    	switch ($type) {
    		case self::LANTV_PUSH_LIST:			// 列表推送
    			$rep = $this->pushMsgToList($template, $client, $mold);
    			break;
    		case self::LANTV_PUSH_SINGLE:		// 单个推送
    			$rep = $this->pushMsgToSingle($message, $client, $mold);
    			break;
    		case self::LANTV_PUSH_ALL:			// 全量推送
    			$result = $this->igt->pushMessageToApp($message);
    			$rep = $this->pushResult($mold.": TYPE is push_all, ", $result, true);
    			break;
    		case self::LANTV_PUSH_NONE:			// 默认不推送
    			$rep = $this->pushResult($mold.": TYPE is NONE, please check push type", null, false);
    			break;
    		default:
    			$rep = $this->pushResult($mold.": TYPE is default, please check push type", null, false);
    			break;
    	}
    	return $rep;
    }
    
    /**
     * 推送信息回执
     * @param unknown $info 提示消息内容
     * @param unknown $result 推送返回结果
     * @param unknown $type 是否符合推送
     * @param unknown $rep 推送结果集合
     * @return number
     */
    private function pushResult($info, $result, $type) {
    	if($type == true) {
    		$resultInfo = "return: ";
    		foreach ($result as $k => $v) {
    			$resultInfo .= $k.":".$v."; ";
    		}
    		$repInfo = array("result"=>"ok", "SUCCESS_INFO"=>$info.$resultInfo, " push_time" => date("Y-m-d H:i:s"));
    	}else {
    		$repInfo = array("result"=>"ok", "ERROR_INFO"=>$info, " push_time" => date("Y-m-d H:i:s"));
    	}
    	return $repInfo;
    }
    
    /**
     * 单个推送 
     * @param unknown $client
     * @return Ambigous <multitype:, mixed>
     */
    private function pushMsgToSingle($message, $client, $mold) {
    	if(!is_array($client)) {
    		$target = $this->targetSet($client);
    		$result = $this->igt->pushMessageToSingle($message, $target);
    		return $this->pushResult($mold.": type is push_single, ", $result, true);
    	}else {
    		return $this->pushResult($mold.": type is push_single, but the client is array", "", false);
    	}
    }
    
    /**
     * 列表推送
     * @param unknown $template
     * @param unknown $client
     * @return Ambigous <multitype:, mixed>
     */
    private function pushMsgToList($template, $client, $mold){
    	if(is_array($client)) {
    		$message = new \IGtListMessage();
    		$message->set_isOffline(true);							// 是否离线
    		$message->set_offlineExpireTime($this->expireTime);		// 离线时间
    		$message->set_data($template);							// 设置推送消息类型
    		$message->set_PushNetWorkType(0);						// 设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
    		//用户列表推送
    		$contentID = $this->igt->getContentId($message);
    		$targetgetList = array();
    		foreach($client as $c) {
    			$target = $this->targetSet($c);
    			array_push($targetgetList,$target);
    		}
    		$result = $this->igt->pushMessageToList($contentID,$targetgetList);
    		return $this->pushResult($mold.": type is push_list, ", $result, true);
    	}else {
    		return $this->pushResult($mold.": type is push_list, but the client is not array", "", false);
    	}
    }
    
    /**
     * 设置target对象
     * @param unknown $client
     * @return \IGtTarget
     */
    private function targetSet($client) {
    	$target = new \IGtTarget();
    	$target->set_appId($this->appId);
    	$target->set_clientId($client);
    	return $target;
    }
    
    /*透传模板 苹果 ios 10以上版本*/
    function getTransmissionTemplateIosForTen($title, $body, $data) {
    	$template = new \IGtTransmissionTemplate();
    	$template->set_appId($this->appId);
    	$template->set_appkey($this->appKey);
    	$template->set_transmissionType(2);//透传消息类型  1:打开应用 2
    	$template->set_transmissionContent($data);//透传内容
    
    	$apn = new \IGtAPNPayload();
    	$msg=new \DictionaryAlertMsg();
    	$msg->body = $title;					// message
    	echo $data;
        $dataImage = json_decode($data,true);
    	$msg->actionLocKey="查看";
    	$msg->locKey=$title;
    	$msg->locArgs=array("locargs");
    	$msg->launchImage= isset($dataImage["image"]) && !empty($dataImage["image"]) ? $dataImage["image"] : "launchimage";
    	$apn->alertMsg=$msg;
    	$apn->badge=1;
    	$apn->sound=$this->iosSound;
    	$apn->add_customMsg("payload",$data);
    	$apn->contentAvailable=0;				
    	$apn->category="ACTIONABLE";
    	$template->set_apnInfo($apn);
    	return $template;
    }
    
    /*透传模板 安卓*/
    private function getTransmissionTemplateAndroid($title, $body, $data) {
        $dataImage = json_decode($data,true);
        $launchImage = isset($dataImage["image"]) && !empty($dataImage["image"]) ? $dataImage["image"] : "";
        $template =  new \IGtTransmissionTemplate();
        $template->set_appId($this->appId);
        $template->set_appkey($this->appKey);
        $template->set_transmissionType(2);//透传消息类型  1:打开应用
        $template->set_transmissionContent($title);//透传内容
        $template->set_pushInfo("查看", 0, $title, $this->androidSound, $data, "","",$launchImage);
        //第三方厂商推送透传消息带通知处理
        $notify = new \IGtNotify();
//        $notify -> set_payload("{}");
        $notify -> set_title("中国蓝TV");
        $notify -> set_content($body);
        $notify -> set_intent("intent:#Intent;launchFlags=0x10000000;package=com.chinablue.tv;component=com.chinablue.tv/com.chinablue.tv.activity.PushActivity;S.PUSH_DATA=".$title.";end");
        $notify -> set_type(\NotifyInfo_Type::_intent);
        $template -> set3rdNotifyInfo($notify);
        return $template;
    }
}