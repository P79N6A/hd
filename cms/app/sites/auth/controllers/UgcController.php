<?php
/*
 *@desc UGC直播接口
 *
 * */
define('WeiXinPay_ROOT', APP_PATH . 'libraries/WxPayPubHelper/');
require_once(WeiXinPay_ROOT . 'WxPayPubHelper.php');

class UgcController extends ApiBaseController
{
    private $ugcUids = '';   //个推用户IDS
    private $ugcAnchorId = 0;     //主播ID
    private $gt_live_url = '';    //主播直播URL
    private $gt_nick_name = ''; //主播昵称
    private $ugcIOSGtCids = array(); //个推IOS个推Clients
    private $ugcAndroidGtCids = array(); //个推Androids个推Clients。
    private $ugcGtData = array(); //个推内容
    private $ugcConfig = array();
    private $ugcGTType = '10';
    private $ugcGTContent = '';
    const STREAM_PAUSE_DURATION = 600; //禁播默认时长，10分钟
    const MAX_PAUSE_TIME = '2026-01-01 00:00:00'; //永久禁流结束时间
    public function initialize()
    {
        $this->ugcConfig = app_site()->ugc_config;
        $action = $this->dispatcher->getActionName();
        if ($action != 'callback') {
            parent::initialize();
        }
    }

    /*
     *
     * @desc 主播开播消息个推到关注过的用户APP上面
     * @author 冯固
     * @date 2016-6-6
     *
     * */
    public function anchorGeTuiAction()
    {
        //TODO 主播开播消息个推到用户APP上。
        $this->ugcUids = Request::getPost('follow_list', 'string', '');  //用户IDS
        $this->ugcAnchorId = Request::getPost('admin_id', 'string', '');    //主播ID
        $this->gt_live_url = Request::getPost('live_url', 'string'); //主播直播房间URL
        $this->gt_nick_name = Request::getPost('nick', 'string', '');
        file_put_contents('gt.log', "VL>>[" . date("Y-m-d H:i:s", time()) . "]" . json_encode(Request::getPost()) . "\n", FILE_APPEND);
        $this->getUserClients();
        file_put_contents('gt.log', "XL>>[" . date("Y-m-d H:i:s", time()) . "]" . json_encode($this->ugcIOSGtCids) . "\n", FILE_APPEND);
        file_put_contents('gt.log', "XL>>[" . date("Y-m-d H:i:s", time()) . "]" . json_encode($this->ugcAndroidGtCids) . "\n", FILE_APPEND);
        if (!empty($this->ugcIOSGtCids))
            $this->anchorLiveIOSGT();
        if (!empty($this->ugcAndroidGtCids))
            $this->anchorLiveAndroidGT();
        $this->_json([]);
    }


    /*
     * @desc 全量个推
     * */
    public function ugcToAllGeTuiAction()
    {
        $content = Request::getPost('content','string','');
        $type = Request::getPost('type','int',0);
        $url = Request::getPost('url','string','');
        if(empty($content) || empty($type) || empty($url) || ($type != 10 && $type!= 11))
        {
            $this->_json([],3001,'参数不正确');
            exit;
        }
        $this->ugcGTContent = $content;
        $this->ugcGTType = $type;
        $this->ugcAnchorId = 0;
        $this->gt_live_url = $url;
        $this->ugcToAllAndroidGT($this->ugcGTContent,$this->ugcGTType);
        $this->ugcToAllIosGT($this->ugcGTContent,$this->ugcGTType);
        $this->_json([]);
    }



    /*
     * @desc 获取云帆的点播文件
     *
     * */
    public function getUgcMediaUrlAction()
    {
        //TODO 获取点播文件
        $postData = Request::getParams();
        $start_time = $postData['starttime'];
        $end_time = $postData['endtime'];
        $stream = $postData['stream'];
        $admin_id = $postData['admin_id'];
        $ugcStream = UgcStream::getStreamByname($stream);
        if (empty($admin_id) || empty($stream) || empty($start_time) || empty($end_time) || !$ugcStream) {
            $this->_json([], 3001, '参数无效');
            exit();
        }
        $video_file = UgcVideoFile::getVideoUrlByStream($ugcStream->id, $start_time, $end_time);
        if ($video_file) {
            $this->_json(array(
                'media_url' => $video_file->video_url,
                'duration' => $video_file->duration
            ));
            exit();
        }
        //TODO 读取RDS 缓存数据
        //云帆接口获取点播文件
        $ret = $this->getVideoFileFromYF($stream, $start_time, $end_time);
        if (empty($ret) || $ret['status'] != '1') {
            $this->_json([], 3002, '网络接口故障');
            exit();
        }
        $media_url = $ret['url'];
        $start_time = $ret['starttime'];
        $end_time = $ret['endtime'];
        $duration = intval($ret['duration']);
        $data = array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'stream_id' => $ugcStream->id,
            'video_url' => $media_url,
            'duration' => $duration,
            'created_at' => time());
        $modelUgcVideoFile = new UgcVideoFile();
        $id = $modelUgcVideoFile->saveGetId($data);
        if ($id) {
            $this->_json(array('media_url' => $media_url, 'duration' => $duration));
            exit();
        } else {
            $this->_json([], 3003, '数据记录错误');
            exit();
        }
    }

    /*
     * @desc 从云帆接口获取
     * */
    private function getVideoFileFromYF($stream, $start_time, $end_time)
    {
        $play_url = $this->ugcConfig['ugc_yf_play_host'] . "/channels/live/{$stream}/540p.m3u8";
        $url = $this->ugcConfig['ugc_yf_api_url1'];
        $url .= 'starttime=' . $start_time;
        $url .= '&endtime=' . $end_time;
        $url .= '&bitrate=800k';
        $url .= '&live_url=' . base64_encode($play_url);
        $ret_json = F::curlRequest($url, 'get', [], true);
        $arr = json_decode($ret_json, true);
        return $arr;
    }


    /*
     * @desc APP通知微信支付
     * @param order_no string 订单编号
     * @param wx_trade_type  string 交易类型 default APP
     * @param wx_goods_desc string 商品描述
     * @return json
     * */
    public function noticeWeiXinPayAction()
    {
        $post_data = Request::getPost();
        if (!isset($post_data['order_no'])) {
            $this->_json([], 10073, '缺少订单号');
            //$result['result_code'] = 10073;
        } else {
            $order_info = Payment::getItemByOrder($post_data['order_no']);
            if ($order_info) {
                ini_set('date.timezone', 'Asia/Shanghai');
                error_reporting(E_ERROR);
                //require_once "third_party/weixin_pay/WxPayPubHelper/WxPayPubHelper.php";
                $nativeCall = new NativeCall_pub();
                //元转换成分
                $order_amount = $order_info->amount * 100;
                //使用统一支付接口
                $unifiedOrder = new UnifiedOrder_pub();
                //$item_info = get_field_by_where("item", "name", "id='{$order_info['item_id']}'");
                $unifiedOrder->setParameter("body", "ugc充值");//商品描述
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
                // print_r($simplexmlt);exit;
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


    /*
     * @desc 验证苹果支付有效性
     * @param ios_buy_code string IOS订单编号
     * @param is_sandbox boolean 是否是沙盒模式
     * @param order_no string 支付订单编号
     * @param apple_id 苹果商品ID
     *
     * */
    public function applePayValidateAction()
    {
        //TODO 验证苹果支付有效性(1、通知客服端结果，2、通知服务)
        // ios_bid、ios_app_item_id由IOS开发人员提供
        $ios_bid = $this->ugcConfig['ugc_apple_pay_bid'];
        $ios_app_item_id = $this->ugcConfig['ugc_apple_pay_appid'];
        $post_data = Request::getPost();
        $is_sandbox = isset($post_data['is_sandbox']) ? strtoupper($post_data['is_sandbox']) : 'NO';
        $order_no = isset($post_data['order_no']) ? $post_data['order_no'] : 0;
        $order_info = Payment::getItemByOrder($order_no);
        if (!$order_info) {
            $this->_json([], 10073, '数据库未发现订单号');
        }
        $data_order = $order_info->toArray();
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
            if ($is_sandbox == 'YES') {
                $ios_url = 'https://sandbox.itunes.apple.com/verifyReceipt';
            } else {
                $ios_url = 'https://buy.itunes.apple.com/verifyReceipt';
            }

            $curl = curl_init(); // 启动一个CURL会话
            curl_setopt($curl, CURLOPT_URL, $ios_url); // 要访问的地址
            if (CZTV_PROXY_ST == 1) {
                curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
                curl_setopt($curl, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
                curl_setopt($curl, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
                curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
            }
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
                            $order_info->state = 1;
                            $order_info->charge_way = "applepay";
                            $order_info->pay_account = '';
                            $order_info->rec_account = $redata['receipt']['bid'];
                            //order_info->charget_datetime = $redata['receipt']['original_purchase_date_ms'];//苹果支付时间戳有问题
                            $order_info->charget_datetime = time();
                            $order_info->charge_serial_no = $redata['receipt']['transaction_id'];
                            $order_info->return_mess = json_encode($redata['receipt']);
                            $order_info->save();
                            //TODO 通知云朗支付成功
                            $ret = $this->sendPayMessToVL(
                                $order_info->uid,
                                $order_info->order_no,
                                $order_info->charge_way,
                                $order_info->amount);
                            $ret = json_decode($ret, true);
                            if (array_key_exists('result_code', $ret) && $ret['result_code'] == '200') {
                                //修改订单状态为3
                                $order_info->state = 3;
                                $order_info->save();
                            }
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
        }
    }





    /*
     * @desc 禁流
     *
     * */
    public function ugcPauseStreamAction()
    {
        $stream = Request::getPost('stream');   //流名称
        $duration = Request::getPost('duration', 'int', 0); //禁播时长,单位秒
        $duration = $duration == 0 ? self::STREAM_PAUSE_DURATION : $duration;
        $model_stream = UgcStream::getStreamByname($stream);
        if (empty($model_stream->toArray())) {
            $this->_json([], 3001, '流名称不存在');
            exit;
        }
        $starttime = time();
        $access_key = $this->ugcConfig['ugc_yf_api_accesskey'];
        $secret_key = $this->ugcConfig['ugc_yf_api_secretkey'];
        $auth = new YfAuth($access_key, $secret_key);
        $path = '/api/ban_rtmp_url/set';
        $rtmpUrl = $this->ugcConfig['ugc_yf_rtmp_host']."/live/{$stream}_540p";
        $endtime = strtotime(self::MAX_PAUSE_TIME);
        //$endtime = $starttime+$duration;
        $body = array(
            'RtmpUrl' => $rtmpUrl,
            'StartBanSec' => $starttime,
            'EndBanSec' => $endtime);
        $token = $auth->getAccessToken($path, $body);
        $headers = [
            "accessToken:$token",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        $url = $this->ugcConfig['ugc_yf_api_host'].$path;
        $strret = F::crulYFApiRequest($url,$headers,$body);
        $ret = json_decode($strret,true);
        if(boolval($ret['Ok']))
        {
            $model_stream->is_pause = 1;
            $model_stream->save();
            $this->_json([]);
        }
        else{
            $this->_json([],3002,$ret['Errormessage']);
        }
    }
    /*
     * @desc 删除禁流
     *
     * */
    public function ugcRestoreStreamAction()
    {
        $stream = Request::getPost('stream');   //流名称
        $model_stream = UgcStream::getStreamByname($stream);
        if (empty($model_stream->toArray())) {
            $this->_json([], 3001, '流名称不存在');
            exit;
        }
        $access_key = $this->ugcConfig['ugc_yf_api_accesskey'];
        $secret_key = $this->ugcConfig['ugc_yf_api_secretkey'];
        $auth = new YfAuth($access_key, $secret_key);

        $rtmpUrl = $this->ugcConfig['ugc_yf_rtmp_host']."/live/{$stream}_540p";
        $url = "/api/ban_rtmp_url/delete?rtmp_url={$rtmpUrl}";
        $token = $auth->getAccessToken($url);
        $headers = [
            "accessToken:$token",
            "Connection:keep-alive",
            "Cache-Control:max-age=0"
        ];
        $url = $this->ugcConfig['ugc_yf_api_host'].$url;
        $strret = F::crulYFApiRequest($url,$headers);
        $ret = json_decode($strret,true);
        if(boolval($ret['Ok']))
        {
            $model_stream->is_pause = 0;
            $model_stream->save();
            $this->_json([]);
        }
        else{
            $this->_json([],3002,$ret['Errormessage']);
        }
    }




    private function sendPayMessToVL($uid, $order_no, $payment, $amount)
    {
        $timestamp = time();
        $appid = $this->ugcConfig['ugc_vl_app_id'];
        $secret = $this->ugcConfig['ugc_vl_app_secrect'];
        $post_data = array(
            'user_id' => $uid,
            'order_no' => $order_no,
            'payment' => $payment,
            'amount' => $amount);
        $signature = $this->sign($appid, $timestamp, $secret, $post_data);
        $url = $this->ugcConfig['ugc_interface_host'] . '/user/increase_currency?';
        $url .= "app_id={$appid}&secrect={$secret}&signature={$signature}&timestamp=$timestamp";
        $ret = F::curlRequest($url, 'post', $post_data);
        return $ret;
    }

    /*
     * @生成签名     *
     * */
    private function sign($appid, $timestamp, $secret, $inputdata)
    {
        $timestamp = strval($timestamp);
        $inputdata['key'] = substr($secret, $timestamp[strlen($timestamp) - 1]);
        $inputdata['app_id'] = $appid;
        $inputdata['timestamp'] = $timestamp;

        ksort($inputdata);
        $signature = self::buildQuery($inputdata);
        return sha1(base64_encode($signature));
    }


    private static function buildQuery(array $input)
    {
        $query = [];
        if (!empty($input)) {
            foreach ($input as $k => $v) {
                $query[] = $k . "=" . $v;
            }
        }
        return implode("&", $query);
    }

    /*
     * @desc IOS全量推送
     * */
    private function ugcToAllIosGT($content,$type)
    {
        $this->ugcGtData = array($content,
            'data' => array('enterType' => strval($type), 'videoId' => $this->ugcAnchorId, 'url' => $this->gt_live_url, 'videoName' => '蓝魅直播'));
        $pushconfig['AppKey'] = $this->ugcConfig['ugc_gt_ios_appkey'];
        $pushconfig['MasterSecret'] = $this->ugcConfig['ugc_gt_ios_secret'];
        $pushconfig['AppID'] = $this->ugcConfig['ugc_gt_ios_appid'];
        return F::getuiIOSTvCztvProxy($pushconfig, $this->ugcGtData);
    }
    /*
     * @desc Android 全量推送
     * */
    private  function ugcToAllAndroidGT($content,$type)
    {
        $this->ugcGtData = array('title' => $content,
            'data' => array('enterType' => $type, 'videoId' => $this->ugcAnchorId, 'url' => $this->gt_live_url, 'videoName' => '蓝魅直播'));
        $pushconfig['AppKey'] = $this->ugcConfig['ugc_gt_ios_appkey'];
        $pushconfig['MasterSecret'] = $this->ugcConfig['ugc_gt_ios_secret'];
        $pushconfig['AppID'] = $this->ugcConfig['ugc_gt_ios_appid'];
        return F::getuiAndriodTvCztv($pushconfig,$this->ugcGtData);
    }
    /**
     * 主播开播IOS个推开播消息
     * */
    private function anchorLiveIOSGT()
    {
        $this->ugcGtData = array('title' => '亲，您关注的主播@' . $this->gt_nick_name . '开始直播啦，快去围观吧！',
            'data' => array('enterType' => '10', 'videoId' => $this->ugcAnchorId, 'url' => $this->gt_live_url, 'videoName' => '蓝魅直播'));
        $pushconfig['AppKey'] = $this->ugcConfig['ugc_gt_ios_appkey'];
        $pushconfig['MasterSecret'] = $this->ugcConfig['ugc_gt_ios_secret'];
        $pushconfig['AppID'] = $this->ugcConfig['ugc_gt_ios_appid'];
        foreach ($this->ugcIOSGtCids as $clients)
            F::getuiIOSTvCztv($pushconfig, $this->ugcGtData, $clients);
    }
    /**
     * 主播开播Android个推开播消息
     * */
    private function anchorLiveAndroidGT()
    {

        $this->ugcGtData = array('title' => '亲，您关注的主播@' . $this->gt_nick_name . '开始直播啦，快去围观吧！',
            'data' => array('enterType' => 10, 'videoId' => $this->ugcAnchorId, 'url' => $this->gt_live_url, 'videoName' => '蓝魅直播'));
        $pushconfig['AppKey'] = $this->ugcConfig['ugc_gt_android_appkey'];
        $pushconfig['MasterSecret'] = $this->ugcConfig['ugc_gt_android_secret'];
        $pushconfig['AppID'] = $this->ugcConfig['ugc_gt_android_appid'];
        foreach ($this->ugcAndroidGtCids as $clients)
            F::getuiAndriodTvCztv($pushconfig, $this->ugcGtData, $clients);
    }

    /**
     * 获取用户的个推ID号
     * */
    private function getUserClients()
    {
        $this->ugcUids = explode(',', $this->ugcUids);
        $this->ugcAndroidGtCids = [];
        $this->ugcIOSGtCids = [];
        if (!empty($this->ugcUids)) {
            foreach ($this->ugcUids as $user_id) {
                //逐个获取user_id的Redis Key值
                $arrUserIds = Client::getInfoByUserID($user_id);

                if ($arrUserIds) {
                    if ($arrUserIds->client_type == 'ios')
                        array_push($this->ugcIOSGtCids, $arrUserIds->push_client);
                    elseif ($arrUserIds->client_type == 'android')
                        array_push($this->ugcAndroidGtCids, $arrUserIds->push_client);
                }
            }
            $this->ugcIOSGtCids = array_chunk($this->ugcIOSGtCids, 500);
            $this->ugcAndroidGtCids = array_chunk($this->ugcAndroidGtCids, 500);
        }
    }
}
