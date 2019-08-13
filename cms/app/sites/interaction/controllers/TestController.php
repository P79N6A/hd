<?php
use CZTVPush\GeTui\TvCztvComSender;

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/27
 * Time: 11:33
 */

class TestController extends \PublishBaseController
{
    public function commentapiAction()
    {

    }

    public function indexAction()
    {
        echo "hello";
        exit;

    }



    /**
     * 消息队列测试
     */
    public function sendQueueAction()
    {
        $json = array(
            'name' => 'Jason Fan',
            'sex' => 'male',
            'age' => '33'
        );
        $res = $this->queue->sendMessage(json_encode($json), 'xml2');
        if (false !== $res) {
            echo "send queue succesed!";
        } else {
            echo "send queue error";
        }
    }

    /**
     * 获取队列消息测试
     */
    public function getQueueAction()
    {
        $json = $this->queue->getMessage('xml2');
        if (false !== $json) {
            echo $json;
        } else {
            echo "get queue error";
        }
    }


    public function testAndroidGeTuiAction()
    {
        $pushconfig['AppKey'] = 'aPNSzh7XGP9zOxlJHMzRh8';
        $pushconfig['MasterSecret'] = 'mo90xD1HC37ebGKmY26IN';
        $pushconfig['AppID'] = 'eZFGglK1Ej9lQx5yDak0T8';
        $pushdata['title'] = '亲，您关注的主播@兰新华开始直播啦，快去围观吧！';
        $pushdata['data'] = array('enterType'=>'10','videoId'=>'116','url'=>'rtmp://yf.ugcrtmp.l.cztv.com/live/3f34bf496411011fe35eb025553ef836_540p','videoName'=>'蓝魅直播');
        $clientid = array('b7d9a2ce07863150eb1b5e9c9247fda8');
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
        $pushdata['title'] = $android_msg;
        $sender = new TvCztvComSender($pushconfig['AppKey'], $pushconfig['MasterSecret'], $pushconfig['AppID']);
        $rep = $sender->sendToAndroid($pushdata, $clientid);
        echo json_encode($rep);
        exit;
    }

    public function testIOSAction()
    {
        $pushconfig['AppKey'] = 'Ujipw8GPlXAEQintOyt3d1';
        $pushconfig['MasterSecret'] = 'hvyY1PPcdL8SQae4ss43M1';
        $pushconfig['AppID'] = 'FCj1lU2B20AdcKMGM9Wjr';
        $pushdata['title'] = '亲，您关注的主播@陈忠华开始直播啦，快去围观吧！';
        $pushdata['data'] = array('enterType'=>'10','videoId'=>'116','url'=>'rtmp://yf.ugcrtmp.l.cztv.com/live/3f34bf496411011fe35eb025553ef836_540p','videoName'=>'蓝魅直播');
        $clientid = array('fe9bf1ea743b06ffc9feaed1ef1800b5');
        $sender = new TvCztvComSender($pushconfig['AppKey'], $pushconfig['MasterSecret'], $pushconfig['AppID']);
        $rep = $sender->sendToIos($pushdata, $clientid);
        echo json_encode($rep);
        exit;
    }


    public function testAction()
    {
        //$ugc_info = file_get_contents("php://input");
        $ugc_info = '{"stream_event":"end","stream_type":"push","ip":"202.96.126.25","time":1467908588,"app":"yf.push.cztv.com","appname":"live","id":"fde4f4043e72b99686906a95d92c48d2_540p","rtmp_url":"rtmp:\/\/yf.push.cztv.com\/live\/fde4f4043e72b99686906a95d92c48d2_540p","push_tool":"Lavf56.40.101","width":640,"height":480,"video_framerate":15,"videorate":800,"videocoding_algorithm":"AVC","audio_framerate":43,"audiorate":62,"audiocoding_algorithm":"AAC","audio_samplingrate":44100,"audio_channel":0,"Sign":"","node":"115.231.97.66","push_args":{}}';
        $ugc_info = json_decode($ugc_info,true);
        if(!$ugc_info)
            $this->_json([],404,'JSON ERROR');
        $ugcModel = UgcLive::findFirst("rtmp_url = '{$ugc_info['rtmp_url']}'");
        if(!$ugc_info['rtmp_url'] || !$ugcModel)
            $this->_json([],404,'RTMP ERROR');
        $ugcModel->source_ip = $ugc_info['ip'];
        $ugcModel->path = $ugc_info['appname'];
        $ugcModel->stream_nam = $ugc_info['id'];
        $ugcModel->domain = $ugc_info['app'];
        if($ugc_info['stream_event'] == 'end')
        {
            $ugcModel->end_time = $ugc_info['time'];
            $streamname = str_replace('_720p','',str_replace('_540p','',$ugc_info['id']));
            $video_file = $this->getVideoFileFromYF($streamname,$ugcModel->start_time,$ugc_info['time']);
            $ugc_live = UgcLive::getUgcLiveInfoByStream($streamname);
            if($video_file && is_array($video_file) && array_key_exists('url',$video_file) && $ugc_live)
            {
                $model_ugc_live_video = new UgcLiveVideo();
                $model_ugc_live_video->saveGetId(array('rate'=>'800K','date_id'=>0,
                    'file_url'=>$video_file['url'],'stream_id'=>$ugc_live->id));
            }
        }
        else
        {
            $ugcModel->start_time = $ugc_info['time'];
            $ugcModel->end_time = null;
        }


        $ugcModel->save();
        $this->_json([],200);
    }


    private function getVideoFileFromYF($stream,$start_time,$end_time)
    {
        $play_url ="http://yf.ugc.l.cztv.com/channels/live/{$stream}/540p.m3u8";
        $url = 'http://live2demand.yfcdn.net/cdn/v1/live2demand?';
        $url .= 'starttime='.$start_time;
        $url .= '&endtime='.$end_time;
        $url .= '&bitrate=800k';
        $url .= '&live_url='.base64_encode($play_url);
        $ret_json = F::curlRequest($url,'get',[],true);
        echo $url;
        $arr = json_decode($ret_json,true);
        return $arr;
    }



    public function pauseStreamAction()
    {
        $access_key = "44a8b3a2c6488031e937e4a4ace312af0829e2871f58ebaadc80ff9e7b093839";
        $secret_key = "cce61d0876c1612c1e5dd67ea2a19554456761c73e1f590c6459224d33c02dfd";

        $auth = new YfAuth($access_key,$secret_key);

        $url = '/api/ban_rtmp_url/set';
        $body = array(
            'RtmpUrl' => 'rtmp://yf.push.cztv.com/live/7b0cd28e86926d79142c23da3f58a138_540p',
            'StartBanSec'=>time(),
            'EndBanSec' => time()+3600*24*12*10);
        $token = $auth->getAccessToken($url, $body);
        $handle = curl_init();
        $headers = [
            "accessToken:$token",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        $url = 'http://api.yflive.net'.$url;
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($body,JSON_UNESCAPED_SLASHES));
        ob_start();
        $response = curl_exec($handle);
        ob_end_clean();
        curl_close($handle);
        var_dump($response);
    }


    public function restoreStreamAction()
    {
        $access_key = "44a8b3a2c6488031e937e4a4ace312af0829e2871f58ebaadc80ff9e7b093839";
        $secret_key = "cce61d0876c1612c1e5dd67ea2a19554456761c73e1f590c6459224d33c02dfd";

        $auth = new YfAuth($access_key,$secret_key);

        $url = '/api/ban_rtmp_url/delete?rtmp_url=rtmp://yf.push.cztv.com/live/7b0cd28e86926d79142c23da3f58a138_540p';

        $token = $auth->getAccessToken($url);
        $handle = curl_init();
        $headers = [
            "accessToken:$token",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        $url = 'http://api.yflive.net'.$url;
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        ob_start();
        $response = curl_exec($handle);
        ob_end_clean();
        curl_close($handle);
        var_dump($response);
    }


    public function testUrlAction()
    {
        $url = "http://live2demand.yfcdn.net/cdn/v1/live2demand?starttime=1468062437&endtime=1468062575&bitrate=800k&live_url=aHR0cDovL3lmLnVnYy5sLmN6dHYuY29tL2NoYW5uZWxzL2xpdmUvYjQ4NWY1YjdlYmNkYWU2NmYwZTMyNTYyZGVkZDAzMTIvNTQwcC5tM3U4";
        $response = F::curlRequest($url,'get',[],true);
        var_dump($response);
    }


    public function getVideoFileFromYFAction()
    {
        $stream = Request::getQuery('stream');
        $start_time = Request::getQuery('start_time');
        $end_time = Request::getQuery('end_time');
        $play_url ="http://yf.ugc.l.cztv.com/channels/live/{$stream}/540p.m3u8";
        $url = 'http://live2demand.yfcdn.net/cdn/v1/live2demand?';
        $url .= 'starttime='.$start_time;
        $url .= '&endtime='.$end_time;
        $url .= '&bitrate=800k';
        $url .= '&live_url='.base64_encode($play_url);
        echo $url;
    }


    public function testTempAction()
    {
        $pay = Payment::findOneByTradeNo('applepay','8854541252145');
        var_dump($pay);
    }
















}