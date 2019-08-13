<?php

namespace CZTVQueue\AliyunMNS;
/**
 * Created by PhpStorm.
 * User: Bw
 * Date: 16/4/29
 * Time: 下午5:19
 */
define('QUEUE_ROOT', APP_PATH . 'libraries/CZTVQueue/');


require_once(GETUI_ROOT . 'AliyunMNS/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Exception\MnsException;


class AliyunMNS {
    private $accessId;
    private $accessKey;
    private $endPoint;
    private $client;

    public function __construct($accessId, $accessKey, $endPoint) {
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
    public function createQueue($queueName) {
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
    public function viewQueue() {
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
    public function delQueue($queueName) {
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
    public function sendMessage($queueName, $messageBody) {
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
    public function receiveMessage($queueName, $flag = true) {
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
    public function deleteMessage($queueName, $response) {
        $queue = $this->client->getQueueRef($queueName);
        try {
            $res = $queue->deleteMessage($response->getReceiptHandle());
        } catch (MnsException $e) {
            return false;
        }
        return $res;
    }
}
