<?php

/**
 * client_id生成接口,队列处理定时任务
 */
class ApplepaymnsQueueTask extends Task
{

    /**
     * 默认方法,获取消息
     */
    public function mainAction()
    {
        while (1) {
            try {
                $res = $this->queue->getMessage("applepaymns");
                $order_data = json_decode($res, true);
                $order_no = $order_data['order_no'];

                $order_info = Payment::getItemByOrder($order_no);
                
                    $order_info->charge_way = $order_data['charge_way'];
                    $order_info->pay_account = $order_data['pay_account'];
                    $order_info->rec_account = $order_data['rec_account'];
                    //order_info->charget_datetime = $redata['receipt']['original_purchase_date_ms'];//苹果支付时间戳有问题
                    $order_info->charget_datetime = $order_data['charget_datetime'];
                    $order_info->charge_serial_no = $order_data['charge_serial_no'];
                    $order_info->return_mess = is_array($order_data['return_mess'])?json_encode($order_data['return_mess']):$order_data['return_mess'];

                    if($order_info->state != 3) {
                        if ($order_data['charge_way']=='alipay') {
                            if($order_info->state != 0&&$order_data['state']==2) {
                                echo "payment record insert failed!";

                            }
                            else {
                                $order_info->state = $order_data['state'];
                                if (!$order_info->save()) {
                                    echo "payment record insert failed!";
                                }
                            }
                        }
                        else if($order_data['charge_way']=='applepay'||$order_data['charge_way']=='weixinpay') {
                            $order_info->state = $order_data['state'];
                            if (!$order_info->save()) {
                                echo "payment record insert failed!";
                            }
                        }

                    }

            } catch (Exception $e) {
                sleep(1);                       //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }


}
