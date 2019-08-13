<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/7/13
 * Time: 19:53
 */
define('WeiXinPay_ROOT', APP_PATH . 'libraries/WxPayPubHelper/');
require_once(WeiXinPay_ROOT . 'WxPayPubHelper.php');
class ValidatorController extends ApiBaseController
{
    private $ugcConfig = array();
    private $queueOrderName = '';
    private $queuePaySuccessName = '';
    public function initialize()
    {
        $this->ugcConfig = app_site()->ugc_config;
        $this->queueOrderName = 'applepaymns';
        $this->queuePaySuccessName = 'notifypaymns';
        parent::initialize();
    }

    /**
     * 验证苹果支付
     * is_sandbox 不需要传值，先验证苹果返回0，成功，返回错误代码后21007，再验证沙盒。其他错误代码，直接抛出错误。
     * 2019-02-21
     * 文娱-李红刚
     */
    public function appleOrderAction()
    {
        //TODO 验证苹果支付有效性(1、通知客服端结果，2、通知服务)
        // ios_bid、ios_app_item_id由IOS开发人员提供
        $ios_bid = $this->ugcConfig['ugc_apple_pay_bid'];
        $ios_app_item_id = $this->ugcConfig['ugc_apple_pay_appid'];
        $post_data = Request::getPost();
        // $is_sandbox = isset($post_data['is_sandbox']) ? strtoupper($post_data['is_sandbox']) : 'NO';
        $order_no = isset($post_data['order_no']) ? $post_data['order_no'] : 0;
        if(empty($post_data['order_no']))
        {
            $result['result_code'] = 10073;
            $this->_json([], $result['result_code'], '缺少订单号');exit();
        }
        $rediskey = D::redisKey('ltv_apple_order',$post_data['order_no']);
        if(RedisIO::exists($rediskey))
        {
            $data_order = json_decode(RedisIO::get($rediskey),true);
        }else{
            $order_info = Payment::getItemByOrder($order_no);
            if (!$order_info) {
                $result['result_code'] = 10073;
                $this->_json([], $result['result_code'], '数据库未发现订单号');
            }
            $data_order = $order_info->toArray();
        }
        if (empty($post_data['apple_id'])) {
            $result['result_code'] = 10073;
            $this->_json([], $result['result_code'], '缺少苹果商品ID');
        } elseif (empty($post_data['order_no'])) {
            //缺少订单号
            $result['result_code'] = 10073;
            $this->_json([], $result['result_code'], '缺少订单号');
        } elseif (!isset($data_order['order_no']) || $data_order['order_no'] == '') {
            //缺少订单号
            $result['result_code'] = 10080;
            $this->_json([], $result['result_code'], '订单不存在');
        } elseif (!isset($post_data['ios_buy_code']) || $post_data['ios_buy_code'] == '') {
            //缺少IOS购买凭证
            $result['result_code'] = 10074;
            $this->_json([], $result['result_code'], '缺少IOS购买凭证');
        } elseif (isset($data_order['state']) && $data_order['state'] == 1) {
            $result['result_code'] = 10075;
            $this->_json([], $result['result_code'], '订单已处理');
        } else {
            $postdata = array("receipt-data" => $post_data['ios_buy_code']);
            // if ($is_sandbox == 'YES') {
            //     $ios_url = 'https://sandbox.itunes.apple.com/verifyReceipt';
            // } else {
            $ios_url = 'https://buy.itunes.apple.com/verifyReceipt';
            // }

            $curl = curl_init(); // 启动一个CURL会话
            curl_setopt($curl, CURLOPT_URL, $ios_url); // 要访问的地址
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata)); // Post提交的数据包
            curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($postdata)))
            );
            $tmpInfo = curl_exec($curl); // 执行操作
            if (curl_errno($curl)) {
                echo 'Errno' . curl_error($curl);//捕抓异常
            }
            curl_close($curl); // 关闭CURL会话

            if ($tmpInfo != '') {
                $redata = json_decode($tmpInfo, true);
                if (isset($redata['status']) && $redata['status'] == 0 && isset($redata['receipt']) && $redata['receipt']['bid'] == $ios_bid) {
                    if (isset($redata['receipt']['app_item_id']) && $redata['receipt']['app_item_id'] != $ios_app_item_id) {
                        $result['result_code'] = 10076;
                        $this->_json([], $result['result_code'], '购买凭证中的app_item_id有误');
                    } else {
                        if ($post_data['apple_id'] == $redata['receipt']['product_id']) {
                            //TODO 通知云朗支付成功要求队列
                           
                            //构建数据
                            $queue_data_order = array(
                                "order_no" => $order_no,
                                "state" => 1,
                                "charge_way" => "applepay",
                                "pay_account" => "",
                                "rec_account" => $redata['receipt']['bid'],
                                "charget_datetime" => time(),
                                "charge_serial_no" => $redata['receipt']['transaction_id'],
                                "return_mess" => json_encode($redata['receipt']),
                            );

                            //队列处理paymentordermns
                            $this->queue->sendMessage(json_encode($queue_data_order), $this->queueOrderName);
                            //队列处理通知云朗支付成功

                            $queue_data_success = array(
                                'uid'=>$data_order['uid'],
                                'order_no'=> $data_order['order_no'],
                                'payment'=>'applepay',
                                'amount'=>$data_order['amount']);
                            $this->queue->sendMessage(json_encode($queue_data_success), $this->queuePaySuccessName);

                            $result['result_code'] = 1;
                            $this->_json([], $result['result_code'], 'SUCCESS');
                        } else {
                            $result['result_code'] = 10077;
                            $this->_json([], $result['result_code'], "购买凭证中的product_id有误");
                        }
                    }
                } elseif (isset($redata['status']) && $redata['status'] == 21007) {
                    $ios_url = 'https://sandbox.itunes.apple.com/verifyReceipt';
                    $curl = curl_init(); // 启动一个CURL会话
                    curl_setopt($curl, CURLOPT_URL, $ios_url); // 要访问的地址
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
                    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata)); // Post提交的数据包
                    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
                    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen(json_encode($postdata)))
                    );
                    $tmpInfo = curl_exec($curl); // 执行操作
                    if (curl_errno($curl)) {
                        echo 'Errno' . curl_error($curl);//捕抓异常
                    }
                    curl_close($curl); // 关闭CURL会话

                    if ($tmpInfo != '') {
                        $redata = json_decode($tmpInfo, true);
                        if (isset($redata['status']) && $redata['status'] == 0 && isset($redata['receipt']) && $redata['receipt']['bid'] == $ios_bid) {
                            if (isset($redata['receipt']['app_item_id']) && $redata['receipt']['app_item_id'] != $ios_app_item_id) {
                                $result['result_code'] = 10076;
                                $this->_json([], $result['result_code'], '购买凭证中的app_item_id有误');
                            } else {
                                if ($post_data['apple_id'] == $redata['receipt']['product_id']) {
                                    //TODO 通知云朗支付成功要求队列

                                    //构建数据
                                    $queue_data_order = array(
                                        "order_no" => $order_no,
                                        "state" => 1,
                                        "charge_way" => "applepay",
                                        "pay_account" => "",
                                        "rec_account" => $redata['receipt']['bid'],
                                        "charget_datetime" => time(),
                                        "charge_serial_no" => $redata['receipt']['transaction_id'],
                                        "return_mess" => json_encode($redata['receipt']),
                                    );

                                    //队列处理paymentordermns
                                    $this->queue->sendMessage(json_encode($queue_data_order), $this->queueOrderName);
                                    //队列处理通知云朗支付成功

                                    $queue_data_success = array(
                                        'uid'=>$data_order['uid'],
                                        'order_no'=> $data_order['order_no'],
                                        'payment'=>'applepay',
                                        'amount'=>$data_order['amount']);
                                    $this->queue->sendMessage(json_encode($queue_data_success), $this->queuePaySuccessName);

                                    $result['result_code'] = 1;
                                    $this->_json([], $result['result_code'], 'SUCCESS');
                                } else {
                                    $result['result_code'] = 10077;
                                    $this->_json([], $result['result_code'], "购买凭证中的product_id有误");
                                }
                            }
                        } else {
                            $result['result_code'] = 10078;
                            $this->_json(['url' => $ios_url], $result['result_code'], "购买凭证中的bid有误");
                        }
                    } else {

                        $result['result_code'] = 10079;
                        $this->_json([], $result['result_code'], "购买凭证校验失败");
                    }
                } else {
                    $result['result_code'] = 10078;
                    $this->_json(['url' => $ios_url], $result['result_code'], "购买凭证中的bid有误");
                }
            } else {

                $result['result_code'] = 10079;
                $this->_json([], $result['result_code'], "购买凭证校验失败");
            }
        }
    }


    public function weixinOrderAction()
    {
        $post_data = Request::getPost();
        if (!isset($post_data['order_no'])) {
            $this->_json([], 10073, '缺少订单号');
        } else {
            $redis_order_key = D::redisKey('ltv_wx_order',$post_data['order_no']);
            $order_info = json_decode(RedisIO::get($redis_order_key),true);
            $amount = $order_info['amount'];
            if ($order_info) {
                ini_set('date.timezone', 'Asia/Shanghai');
                $nativeCall = new NativeCall_pub();
                //元转换成分
                $order_amount = $amount * 100;
                //使用统一支付接口
                $unifiedOrder = new UnifiedOrder_pub();
                $unifiedOrder->setParameter("body", "中国蓝TV蓝魅直播充值");//商品描述
                //自定义订单号，此处仅作举例
                $timeStamp = time();
                $notify_url = $this->ugcConfig['ugc_pay_wx_notice_url'];
                $unifiedOrder->setParameter("out_trade_no", $post_data['order_no']);//商户订单号
                $unifiedOrder->setParameter("total_fee", $order_amount);//总金额
                $unifiedOrder->setParameter("notify_url", $notify_url);//通知地址
                $unifiedOrder->setParameter("trade_type", "APP");//交易类型
                //获取prepay_id
                $prepay_id = $unifiedOrder->getPrepayId();
                $nativeCall->setReturnParameter("return_code", "SUCCESS");//返回状态码
                $nativeCall->setReturnParameter("result_code", "SUCCESS");//业务结果
                $nativeCall->setReturnParameter("prepay_id", "$prepay_id");//预支付ID
                //将结果返回微信
                $returnXml = $nativeCall->returnXml();
                $simplexmlt = json_decode(json_encode(simplexml_load_string($returnXml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

                if (!$simplexmlt['prepay_id']) {
                    $result['result_code'] = 1170;
                    $this->_json([], 1170, '生成微信预支付ID失败请重试');
                    exit;
                }
                $simplexml['appid'] = WxPayConf_pub::APPID;
                $simplexml['partnerid'] = WxPayConf_pub::MCHID;
                $simplexml['prepayid'] = $simplexmlt['prepay_id'];
                $simplexml['package'] = 'Sign=WXPay';
                $simplexml['noncestr'] = $nativeCall->createNoncestr();
                $simplexml['timestamp'] = time();
                $simplexml['sign'] = $nativeCall->getSign($simplexml);
                $simplexml['package_val'] = $simplexml['package'];
                unset($simplexml['package']);
                $this->_json($simplexml);
            } else {
                $this->_json([], 10080, '订单号不存在');
            }
        }
    }

}