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

class RealqueueTask extends Task {
    //每秒钟一次
    public function doAction() {
        $accessKeyId = 'LTAIcdYW4QQTMuQz';
        $accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
        $endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";
        $mns = new AliyunMNS($accessKeyId,$accessKeySecret,$endPoint);


        $queueName = 'xlwMNS';
        while(1){
            if($message = $mns->receiveMessage($queueName,false)){
                $queuedata = $message->getMessageBody();                
                $queuedata_arr = json_decode($queuedata,true);                
                if('lotterywinning' == $queuedata_arr['dbname']) {
                    try{

                    $lottery = $queuedata_arr['data'];
                    $lotterywinning = new LotteryWinnings();
                    $lotterywinning->channel_id = $lottery['channel_id'];
                    $lotterywinning->prize_id = $lottery['prize_id'];
                    $lotterywinning->prize_name = $lottery['prize_name'];
                    $lotterywinning->prize_level = $lottery['prize_level'];
                    $lotterywinning->prize_is_real = $lottery['prize_is_real'];
                    $lotterywinning->lottery_id = $lottery['lottery_id'];     //活动ID
                    $lotterywinning->lottery_group_id = $lottery['lottery_group_id'];    //抽奖活动ID
                    $lotterywinning->lottery_channel_id = $lottery['lottery_channel_id'];
                    $lotterywinning->client_id = $lottery['client_id'];
                    $lotterywinning->extra_value = $lottery['extra_value'];
                    $lotterywinning->created_at = $lottery['created_at'];
                    $id = $lotterywinning->saveGetId();
                    MemcacheIO::set('lotterywinning_'.$lottery['client_id'], $id, 600);
                    } catch (Exception $e) {
                    }
                    $mns->deleteMessage($queueName, $message);
                    echo "lotterywinning".PHP_EOL;
                      
                }
                else if('lotterycontact' == $queuedata_arr['dbname']) {
                    $contact = $queuedata_arr['data'];  //获取data数据
                    if((time()-$contact['created_at'])>600) {
                        $mns->deleteMessage($queueName, $message);

                    }
                    else {

                    $id = MemcacheIO::get('lotterywinning_'.$contact['token']);  //redis获取winid
                    if($id) {
                        $LotteryContacts = new LotteryContacts();
                        $LotteryContacts->id = $id;                 //ID不自增
                        //$LotteryContacts->token = $contact['token']; //注意传过来client_id要改
                        $LotteryContacts->token = time().md5(uniqid(str_random()));
                        $LotteryContacts->name = $contact['name'];
                        $LotteryContacts->mobile = $contact['mobile'];
                        $LotteryContacts->address = $contact['address'];
                        $LotteryContacts->prize_is_real = $contact['prize_is_real'];
                        $LotteryContacts->updated_at = $contact['updated_at'];
                        $LotteryContacts->created_at = $contact['created_at'];
                        $LotteryContacts->save();
                        $mns->deleteMessage($queueName, $message);
                        echo "lotterycontact".PHP_EOL;
                    }
                    }
                }
                MemcacheIO::set('queue_end',date('Y-m-d H:i:s',time()));  //记录队列处理时间
                
                echo "I got u".PHP_EOL;
                //todo something success
            }else{
                echo "do nothing".PHP_EOL;
                sleep(1);
            }
            
        }
    }
}