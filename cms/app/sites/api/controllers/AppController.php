<?php

/**
 * @RoutePrefix("/app")
 */
class AppController extends ApiBaseController {

    private $popup_cache = "app::popup::status:";//用户登入状态
    private $popup_app = "app::popup::app:";//获取的APP对象
    private $popup_popup = "app::popup::popup:";//获取的APP对象

    /**
     * @Get("/")
     */
    public function versionCheckAction() {
        $sku = Request::getQuery('sku');
        $type =  (int)Request::getQuery('type');
        $v =  Request::getQuery('v');

        $app = AppList::getAppBySku($sku);
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

    /**
     * @Post("/downloader")
     */
    public function statisticsAction() {
        $sku = Request::getPost('sku');
        $type =  (int)Request::getPost('type');
        $v =  Request::getPost('v');
        $app = AppList::getAppBySku($sku);
        /*
         * $app->id  $app->version_android 可对这两个参数进行缓存
         */
        $versiondata = AppVersion::findFirst(array(
                'app_id=:app_id: AND type=:type: AND version=:version:',
                'bind' => array('app_id' => $app->id, 'type' => $type, 'version' => $v)
            ));
        if($versiondata) {
            $versiondata->updateVersion(array('downloads'=>$versiondata->downloads+1));
            $data = array('status'=>1, 'downloads'=>$versiondata->downloads);
        }
        else {
            $this->_json([], 404, 'Not Found');
        }
        $this->_json($data);
    }

    private function getVersionData($app, $type) {
        if ($type == 1) {
            $versiondata = AppVersion::findFirst(array(
                    'app_id=:app_id: AND type=:type: AND version=:version:',
                    'bind' => array('app_id' => $app->id, 'type' => $type, 'version' => $app->version_android)
                ));
        }
        elseif ($type == 2) {
            $versiondata = AppVersion::findFirst(array(
                    'app_id=:app_id: AND type=:type: AND version=:version:',
                    'bind' => array('app_id' => $app->id, 'type' => $type, 'version' => $app->version_ios)
                ));            
        }
        if($versiondata) {
          $data = array(
                'status'=>1,
                'version' => $versiondata->version,
                'name' => $versiondata->name,
                'nextcheck' => 3,
                'newfeature' => $versiondata->newfeature,
                'url' => ($type == 1)?cdn_url('image', $versiondata->apk):$versiondata->url
            );
        }
        else {
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

    /**
     * @Get("/popup")
     */
    public function popupAction() {
        $sku = Request::getQuery('sku');
        $app_version = Request::getQuery('app_version');
        $client_type =  Request::getQuery('client_type');
        $sdk_version =  Request::getQuery('sdk_version');
        $latest =  Request::getQuery('latest');
        $client_id =  Request::getQuery('client_id');
        $mem_key = $this->popup_app.$sku;
        if($app = MemcacheIO::get($mem_key)){

        }else {
            $app = AppList::getAppBySku($sku);
            if($app){
                $app=$app->toArray();
                MemcacheIO::set($mem_key , $app , 60*10);
            }
        }
        if($client_type=='ios'){
            $type = 2;
        }elseif($client_type=='android'){
            $type = 1;
        }else{
            $this->_jsonzgltv(array(
                "status" => 0,
                "imageURL" => '',
                "webURL" => '',
                'title' => '',
                "videoId" => '',
                "unitDetailType" => ''
            ),0,'false' ,false , '移动端类型错误');
        }
        if(!$app){
            $this->_jsonzgltv(array(
                "status" => 0,
                "imageURL" => '',
                "webURL" => '',
                'title' => '',
                "videoId" => '',
                "unitDetailType" => ''
            ),0,'false' ,false , '对应APP不存在');
        }
        $mem_popup = $this->popup_popup.$sku.$app_version.$client_type.$latest;
        if($app_popup = MemcacheIO::get($mem_popup)){

        }else {
            $app_popup = AppPopup::findOne(array(
                'app_id' => $app['id'],
                'type' => $type,
                'version' => $app_version,
                'poptype' => $latest,
                'status' =>1
            ));
            if($app_popup) {
                $app_popup = $app_popup->toArray();
                MemcacheIO::set($mem_popup, $app_popup, 60 * 10);
            }
        }

        if($app_popup && $latest==1) {//app_popup存在并且为首次弹窗
            $redis_key = $this->popup_cache. $client_id . $app_version . $app['id'] . $client_type;
            
            if ($latest == 1 && !RedisIO::get($redis_key)) {//未登入过

                RedisIO::set($redis_key, 1);
                $popupdata = json_decode($app_popup['popupdata'],true);
                $app_popup['url'] = isset($popupdata['url'])?$popupdata['url']:'';
                $app_popup['video_id'] = isset($popupdata['video_id'])?$popupdata['video_id']:'';
                $this->_jsonzgltv(array(
                    'status' => 1,
                    'imageURL' => cdn_url('image',$app_popup['thumb']),
                    'webURL' => $app_popup['url'],
                    'title' => $app_popup['title']?$app_popup['title']:'中国蓝TV',
                    "videoId" => $app_popup['video_id'],
                    "unitDetailType" => $app_popup['datatype']
                ),0 , 'success' , false, '仅首次登入显示');

            } else {//登入过
                $this->_jsonzgltv(array(
                    "status" => 0,
                    "imageURL" => '',
                    "webURL" => '',
                    'title' => '',
                    "videoId" => '',
                    "unitDetailType" => ''
                ),0,'false' ,false, '已登入过');
            }
        }elseif($app_popup && $latest!=1){//不是单次弹窗
            $popupdata = json_decode($app_popup['popupdata'],true);
            $app_popup['url'] = isset($popupdata['url'])?$popupdata['url']:'';
            $app_popup['video_id'] = isset($popupdata['video_id'])?$popupdata['video_id']:'';
            $this->_jsonzgltv(array(
                'status' => 1,
                'imageURL' => cdn_url('image',$app_popup['thumb']),
                'webURL' => $app_popup['url'],
                'title' => $app_popup['title']?$app_popup['title']:'中国蓝TV',
                "videoId" => $app_popup['video_id'],
                "unitDetailType" => $app_popup['datatype']
            ),0 , 'success' , false, '每次登入都显示');
        }else{//提示弹窗不存在
            $this->_jsonzgltv(array(
                "status" => 0,
                "imageURL" => '',
                "webURL" => '',
                'title' => '',
                "videoId" => '',
                "unitDetailType" => ''
            ),0,'false' , false, '不存在对应弹窗提示');
        }



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