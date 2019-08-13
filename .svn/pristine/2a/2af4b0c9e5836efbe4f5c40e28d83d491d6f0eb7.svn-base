<?php

/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/5/3
 * Time: 17:17
 */
class Mail {
    /**
     * @desc 邮箱激活邮件下发
     * @param $email
     * @param $uid
     * @param string $from
     * @param string $next_action
     * @return bool
     */
    public function sendActiveEmail($email, $uid, $from = "", $next_action = "") {
        require_once APP_PATH . 'libraries/Plugin/Util.php';
        $verify = Plugin_Util::authcode($uid, 'ENCODE', ACTIVE_EMAIL_KEY, 3600 * 24);//1天有效
        $verify = urlencode(base64_encode($verify));
        $active_url = 'http://sso.cztv.com/user/activeEmail/verify/' . $verify;
        if (!empty($_GET['next_action'])) {
            $next_action = $_GET['next_action'];
        }
        !empty($from) && $active_url .= '?from=' . $from;
        if (isset($next_action) && !empty($next_action) && empty($from)) {
            $active_url .= '?next_action=' . trim(urlencode($next_action));
        } elseif (isset($next_action) && !empty($next_action) && !empty($from)) {
            $active_url .= '&next_action=' . trim(urlencode($next_action));
        }

        $url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';
        $vars = json_encode(array("to" => array($email), "sub" => array("%url%" => Array($active_url))));
        $API_USER = app_site()->email_sendcloud->email_api_user;
        $API_KEY = app_site()->email_sendcloud->email_api_key;
        $param = array(
            'api_user' => $API_USER, # 使用api_user和api_key进行验证
            'api_key' => $API_KEY,
            'from' => app_site()->email_sendcloud->email_from, # 发信人，用正确邮件地址替代
            'fromname' => '中国蓝TV',
            'substitution_vars' => $vars,
            'template_invoke_name' => 'sendActiveEmail',//对应模板
            'resp_email_id' => 'true'
        );
        $data = http_build_query($param);
        $result = F::curlRequest($url, 'post', $data);
        return $result;
    }

    public function sendCloudEmail($emailTemplate, $vars, $fromname = "") {
        
     	$url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';
    	$API_USER = app_site()->email_sendcloud->email_api_user;
    	$API_KEY = app_site()->email_sendcloud->email_api_key;
    	$param = array(
    			'api_user' => $API_USER, # 使用api_user和api_key进行验证
    			'api_key' => $API_KEY,
    			'from' => app_site()->email_sendcloud->email_from, # 发信人，用正确邮件地址替代
    			'fromname' => $fromname,
    			'substitution_vars' => $vars,
    			'template_invoke_name' => $emailTemplate,//对应模板
    			'resp_email_id' => 'true'
    	);
    	$data = http_build_query($param);
    	$result = F::curlRequest($url, 'post', $data);
    	return $result;
    }
    
    /**
     * @desc 邮箱找回密码邮件下发
     * @version 2015-06-05
     * @param unknown $email
     * @param unknown $uid
     * @param string $plat
     * @return Ambigous <boolean, mixed>
     */
    public function sendBackpwdEmail($email, $uid, $plat = 'web', $code = '') {

        $url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';
        $vars = json_encode(array("to" => array($email), "sub" => array("%code%" => Array($code))));
        $API_USER = app_site()->email_sendcloud->email_api_user;
        $API_KEY = app_site()->email_sendcloud->email_api_key;
        $param = array(
            'api_user' => $API_USER, # 使用api_user和api_key进行验证
            'api_key' => $API_KEY,
            'from' => app_site()->email_sendcloud->email_from, # 发信人，用正确邮件地址替代
            'fromname' => '中国蓝TV',
            'substitution_vars' => $vars,
            'template_invoke_name' => 'sendBackpwdEmail',//对应模板
            'resp_email_id' => 'true'
        );
        $data = http_build_query($param);
        $result = F::curlRequest($url, 'post', $data);
        return $result;
    }
}