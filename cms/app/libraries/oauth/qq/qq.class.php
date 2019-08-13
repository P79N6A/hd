<?php
// <copyright file="qq.class.php" company="www.letv.com">
//     Copyright (c) letv.com. All rights reserved.
// </copyright>
// <summary>qq开放平台接口文件</summary>
// <Create Author="heliqin" Time="2012.05.30"></Create>
// <Revision>2.0</Revision>
// <Modify>
//  wxliu  2012.05.30 创建
// </Modify>
class QQ {
    var $request_token_url = 'https://graph.qq.com/oauth2.0/token';
    var $authorize_url = 'https://graph.qq.com/oauth2.0/authorize';
    var $access_token_url = 'https://graph.qq.com/oauth2.0/me';
    var $user_info_url = 'https://graph.qq.com/user/get_user_info';
    var $add_share_url = 'https://graph.qq.com/share/add_share';

    /**
     * @desc 得到授权的URL
     * @version 2015-06-04
     * @param string $from
     * @return string
     */
    function getAuthorizeURL($from = '', $callbackUrl = '',$channel_id = '' ) {
        $oauth_tv_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_qq');
        if (!empty($from)) {
            //$callback = QQ_TV_CALLBACK . '?from=' . $from;
            $callback = WEB_HOST . $oauth_tv_qq['callback'] . '?from=' . $from;
        } elseif(!empty($callbackUrl)){
            $callback = WEB_HOST . $oauth_tv_qq['callback'] . '?channel_id=' . $channel_id . '&callbackurl=' . $callbackUrl;
        } else {
            //$callback = QQ_TV_CALLBACK;
            $callback = WEB_HOST . $oauth_tv_qq['callback'];
        }

        //$url = $this->authorize_url.'?response_type=code&client_id='.QQ_TV_AKEY.'&redirect_uri='.rawurlencode($callback).'&scope=get_user_info,add_share,add_pic_t';
        $url = $this->authorize_url . '?response_type=code&client_id=' . $oauth_tv_qq['akey'] . '&redirect_uri=' . rawurlencode($callback) . '&scope=get_user_info,add_share,add_pic_t';
        return $url;
    }

    /**
     * @desc 页面登录生成QQ授权地址
     * @version 2015-06-05
     * @param string $param
     * @return string
     */
    function getAppAuthorizeURL($param = null) {
        $oauth_tv_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_qq');
        if (!empty($param)) {
            if (is_array($param)) {
                //$callback = QQ_TV_CALLBACK."?".http_build_query($param);
                $callback = WEB_HOST . $oauth_tv_qq['callback'] . "?" . http_build_query($param);
            } else {
                //$callback = QQ_TV_CALLBACK.'?plat='.$param;
                $callback = WEB_HOST . $oauth_tv_qq['callback'] . '?plat=' . $param;
            }
        } else {
            //$callback = QQ_TV_CALLBACK;
            $callback = WEB_HOST . $oauth_tv_qq['callback'];
        }

        //return $this->authorize_url.'?response_type=code&client_id='.QQ_TV_AKEY.'&redirect_uri='.rawurlencode($callback).'&state=1&scope=get_user_info,add_share,add_pic_t';
        return $this->authorize_url . '?response_type=code&client_id=' . $oauth_tv_qq['akey'] . '&redirect_uri=' . rawurlencode($callback) . '&state=1&scope=get_user_info,add_share,add_pic_t';
    }

    /**
     * @desc 授权返回的KEY
     * @version 2015-06-04
     * @return multitype:mixed |boolean
     */
    function callBack() {
        $oauth_tv_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_qq');
        //获取access_token
        //$oauth_token = $this->get_request_token(QQ_TV_AKEY, QQ_TV_SKEY, $_REQUEST['code']);
        $oauth_token = $this->get_request_token($oauth_tv_qq['akey'], $oauth_tv_qq['skey'], $_REQUEST['code']);
        $last_key = array();
        parse_str($oauth_token, $last_key);
        if (!isset($last_key["access_token"])) {
            header("Location:{$this->getAuthorizeURL()}", true, $code = 302);
            exit;
        }
        //获取openid
        $access_str = $this->get_access_token($last_key["access_token"]);
        $aTemp = array();
        preg_match('/callback\(\s+(.*?)\s+\)/i', $access_str, $aTemp);
        $aResult = json_decode($aTemp[1], true);
        $last_key['openid'] = $aResult["openid"];
        if (isset($last_key['access_token'], $last_key['openid'])) {
            return $last_key;
        } else {
            return false;
        }
    }

    /**
     * @desc 获取qq用户信息
     * @version 2015-06-04
     * @param unknown $oauth_token
     * @param unknown $openid
     * @return mixed
     */
    function getUserInfo($oauth_token, $openid) {
        $oauth_tv_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_qq');
        $sUrl = $this->user_info_url;
        $aGetParam = array(
            "access_token" => $oauth_token,
            //"oauth_consumer_key"    => QQ_TV_AKEY,
            "oauth_consumer_key" => $oauth_tv_qq['akey'],
            "openid" => $openid,
            "format" => "json"
        );
        $sContent = $this->get($sUrl, $aGetParam);
        $sContent = mb_convert_encoding($sContent, "UTF-8", "UTF-8");
        $aResult = json_decode($sContent, true);
        return $aResult;
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

    /*QQ微博分享*/
    function pushData($last_key, $title = '分享一个视频', $url, $comment, $vid) {

        $sUrl = $this->add_share_url;
        $playurl = 'http://i7.imgs.letv.com/player/swfPlayer.swf?id=' . $vid . '&autoplay=1';
        $oauth_tv_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_qq');
        $aPOSTParam = array(
            "access_token" => $last_key["oauth_token"],
            //"oauth_consumer_key"    => QQ_TV_AKEY,
            "oauth_consumer_key" => $oauth_tv_qq['akey'],
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
     * @desc 请求临时token.请求需经过URL编码，编码时请遵循 RFC 1738
     * @version 2015-06-04
     * @param unknown $appid
     * @param unknown $appkey
     * @param unknown $code
     * @return Ambigous <boolean, mixed> 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx
     */
    function get_request_token($appid, $appkey, $code) {
        $oauth_tv_qq = Setting::getByChannel(LETV_CHANNEL_ID, 'oauth_tv_qq');
        $sUrl = $this->request_token_url;
        //必要参数
        $aGetParam = array();
        $aGetParam["grant_type"] = "authorization_code";
        $aGetParam["client_id"] = $appid;
        $aGetParam["client_secret"] = $appkey;
        $aGetParam["code"] = $code;
        $aGetParam["state"] = '';
        //$aGetParam["redirect_uri"]		  = QQ_TV_CALLBACK;
        $aGetParam["redirect_uri"] = WEB_HOST . $oauth_tv_qq['callback'];
        $sContent = $this->get($sUrl, $aGetParam);
        return $sContent;
    }

    /**
     * @desc 获取access_token。请求需经过URL编码，编码时请遵循 RFC 1738
     * @version 2015-06-04
     * @param unknown $request_token
     * @return Ambigous <boolean, mixed>
     * 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx&openid=xxx&oauth_signature=xxx&oauth_vericode=xxx&timestamp=xxx
     */
    function get_access_token($request_token) {
        $sUrl = $this->access_token_url;
        //必要参数
        $aGetParam = array();
        $aGetParam["access_token"] = $request_token;
        $sContent = $this->get($sUrl, $aGetParam);
        return $sContent;
    }


}
