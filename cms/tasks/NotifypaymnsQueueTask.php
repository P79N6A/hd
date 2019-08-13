<?php

/**
 * client_id生成接口,队列处理定时任务
 */
class NotifypaymnsQueueTask extends Task{
    private $order_sn = '';
    private $queuename = '';
    const MAX_QUE_COUNT = 10;


    /**
     * 默认方法,获取消息
     */
    public function mainAction(){
        while(1) {
            try {
                $this->queuename = 'notifypaymns';
                $res = $this->queue->getMessage($this->queuename);
                $orderData = json_decode($res, true);
                $this->order_sn = $orderData['order_no'];
                $redis_count_key = D::redisKey('queue_count', $this->order_sn);
                $ret = $this->sendPayMessToVL($orderData['uid'], $orderData['order_no'],
                    $orderData['payment'],$orderData['amount']);
                $ret = json_decode($ret, true);
                if (array_key_exists('result_code', $ret) && $ret['result_code'] == '200') {
                    $order_info = Payment::getItemByOrder($this->order_sn);
                    $order_info->state = 3;
                    $order_info->charge_way = $orderData['payment'];
                    $order_info->charget_datetime = $order_info->charget_datetime?$order_info->charget_datetime:time();
                    $order_info->save();
                }
                else {
                    RedisIO::incr($redis_count_key);                   //没有任务时休息1秒
                    if(RedisIO::get($redis_count_key) <= self::MAX_QUE_COUNT) {
                        sleep(10);
                        $this->queue->sendMessage($res, $this->queuename);
                    }
                }

            }
            catch (Exception $e) {
                sleep(1);                       //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }
    private function sendPayMessToVL($user_id, $order_no, $payment, $amount)
    {
        $timestamp = time();
        $appid = "776d0a3681142ed9fa4af34c9fa757b3";
        $secret = "533536ec9ac8665b17238c3453c5049d";
        $post_data = array(
            'user_id' => $user_id,
            'order_no' => $order_no,
            'payment' => $payment,
            'amount' => $amount);
        $signature = F::getSignature($appid, $timestamp, $secret, $post_data);
        $url =  "https://apihudongugc.cztv.com/user/increase_currency?";
        $url .= "app_id={$appid}&secrect={$secret}&signature={$signature}&timestamp=$timestamp";
        $ret = F::curlRequest($url, 'post', $post_data);
        return $ret;
    }






}
