<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/6/20
 * Time: 2:17
 */
define('WeiXinPay_ROOT', APP_PATH . 'libraries/WxPayPubHelper/');
define('Alipay_ROOT',APP_PATH.'libraries/alipay/');
require_once(WeiXinPay_ROOT . 'WxPayPubHelper.php');
require_once(Alipay_ROOT . 'alipay.config.php');
require_once(Alipay_ROOT . 'lib/alipay_notify.class.php');


class PaymentnotifyController extends InteractionBaseController
{

    private $pay_way = '';
    private $ugcConfig = array();
    private $trade_no = ''; //第三方支付生成的流水号

    public function initialize()
    {
        $this->ugcConfig = app_site()->ugc_config;
    }

    public function alipayAction()
    {
        $alipay_config = $GLOBALS['alipay_config'];
        $post_data= Request::getPost();
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            $order_no = $post_data['out_trade_no'];			//订单号
            $trade_no = $post_data['trade_no'];				//支付宝交易号
            $trade_status = $post_data['trade_status'];		//交易状态
            $this->pay_way = 'alipay';
            $this->trade_no = $trade_no;
            if($this->preventTradeUnique() === true)
            {
                exit;                                       //防止刷单机制 如果第三方订单存在，就退出,不做任何处理
            }
            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $order_info = Payment::getItemByOrder($order_no);
                if($order_info)
                {
                    if(isset($order_info->amount) && $order_info->amount == $post_data['total_fee'] && $order_info->state != 1){
                        //队列处理paymentordermns
                        $queueName = "applepaymns";
                        $orderData = array(
                            "order_no" => $order_no,
                            "state" => 1,
                            "charge_way" => $this->pay_way,
                            "pay_account" => $post_data['buyer_email'],
                            "rec_account" => $post_data['seller_email'],
                            "charget_datetime" => time(),
                            "charge_serial_no" => $trade_no,
                            "return_mess" => json_encode($post_data),
                        );
                        $this->queue->sendMessage(json_encode($orderData), $queueName);

                        //通知APP接口支付成功
                        $queueNotifyName = 'notifypaymns';
                        $notifydata = array(
                            'uid'=>$order_info->uid,
                            'order_no'=> $order_info->order_no,
                            'payment'=>"alipay",
                            'amount'=>$order_info->amount);
                        $this->queue->sendMessage(json_encode($notifydata), $queueNotifyName);

                        echo "success";exit;
                    }else{
                        echo "fail";
                    }
                }
            }elseif($trade_status == 'WAIT_BUYER_PAY'){
                $order_info = Payment::getItemByOrder($order_no);
                if($order_info->state == 0){
                    $orderData['state'] = 2;
                    $queueName = "applepaymns";
                    $this->queue->sendMessage(json_encode($orderData), $queueName);
                }
                echo "fail";
            }else{
                echo "fail";
            }
        }else{
            echo "fail";
        }
    }
    /*
     * @desc 微信异步通知支付结果
     *
     * */
    public function weixinAction()
    {
        $queueName = "applepaymns";
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        if ($notify->checkSign()) {
            $this->trade_no = $notify->data["transaction_id"];
            $this->pay_way = 'weixinpay';
            if($this->preventTradeUnique() === true)
            {
                exit;                                       //防止刷单机制 如果第三方订单存在，就退出,不做任何处理
            }
            if ($notify->data["return_code"] == 'SUCCESS') {
                $total_fee = $notify->data["total_fee"] / 100;
                //获取微信的YB购买比率
                $detail['err_code'] = $notify->data["err_code"];
                $detail['err_code_des'] = $notify->data["err_code_des"];
                $detail['trade_type'] = $notify->data["trade_type"];
                $detail['bank_type'] = $notify->data["bank_type"];
                $detail['total_fee'] = $total_fee;
                $detail['cash_fee'] = $notify->data["cash_fee"];
                $order_detail = json_encode($notify->data);
                if ($notify->data["result_code"] == 'SUCCESS') {
                    $order = $notify->data["out_trade_no"];
                    $order_info = Payment::getItemByOrder($order);
                    if ($order_info &&
                        ($order_info->state != 1 || $order_info->state != 100) &&
                        $order_info->amount = $total_fee) {

                        //构建数据
                        $orderData = array(
                            "order_no" => $order,
                            "state" => 1,
                            "charge_way" => $this->pay_way,
                            "pay_account" => $notify->data['openid'],
                            "rec_account" => $notify->data['mch_id'],
                            "charget_datetime" => time(),
                            "charge_serial_no" => $notify->data["transaction_id"],
                            "return_mess" => $order_detail,
                        );
                        //队列处理paymentordermns
                        $this->queue->sendMessage(json_encode($orderData), $queueName);
                        //队列通知云朗支付成功状态消息
                        $queueNotifyName = 'notifypaymns';
                        $notifydata = array(
                            'uid'=>$order_info->uid,
                            'order_no'=> $order_info->order_no,
                            'payment'=>$this->pay_way,
                            'amount'=>$order_info->amount);
                        $this->queue->sendMessage(json_encode($notifydata), $queueNotifyName);
                        $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
                        $notify->setReturnParameter("return_msg", "支付成功");//返回信息
                    }
                } else {
                    $notify->setReturnParameter("return_code", "FAIL");    //返回状态码
                    $notify->setReturnParameter("return_msg", "支付失败"); //返回信息
                }
            }
        } else {
            $notify->setReturnParameter("return_code", "FAIL");    //返回状态码
            $notify->setReturnParameter("return_msg", "支付失败"); //返回信息
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
    }


    private function preventTradeUnique()
    {
        $rec = Payment::findOneByTradeNo($this->trade_no,$this->pay_way);
        return $rec?true:false;
    }
}
