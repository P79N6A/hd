<?php

/**
 * @RoutePrefix("/proxy")
 */
class ProxyController extends ApiBaseController {

    //弱签名验证，仅用于移动代理使用，不要加入敏感信息查询方法
    public function initialize() {
        $this->simplisticSignature();
    }

    /**
     * 弱化的签名验证，仅验证app_id
     */
    protected function simplisticSignature() {
        $input = Request::getQuery();
        if (!issets($input, ['app_id'])) {
            $this->_json([], 404, D::apiError(4001));
        }
        // 站点信息读取
        $data = Site::getByAppId($input['app_id']);
        if (empty($data)) {
            $this->_json([], 404, D::apiError(4002));
        }
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
        $channel_info = Channel::getOneChannel($this->channel_id);
        if(!($channel_info&&$channel_info->status==1)) {
            $this->_json([], 403, D::apiError(4005));
        }
    }

    /**
     * @Get("/versionCheck")
     */
    public function versionCheckAction() {
        $sku = Request::getQuery('sku');
        $type =  (int)Request::getQuery('type');
        $v =  Request::getQuery('v');

        $key = D::memKey('getAppBySku', ['sku' => $sku]);
        $app = RedisIO::get($key);
        if (!$app) {
            $app = AppList::getAppBySku($sku);
            if ($app) {
                $app = json_encode($app);
            }
            RedisIO::set($key , $app);
        }
        if ($app) {
            $app = json_decode($app);
        } else {
            $this->_json( array('status'=>0));
        }

        if ($type == 1 && $this->verCompare(trim($app->version_android), $v)) {//android 有新版本
            $data = $this->getVersionData($app, $type);
        }
        elseif ($type == 2 && $this->verCompare(trim($app->version_ios), $v)) {//ios
            $data = $this->getVersionData($app, $type);
        }
        else {
            $data = array('status'=>0);
        }
        $this->_json($data);
    }

    private function getVersionData($app, $type) {
        $versiondata = false;
        if ($type == 1) {//安卓版本
            $key = D::memKey('apiGetAppVersionData', ['app_id' => $app->id , 'type' => $type, 'version' => $app->version_android]);
            $versiondata = RedisIO::get($key);
            if (!$versiondata) {
                $versiondata = AppVersion::findFirst(array(
                    'app_id=:app_id: AND type=:type: AND version=:version:',
                    'bind' => array('app_id' => $app->id, 'type' => $type, 'version' => $app->version_android)
                ));
                if ($versiondata) {
                    $versiondata = json_encode($versiondata);
                }
                RedisIO::set($key , $versiondata);
            }
        }
        elseif ($type == 2) {//ios版本
            $key = D::memKey('apiGetAppVersionData', ['app_id' => $app->id , 'type' => $type, 'version' => $app->version_ios]);
            $versiondata = RedisIO::get($key);
            if (!$versiondata) {
                $versiondata = AppVersion::findFirst(array(
                    'app_id=:app_id: AND type=:type: AND version=:version:',
                    'bind' => array('app_id' => $app->id, 'type' => $type, 'version' => $app->version_ios)
                ));
                if ($versiondata) {
                    $versiondata = json_encode($versiondata);
                }
                RedisIO::set($key , $versiondata);
            }
        }
        if($versiondata) {
            $versiondata = json_decode($versiondata , true);
            $data = array(
                'status'=>1,
                'version' => $versiondata['version'],
                'name' => $versiondata['name'],
                'nextcheck' => 3,
                'newfeature' => $versiondata['newfeature'],
                'url' => $versiondata['url']
            );
        }else {
            $data = array('status'=>0);
        }
        return $data;
    }
    
    private function verCompare($check, $data) {
        $check_arr = explode('.', $check);
        $data_arr = explode('.', $data);

        $check_len = count($check_arr);
        $data_len = count($data_arr);
        $len = $check_len > $data_len ? $check_len : $data_len;

        for ($i = 0; $i < $len; $i++) {
            $check_num = intval($check_arr[$i]);
            $data_num = intval($data_arr[$i]);
            if ($check_num > $data_num) {
                return true;
            } else if ($check_num < $data_num) {
                return false;
            }
        }
        return false;
    }

    protected function _jsonzgltv($data, $code = 0, $msg = "success", $aleradyarray=false ,$alertMes="数据获取成功") {
        header('Content-type: application/json');
        $listdata = [];
        if($data!=[]) $listdata[] = $data;
        if($aleradyarray) $listdata = $data;
        echo json_encode([
            'alertMessage' => $alertMes,
            'state' => $code,
            'message' => $msg,
            'content' => ['list'=>$listdata],
        ]);
        exit;
    }
}