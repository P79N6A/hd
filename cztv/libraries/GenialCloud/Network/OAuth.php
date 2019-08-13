<?php

namespace GenialCloud\Network;

class OAuth extends Http {

    /**
     * Consumer key
     * @var string
     */
    protected $key = '';

    /**
     * Consumer key secret
     * @var string
     */
    protected $secret = '';

    /**
     * callback url
     * @var string
     */
    protected $callback = null;

    /**
     *
     * @var string
     */
    protected $scope = '';

    /**
     * url to get access token
     * @var string
     */
    protected $accessTokenUrl = '';

    /**
     * url to swap authorization code
     * @var string
     */
    protected $tokenUrl = '';

    /**
     * construction function
     */
    public final function __construct($config) {
        parent::__construct();
        $this->key = $config['key'];
        $this->secret = $config['secret'];
        if(isset($config['callback'])) {
            $this->callback = $config['callback'];
        }
        if(isset($config['scope'])) {
            $this->scope = $config['scope'];
        }
        $this->init();
    }

    /**
     * 初始化设定
     */
    protected function init() {
    }

    /**
     * set consumer key secret
     * @return Null
     */
    public function setCallback($url) {
        $this->callback = $url;
    }

    /**
     * set consumer key secret
     * @return Null
     */
    public function setScope($scope) {
        $this->scope = $scope;
    }

    /**
     * do a request to token url
     */
    protected function doRequest($params) {
        return $this->setHeaders([
            'Content-Type'=>'application/x-www-form-urlencoded'
        ])->post($this->tokenUrl, $params);
    }

    /**
     * request Access Token
     * @return null
     */
    public function requestAccessToken() {
        $params = $this->buildAccessTokenParams();
        $url = $this->accessTokenUrl.'?'.http_build_query($params);
        redirect($url);
    }

    /**
     * swap authorization code
     * @return string
     */
    public function swapToken($code) {
        $params = $this->buildSwapTokenParams();
        $params['code'] = $code;
        return $this->doRequest($params);
    }

    /**
     * refresh access token
     * @refresh string
     */
    public function refreshToken($refreshToken) {
        $params = $this->buildRefreshTokenParams();
        $params['refresh_token'] = $refreshToken;
        return $this->doRequest($params);
    }

    /**
     * build request Access Token params
     * @return array
     */
    protected function buildAccessTokenParams() {
        throw new OAuthException('Method `buildAccessTokenParams` not implemented.', 0);
    }

    /**
     * build swap authorization code params
     * @return array
     */
    protected function buildSwapTokenParams() {
        throw new OAuthException('Method `buildSwapTokenParams` not implemented.', 0);
    }

    /**
     * build refresh access token params
     * @return array
     */
    protected function buildRefreshTokenParams() {
        throw new OAuthException('Method `buildRefreshTokenParams` not implemented.', 0);
    }

}
