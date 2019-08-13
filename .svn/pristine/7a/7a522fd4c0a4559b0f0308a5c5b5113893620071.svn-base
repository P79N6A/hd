<?php
use CZTVPush\GeTui\Sender;
use CZTVPush\GeTui\TvCztvComSender;
/**
 * @RoutePrefix("/getui")
 */
class GetuiController extends ApiBaseController {
    /**
     * @Put("/")
     */
    public function indexAction() {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $pushconfig = $params['pushconfig'];
        $pushdata = $params['pushdata'];
        $clientId = $params['clientid'];
        $sender = new Sender($pushconfig['AppKey'], $pushconfig['MasterSecret'], $pushconfig['AppID']);
        $rep = $sender->send($pushdata,$clientId);
        echo json_encode($rep);
        exit;
    }
    /**
     * @Put("/tvcztvcom")
     */
    public function tvCztvComAction() {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $pushconfig = $params['pushconfig'];
        $pushdata = $params['pushdata'];
        $clientid = $params['clientid'];
        $pushType = $params['pushtype'];
        $sender = new TvCztvComSender($pushconfig['AppKey'], $pushconfig['MasterSecret'], $pushconfig['AppID']);
        $rep = $sender->sendToIos($pushdata, $clientid, $pushType);
        echo json_encode($rep);
        exit;
	}
	
    /**
     * @Put("/tvcztvcomandroid")
     */	
	public function tvCztvComAndroidAction() {
        $json = file_get_contents("php://input");
        $params = json_decode($json, true);
        $pushconfig = $params['pushconfig'];
        $pushdata = $params['pushdata'];
        $clientid = $params['clientid'];
        $pushType = $params['pushtype'];
        $push_video_type = $pushdata['data']['enterType'];
        switch($push_video_type) {
            case 1: $entertype_android = 0; break;//直播
            case 2: $entertype_android = 1; break;//点播
            case 3: $entertype_android = 2; break;//专题
            case 4: $entertype_android = 3; break;//web页
            case 5: $entertype_android = 5; break;//全景直播
            case 6: $entertype_android = 6; break;   //全景点播
            case 10: $entertype_android = 10; break; //UGC直播
            case 11: $entertype_android = 11; break; //UGC点播
            case 12: $entertype_android = 12; break; //节目单推送
            case 15: $entertype_android = 15; break; //答题推送
        }
		$push_video_id = $pushdata['data']['videoId'];
		$ac_code = $push_video_id;
		$push_video_content = $pushdata['title'];
		$push_video_intro =  isset($pushdata['data']['intro'])?$pushdata['data']['channelId']:"";
		$push_video_shareurl =  isset($pushdata['data']['shareUrl'])?$pushdata['data']['shareUrl']:"";
		$push_video_ablumid = isset($pushdata['data']['ablumId'])?$pushdata['data']['ablumId']:0;
		$push_video_channelid = isset($pushdata['data']['channelId'])?$pushdata['data']['channelId']:0;
		$push_video_image =  isset($pushdata['data']['image'])?$pushdata['data']['image']:"";
        $push_video_name = isset($pushdata['data']['videoName'])?$pushdata['data']['videoName']:"";
        $push_video_url = isset($pushdata['data']['url'])?$pushdata['data']['url']:"";
        $android_msg = "ChinaBlueTV://cztv/xinlan?";
        $android_msg .= "enterType=".$entertype_android;
        $android_msg .= "&videoId=".(($push_video_type==1)?$ac_code:$push_video_id);
        $android_msg .= "&videoChannelId=".$push_video_channelid;
        $android_msg .= "&videoAblumId=".$push_video_ablumid;
        $android_msg .= "&videoTitle=".$push_video_content;
        $android_msg .= "&videoBrief=".$push_video_intro;
        $android_msg .= "&videoShareUrl=".$push_video_shareurl;
        $android_msg .= "&imageUrl=".$push_video_image;
        $android_msg .= "&url=".$push_video_url;
        $android_msg .= "&videoName=".$push_video_name;
        if(isset($pushdata['data']['videoDate'])) {
        	$android_msg .= "&videoDate=".$pushdata['data']['videoDate'];
        }
        $pushdata['body'] = $push_video_content;
		$pushdata['title'] = $android_msg;
        $sender = new TvCztvComSender($pushconfig['AppKey'], $pushconfig['MasterSecret'], $pushconfig['AppID']);
        $rep = $sender->sendToAndroid($pushdata, $clientid, $pushType);
        echo json_encode($rep);
        exit;
    }
}