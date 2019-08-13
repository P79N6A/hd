<?php namespace GenialCloud\Network;

class WeiboOAuth extends OAuth {

    /**
     * url to get access token
     * @var string
     */
    protected $accessTokenUrl = 'https://api.weibo.com/oauth2/authorize';

    /**
     * url to swap authorization code
     * @var string
     */
    protected $tokenUrl = 'https://api.weibo.com/oauth2/access_token';

    /**
     * build request Access Token params
     * @return array
     */
    protected function buildAccessTokenParams() {
        $params['client_id'] = $this->key;
        $params['redirect_uri'] = $this->callback;
        $params['scope'] = $this->scope;
        return $params;
    }

    /**
     * build swap authorization code params
     * @return array
     */
    protected function buildSwapTokenParams() {
        $params['client_id'] = $this->key;
        $params['client_secret'] = $this->secret;
        $params['grant_type'] = 'authorization_code';
        $params['redirect_uri'] = $this->callback;
        return $params;
    }

    /**
     * build refresh access token params
     * @return array
     */
    protected function buildRefreshTokenParams() {
        $params['client_id'] = $this->key;
        $params['client_secret'] = $this->secret;
        $params['refresh_token'] = '';
        $params['grant_type'] = 'refresh_token';
        return $params;
    }

    /**
     * update status
     */
    public function updateStatus($token, $text) {
        $url = 'https://api.weibo.com/2/statuses/update.json';
        $params = [
            'status' => $text,
            'access_token' => $token,
        ];
        $res = json_decode($this->request('post', $url, $params));
        if(isset($res->error_code)) {
            return array(false, $res->error_code, $res->error);
        } else {
            return array(true, 0, '');
        }
    }
}