<?php

use GenialCloud\Auth\Signature;

require APP_PATH . 'libraries/Excel/PHPExcel.php';

class F {

    /**
     * @var WeChat
     */
    protected static $wechat = null;

    /**
     * 检查heard最后修改时间
     */
    public static function checkLastModified() {
        if(isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $etag = $_SERVER['HTTP_IF_NONE_MATCH'];
            $time = RedisIO::get($etag);
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $time && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $time) {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * 设置最后请求时间
     * @param $key
     */
    public static function setLastModified($key){
        $nowgmt = gmdate('D, d M Y H:i:s ') . "GMT";
        header('Etag: ' . $key);
        header('Last-Modified: '.$nowgmt);
        RedisIO::set($key, $nowgmt, 86400);
    }


    /**
     * 删除IE(lastmodeified)缓存
     * @param $action "dept/test"
     * @param $channel_id 3
     */
    public static function _clearCache($action,$channel_id){
        $listName = "z/".$action . ":";
        if(false!==stripos($listName, "multimedia")) $listName = str_ireplace("multimedia", "news", $listName) ;
        $lists = (RedisIO::zRevRange($listName . $channel_id, 0, -1));
        foreach($lists as $val){
            RedisIO::delete($val);
            RedisIO::zRem($listName . $channel_id, $val);
        }
        RedisIO::delete($listName . $channel_id);
    }



    public static function createExcel($properties, $models, $filepre) {
        $headers = array_values($properties);
        $excel = new PHPExcel;
        $key = ord('A');
        foreach ($headers as $v) {
            $col = chr($key);
            $excel->setActiveSheetIndex(0)->setCellValue($col . '1', $v);
            $key += 1;
        }
        $col = 2;
        $sheet = $excel->getActiveSheet();
        $modelProperties = array_keys($properties);
        foreach ($models as $model) { //行写入

            $span = ord('A');
            foreach ($modelProperties as $p) {
                $j = chr($span);
                if('array'==gettype($model)) {
                    $sheet->setCellValue($j . $col, $model[$p]);
                }
                else {
                    $sheet->setCellValue($j . $col, $model->{$p});
                }
                $span++;
            }
            $col++;
        }
        $excel->getActiveSheet()->setTitle('Data');
        $excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filepre . str_random() . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    public static function createExcelSimple($models, $filepre) {
        $headers = [];
        $excel = new PHPExcel;
        $col = 2;
        $sheet = $excel->getActiveSheet();
        foreach ($models as $model) { //行写入
            $modelProperties = array_keys($model);
            sort($modelProperties);
            $headers = array_keys(array_flip($headers) + array_flip($modelProperties));
            $span = ord('A');
            foreach ($modelProperties as $p) {
                $j = chr($span);
                $sheet->setCellValueExplicit($j . $col, $model[$p], PHPExcel_Cell_DataType::TYPE_STRING);
                $span++;
            }
            $col++;
        }
        $key = ord('A');
        foreach ($headers as $v) {
            $col = chr($key);
            $excel->setActiveSheetIndex(0)->setCellValueExplicit($col . '1', $v, PHPExcel_Cell_DataType::TYPE_STRING);
            $key += 1;
        }
        $excel->getActiveSheet()->setTitle('Data');
        $excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filepre . str_random() . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    public  static function  readExcel($path) {
        $objReader = PHPExcel_IOFactory::createReader('CSV')
            ->setDelimiter(',')
            ->setInputEncoding('GBK')
            ->setEnclosure('"')
            ->setLineEnding("\r\n")
            ->setSheetIndex(0);
        $objPHPExcel = $objReader->load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
}

    /*
     * @desc 云帆接口请求专用
     * @params $url
     * @params string $url
     * @params array $headers
     * @params bool $body
     * @return string
     */
    public static function crulYFApiRequest($url, $headers, $body = []) {
        $handle = curl_init();
        if (CZTV_PROXY_ST == 1) {
            curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($handle, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($handle, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        if ($body) {
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_SLASHES));
        }
        $response = curl_exec($handle);
        curl_close($handle);
        return $response;
    }

    /*
     * @desc 解析http头返回值
     * @params $url
     * @params $args 发送数据
     * @return 返回状态 ，200:成功
     */
    public static function curlInfoRequestCode($url, $method = 'post', $args = [], $json = false, $allowproxy = true) {
        $url = $url;
        $method = $method;
        $data = $args;
        if ($json) {
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
            );
            $data = json_encode($args);
        }
        $url_arr = parse_url($url);
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        if ($json) curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        if ($allowproxy && CZTV_PROXY_ST == 1) {
            curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($handle, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($handle, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        if ($url_arr['scheme'] == 'https') {
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        }
        switch (strtoupper($method)) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, 1);
                if (is_array($data)) {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                } else {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                break;
        }
        ob_start();
    	$response = curl_exec($handle);
    	$response_code = curl_getinfo($handle,CURLINFO_RESPONSE_CODE);
    	ob_end_clean();
    	curl_close($handle);
    	return $response_code;
    }
    
    /*
     * @params $url
     * @params string $method
     * @params array $args
     * @params bool $json
     * @return string
     */
    public static function curlProxy($url, $method = 'get', $args = [], $json = true, $allowproxy=false) {//内部调用默认不允许走代理
        $network_config = app_site()->network_config;
        $app_id = $network_config['app_id'];
        $app_secret = $network_config['app_secret'];
        $input = array(
            'client_id' => "0",
            'key' => "00000000",
            'app_id' => $app_id,
        );
        $timestamp = (string)time();
        $input['timestamp'] = $timestamp;
        $input['key'] = substr($app_secret, $timestamp[strlen($timestamp) - 1]);
        ksort($input);
        $signature = Signature::buildQuery($input);
        $input['signature'] = sha1(base64_encode($signature));
        $url_curl = $network_config['url'];
        $url_curl .= "curl?app_id=" . $app_id . "&client_id=" . $input['client_id'] . "&signature=" . $input['signature'] . "&timestamp=" . $input['timestamp'];
        $input_post = ['url' => $url, 'method' => $method, 'args' => $args];
        return F::curlRequest($url_curl, 'put', $input_post, $json, $allowproxy);
    }

    public static function curlProxyCli($url, $method = 'get', $args = [], $json = true, $allowproxy=false) {//内部调用默认不允许走代理
        global $config;
        $network_config = $config->push_network;
        $app_id = $network_config['app_id'];
        $app_secret = $network_config['app_secret'];
        $input = array(
            'client_id' => "0",
            'key' => "00000000",
            'app_id' => $app_id,
        );
        $timestamp = (string)time();
        $input['timestamp'] = $timestamp;
        $input['key'] = substr($app_secret, $timestamp[strlen($timestamp) - 1]);
        ksort($input);
        $signature = Signature::buildQuery($input);
        $input['signature'] = sha1(base64_encode($signature));
        $url_curl = $network_config['url'];
        $url_curl .= "curl?app_id=" . $app_id . "&client_id=" . $input['client_id'] . "&signature=" . $input['signature'] . "&timestamp=" . $input['timestamp'];
        $input_post = ['url' => $url, 'method' => $method, 'args' => $args];
        return F::curlRequest($url_curl, 'put', $input_post, $json, $allowproxy);
    }

    public static function curlRequest($url, $method = 'get', $args = [], $json = false, $allowproxy=true) {
        $url = $url;
        $method = $method;
        $data = $args;
        if ($json) {
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
            );
            $data = json_encode($args);
        }
        $url_arr = parse_url($url);
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        if ($json) curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        if ($allowproxy && CZTV_PROXY_ST == 1) {
            curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($handle, CURLOPT_PROXY, CZTV_PROXY_IP); //代理服务器地址
            curl_setopt($handle, CURLOPT_PROXYPORT, CZTV_PROXY_PORT); //代理服务器端口
            curl_setopt($handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        }
        if ($url_arr['scheme'] == 'https') {
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        }
        switch (strtoupper($method)) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, 1);
                if (is_array($data)) {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                } else {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                break;
        }
        ob_start();
        $response = curl_exec($handle);
        ob_end_clean();
        curl_close($handle);
        return $response;
    }
    
    /**
     * 发送上传文件地址给cdn服务器
     * @param array $arrConfig
     * @param unknown $filePath
     * @param unknown $data
     */
    public static function cdnProxy($title, $data_id, $data, $channel_id=-1) {
    	$arrConfig = self::getConfig('cdn_refresh');
    	$cdnUrl = $arrConfig['cdn_url'];
    	$appId = $arrConfig['app_id'];
    	$appSecret = $arrConfig['app_secret'];
    	$channel_id = $channel_id == -1 ? $arrConfig['channel_id'] : $channel_id;
    	$pushData = array(
    			"app_id" 	 => $appId,
    			"app_secret" => $appSecret,
    			"channel_id" => $channel_id,
    			"cdn_id" 	 => 2,
    			"title" 	 => $title,
    			"task_id" 	 => $data_id,
    			"operation"  => 1,
    			"fc_sub" 	 => $data,
    	);
    	$timestamp = (int)time();
    	$signature = md5($appId.$appSecret.$timestamp);
    	$url = $cdnUrl."?app_id=".$appId."&signature=".$signature."&timestamp=".$timestamp;   
     	$result = F::curlRequest($url, 'post', $pushData, true, false);
     	return $result;
    }
    
   /**
    * 发送直播 手机导播 (直播流推送到云帆)
    * @param unknown $taskValue 	导播直播：播放地址
    * @param unknown $streamValue   导播直播：目标流地址
   */
    public static function liveUgcGuideProxy($streamValue, $taskValue, $channel_id=null) {
    	$data = F::getConfig("ugcLive", $channel_id);
    	$ip = isset($data) && array_key_exists("ip", $data) ? $data["ip"] :"ip";
    	$port = isset($data) && array_key_exists("port", $data) ? $data["port"] : "port";
    	//$taskValue = isset($data) && array_key_exists("playurl", $data) ? $data["playurl"] : "";
    	$task = base64_encode(trim($taskValue));
    	$stream = base64_encode(trim($streamValue));
    	$url = "http://".$ip.":".$port."/change/stream?task=".$task."&stream=".$stream;
    	//var_dump($url);
    	$result = F::curlRequest($url, 'post');
    	return $result;
    }
    
    /**
     * 获取setting表中cdn_url配置值
     * @return multitype:string
     */
    public static function getConfig($type,$channel_id=null) {
        if(!$channel_id){
    	    $channel_id = !empty(Session::get('user')) ? Session::get('user')->channel_id : "";
        }
    	if($channel_id == "") {
    		$channel_id = LETV_CHANNEL_ID;
    	}
    	$pushConfigJson =json_encode(Setting::getByChannel($channel_id, $type));
    	$pushConfigArr = json_decode($pushConfigJson);
    	switch ($type) {
    		case 'cdn_refresh':
    			$push_config= array(
    				"channel_id" 	=> $channel_id,
    				'app_id'		=> isset($pushConfigArr->app_id) ? trim($pushConfigArr->app_id) : "",
    				'app_secret'	=> isset($pushConfigArr->app_secret) ? trim($pushConfigArr->app_secret) : "",
    				'cdn_url'		=> isset($pushConfigArr->cdn_url) ? trim($pushConfigArr->cdn_url) : ""
    			);
    			break;
    		case 'domain_config':
    			$push_config= array(
    				'api'			=> isset($pushConfigArr->api) ? trim($pushConfigArr->api) : "",
    				'auth'			=> isset($pushConfigArr->auth) ? trim($pushConfigArr->auth) : "",
    				'backend'		=> isset($pushConfigArr->backend) ? trim($pushConfigArr->backend) : "",
    				'frontend'		=> isset($pushConfigArr->frontend) ? trim($pushConfigArr->frontend) : "",
    				'interaction'	=> isset($pushConfigArr->interaction) ? trim($pushConfigArr->interaction) : "",
    				'networkapi'	=> isset($pushConfigArr->networkapi) ? trim($pushConfigArr->networkapi) : "",
                    'publish'	=> isset($pushConfigArr->publish) ? trim($pushConfigArr->publish) : "",
    			);
    			break;
            case 'BosonNLP':
                $push_config = ["apikey"=> isset($pushConfigArr->apikey) ? trim($pushConfigArr->apikey):""];
                break;
            case 'baoliao_mail':
                $push_config =json_decode($pushConfigJson,true);
                break;
            case 'ugcLive':
            	$push_config= array(
            		'ip'			=> isset($pushConfigArr->ip) ? trim($pushConfigArr->ip) : "",
            		'port'			=> isset($pushConfigArr->port) ? trim($pushConfigArr->port) : "",
            		'playurl'		=> isset($pushConfigArr->playurl) ? trim($pushConfigArr->playurl) : "",
            	);
            	break;
            case 'oss_conf':
                $push_config =json_decode($pushConfigJson,true);
                break;
            case 'app_category':
                $push_config =json_decode($pushConfigJson,true);
                break;
    		default:
    			$push_config = array();
    			break;
    	}
    	return $push_config;
    }

    public static function getuiProxy($pushconfig, $pushdata, $clientid = null, $allowproxy=false) {//内部调用默认不允许走代理
        global $config;
        $network_config = $config->push_network;
        $app_id = $network_config['app_id'];
        $app_secret = $network_config['app_secret'];
        $input = array(
            'client_id' => "0",
            'key' => "00000000",
            'app_id' => $app_id,
        );
        $timestamp = (string)time();
        $input['timestamp'] = $timestamp;
        $input['key'] = substr($app_secret, $timestamp[strlen($timestamp) - 1]);
        ksort($input);
        $signature = Signature::buildQuery($input);
        $input['signature'] = sha1(base64_encode($signature));
        $url_curl = $network_config['url'];
        $url_curl .= "getui?app_id=" . $app_id . "&client_id=" . $input['client_id'] . "&signature=" . $input['signature'] . "&timestamp=" . $input['timestamp'];
        $input_post = ['pushconfig' => $pushconfig, 'pushdata' => $pushdata, 'clientid' => $clientid];
        return F::curlRequest($url_curl, 'put', $input_post, true, $allowproxy);
    }

    public static function getuiIOSTvCztvProxy($pushconfig, $pushdata, $pushType = 0, $clientid = null, $allowproxy=false) {//内部调用默认不允许走代理
        global $config;
        $network_config = $config->push_network;
        $app_id = $network_config['app_id'];
        $app_secret = $network_config['app_secret'];
        $input = array(
            'client_id' => "0",
            'key' => "00000000",
            'app_id' => $app_id,
        );
        $timestamp = (string)time();
        $input['timestamp'] = $timestamp;
        $input['key'] = substr($app_secret, $timestamp[strlen($timestamp) - 1]);
        ksort($input);
        $signature = Signature::buildQuery($input);
        $input['signature'] = sha1(base64_encode($signature));
        $url_curl = $network_config['url'];
        $url_curl .= "getui/tvcztvcom?app_id=" . $app_id . "&client_id=" . $input['client_id'] . "&signature=" . $input['signature'] . "&timestamp=" . $input['timestamp'];
        $input_post = ['pushconfig' => $pushconfig, 'pushdata' => $pushdata, 'clientid' => $clientid, 'pushtype' => $pushType];
        $x =  F::curlRequest($url_curl, 'put', $input_post, true, $allowproxy);

        return $x;
    }


    public static function getuiAndriodTvCztvProxy($pushconfig, $pushdata, $pushType = 0, $clientid = null, $allowproxy=false) {//内部调用默认不允许走代理
        global $config;
        $network_config = $config->push_network;
        $app_id = $network_config['app_id'];
        $app_secret = $network_config['app_secret'];
        $input = array(
            'client_id' => "0",
            'key' => "00000000",
            'app_id' => $app_id,
        );
        $timestamp = (string)time();
        $input['timestamp'] = $timestamp;
        $input['key'] = substr($app_secret, $timestamp[strlen($timestamp) - 1]);
        ksort($input);
        $signature = Signature::buildQuery($input);
        $input['signature'] = sha1(base64_encode($signature));
        $url_curl = $network_config['url'];
        $url_curl .= "getui/tvcztvcomandroid?app_id=" . $app_id . "&client_id=" . $input['client_id'] . "&signature=" . $input['signature'] . "&timestamp=" . $input['timestamp'];
        $input_post = ['pushconfig' => $pushconfig, 'pushdata' => $pushdata, 'clientid' => $clientid, 'pushtype' => $pushType];
        $result = F::curlRequest($url_curl, 'put', $input_post, true, $allowproxy);

        return $result;
    }

    /**
     * @desc 获取客户端IP.
     * @return mixed
     */
    public static function getRealIp() {
        $pattern = '/(\d{1,3}\.){3}\d{1,3}/';
        if (isset($_SERVER ["HTTP_X_FORWARDED_FOR"]) && preg_match_all($pattern, $_SERVER ['HTTP_X_FORWARDED_FOR'], $mat)) {
            foreach ($mat [0] as $ip) {
                //得到第一个非内网的IP地址
                if ((0 != strpos($ip, '192.168.')) && (0 != strpos($ip, '10.')) && (0 != strpos($ip, '172.16.'))) {
                    return $ip;
                }
            }
            return $ip;
        } else {
            if (isset($_SERVER ["HTTP_CLIENT_IP"]) && preg_match($pattern, $_SERVER["HTTP_CLIENT_IP"])) {
                return $_SERVER["HTTP_CLIENT_IP"];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
        }
    }

    /*
     * @desc 生成认证签名
     * */
    public static function getSignature($appid, $timestamp, $secret, $inputdata) {
        $timestamp = strval($timestamp);
        $inputdata['key'] = substr($secret, $timestamp[strlen($timestamp) - 1]);
        $inputdata['app_id'] = $appid;
        $inputdata['timestamp'] = $timestamp;
        ksort($inputdata);
        $signature = self::buildQuery($inputdata);
        return sha1(base64_encode($signature));
    }

    /*
     * @desc 生成查询字符串
     * */
    public static function buildQuery(array $input) {
        $query = [];
        if (!empty($input)) {
            foreach ($input as $k => $v) {
                $query[] = $k . "=" . $v;
            }
        }
        return implode("&", $query);
    }

    /*
     * @desc 不使用代理网络直接个推方法
     *
     * */
    public static function getuiAndriodTvCztv($pushconfig, $pushdata, $clientid = null) {
        $push_video_type = $pushdata['data']['enterType'];
        switch ($push_video_type) {
            case 1:
                $entertype_android = 0;
                break;//直播
            case 2:
                $entertype_android = 1;
                break;//点播
            case 3:
                $entertype_android = 2;
                break;//专题
            case 4:
                $entertype_android = 3;
                break;//web页
            case 5:
                $entertype_android = 5;
                break;//全景直播
            case 6:
                $entertype_android = 6;
                break;   //全景点播
            case 10:
                $entertype_android = 10;
                break; //UGC直播
            case 11:
                $entertype_android = 11;
                break; //UGC点播
        }
        $push_video_id = $pushdata['data']['videoId'];
        $ac_code = $push_video_id;
        $push_video_content = $pushdata['title'];
        $push_video_intro = isset($pushdata['data']['intro']) ? $pushdata['data']['channelId'] : "";
        $push_video_shareurl = isset($pushdata['data']['shareUrl']) ? $pushdata['data']['shareUrl'] : "";
        $push_video_ablumid = isset($pushdata['data']['ablumId']) ? $pushdata['data']['ablumId'] : 0;
        $push_video_channelid = isset($pushdata['data']['channelId']) ? $pushdata['data']['channelId'] : 0;
        $push_video_image = isset($pushdata['data']['image']) ? $pushdata['data']['image'] : "";
        $push_video_name = isset($pushdata['data']['videoName']) ? $pushdata['data']['videoName'] : "";
        $push_video_url = isset($pushdata['data']['url']) ? $pushdata['data']['url'] : "";
        $android_msg = "ChinaBlueTV://cztv/xinlan?";
        $android_msg .= "enterType=" . $entertype_android;
        $android_msg .= "&videoId=" . (($push_video_type == 1) ? $ac_code : $push_video_id);
        $android_msg .= "&videoChannelId=" . $push_video_channelid;
        $android_msg .= "&videoAblumId=" . $push_video_ablumid;
        $android_msg .= "&videoTitle=" . $push_video_content;
        $android_msg .= "&videoBrief=" . $push_video_intro;
        $android_msg .= "&videoShareUrl=" . $push_video_shareurl;
        $android_msg .= "&imageUrl=" . $push_video_image;
        $android_msg .= "&url=" . $push_video_url;
        $android_msg .= "&videoName=" . $push_video_name;
        $pushdata['title'] = $android_msg;
        $sender = new TvCztvComSender($pushconfig['AppKey'], $pushconfig['MasterSecret'], $pushconfig['AppID']);
        $rep = $sender->sendToAndroid($pushdata, $clientid);
        return json_encode($rep);
    }


    public static function getVersionIOS($sdk_version){
        $digit_first = substr($sdk_version,0,1);
        if($digit_first== '1'){
            return substr(str_replace(".",'',$sdk_version),0,2);
        }
        return $digit_first;
    }


    public static function  isemail($email) {
        if (preg_match("/^[a-z0-9]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_\.]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i", $email)) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function  ismobile($mobile) {
        if (preg_match("/^1[34578]\d{9}$/i", $mobile)) {
            return true;
        }
        else {
            return false;
        }
    }

    public static  function isUrl($url) {
        if (preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $url)) {
            return true;
        }
        else {
            return false;
        }

    }


}