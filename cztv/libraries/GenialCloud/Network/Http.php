<?php

namespace GenialCloud\Network;


class Http {

    protected $r = null;

    protected $options = [
        'user_agent' => [CURLOPT_USERAGENT, 'GenialCloud Http Request v1.0'],
        'return_transfer' => [CURLOPT_RETURNTRANSFER, 1],
    ];

    protected $params = [];

    /**
     * construct function
     */
    public function __construct($params = []) {
        $this->r = curl_init();
        $this->setParams($params);
    }

    /**
     * destruct function
     */
    public function __destruct() {
        if($this->r) {
            curl_close($this->r);
        }
    }

    /**
     * @param array $params
     * @return null
     */
    protected final function setParams(array $params) {
        foreach($this->options as $k => $v) {
            if(isset($params[$k])) {
                $this->params[$k] = $params[$k];
            } else {
                $this->params[$k] = $v[1];
            }
        }
    }

    public function setOption($option, $value) {
        curl_setopt($this->r, $option, $value);
        return $this;
    }

    public function setHeaders(array $headers) {
        return $this->setOption(CURLOPT_HTTPHEADER, $headers);
    }

    public function setOptions(array $options) {
        curl_setopt_array($this->r, $options);
        return $this;
    }

    protected function applyOptions() {
        foreach($this->params as $k => $v) {
            curl_setopt($this->r, $this->options[$k][0], $v);
        }
    }

    /**
     * @param $auth
     */
    public function basicAuth($auth) {
        curl_setopt($this->r, CURLOPT_USERPWD, $auth);
        curl_setopt($this->r, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        return $this;
    }

    /**
     * GET
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public function get($url, $data = []) {
        return $this->request('get', $url, $data);
    }

    /**
     * POST
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public function post($url, $data = []) {
        return $this->request('post', $url, $data);
    }

    /**
     * PUT
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public function put($url, $data = []) {
        return $this->request('put', $url, $data);
    }

    /**
     * DELETE
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public function delete($url, $data = []) {
        return $this->request('delete', $url, $data);
    }

    /**
     * 请求数据
     *
     * @param string $method
     * @param array $data
     * @return array
     */
    public function request($method, $url, $data = []) {
        $method = strtolower($method);
        $str = '';
        if(is_string($data)) {
            $str = $data;
        } elseif(!empty($data)) {
            $str = http_build_query($data);
        }
        switch($method) {
            case 'post':
                if($str) {
                    curl_setopt($this->r, CURLOPT_POSTFIELDS, $str);
                }
                break;
            case 'head':
                curl_setopt($this->r, CURLOPT_NOBODY, 1);
                break;
            case 'get':
            default:
                if($str) {
                    if(strpos($url, '?') === false) {
                        $str = '?'.$str;
                    } else {
                        $str = '&'.$str;
                    }
                    $url .= $str;
                }
                break;
        }
        curl_setopt($this->r, CURLOPT_URL, $url);
        return $this->exec();
    }

    /**
     * 执行请求
     *
     * @return array
     * @throws \GenialCloud\Network\HttpException
     */
    protected function exec() {
        $this->applyOptions();
        $body = curl_exec($this->r);
        if($error = curl_error($this->r)) {
            throw new HttpException($error, curl_errno($this->r));
        }
        $code = curl_getinfo($this->r, CURLINFO_HTTP_CODE);
        return compact('code', 'body');
    }

    /*
        $size = curl_getinfo($this->r, CURLINFO_HEADER_SIZE);
        $header = substr($r, 0, $size);
        $i = $j = 0;
        $headers = [];
        foreach(explode(PHP_EOL, $header) as $row) {
            if(!$row = trim($row)) {
                continue;
            }
            $pos = strpos($row, ':');
            $key = $pos !== false? substr($row, 0, $pos): $i++;
            $headers[$key] = $pos !== false? trim(substr($row, $pos + 1)): $row;
        }
        foreach($headers as $key => $val) {
            $vals = explode(';', $val);
            if(count($vals) >= 2) {
                unset($headers[$key]);
                foreach($vals as $vk => $vv) {
                    $equalpos = strpos($vv, '=');
                    $vkey = $equalpos !== false? trim(substr($vv, 0, $equalpos)): $j++;
                    $headers[$key][$vkey] = $equalpos !== false? trim(substr($vv, $equalpos + 1)): $vv;
                }
            }
        }
        $body = substr($r, $size);
     */

}