<?php namespace GenialCloud\Network;

class QQOAuth extends OAuth {

    /**
     * url to get access token
     * @var string
     */
    protected $accessTokenUrl = 'https://graph.qq.com/oauth2.0/authorize';

    /**
     * url to swap authorization code
     * @var string
     */
    protected $tokenUrl = 'https://graph.qq.com/oauth2.0/token';

    /**
     * build request Access Token params
     * @return array
     */
    protected function buildAccessTokenParams() {
        $params['response_type'] = 'code';
        $params['client_id'] = $this->key;
        $params['redirect_uri'] = $this->callback;
        $params['scope'] = $this->scope;
        $params['state'] = str_random();
        return $params;
    }

    /**
     * build swap authorization code params
     * @return array
     */
    protected function buildSwapTokenParams() {
        $params['grant_type'] = 'authorization_code';
        $params['client_id'] = $this->key;
        $params['client_secret'] = $this->secret;
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

    public function requestOpenId($access_token) {
        $url = 'https://graph.qq.com/oauth2.0/me';
        return $this->post($url, compact('access_token'));
    }

}