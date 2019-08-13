<?php

class Plugin_Util {
    //得到当前用户Ip地址
    public static function getRealIp() {
        $pattern = '/(\d{1,3}\.){3}\d{1,3}/';
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && preg_match_all($pattern, $_SERVER['HTTP_X_FORWARDED_FOR'], $mat)) {
            foreach ($mat[0] AS $ip) {
                //得到第一个非内网的IP地址
                if ((0 != strpos($ip, '192.168.')) && (0 != strpos($ip, '10.')) && (0 != strpos($ip, '172.16.'))) {
                    return $ip;
                }
            }
            return $ip;
        } else {
            if (isset($_SERVER["HTTP_CLIENT_IP"]) && preg_match($pattern, $_SERVER["HTTP_CLIENT_IP"])) {
                return $_SERVER["HTTP_CLIENT_IP"];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
        }
    }

    //得到无符号整数表示的ip地址
    public static function getIntIp() {
        return sprintf('%u', ip2long(self::getRealIp()));
    }

    //文本入库前的过滤工作
    public static function getSafeText($textString, $htmlspecialchars = true) {
        return $htmlspecialchars ? htmlspecialchars(trim(strip_tags($textString))) : trim(strip_tags($textString));
    }

    /**
     * @去除XSS（跨站脚本攻击）的函数
     * @par $val 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
     * @return  处理后的字符串
     **/
    public static function removeXss($val) {
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional 
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
            // @ @ search for the hex values 
            $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ; 
            // @ @ 0{0,7} matches '0' zero to seven times  
            $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ; 
        }
        // now the only remaining whitespace attacks are \t, \n, and \r 
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);
        $found = true; // keep replacing as long as the previous round replaced something 
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag  
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop  
                    $found = false;
                }
            }
        }
        $val = preg_replace('/"|\'/', '', $val);
        return $val;
    }

    /*
    |----------------------------------------------------------------------------
    | 字符串加密与解密函数            来源ucenter
          $string    原文或者密文
          $operation    操作(ENCODE | DECODE), 默认为 DECODE解密
          $key        密钥
          $expiry        密文有效期, 加密时候用的， 单位 秒，0 为永久有效
          return   处理后的 原文或者 经过 base64_encode 处理后的密文,如果失效，返回空
          如：
                $a = authcode('abc', 'ENCODE', 'key');//加密
                $b = authcode($a, 'DECODE', 'key');  // $b(abc)
                $a = authcode('abc', 'ENCODE', 'key', 3600);//加密
                $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
     |----------------------------------------------------------------------------
     |
     */
    public static function authCode($string, $operation = 'DECODE', $key = '', $expiry = 0, $need_expire = false) {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'DECODE' ? self::base62_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', self::base62_encode($result));
        }
    }

    private static function base62_encode($str) {
        $ret = base64_encode($str);
        $ret = str_replace(array('m', '+', '/'), array('m1', 'm2', 'm3'), $ret);
        return trim($ret);
    }

    private static function base62_decode($str) {
        $str = str_replace(array('m3', 'm2', 'm1'), array('/', '+', 'm'), trim($str));
        return base64_decode($str);
    }

    //CURL请求
    public static function curl($destURL, $paramStr = '', $flag = 'get') {
        if (!extension_loaded('curl')) exit('php_curl.dll');
        $curl = curl_init();
        if ($flag == 'post') {//post
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $paramStr);
        }
        curl_setopt($curl, CURLOPT_URL, $destURL);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }

    public static function curl_post($url, $args) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (CZTV_PROXY_ST == 1) {
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($ch, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($ch, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //用户中心后台接口调用函数
    public static function sendRequest($reqUrl, $sendParams, $timeout = 5) {
        $params = array();
        $encRequest = base64_encode(json_encode($sendParams));
        $params['data'] = $encRequest;

        //initialize and setup the curl handler
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $reqUrl,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_USERAGENT => 'LETV_API',
        );
        curl_setopt_array($ch, $options);
        //execute the request
        $result = curl_exec($ch);
        return $result;
    }

    //生成一个17字节长唯一随机文件名
    public static function getRandNumber() {
        return chr(mt_rand(97, 122)) . mt_rand(10000, 99999) . time();
    }


    //得到email的登录地址
    public static function getEmailUrl($email) {
        $tmpurl = trim(strrchr($email, '@'), '@');
        $emailArr = array(
            'yahoo.com.cn' => 'http://mail.cn.yahoo.com',
            'yahoo.cn' => 'http://mail.cn.yahoo.com',
            'sina.com' => 'http://mail.sina.com.cn',
            'sina.cn' => 'http://mail.sina.com.cn',
            'vip.sina.com' => 'http://vip.sina.com',
            'gmail.com' => 'http://mail.google.com',
            '189.cn' => 'http://mail.189.cn/webmail/',
            '139.com' => 'http://mail.10086.cn/',
            'hotmail.com' => 'http://mail.live.com/',
            'vip.163.com' => 'http://mail.163.com/',
        );
        if (isset($emailArr[$tmpurl])) {
            $emailUrl = $emailArr[$tmpurl];
        } else {
            $emailUrl = 'http://mail.' . $tmpurl;
        }
        return $emailUrl;
    }


    public static function send_mail($email, $mailTitle = '测试标题', $mailContent = '测试内容', $from = 'From: cztv@cztv.com', $fromName = '中国蓝TV') {
        require_once 'phpmailer.php';
        $mail = new phpmailer();
        // 设置PHPMailer使用SMTP服务器发送Email
        $mail->IsSMTP();
        $mail->IsHTML(true);
        //设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet = 'UTF-8';
        $mail->AddAddress($email);
        // 设置邮件标题
        $mail->Subject = $mailTitle;
        // 设置邮件正文
        $mail->Body = $mailContent;
        // 设置SMTP服务器。
        $mail->Host = MAIL_SMTP;
        // 设置邮件头的From字段。
        $mail->From = MAIL_ADDRESS;
        // 设置发件人名字
        $mail->FromName = $fromName;
        // 设置为需要smtp验证
        $mail->SMTPAuth = true;
        // 设置用户名和密码。
        $mail->Username = MAIL_LOGINNAME;
        $mail->Password = MAIL_PASSWORD;
        if (!$mail->Send()) {
            sleep(5);
            return $mail->Send();
        } else {
            return TRUE;
        }
    }

    /**
     * @注释掉原有的短信发送
     * 手机发信息接口
     * 为兼容聚信通道发送短信，添加corpId参数
     * 聚集通道corpId为 800051, 800052
     */
    /*public static function mobileSend($mobile, $msg, $srcAddr='10690228102921', $channel=0, $corpId='800000'){
        if (empty($srcAddr)) {
            $srcAddr = '10690228102921';
        }
        if(in_array($corpId, array("800051", "800052"))){
            $channel = 'http://115.182.51.124:7070/thirdPartner/letvqxtmt';
            $paramStr = "corpID=$corpId&destAddr={$mobile}&msg=" . mb_convert_encoding($msg, "gbk", "utf-8");
        }else{
            //主副通道
            $destURL = array(
                '0'=>'http://115.182.51.124:7070/thirdPartner/gdmt',
                '1'=>'http://115.182.51.124:7070/thirdPartner/mongatemt'
            );
            !isset($destURL[$channel]) && $channel = 0;
            $channel = $destURL[$channel];
            $paramStr = "srcAddr=" . $srcAddr . "&serviceName=ZMMDB&corpID=800000&destAddr={$mobile}&msg=" . mb_convert_encoding($msg, "gbk", "utf-8");
        }
        $a = self::curl($channel, $paramStr, 'post');
		
        if($a=="OK"){
            return true;
        }else{
            return false;
        }
    }*/

    public static function mobileSend($mobile, $msg, $period = 10, $from = '') {
        //发送短信验证码平台
        $platform = "open.189.cn";
        //天翼平台accesstoken接口地址
        $url_access_token = "https://oauth.api.189.cn/emp/oauth2/v3/access_token";
        //天翼平台发送短信接口地址
        $url_send_message = "http://api.189.cn/v2/emp/templateSms/sendSms";
        if ($from == 'news') {//蓝新闻
            $app_id = "120369370000035989";
            $app_secret = "77e79f1927545c8ed5003ce639b6f830";
        } else {//蓝TV
            $app_id = "398350640000043874";
            $app_secret = "681926f6c1d626be7f28ad0e4968edb9";
        }
        //短信验证码字符集
        $charset = "0123456789";
        //短信模板ID
        $template_id = "91004089";
        $template_param = array();
        $template_param['param1'] = $msg;
        $template_param['param2'] = $period;

        $token_data = 'grant_type=client_credentials&app_id=' . $app_id . '&app_secret=' . $app_secret;
        $token = json_decode(self::curl_post($url_access_token, $token_data), true);
        if ($token && $token['res_code'] == 0) {
            $access_token = $token['access_token'];
        } else {
            return false;
        }

        $timestamp = date('Y-m-j H:i:s');
        $params = array();
        $params['app_id'] = 'app_id=' . $app_id;
        $params['access_token'] = 'access_token=' . $access_token;
        $params['acceptor_tel'] = 'acceptor_tel=' . $mobile;
        $params['template_id'] = 'template_id=' . $template_id;

        $params['template_param'] = 'template_param=' . json_encode($template_param);
        $params['timestamp'] = 'timestamp=' . $timestamp;
        ksort($params);
        $params_str = join('&', $params);
        $sign = urlencode(base64_encode(hash_hmac('sha1', $params_str, $app_secret, $raw_output = true)));
        $msg_data = $params_str . '&sign=' . $sign;
        $a = self::curl_post($url_send_message, $msg_data);
        $a = json_decode($a, TRUE);
        if ($a['res_code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function mobileSendByJianZhou($mobile, $msg, $period = 10) {
        //建周平台发送短信接口地址
        $url_send_message = 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage';
        //短信参数
        $post_data = array(
            "account" => 'sdk_zgltv',
            "password" => 'cztv.com3703',
            "destmobile" => $mobile,
            "msgText" => "您的验证码为{$msg}，本验证码{$period}分钟内有效，感谢您的使用！【中国蓝】",
            "sendDateTime" => ""
        );
        $post_data = http_build_query($post_data);
        $a = self::curl_post($url_send_message,$post_data);
        $a = F::curlProxy($url_send_message, 'post', $post_data);

        if ($a > 0) {
            return true;
        }else{
            return false;
        }

    }

    public static function mobileSendByCommon($mobile, $msg, $type = 'JZ', $period = 10) {
        //建周平台发送短信接口地址
        $url_send_message = 'http://192.168.140.1/api/sms/send';
        //模板ID,短信服务业务91004089
        $templateId = '91004089';
        if ($type == 'JZ') {
            $templateId = $templateId.'00';
        }
        if ($type == 'TY') {
            $templateId = '91004089';
        }
        if ($type == 'TX') {
            $templateId = '110219';
        }
        //短信参数
        $post_data = array(
            "destMobile" => $mobile,
            "param1" => $msg,
            "param2" => $period,
            "templateId" => $templateId,
            "type" => $type
        );
        $common_return = F::curlRequest($url_send_message,'post',$post_data,true,false);

        if ($common_return != null) {
            $common_return_arr = json_decode($common_return, true);
            if (isset($common_return_arr['code']) && $common_return_arr['code'] == '200') {
                return true;
            }
            error_log(date("Y-M-d H:i:s") . ' | ' . $mobile . " | error code {$common_return_arr['code']}, msg {$common_return_arr['msg']}" . "\r\n", 3, "/tmp/code/{$mobile}.log");//TODO新增短信日志
        } else {
            error_log(date("Y-M-d H:i:s") . ' | ' . $mobile . " | {$type} common_return null ". "\r\n", 3, "/tmp/code/{$mobile}.log");//TODO新增短信日志
        }
        return false;
    }

    public static function mobileSendCIBN($mobile, $msg, $srcAddr = '106902512188', $channel = 0) {
        $srcAddr = '106902512188';
        //主副通道
        $destURL = array(
            '0' => 'http://115.182.51.124:7070/thirdPartner/qxt',
        );
        !isset($destURL[$channel]) && $channel = 0;
        $paramStr = "srcAddr=" . $srcAddr . "&serviceName=TVUSERZC&corpID=800050&destAddr={$mobile}&msg=" . mb_convert_encoding($msg, "gbk", "utf-8");
        $a = self::curl($destURL[$channel], $paramStr, 'post');

        if ($a == "OK") {
            return true;
        } else {
            return false;
        }
    }

    //手机发信息接口,国际通道
    public static function mobileSendGJ($mobile, $msg, $srcAddr = '008526451') {
        $mobile = intval($mobile);
        $destURL = 'http://115.182.51.124:7070/thirdPartner/nexmomt';
        $paramStr = "corpID=800000&serviceName=ZMMDB&destAddr={$mobile}&srcAddr={$srcAddr}&msg=" . mb_convert_encoding($msg, "gbk", "utf-8");
        $a = self::curl($destURL, $paramStr, 'post');
        if ($a == "OK") {
            return true;
        } else {
            return false;
        }
    }


    public static function createGuid($namespace = '') {
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER ['REQUEST_TIME'];
        $data .= $_SERVER ['HTTP_USER_AGENT'];
        $data .= $_SERVER ['LOCAL_ADDR'];
        $data .= $_SERVER ['LOCAL_PORT'];
        $data .= $_SERVER ['REMOTE_ADDR'];
        $data .= $_SERVER ['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash, 0, 8) . substr($hash, 8, 4) . substr($hash, 12, 4) . substr($hash, 16, 4) . substr($hash, 20, 12);
        return $guid;
    }

    public static function authCodePtv($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;    //note 随机密钥长度 取值 0-32;
        //note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        //note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        //note 当此值为 0 时，则不产生随机密钥
        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }
}
