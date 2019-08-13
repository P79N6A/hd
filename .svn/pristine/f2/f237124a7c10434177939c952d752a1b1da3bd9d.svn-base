<?php

/**
 * Class AppController
 * PC端获取与APP相关信息的接口
 */
class AppController  extends InteractionBaseController {
    /**
     * 下载APP对应的最新安卓版本，直接返回文件地址
     */
    public function downloadAndroidAction() {
        $sku = Request::getQuery('sku');
        if (!$sku) {
            $this->jsonp(array('code'=>201,'msg'=>'sku不能为空'));
        }

        $app = AppList::getAppBySku($sku);
        if ($app && $app->version_android) {
            $app_version = AppVersion::getOneByVersion($app->id, 1, $app->version_android);
            if ($app_version && $app_version->url) {
                header("Location:" . $app_version->url);
                exit;
            }else {
                $this->jsonp(array('code'=>203,'msg'=>'版本下载地址不存在'));
            }
        }else {
            $this->jsonp(array('code'=>202,'msg'=>'对应APP不存在或安卓版本不存在'));
        }
    }
}