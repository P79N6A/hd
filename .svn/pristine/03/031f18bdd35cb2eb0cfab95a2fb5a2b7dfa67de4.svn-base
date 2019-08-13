<?php

use GenialCloud\Network\WeiboOAuth;
use GenialCloud\Network\QQOAuth;

class ConnectController extends InteractionBaseController {

    protected function tryLoginOrConnect($data) {
        $r = UserSocials::getAndRefreshByOpenId($this->channel_id, $data);
        if($r) {
            Auth::login($r);
            redirect(Url::get('auth/login'));
        } elseif($r === 0) {
            Session::set('user_socials', $data);
            redirect(Url::get('auth/connect'));
        } else {
            echo Lang::_('user frozen');
            exit;
        }
    }

    public function initialize() {
        parent::initialize();
        if(Auth::check()) {
            redirect(Url::get('auth/login'));
        }
    }

    public function qqAction() {
        $config = Setting::getByChannel($this->channel_id, 'qq.oauth');
        if(!$config) {
            abort(403);
        }
        $oauth = new QQOAuth($config);
        if(!$code = Request::get('code')) {
            redirect(Url::get($oauth->requestAccessToken()));
        } else {
            $r = $oauth->swapToken($code);
            parse_str($r['body'], $resp);
            if(isset($resp['access_token']) && $resp['access_token']) {
                //获取 open ID
                $resp['open_id'] = '';
                if($open_r = $oauth->requestOpenId($resp['access_token'])) {
                    $body = trim($open_r['body']);
                    if(preg_match('#callback\( (.*) \)#i', $body, $matches)) {
                        $open_resp = json_decode($matches[1], true);
                        $resp['open_id'] = $open_resp['openid'];
                    }
                }
                if($resp['open_id']) {
                    $data = [
                        'type' => 'qq',
                        'token' => $resp['access_token'],
                        'open_id' => $resp['open_id'],
                        'refresh_token' => $resp['refresh_token'],
                    ];
                    $this->tryLoginOrConnect($data);
                } else {
                    echo 'Get open_id error.';
                }
            } else {
                echo key($resp);
            }
        }
    }

    public function weiboAction() {
        $config = Setting::getByChannel($this->channel_id, 'weibo.oauth');
        if(!$config) {
            abort(403);
        }
        $oauth = new WeiboOAuth($config);
        if(!$code = Request::get('code')) {
            redirect(Url::get($oauth->requestAccessToken()));
        } else {
            $r = $oauth->swapToken($code);
            $resp = json_decode($r['body'], true);
            if($r['code'] == 200) {
                $data = [
                    'type' => 'weibo',
                    'token' => $resp['access_token'],
                    'open_id' => $resp['uid'],
                    'refresh_token' => '',
                ];
                $this->tryLoginOrConnect($data);
            } else {
                echo $resp['error'];
            }
        }
    }

}