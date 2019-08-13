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

class UgcVideoTask extends Task {    


    public function check_message($memprefix, $streamname) {
        $ugc_prefix = "yun";
        switch($memprefix) {
            case "nhudong_product": $ugc_prefix = "hd"; break;
            case "test911": $ugc_prefix = "test"; break;
            case "product": $ugc_prefix = "yun";break;
        }
        if(preg_match("/^(".$ugc_prefix.")[a-z0-9]{32}$/", strtolower($streamname))) {
            return true;
        }
        else {
            return false;
        }
    }


    //每秒钟一次
    public function doAction() {
        global $config;
        $queueName = "xlwMNS";
        switch($config->memprefix) {
            case "nhudong_product": $queueName = "hdugc"; break;
            case "test911": $queueName = "testugc"; break;
        }
        $accessKeyId = 'LTAIcdYW4QQTMuQz';
        $accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
        $endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";

        $mns = new AliyunMNS($accessKeyId,$accessKeySecret,$endPoint);
        while(1){
            if($message = $mns->receiveMessage($queueName,false)){
                $queuedata = $message->getMessageBody();
                $queuedata_arr = json_decode($queuedata,true);
                echo "I got u".PHP_EOL;
                if($queuedata_arr['stream_event'] == 'mp4') {
                    if($queuedata_arr['stop_time'] !=0) {
                        $streamname = str_replace('_720p','',str_replace('_540p','', $queuedata_arr['ori_url']));
						$streamname = str_replace('rtmp://yf.push.cztv.com/live/', '', $streamname);
                        if($this->check_message($config->memprefix, $streamname)) {
                            $ugc_live = UgcyunLive::getUgcLiveInfoByStream($streamname);
                            if($ugc_live) {
                            $model_ugc_live_video = new UgcyunLiveVideo();
                            $model_ugc_live_video->saveGetId(array('rate'=>'800K','data_id'=>$queuedata_arr['stop_time'],
                                'file_flv_url'=>$queuedata_arr['cdn_url'], 'stream_id'=>$ugc_live->id, 'start_time'=>$ugc_live->start_time, 'end_time'=>$ugc_live->end_time));
                            }
                        }
                        else {
                            if($config->memprefix=="nhudong_product") {
                                F::curlRequest("http://test-i.cztvcloud.com/ugcyun/callbackflv", 'put', $queuedata);
                                F::curlRequest("http://i.cztvcloud.com/ugcyun/callbackflv", 'put', $queuedata);
                            }
                        }
                    }
                    //$mns->deleteMessage($queueName, $message);
                }
                else if($queuedata_arr['stream_event'] == 'end') {
                    $rtmpurlarr = explode('?', $queuedata_arr['rtmp_url']);
                    $streamname = str_replace('_720p','',str_replace('_540p','',$queuedata_arr['id']));
                    if($this->check_message($config->memprefix, $streamname)) {
                        $ugcModel = UgcyunLive::findFirst("rtmp_url =  '{$rtmpurlarr[0]}'");
                        $video_file = $this->getVideoFileFromYF($streamname, $ugcModel->start_time, $queuedata_arr['time']);
                        $ugc_live = UgcyunLive::getUgcLiveInfoByStream($streamname);
                        if($video_file && array_key_exists('url', $video_file) && $ugc_live) {
                            $model_ugc_live_video = new UgcyunLiveVideo();
                            $model_ugc_live_video->saveGetId(array('rate'=>'800K','data_id'=>$queuedata_arr['time'],
                                'file_url'=>$video_file['url'],'stream_id'=>$ugc_live->id, 'start_time'=>$ugc_live->start_time, 'end_time'=>$ugc_live->end_time));
                        }
                        $dataJson = RedisIO::get(Signals::liveSignalRedisKey($ugc_live->rtmp_url));
                        if($dataJson){
                            $dataInfo = json_decode($dataJson,true);
                            self::updateLiveData($dataInfo["data_id"],$video_file['url']);
                        }
                    }
                    else {
                        if($config->memprefix=="nhudong_product") {
                            F::curlRequest("http://test-i.cztvcloud.com/ugcyun/callback", 'put', $queuedata);
                            F::curlRequest("http://i.cztvcloud.com/ugcyun/callback", 'put', $queuedata);								
                        }
                    }
                    //$mns->deleteMessage($queueName, $message);
                }
                else if($queuedata_arr['stream_event'] == 'start') {
                    if($config->memprefix=="nhudong_product") {
                        F::curlRequest("http://test-i.cztvcloud.com/ugcyun/callback", 'put', $queuedata);
                        F::curlRequest("http://i.cztvcloud.com/ugcyun/callback", 'put', $queuedata);
                    }
                }
                //else {
                    $mns->deleteMessage($queueName, $message);
                //}
            }
			else {
                echo "do nothing".PHP_EOL;
                sleep(1);
            }
        }
    }

    /*
     * @desc 从云帆接口获取
     * */
    private function getVideoFileFromYF($stream, $start_time, $end_time) {
        $play_url ="http://yf.ugc.l.cztv.com/channels/live/{$stream}/540p.m3u8";
        $url = "http://live2demand.yfcdn.net/cdn/v1/live2demand?";
        $url .= 'starttime='.$start_time;
        $url .= '&endtime='.$end_time;
        $url .= '&bitrate=800k';
        $url .= '&live_url='.base64_encode($play_url);
        $ret_json = F::curlRequest($url,'get',[],true);
        $arr = json_decode($ret_json, true);
        return $arr;
    }

    /**更新直播媒资为点播媒资
     * @function updateDataLimitEnd
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $data_id
     * @param $video_file
     */
    public static function updateLiveData($data_id,$video_file){

        if($data_id){
            $data = Data::query()
                ->andWhere("id = {$data_id}")
                ->execute()
                ->getFirst();
            if($data){
                $data->timelimit_end = time();
                $data->redirect_url = $video_file;
                $data->save();
                $key = Data::data_detail_key .":". $data_id;
                RedisIO::delete($key);
                //取消推荐
                $categoryData = CategoryData::findCategoryDataByDataId($data_id);
                $categoryData->weight = 0;
                $categoryData->save();

                $categoryId = $categoryData->category_id;
                CategoryData::deleteListRedis($categoryId, $data->channel_id);
                if (CategoryData::PAGE_CACHE_NUMBER > 0) {
                    for ($i = 0; $i < CategoryData::PAGE_CACHE_NUMBER; $i++) {
                        $page = $i + 1;
                        $key_cache_json_key = "Backend:cache_json:" . $data->channel_id . ":" . $categoryId . ":" . $page;
                        if (RedisIO::exists($key_cache_json_key)) {
                            RedisIO::delete($key_cache_json_key);
                        }
                    }
                }				
                $key_cache_json_recommend_key = "Backend:cache_json_recommend:".$data->channel_id .":". $categoryId;
                RedisIO::delete($key_cache_json_recommend_key);				
			    SmartyData::delCategoryDataRedisChannel($categoryId, $data->channel_id);
				SmartyData::delDataRedisChannel($data_id, $data->channel_id);
            }
        }

    }

    
}
