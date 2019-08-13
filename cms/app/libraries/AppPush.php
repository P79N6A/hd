<?php

/*
 * $app_id，$app_key，$master_secret 这些参数是个推为app分配的参数可以登录个推获取到
 * 
 * $terminal_type 推送的终端类型 1.安卓 2.苹果 3.安卓和苹果
 *
 * $template_type 推送推送模板类型选择 1.点击通知打开应用模板 2.点击通知打开网页模板 3.透传消息模板 4.透传消息模板（字典）
 * 当前市县安卓版本大部分使用1
 * 当前市县苹果版本大部分使用3
 * 中国蓝tv 安卓版 使用的是3
 * 中国蓝tv 苹果版 使用的是4
 * 
 * $not_single_test 设置该值为1时表示改推送为正式推送给所有用户, 改值未传或为0时，请传测试机的client_id 
 * 
 * $task_msg 为推送的标题
 * $extras_id 推送的内容id
 * $extras_type 推送的类型 直播 点播 广播等
 */

include_once APP_PATH . 'libraries/igetui3.3.2.1/IGt.Push.php';
include_once APP_PATH . 'libraries/igetui3.3.2.1/igetui/IGt.AppMessage.php';
include_once APP_PATH . 'libraries/igetui3.3.2.1/igetui/IGt.APNPayload.php';
include_once APP_PATH . 'libraries/igetui3.3.2.1/igetui/template/IGt.BaseTemplate.php';
include_once APP_PATH . 'libraries/igetui3.3.2.1/IGt.Batch.php';
include_once APP_PATH . 'libraries/igetui3.3.2.1/IGt.Push.php';


class AppPush {

    function getuiPush($app_id, $app_key, $master_secret, $terminal_type, $template_type, $not_single_test = 0, $client_id, $msg_title, $msg_content) {
        define('HOST', 'http://sdk.open.api.igexin.com/apiex.htm');
        header("Content-Type: text/html; charset=utf-8");

        if ($client_id == "") $client_id = "19f5e24877b2291dc733d1dc5e2e9da9";

        $igt = new IGeTui(HOST, $app_key, $master_secret);

        //推送模板选择 1.点击通知打开应用模板 2.点击通知打开网页模板 3.透传消息模板 4.透传消息模板（字典）
        switch ($template_type) {
            case 1:
                $appname = "上虞视听网";
                $logourl = "http://cloudimg.cztv.com/templates/project/shangyuweb/images/shangyulogo100.png";
                $template = IGtNotificationTemplate($appid, $appkey, $appname, $logourl, $msg_title, $msg_content);
		        break;
            case 2:
                break;
            case 3:
                $template = $this->IGtTransmissionTemplate($app_id, $app_key, $msg_title, $msg_content);
                break;
            case 4:
                $template = $this->IGtTransmissionTemplateDictionary($app_id, $app_key, $msg_title, $msg_content);
                break;

        }
        switch ($terminal_type) {
            case 1:
                $phonetypelist = array('ANDROID');
                break;
            case 2:
                $phonetypelist = array('IOS');
                break;
            case 3:
                $phonetypelist = array('IOS', 'ANDROID');
                break;
        }
        //个推信息体
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600 * 1000 * 2);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        $message->set_appIdList(array($app_id));
        $message->set_phoneTypeList($phonetypelist);
        if ($not_single_test) {
            $rep = $igt->pushMessageToApp($message);
        } else {
            $target = new IGtTarget();
            $target->set_appId($app_id);
            $target->set_clientId($client_id);
            $rep = $igt->pushMessageToSingle($message, $target);
        }
        echo json_encode($rep);
        die();
    }

    //点击通知打开应用模板
    function IGtNotificationTemplate($app_id, $app_key, $appname, $logourl, $msg_title, $msg_content) {
        $template = new IGtNotificationTemplate();
        $template->set_appId($app_id);//应用appid
        $template->set_appkey($app_key);//应用appkey
        $template->set_transmissionType(1);// 不强制启动应用
        $template->set_transmissionContent($msg_content);//透传内容, '{"id":"123456","type":"0"}';

        $template->set_title($appname);//通知栏标题
        $template->set_logoURL($logourl);//通知栏网络图片展示

        $template->set_text($msg_title);//通知栏内容
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        return $template;
    }


    //透传消息模板
    function IGtTransmissionTemplate($app_id, $app_key, $msg_title, $msg_content) {
        $template = new IGtTransmissionTemplate();
        $template->set_appId($app_id);//应用appid
        $template->set_appkey($app_key);//应用appkey
        $template->set_transmissionType(0);//透传消息类型  1:打开应用 
        $template->set_transmissionContent($msg_content);//透传内容
        $template->set_pushInfo("test", 0, $msg_title, "1.wav", $msg_content, "", "", "");
        return $template;
    }

    //透传消息模板（字典）
    function IGtTransmissionTemplateDictionary($app_id, $app_key, $msg_title, $msg_content) {
        $template = new IGtTransmissionTemplate();
        $template->set_appId($app_id);//应用appid
        $template->set_appkey($app_id);//应用appkey
        $template->set_transmissionType(2);//透传消息类型  1:打开应用 2
        $template->set_transmissionContent($msg_content);//透传内容

        $apn = new IGtAPNPayload();
        $alertmsg = new DictionaryAlertMsg();

        $alertmsg->body = $msg_content; //'{"id":"123456","type":"0"}';
        $alertmsg->actionLocKey = "查看";
        $alertmsg->locKey = $msg_title;
        $alertmsg->locArgs = array("locargs");
        $alertmsg->launchImage = "launchimage";

        $apn->alertMsg = $alertmsg;
        $apn->badge = 1;
        $apn->sound = "1.wav";
        $apn->add_customMsg("payload", "payload");
        $apn->contentAvailable = 0;
        $apn->category = "ACTIONABLE";
        $template->set_apnInfo($apn);
        return $template;
    }

}
