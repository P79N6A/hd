<?php

/**
 * client_id生成接口,队列处理定时任务
 */
class PaymentOrderQueueTask extends Task{

    /**
     * 默认方法,获取消息
     */
    public function mainAction(){
        while(1) {
            try {
                $res = $this->queue->getMessage("paymentordermns");
                $pay_data = json_decode($res, true);

                $modelPayment = new Payment();
                if(!$modelPayment->saveGetId($pay_data)) {
                    echo "order insert failed!";
                }
            } catch (Exception $e) {
                sleep(1);                       //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }




}
