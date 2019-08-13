<?php
include_once APP_PATH.'libraries/WxBizMsgCrypt/wxBizMsgCrypt.php';

/**
 * Created by PhpStorm.
 */
class KeywordController extends BaseController{

    private function checkSignature()
    {
        $signature = Request::get("signature");
        $timestamp = Request::get("timestamp");
        $nonce = Request::get("nonce");

        $tmpArr = array(
            'token' => 'dCbFLlQrUj',
            'timestamp' => $timestamp,
            'nonce' => $nonce
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode('' , $tmpArr);
        $tmpStr = sha1( $tmpStr );

        if( $signature == $tmpStr ){
            return true;
        }else{
            return false;
        }
    }

    public function indexAction() {
        if (!$this->checkSignature()) {
            echo '';exit;
        }
        if ($this->checkSignature() && $echostr = Request::get("echostr")) {
            echo $echostr;exit;
        }

        $zgltv_weixin = Setting::getByChannel(LETV_CHANNEL_ID, 'zgltv_wechat');
        $encodingAesKey = $zgltv_weixin['encodingAesKey'];
        $token = $zgltv_weixin['token'];
        $appId = $zgltv_weixin['app_id'];

//        $encodingAesKey = 'ql4SF8Qm9HEd4pTwbA9tQU80UgbvDcRuYmN3U4QubV7';
//        $token = 'dCbFLlQrUj';
//        $appId = "wx137d9f4bf9f97395";
//        $appId = 'wx652174cd17eda5c5';

        $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);

        $encryptMsg = file_get_contents("php://input");
        libxml_disable_entity_loader(true);

//        $log_file = 'D:/wamp/logs/' . date('Ymd', time()) . '_wx_message.log';
////当前链接
//        $url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
////写入的内容
//        $content = "[url:] {$url}\r\n[time:]".date('Y-m-d H:i:s',time())."\r\n\n[openid:]asd \r\n\n [data]".$encryptMsg;
////写入到log文本中
//        file_put_contents($log_file,$content, FILE_APPEND);


        $xml_tree = new DOMDocument();
        $xml_tree->loadXML($encryptMsg);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $encrypt = $array_e->item(0)->nodeValue;

        $msg_sign = Request::get('msg_signature');
        $timeStamp = Request::get('timestamp');
        $nonce = Request::get('nonce');

        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);

        // 第三方收到公众号平台发送的消息
        $msg = '';
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if ($errCode == 0) {
            $value_array = json_decode(json_encode(simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
//            var_dump($value_array);exit;
        } else {
            echo 'success';exit;
        }

//        $value_array = array(
//            'URL' => 'http://d0d4f863.ngrok.io/cdkey/index',
//            'ToUserName' => 'wx137d9f4bf9f97395',
//            'FromUserName' => 'gh_2d53b2dae65f',
//            'CreateTime' => '1525246868',
//            'MsgType' => 'text',
//            'Content' => '跑男',
//            'MsgId' => '1234567890123456',
//        );

        //针对文本消息     判断提交内容，匹配不同关键词
        if ($value_array['MsgType'] == 'text' && $value_array['Content'] != null && $value_array['FromUserName'] != null && $value_array['MsgId'] != null) {
            $content = '';
            $key_word = WxKeyword::getCdkeyKeyword($value_array['Content']);
//            var_dump($key_word);exit;
            if ($key_word == null) {
                //未发现精准匹配的情况下，直接回复微信收到请求，后期在此处增加模糊查询，支持普通回复
                $birthday_index = strpos($value_array['Content'], "生日");
                if ($birthday_index !== false && $birthday_index >= 0) {
                    $content = "目前该明星的生日应援活动暂未开启~";
                }else {
                    echo 'success';
                    exit;
                }
            }else {
                if ($key_word->callback_type == 1) {//普通类型回复
                    $content = $key_word->answer_text;
                }

                if ($key_word->callback_type == 2) {//兑换码类型回复
                    $cdkey = WxCdkey::findCdkeyByOpenId($value_array['FromUserName'], $key_word->keyword_code);
                    if ($cdkey == null) {
                        //不存在的时候创建一条
                        $cdkey = WxCdkey::createCdkeyByOpenId($value_array['FromUserName'], $key_word->keyword_code, $value_array['MsgId']);
                        if ($cdkey == false) {
                            echo 'success';
                            exit;
                        }
                    }
                    $content = preg_replace("/{cdkey}/", $cdkey->cdkey, $key_word->answer_text);
                }

                if ($key_word->callback_type == 3) {//应援类型回复
                    /*
                     * 1.先在新表中确认是否已应援过该应援
                     * 1 if true 2.回复已应援成功和应援时间（已应援）
                     * 1 if false 2.创建一条应援记录，判断应援次数是否达标
                     * 2 if true 3.如果达标了，返回已成功,并返回应援成功次数（已成功）
                     * 2 if false 3.还未达标，返回未成功, 并返回应援成功次数（未成功）
                     *
                     */
                    $birthday_support = WxBirthdaySupport::findByOpenId($value_array['FromUserName'], $key_word->id);
                    $answer_text = $key_word->answer_text;
                    $answer_text_array = json_decode($answer_text, true);
                    if ($birthday_support != false) {
                        $content = preg_replace("/{时间}/", date('Y年m月d日H时i分s秒', $birthday_support->support_time), $answer_text_array['had']);
                    } else {
                        $key_word->keyword_code++;
                        if ($key_word->keyword_code >= $key_word->title) {
                            $content = preg_replace("/{目标}/", $key_word->title, $answer_text_array['success']);
                            $content = preg_replace("/{目前}/", $key_word->keyword_code, $content);
                        } else {
                            $content = preg_replace("/{目标}/", $key_word->title, $answer_text_array['ongoing']);
                            $content = preg_replace("/{目前}/", $key_word->keyword_code, $content);
                        }
                        $birthday_support = WxBirthdaySupport::createCdkeyByOpenId($value_array['FromUserName'], $key_word, $value_array['MsgId']);
                        if ($birthday_support == false) {
                            echo 'success';
                            exit;
                        }
                        $key_word->update();
                    }
                }
            }

            //返回消息
            $text = "<xml><ToUserName>{$value_array['FromUserName']}</ToUserName><FromUserName>{$value_array['ToUserName']}</FromUserName><CreateTime>{$timeStamp}</CreateTime><MsgType>text</MsgType><Content><![CDATA[{$content}]]></Content></xml>";

            $encryptMsg = "";
            $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) {
                echo $encryptMsg;exit;
            }
        }elseif ($value_array['MsgType'] == 'event' && $value_array['Event'] != null && $value_array['FromUserName'] != null){
            //对事件进行回复
            //1.关注事件进行回复
            if ($value_array['Event'] == 'subscribe') {
                $key_word = WxKeyword::getSubscribeEvent();
                if ($key_word == null) {
                    //未设置关注返回内容时，不进行响应
                    echo 'success';
                    exit;
                }

                //返回消息
                $text = "<xml><ToUserName>{$value_array['FromUserName']}</ToUserName><FromUserName>{$value_array['ToUserName']}</FromUserName><CreateTime>{$timeStamp}</CreateTime><MsgType>text</MsgType><Content><![CDATA[{$key_word->answer_text}]]></Content></xml>";

                $encryptMsg = "";
                $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
                if ($errCode == 0) {
                    echo $encryptMsg;exit;
                }
            }
        }else{
            echo 'success';exit;
        }

        echo 'success';exit;
    }

    //检查cdkey是否存在
    public function checkcdkeyAction() {
        $cdkey = Request::getQuery('cdkey');

        if ($cdkey == '') {
            $this->_json('', 201, '参数为空');
        }
        $wx_cdkey = WxCdkey::findCdkeyByCdkey($cdkey);
        if ($wx_cdkey != null ) {
            $this->_json('', 200, 'success');
        }else{
            $this->_json('', 202, '兑换码不存在');
        }
    }

    protected function _json($data, $code = 200, $msg = "success") {
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

}