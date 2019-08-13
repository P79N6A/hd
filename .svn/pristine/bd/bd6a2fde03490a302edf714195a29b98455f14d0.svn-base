<?php
/**
 * 李红刚
 * 2019-05-17
 */

class TxFaceController extends InteractionBaseController{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 人脸融合
     * 李红刚
     * 2019-05-15
     */
    public function changeFaceAction()
    {
        $channel_id = Request::getPost('channel_id','int');
        $activity_id = Request::getPost('activity_id','int');
        $model_id = Request::getPost('model_id','int');
        if (!$channel_id || !$activity_id || !$model_id) {
            $this->jsonp(array('code' => 2001, 'msg' => '参数错误'));
        }
        if(Request::hasFiles()) {
            $files = Request::getUploadedFiles();
            $count = count($files);
            if ($count !== 1){
                $this->_json([], 403, 'Forbidden');
            }
            $file = $files[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(!in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                    $this->_json([], 403, 'Forbidden');
                }
                // 获取活动

                // 图片人脸分析

                // base64
                // $path = Oss::uniqueUpload($ext, $file->getTempName(), 'face');
                // $image_url = Oss::url($path);

                $file_url = file_get_contents($file->getTempName());
                $imgae_base64 = base64_encode($file_url);
                // 获取配置
                $params['ProjectId'] = '101381';
                $params['ModelId'] = 'qc_101381_144954_3';
                $params['Image'] = $imgae_base64;
                $params['RspImgType'] = 'url';
                $setting = Setting::getByChannel($channel_id, 'tencent_cloud');
                if ($setting) {
                    $url = 'http://127.0.0.9/txcloud/face';
                    $data['params'] = $params;
                    $data['setting'] = $setting;
                    $res = F::curlRequest($url, 'post', $data);
                    $res = json_decode($res, true);
                    // 转成数组，保存图片，返回图片地址
                    $path = Oss::uniqueUpload('jpg', $res['Image'], 'face');
                    $image_url = Oss::url($path);
                    $this->jsonp(array('code' => 200, 'msg' => '融合成功', 'url'=>$image_url));
                } else {
                    $this->jsonp(array('code' => 2003, 'msg' => '参数配置'));
                }
            }
        }
        $this->jsonp(array('code' => 2002, 'msg' => '图片信息有误'));
    }
}