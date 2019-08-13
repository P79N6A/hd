<?php

namespace GenialCloud\Network\Services;

use \GenialCloud\Network\OAuth;

class FastWebCDN extends OAuth {

    protected $tokenUrl = 'https://cdncs-api.fastweb.com.cn/oauth/access_token.json';

    /**
     * request Access Token
     * @return array
     */
    public function requestAccessToken() {
        $token = '';
        $expires_in = 0;
        $info = '';
        $params = $this->buildAccessTokenParams();
        $rs = $this->doRequest($params)['body'];
        if($rs) {
            $rs = json_decode($rs);
            if($rs->status == 1) {
                $token = $rs->result->access_token;
                $expires_in = $rs->result->expires_in;
            } else {
                $info = $rs->info;
            }
        }
        return compact('token', 'expires_in', 'info');
    }

    /**
     * build request Access Token params
     * @return array
     */
    protected function buildAccessTokenParams() {
        $params['grant_type'] = 'client_credentials';
        $params['appid'] = $this->key;
        $params['appsecret'] = $this->secret;
        return json_encode($params);
    }

    public function addPurge($token, $dirs=[], $files=[]) {
        $url = 'https://cdncs-api.fastweb.com.cn/cont/add_purge.json';
        $params = [];
        $params['access_token'] = $token;
        $rs = false;
        if(!empty($dirs) || !empty($files)) {
            foreach($dirs as $dir) {
                $params['dirs'][] = ['url_name' => $dir];
            }
            foreach($files as $file) {
                $params['files'][] = ['url_name' => $file];
            }
            $rs = $this->post($url, json_encode($params))['body'];
            if($rs) {
                $rs = json_decode($rs);
            }
        }
        return $rs;
    }

}