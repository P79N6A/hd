<?php

class PaymentController extends InteractionBaseController
{
    public function IndexAction()
    {
        echo 'ok';
        die();
    }

    /*
     * @desc 获取支付页面中的页面元素
     * @param apple_pay int 是否是苹果支付页面
     * @return string json
     * */
    public function getPaymentPageElementAction()
    {
        //TODO 返回支付方式JSON数据
        $data = array(
            "id" => "1",
            "name" => "支付宝",
            "identifier" => "alipay",
            "ratio" => "100000",
            "image" => "http://192.168.1.46/uploads/payment_type/2015-11-05/3d215da8d0198e784efe5d6271b40643.png");
        $this->_json($data);
    }

    /*
     *  @desc 请求支付，生成一个支付订单号，返回一个支付流水号
     *  @param channel_id 频道id
     *  @param order_type 订单类型
     *  @param application_id 应用ID
     *  @param request_time 请求时间
     *  @param source_order_no 请求订单编号
     *  @param uid 充值会员ID
     *  @param payment_type string 支付方式
     *  @param order_amount decimal 订单金额
     *  @return string json
     * */
    public function requestPayAction()
    {
        //TODO 生成支付订单
        $data = array(
            'order_no' => 'B2016022398100100',
            'order_amount' => 30);
        $this->_json($data);
    }

    /*
     * @desc 获取微信支付订单信息
     * @param orderid int 订单号
     *
     * */
    public function getWXOrderInfoAction()
    {
        //TODO 获取微信支付订单信息
        $data = array(
            'appid' => '',
            'partnerid' => '',
            'prepayid' => '',
            'package_val' => '',
            'noncestr' => '',
            'timestamp' => 0,
            'sign' => '');
        $this->_json($data);
    }


    protected function _json($data, $code = 1)
    {
        header('Content-type: application/json');
        echo json_encode([
            'result_code' => $code,
            'result_data' => $data,
        ]);
        exit;
    }


}