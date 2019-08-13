<?php


class Weixin {
    var $request_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    var $user_info_url = 'https://api.weixin.qq.com/sns/userinfo';
    var $authorize_url = 'https://open.weixin.qq.com/connect/qrconnect';

    var $authorize_public_url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    public $channel_id;


    /**
     * 得到微信二维码登录页
     */
    function getAuthorizeURL($from = '',$callbackurl = '', $channel_id = '') {
        $oauth_weixin = Setting::getByChannel($this->channel_id, 'oauth_weixin');
        if(!$oauth_weixin){
            $this->_json([], 404, "Channel Id Not Found!");
        }
        if (!empty($from)) {
            //$callback = WEIXIN_CALLBACK . '?from=' . $from;
            $callback = WEB_HOST . $oauth_weixin['callback'] . '?from=' . $from;
        }elseif (!empty($callbackurl) || !empty($channel_id)){
            $callback = WEB_HOST . $oauth_weixin['callback'] . '?channel_id=' . $channel_id . '&callbackurl=' . $callbackurl;
        } else {
            //$callback = WEIXIN_CALLBACK;
            $callback = WEB_HOST . $oauth_weixin['callback'];
        }
        //$url = $this->authorize_url.'?response_type=code&appid='.WEIXIN_AKEY.'&redirect_uri='.rawurlencode($callback).'&scope=snsapi_login&state=1';
        $url = $this->authorize_url . '?response_type=code&appid=' . $oauth_weixin['akey'] . '&redirect_uri=' . rawurlencode($callback) . '&scope=snsapi_login&state=1';
        return $url;
    }

    /**
     * 授权返回的KEY
     */
    function callBack() {
        $oauth_weixin = Setting::getByChannel($this->channel_id, 'oauth_weixin');
        //获取access_token
        //$sContent = $this->get_request_token(WEIXIN_AKEY,WEIXIN_SKEY,$_REQUEST['code']);
        $sContent = $this->get_request_token($oauth_weixin['akey'], $oauth_weixin['skey'], $_REQUEST['code']);
        $sContent = mb_convert_encoding($sContent, "UTF-8", "UTF-8");
        $aResult = json_decode($sContent, true);
        $last_key = array();
        $last_key['access_token'] = $aResult['access_token'];
        $last_key['openid'] = $aResult['openid'];
        $last_key['refresh_token'] = $aResult['refresh_token'];
        $last_key['unionid'] = $aResult['unionid'];
        if (!isset($last_key["access_token"])) {
            header("Location:{$this->getAuthorizeURL()}", true, $code = 302);
            exit;
        }
        if (isset($last_key['access_token'], $last_key['openid'])) {
            return $last_key;
        } else {
            return false;
        }
    }

    function publiccallback() {
        $sContent = $this->get_request_token(WEIXIN_PUBLIC_AKEY, WEIXIN_PUBLIC_SKEY, $_REQUEST['code']);
        $sContent = mb_convert_encoding($sContent, "UTF-8", "UTF-8");
        $aResult = json_decode($sContent, true);
        $last_key = array();
        $last_key['access_token'] = $aResult['access_token'];
        $last_key['openid'] = $aResult['openid'];
        $last_key['refresh_token'] = $aResult['refresh_token'];
        $last_key['unionid'] = $aResult['unionid'];
        if (!isset($last_key["access_token"])) {
            header("Location:{$this->getAuthorizeURL()}", true, $code = 302);
            exit;
        }
        if (isset($last_key['access_token'], $last_key['openid'])) {
            return $last_key;
        } else {
            return false;
        }
    }

    /**
     * 得到用户信息
     */
    function getUserInfo($oauth_token, $openid) {
        $sUrl = $this->user_info_url;
        $aGetParam = array(
            "access_token" => $oauth_token,
            "openid" => $openid,
        );
        $sContent = $this->get($sUrl, $aGetParam);
        $sContent = mb_convert_encoding($sContent, "UTF-8", "UTF-8");
        $aResult = json_decode($sContent, true);
        return $aResult;
    }


    /**
     * 刷新token
     */
    function refreshToken($refresh_token) {
        if (empty($refresh_token)) {
            return false;
        }
        $oauth_weixin = Setting::getByChannel($this->channel_id, 'oauth_weixin');
        //$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . WEIXIN_AKEY ."&grant_type=refresh_token&refresh_token=$refresh_token";
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $oauth_weixin['akey'] . "&grant_type=refresh_token&refresh_token=$refresh_token";
        $ret = @file_get_contents($url);
        $token_arr = json_decode($ret, true);
        return $token_arr;
    }

    function getOauthUserInfo($oauth_token, $openid, $appkey) {
        $sUrl = $this->user_info_url;
        $aGetParam = array(
            "access_token" => $oauth_token,
            "oauth_consumer_key" => $appkey,
            "openid" => $openid,
            "format" => "json"
        );
        $sContent = $this->get($sUrl, $aGetParam);
        $sContent = mb_convert_encoding($sContent, "UTF-8", "UTF-8");
        $aResult = json_decode($sContent, true);
        return $aResult;
    }

    /*
     * GET请求
     */
    function get($sUrl, $aGetParam) {
        global $aConfig;
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $aGet = array();
        foreach ($aGetParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl . "?" . join("&", $aGet));
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        if (CZTV_PROXY_ST == 1) {
            curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($oCurl, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($oCurl, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aConfig["debug"]) === 1) {
            echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>" . $sUrl . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>GET参数:</td><td><pre>" . var_export($aGetParam, true) . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>" . var_export($aStatus, true) . "</pre></td></tr>";
            if (intval($aStatus["http_code"]) == 200) {
                echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>" . $sContent . "</pre></td></tr>";
                if ((@$aResult = json_decode($sContent, true))) {
                    echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>" . var_export($aResult, true) . "</pre></td></tr>";
                }
            }
        }
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>" . $aStatus["http_code"] . ",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
            return FALSE;
        }
    }

    /*
     * POST 请求
     */
    function post($sUrl, $aPOSTParam) {
        global $aConfig;
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $aPOST = array();
        foreach ($aPOSTParam as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, join("&", $aPOST));
        if (CZTV_PROXY_ST == 1) {
            curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($oCurl, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($oCurl, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aConfig["debug"]) === 1) {
            echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>" . $sUrl . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>POST参数:</td><td><pre>" . var_export($aPOSTParam, true) . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>" . var_export($aStatus, true) . "</pre></td></tr>";
            if (intval($aStatus["http_code"]) == 200) {
                echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>" . $sContent . "</pre></td></tr>";
                if ((@$aResult = json_decode($sContent, true))) {
                    echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>" . var_export($aResult, true) . "</pre></td></tr>";
                }
            }
        }
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>" . $aStatus["http_code"] . ",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
            return FALSE;
        }
    }

    /*微博分享*/
    function pushData($last_key, $title = '分享一个视频', $url, $comment, $vid) {
        $oauth_qq = Setting::getByChannel($this->channel_id, 'oauth_qq');
        $sUrl = $this->add_share_url;
        $playurl = 'http://i7.imgs.letv.com/player/swfPlayer.swf?id=' . $vid . '&autoplay=1';
        $aPOSTParam = array(
            "access_token" => $last_key["oauth_token"],
            //"oauth_consumer_key"    => QQ_AKEY,
            "oauth_consumer_key" => $oauth_qq['akey'],
            "openid" => $last_key["openid"],
            "format" => "json",
            "title" => (get_magic_quotes_runtime() ? stripslashes($title) : $title),
            "url" => (get_magic_quotes_runtime() ? stripslashes($url) : $url),
            "playurl" => (get_magic_quotes_runtime() ? stripslashes($playurl) : $playurl),
            "comment" => (get_magic_quotes_runtime() ? stripslashes($comment) : $comment),
            "type" => 5
        );
        $sContent = $this->post($sUrl, $aPOSTParam);
        if ($sContent !== FALSE) {
            $aResult = json_decode($sContent, true);
            if ($aResult["ret"] == 0) {//发表成功
                return true;
            } else {
                return $aResult;
            }
        }

    }

    /**
     * @brief 请求临时token.请求需经过URL编码，编码时请遵循 RFC 1738
     *
     * @param $appid
     * @param $appkey
     *
     * @return 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx
     */
    function get_request_token($appid, $appkey, $code) {
        $sUrl = $this->request_token_url;
        //必要参数
        $aGetParam = array();
        $aGetParam["grant_type"] = "authorization_code";
        $aGetParam["appid"] = $appid;
        $aGetParam["secret"] = $appkey;
        $aGetParam["code"] = $code;
        $sContent = $this->get($sUrl, $aGetParam);
        return $sContent;
    }

    /**
     * @brief 获取access_token。请求需经过URL编码，编码时请遵循 RFC 1738
     *
     * @param $appid
     * @param $appkey
     * @param $request_token
     * @param $request_token_secret
     * @param $vericode
     *
     * @return 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx&openid=xxx&oauth_signature=xxx&oauth_vericode=xxx&timestamp=xxx
     */
    function get_access_token($request_token) {
        $sUrl = $this->access_token_url;
        //必要参数
        $aGetParam = array();
        $aGetParam["access_token"] = $request_token;
        $sContent = $this->get($sUrl, $aGetParam);
        return $sContent;
    }
    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = "success")
    {
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }


}
