<?php
/**
 * Created by PhpStorm.
 * User: Bw
 * Date: 16/4/29
 * Time: 下午5:19
 */
define('QUEUE_ROOT', APP_PATH.'libraries/CZTVQueue/');


require_once(QUEUE_ROOT.'AliyunMNS/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Exception\MnsException;


class AliyunMNS
{
    private $accessId;
    private $accessKey;
    private $endPoint;
    private $client;

    public function __construct($accessId, $accessKey, $endPoint)
    {
        $this->accessId = $accessId;
        $this->accessKey = $accessKey;
        $this->endPoint = $endPoint;
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
    }

    /**
     * 创建队列
     * @param $queueName
     * @return \AliyunMNS\Queue|void
     */
    public function createQueue($queueName)
    {
        $request = new CreateQueueRequest($queueName);
        try {
            $res = $this->client->createQueue($request);
        } catch (MnsException $e) {
            return false;
        }
        return $res;
    }

    /**
     * 查看队列
     * @return \AliyunMNS\Responses\ListQueueResponse|void
     */
    public function viewQueue()
    {
        $request = new \AliyunMNS\Requests\ListQueueRequest();
        try {
            $res = $this->client->listQueue($request);
        } catch (MnsException $e) {
            return false;
        }
        return $res;
    }

    /**
     * 删除指定队列
     * @param $queueName
     * @return bool|void
     */
    public function delQueue($queueName)
    {
        try {
            $this->client->deleteQueue($queueName);
        } catch (MnsException $e) {
            return false;
        }
        return true;
    }

    /**
     * 发送消息
     * @param $queueName
     * @return \AliyunMNS\Responses\SendMessageResponse|void
     */
    public function sendMessage($queueName, $messageBody)
    {
        $queue = $this->client->getQueueRef($queueName);
        $bodyMD5 = md5(base64_encode($messageBody));
        $request = new SendMessageRequest($messageBody);
        try {
            $res = $queue->sendMessage($request);
        } catch (MnsException $e) {
            return false;
        }
        return $res;
    }

    /**
     * @param $queueName 队列名称
     * @param bool|true $flag 接收消息后是否删除该消息 默认删除
     * @return \AliyunMNS\Responses\ReceiveMessageResponse|bool
     */
    public function receiveMessage($queueName, $flag = true)
    {
        $queue = $this->client->getQueueRef($queueName);
        $receiptHandle = NULL;
        try {
            $res = $queue->receiveMessage();
        } catch (MnsException $e) {
            return false;
        }
        if ($flag) {
            try {
                $res = $queue->deleteMessage($receiptHandle);
            } catch (MnsException $e) {
                return false;
            }
        }
        return $res;
    }

    /**
     * @param $queueName    队列名称
     * @param $message
     * @return \AliyunMNS\Responses\ReceiveMessageResponse|bool
     */
    public function deleteMessage($queueName, $response)
    {
        $queue = $this->client->getQueueRef($queueName);
        try {
            $res = $queue->deleteMessage($response->getReceiptHandle());
        } catch (MnsException $e) {
            return false;
        }
        return $res;
    }
}

class UgcyunController extends ApiBaseController {

    public $admin_id;
    public $mediaId;
    public $rtmpUrl;
    public $cdnUrl1;
    public $cdnUrl2;
    public $is_latest = null;
    public $_terminal = 1;//1 IOS  2安卓
    public $rand;
    private $streamname = '';

    const PUSH_RTMP_HOST = 'rtmp://yf.push.cztv.com/live/';
    const PULL_RTMP_HOST = 'rtmp://yf.ugcrtmp.l.cztv.com/live/';
    const HLS_RTMP_HOST = 'http://yf.ugc.l.cztv.com/channels/live/';


    public function initialize() {
        $action = $this->dispatcher->getActionName();
        if($action != 'callback'&&$action != 'callbackflv'){
            parent::initialize();
            $this->checkToken();
        }
        $this->channel_id = Request::get("channel_id");
    }

    public function rtmpAction() {
        $this->admin_id = Request::get('admin_id');
        if($this->admin_id <= 0) $this->_json([],404,'ERROR');
        $this->is_latest = Request::get('latest');
        $this->_terminal = Request::get('device')?Request::get('device'):1;
        if($this->is_latest) {
            $this->getLastMediaId();
        }
        else {
            $this->GenerateMediaId();
        }

        $this->initSmartData();
        $share_url = $this->mediaUrl(array('type'=>'ugc_signal', 'id'=>0));


        if(Request::get("category_id") == 0){
            $data_id = 0;
        }
		else {
            $ugc = UgcyunLive::query()
                ->columns(["stream_event"])
                ->andWhere("admin_id = {$this->admin_id}")
                ->orderBy("id desc")
                ->limit(1)
                ->execute()
                ->toArray();
            $ugc = reset($ugc);
            if($ugc["stream_event"] == "start") {
                $dataJson = RedisIO::get(Signals::liveSignalRedisKey($this->rtmpUrl));
                if($dataJson) {
                    $dataInfo = json_decode($dataJson, true);
                    $data_id = $dataInfo["data_id"];
                    $model = Data::getByDataId($dataInfo["channel_id"],$data_id);
                    $model->timelimit_begin = time();
                    $model->save();
					
                }
				else {
                    $data_id = $this->createSignalData(Request::get());
                }
            }
			else {
                $data_id = $this->createSignalData(Request::get());
            }
			$category_id = Request::get("category_id");
			SmartyData::delCategoryDataRedisChannel($category_id, $this->channel_id);
			

        }



        $dataInfo = [
            "data_id"=>$data_id,
            "channel_id"=>Request::get("channel_id"),
            "category_id" =>Request::get("category_id")
        ];
        RedisIO::set(Signals::liveSignalRedisKey($this->rtmpUrl),json_encode($dataInfo));

        //返回客户端数据
        $this->_json(['rtmp_url'=>$this->rtmpUrl, 'share_url'=>$share_url."?url=".$this->cdnUrl2,"data_id"=>$data_id], 200);
    }

    //返回主播点播文件
    public function videosAction() {
        $this->admin_id = Request::get('admin_id');
        $ugc_live = UgcyunLive::getUgcLiveInfoByAdmin($this->admin_id);
        $videos = UgcyunLiveVideo::getVideosByStreamId($ugc_live->id, $this->page, $this->per_page);

        $this->initSmartData();
        $share_url = $this->mediaUrl(array('type'=>'ugc_video', 'id'=>0));
        if($videos) {
            $result = array();
            foreach($videos as $v) {
                $result[] = array(
                    'file_url' => $v->file_url,
                    'start_time' => $v->start_time,
                    'end_time' => $v->end_time,
                    'rate' => $v->rate,
                    'share_url'=>$share_url."?url=".$v->file_url
                    );
            }
            return $this->_json($result);
        } else {
            return $this->_json([]);
        }
    }

    //获取用户uid
    protected function getAdminIdByToken($token) {
        $key = ":{$token}";
        if($admin_id = RedisIO::get($key)) {
            $this->admin_id = $admin_id;
        }
        else {
            $this->_json([],403,"Token Error!");
        }
    }

    protected function getLastMediaId() {
        $ugc_info = UgcyunLive::findFirst(array("admin_id = '{$this->admin_id}'", 'order'=>'id DESC'));

        if($ugc_info) {
            $this->rtmpUrl = $ugc_info->rtmp_url;
            $this->cdnUrl1 = $ugc_info->cdn_url1;
            $this->cdnUrl2 = $ugc_info->cdn_url2;
        }
        else
            $this->GenerateMediaId();
    }

    protected function GenerateMediaId() {
        global $config;
        $ugcyunLive = new UgcyunLive;
		$ugc_prefix = "yun";
        switch($config->memprefix) {
            case "nhudong_product": $ugc_prefix = "hd"; break;
            case "test911": $ugc_prefix = "test"; break;
            case "product": $ugc_prefix = "yun";break;
        }
        $this->streamname = $ugc_prefix.md5(strval($this->admin_id).strval(time()));
        $this->rtmpUrl = self::PUSH_RTMP_HOST.$this->streamname.'_540p';
        $this->cdnUrl1 = self::PULL_RTMP_HOST .$this->streamname.'_540p';
        $this->cdnUrl2 = self::HLS_RTMP_HOST.$this->streamname.'/540p.m3u8';

        $inArr = [
            'admin_id' => $this->admin_id,
            'stream_name' => $this->streamname,
            'rtmp_url' => $this->rtmpUrl,
            'start_time' => time(),
            'cdn_url1' => $this->cdnUrl1,
            'cdn_url2' => $this->cdnUrl2,
            'cdn_url3' => '',
            'is_rec' => 1,
            'terminal' => $this->_terminal,
        ];
        if(!$id = $ugcyunLive->saveGetId($inArr)) {
            $this->_json([], 404, "Error1!");
        }
        $this->mediaId = $id;
    }

    //生成RTMP
    protected function GenerateRtmpUrl() {
        $this->rand = rand(1000, 9999);
        $this->rtmpUrl = self::PUSH_RTMP_HOST . $this->admin_id . "_" . $this->rand;
        //判断是否重复
        if(!UgcyunLive::findFirst("rtmp_url = '{$this->rtmpUrl}'")) {
            return;
        }
        else {
            $this->GenerateRtmpUrl();
        }
    }

    //默认配置
    protected function ugcConf($rec, $transcode) {
        $conf = '{
            "RtmpUrl": "'.$this->rtmpUrl.'",
            "HlsOpen": true,
            "HlsDuration": 3, 
            "HlsNum": 3, 
            "HlsPlayOpen": '.(bool)$rec.', 
            "FlvLiveOpen": true, 
            "FlvPlayOpen": true, 
            "TransCodes":[
                {
                    "Width": 1024, 
                    "Height": 720, 
                    "VideoRate": 720, 
                    "AudioRate": 64, 
                    "KeyFrameDist": 30, 
                    "CodeLevel": "main", 
                    "FrameRate": 30, 
                    "HlsOpen": true, 
                    "HlsDuration": 3, 
                    "HlsNum": 3, 
                    "HlsPlayOpen": '.(bool)$rec.', 
                    "FlvLiveOpen": true, 
                    "FlvPlayOpen": true
                }';
        if($transcode >= 2){
            $conf .= ',{
                    "Width": 1024, 
                    "Height": 720, 
                    "VideoRate": 720, 
                    "AudioRate": 64, 
                    "KeyFrameDist": 30, 
                    "CodeLevel": "main", 
                    "FrameRate": 30, 
                    "HlsOpen": true, 
                    "HlsDuration": 3, 
                    "HlsNum": 3, 
                    "HlsPlayOpen": '.(bool)$rec.', 
                    "FlvLiveOpen": true, 
                    "FlvPlayOpen": true
                }';
        }

        if($transcode >= 3){
            $conf .= ',{
                    "Width": 1024, 
                    "Height": 720, 
                    "VideoRate": 720, 
                    "AudioRate": 64, 
                    "KeyFrameDist": 30, 
                    "CodeLevel": "main", 
                    "FrameRate": 30, 
                    "HlsOpen": true, 
                    "HlsDuration": 3, 
                    "HlsNum": 3, 
                    "HlsPlayOpen": '.(bool)$rec.', 
                    "FlvLiveOpen": true, 
                    "FlvPlayOpen": true
                }';
        }
        $conf .= '
            ]
        }';

        return $conf;
    }
	
    //云帆回调接口
    public function callbackflvAction() {        
        $ugcyunLive = new UgcyunLive;
		$queueName = "xlwMNS";
        switch(app_site()->memprefix) {
            case "nhudong_product": $queueName = "hdugc"; break;
            case "test911": $queueName = "testugc"; break;
        }
        $ugc_info = file_get_contents("php://input");
        $accessKeyId = 'LTAIcdYW4QQTMuQz';
		$accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
		$endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";
		$mns = new AliyunMNS($accessKeyId,$accessKeySecret,$endPoint);

		$mns->sendMessage($queueName, $ugc_info);
        $this->_json([],200);
	}


    public function callbackclientAction() {
        $accessKeyId = 'LTAIcdYW4QQTMuQz';
        $accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
        $endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";
        $queueName = "xlwMNS";
        switch(app_site()->memprefix) {
            case "nhudong_product": $queueName = "hdugc"; break;
            case "test911": $queueName = "testugc"; break;
        }
        $admin_id = Request::get('admin_id');
        $client_end_time = time();
        $ugcModel = UgcyunLive::findFirst(array("admin_id = '{$admin_id}'", 'order'=>'id DESC'));
        $ugcModel->end_time = $client_end_time;
        $ugcModel->stream_event = 'end';
        $ugcModel->save();
        UgcyunLive::setUgcEnd($ugcModel->admin_id, $client_end_time);
        $ugc_info = array('stream_event' => 'clientend', 'admin_id'=>$admin_id, 'time' => $client_end_time);
        $mns = new AliyunMNS($accessKeyId, $accessKeySecret, $endPoint);
        $mns->sendMessage($queueName, json_encode($ugc_info));
        $this->_json([],200);
    }

    //云帆回调接口
    public function callbackAction() {
		$queueName = "xlwMNS";
        switch(app_site()->memprefix) {
            case "nhudong_product": $queueName = "hdugc"; break;
            case "test911": $queueName = "testugc"; break;
        }
        $ugc_info = file_get_contents("php://input");

		$accessKeyId = 'LTAIcdYW4QQTMuQz';
		$accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
		$endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";
		$mns = new AliyunMNS($accessKeyId, $accessKeySecret, $endPoint);
		
        $mns->sendMessage($queueName, $ugc_info);
        $ugc_info = json_decode($ugc_info,true);
        $rtmpurlarr = explode('?', $ugc_info['rtmp_url']);
        if(!$ugc_info) {
            $this->_json([], 404, 'JSON ERROR');
        }
        $ugcModel = UgcyunLive::findFirst("rtmp_url = '{$rtmpurlarr[0]}'");
        if(!$ugc_info['rtmp_url'] || !$ugcModel) {
            $this->_json([], 404, 'RTMP ERROR');
        }
        $ugcModel->source_ip = $ugc_info['ip'];
        $ugcModel->path = $ugc_info['appname'];
        $ugcModel->stream_nam = $ugc_info['id'];
        $ugcModel->domain = $ugc_info['app'];
        if($ugc_info['stream_event'] == 'end') {
            $ugcModel->end_time = $ugc_info['time'];
            $ugcModel->stream_event = 'end';
        }
        else if($ugc_info['stream_event'] == 'start') {
            $ugcModel->stream_event = 'start';
            $ugcModel->start_time = $ugc_info['time'];
            $ugcModel->end_time = null;
        }
        $ugcModel->save();
        $this->_json([],200);
    }

    /*
     * @desc 获取主播的推流地址
     * @author 冯固
     * @date 2016-6-6
     * */
    public function getRtmpUrlAction() {
        //TODO 获取主播的推流 地址
        $data = [];
        if($admin_id = Request::getQuery('adminid')) {
            $json = RedisIO::get('ugcyun::admin::anchor::'.$admin_id);
            if($json) {
                $arr = json_decode($json,true);
                $data = array('rtmp' => $arr['rtmpurl']);
            }
        }
        $this->_json($data);
    }


    /**
     * @desc 获取主播的播放地址
     * @author 冯固
     * @date 2016-6-6
     *
     */
    public function  getPlayUrlAction() {
        //TODO 获取主播的播放地址
        $data = [];
        if($admin_id = Request::getQuery('adminid')) {
            $json = RedisIO::get('ugcyun::admin::anchor::'.$admin_id);
            if($json) {
                $arr = json_decode($json);
                $data = array('play' => $arr['playurl']);
            }
        }
        $this->_json($data);
    }


    /*
     * @desc 主播开播消息个推到关注过的用户APP上面
     * @author 冯固
     * @date 2016-6-6
     *
     * */
    public function geTuiStartPlayAction() {
        //TODO 主播开播消息个推到用户APP上。
    }

    public function ugclivebymobileAction() {
        $mobile = Request::getQuery('mobile');
        $channel_id = Request::getQuery('channel_id');
        $data = array();
        if(!empty($mobile)) {
            $ugcyun_live = UgcyunLive::getUgcLiveInfoByMob($mobile, $channel_id);
            if($ugcyun_live) {
                $data = array(
                    'rtmp_url'=>$ugcyun_live->ugcyunLive->rtmp_url,
                    'play_url'=>$ugcyun_live->ugcyunLive->cdn_url1,
                    'hls_url'=>$ugcyun_live->ugcyunLive->cdn_url2,
                    'mobile'=>$mobile
                  );
            }
            else {
                $data = array(
                    'rtmp_url'=>"",
                    'play_url'=>"",
                    'hls_url'=>"",
                    'mobile'=>$mobile
                );
            }
        }
        if(!empty($data)) {
            $this->_json($data);
        }
        else {
            $this->_json([], 3001, '参数无效');
        }
    }
    
    public function createSignalData($data){

        try{

            DB::begin();

            $data["created_at"] = time();
            $data["updated_at"] = time();
            $data["title"] =  $this->user->name;
            $data["author_id"] = $this->user->id;
            $data["author_name"] = $this->user->name;
            $data["referer_author"] = $this->user->name;
            $data["comment_type"] = 2;
            $data["partition_by"] = date("Y");
            $data["intro"] = "live";
            $data["timelimit_begin"] = time();
            $data["content"] = "";
            $data["status"] = 1; //审核
            $categoryId = $data["category_id"];
            unset($data["category_id"]);

            //1、新建新闻
            $new_data_id = Data::createData($data, 'news');
            if (!$new_data_id) {
                $this->throwDbE('News save failed');
            }
            //2、新建直播
            $data["types"] = 5;//手机直播

            $data["json"] = json_encode($this->preProcessData());
            $data["live_status"] = 1;//直播开启
            $signals_data_id = Data::createData($data, "signals");

            //3、新建复合媒资
            $data["data_data"] = "[{$signals_data_id}, {$new_data_id}]";
            $data_data_ext = new stdClass();
            $data_data_ext->news = [
                ["data_id"=>$new_data_id,"template"=>"default"]
            ];
            $data_data_ext->signal = [
                ["data_id"=>$signals_data_id,"template"=>"default"]
            ];
            $data["data_data_ext"] = json_encode($data_data_ext);
            $multimedia_data_id = Data::createData($data, 'multimedia');

            $media_publish = explode(',', $categoryId);
            if (CategoryData::publish($multimedia_data_id, $media_publish,1) === false) {
                $this->throwDbE('Category Publish Error');
            }

            CategoryData::deleteListRedis($categoryId, $this->channel_id);
            if (CategoryData::PAGE_CACHE_NUMBER > 0) {
                for ($i = 0; $i < CategoryData::PAGE_CACHE_NUMBER; $i++) {
                    $page = $i + 1;
                    $key_cache_json_key = "Backend:cache_json:" . $this->channel_id . ":" . $categoryId . ":" . $page;
                    if (RedisIO::exists($key_cache_json_key)) {
                        RedisIO::delete($key_cache_json_key);
                    }
                }
            }

            DB::commit();

            return $multimedia_data_id;
        }catch (\Exception $e){
            DB::rollback();
            throw  $e;
        }

    }

    public function preProcessData() {
        $playUrl1 = new stdClass();
        $playUrl1->play_url = $this->cdnUrl1;
        $playUrl1->play_rate = 1;
        $playUrl2 = new stdClass();
        $playUrl2->play_url = $this->cdnUrl2;
        $playUrl2->play_rate = 1;

        $mobileLive = [
            "url_sourse"=> self::PUSH_RTMP_HOST,
            "rate" => 1,
            "data" =>[
                [
                    "cdnUrl"=> $this->rtmpUrl,
                    "cdn_rate"=>1,
                    "playArr"=>[
                        [
                            $playUrl1,$playUrl2
                        ]
                    ],
                    "textArr"=>[""],
                    "encryptArr"=>["1"],
                    "type_select"=>["m3u8"],
                    "vendor_select"=>[null],
                    "passwordArr"=>[null],
                    "default_rate"=>["1"]

                ]
            ]
        ];
        $jsonData = [$mobileLive];
        return $jsonData;
    }

}
