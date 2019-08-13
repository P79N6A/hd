<?php
/**
 * Created by yantengwei.
 * User: Administrator
 * Date: 2015/9/22
 * Time: 15:37
 */
class ApplistController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;


    public function indexAction() {
        $channel_id = Session::get("user")->channel_id;
        $data = AppList::getAppsByChannelId($channel_id);
        View::setVars(compact('data'));
    }

    public function addAction() {
        $msg = "";
        if ($this->request->isPost()) {        
            $data = $this->request->getPost();
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'logo');
                if (!$isFileEmpty) {
                    $data['logo'] = $this->uploadFile($uploadFile);
                }
            }
            $data['channel_id'] = Session::get("user")->channel_id;
            $validator = AppList::makeValidators($data);
            if(!$validator->fails()) {
                $app = new AppList();
                $app->createApp($data);
                $msg[] = Lang::_('success');
            } else {
                $msg[] = Lang::_('error');
            }
        }
        $messages = $msg;
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function editAction() {
        $app_id = $this->request->getQuery("id","int");
        $app = AppList::findFirst($app_id);
        $msg = "";
        if ($this->request->isPost()) {        
            $data = $this->request->getPost();
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'logo');
                if (!$isFileEmpty) {
                    $data['logo'] = $this->uploadFile($uploadFile);
                }
            }
            $data['channel_id'] = Session::get("user")->channel_id;
            $validator = AppList::makeValidators($data);
            if(!$validator->fails()) {                
                $app->updateApp($data);
                $key = D::memKey('getAppBySku', ['sku' => $app->sku]);
                RedisIO::delete($key);
                $msg[] = Lang::_('success');
            } else {
                $msg[] = Lang::_('error');
            }
        }
        $messages = $msg;
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'app'));
    }

    /**
     * 删除APP
     */
    public function deleteAction() {
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get('user')->channel_id;

        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if($app->channel_id == $channel_id && AppList::deleteApp($versiondata->app_id, $channel_id)) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function versionslistAction() {
        $app_id = $this->request->get('id');
        $app_type = $this->request->get('type');

        $channel_id = Session::get("user")->channel_id;
        $app = AppList::getApp($app_id, $channel_id);
        $app_name = $app->name;
        if ($app) {
            View::setVars(compact('app','app_name','app_id','app_type'));
            $type = ($app_type=="android")?1:2;
            $data = AppVersion::findAll($app_id, $type);
            View::setVars(compact('data'));
        } else {
            $savesuccess = true;
            $msg = "APP不存在";
            View::setMainView('layouts/add');
            View::setVars(compact('savesuccess', 'msg'));
            View::pick('layouts/save');
        }
    }

    public function androidaddAction() {
        $msg = "";
        $app_id = $this->request->get('app_id');
        $channel_id = Session::get("user")->channel_id;
        $app = AppList::getApp($app_id, $channel_id);
        if(!$app) redirect(Url::get('applist'));

        if (Request::isPost()) {
            $data = $this->request->getPost();
            $versiondata = AppVersion::getLastVersion($app_id, "android");
            $lastversion = ($versiondata)?$versiondata->version:0;
//            if ($this->isNewVersion(trim($data['version']), trim($lastversion))) {
                $data['logo'] = $app->logo;
                //if ($this->isFileTypeValid()) {
                    $uploadFile = '';
                    $fileerror = $this->isFileUploadEmpty($uploadFile, 'logo');
                    if(self::FILE_NOT_UPLOAD!=$fileerror) {
                        if (!$fileerror) {
                            $data['logo'] = $this->uploadFile($uploadFile);
                        }
                        else {
                            $msg[] = $fileerror;
                        }
                    }
                    $uploadFile = '';
                    $fileerror = $this->isFileUploadEmpty($uploadFile, 'apkfile');
                    if(self::FILE_NOT_UPLOAD!=$fileerror) {
                        if (!$fileerror) {
                            $data['apk'] = $this->uploadApkFile($uploadFile);
                            $data['url'] = $data['apk'];
                        }
                        else {
                            $msg[] = $fileerror;
                        }
                    }
                //}
                $validator = AppVersion::makeValidators($data);
                if(!$validator->fails()) {
                    $data['type'] = 1;//1.android, 2.ios
                    $newversion = new AppVersion();
                    $newversion->createVersion($data);
                    $msg[] = Lang::_('success');
                } else {
                    foreach($validator->messages()->all() as $msgx) {
                        $msg[] = $msgx;
                    }
                }
//            }
//            else {
//                $msg[] = "error version";
//            }
        }
        $messages = $msg;
        View::setVars(compact('messages', 'app', 'app_id'));
    }

    

    public function androideditAction() {
        $msg = "";
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get("user")->channel_id;
        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if (Request::isPost()) {
            $data = $this->request->getPost();
            $data['logo'] = $app->logo;
            //if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $fileerror = $this->isFileUploadEmpty($uploadFile, 'logo');
                if(self::FILE_NOT_UPLOAD!=$fileerror) {
                    if (''==$fileerror) {
                        $data['logo'] = $this->uploadFile($uploadFile);
                    }
                    else {
                        $msg[] = $fileerror;
                    }
                }
                $uploadFile = '';
                $fileerror = $this->isFileUploadEmpty($uploadFile, 'apkfile');
                if(self::FILE_NOT_UPLOAD!=$fileerror) {
                    if (''==$fileerror) {
                        $data['apk'] = $this->uploadApkFile($uploadFile);
                        $data['url'] = $data['apk'];
                    }
                    else {
                        $msg[] = $fileerror;
                    }
                }
            //}
            $validator = AppVersion::makeValidators($data);
            if(!$validator->fails()) {
                $versiondata->updateVersion($data);
                $key = D::memKey('apiGetAppVersionData', ['app_id' => $versiondata->app_id , 'type' => 1, 'version' => $versiondata->version]);
                RedisIO::delete($key);
                $msg[] = Lang::_('success');
                $app->updateApp($data);
            } else {
                    foreach($validator->messages()->all() as $msgx) {
                        $msg[] = $msgx;
                    }
            }
        }
        $messages = $msg;
        View::setVars(compact('messages', 'app', 'versiondata'));
    }

    public function iosaddAction() {
        $msg = "";
        $app_id = $this->request->get('app_id');

        $channel_id = Session::get("user")->channel_id;
        $app = AppList::getApp($app_id, $channel_id);
        if(!$app) redirect(Url::get('applist'));

        if (Request::isPost()) {
            $data = $this->request->getPost();
            $versiondata = AppVersion::getLastVersion($app_id, "ios");
            $lastversion = ($versiondata)?$versiondata->version:0;
//            if ($this->isNewVersion(trim($data['version']), trim($lastversion))) {
                $data['logo'] = $app->logo;
                if ($this->isFileTypeValid()) {
                    $uploadFile = '';
                    $fileerror = $this->isFileUploadEmpty($uploadFile, 'logo');
                    if(self::FILE_NOT_UPLOAD!=$fileerror) {
                        if (''==$fileerror) {
                            $data['logo'] = $this->uploadFile($uploadFile);
                        }
                        else {
                            $msg[] = $fileerror;
                        }
                    }
                }
                $validator = AppVersion::makeValidators($data);
                if(!$validator->fails()) {
                    $data['type'] = 2;//1.android, 2.ios
                    $newversion = new AppVersion();
                    $newversion->createVersion($data);
                    $msg[] = Lang::_('success');
                    $app->updateApp($data);
                } else {
                    foreach($validator->messages()->all() as $msgx) {
                        $msg[] = $msgx;
                    }
                }
//            }
//            else {
//                $msg[] = Lang::_('error version');
//            }
        }
        $messages = $msg;
        View::setVars(compact('messages', 'app', 'app_id'));
    }

    public function ioseditAction() {
        $msg = "";
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get("user")->channel_id;
        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if (Request::isPost()) {
            $data = $this->request->getPost();
            $data['logo'] = $app->logo;
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $fileerror = $this->isFileUploadEmpty($uploadFile, 'logo');
                if(self::FILE_NOT_UPLOAD!=$fileerror) {
                    if (''==$fileerror) {
                        $data['logo'] = $this->uploadFile($uploadFile);
                    }
                    else {
                        $msg[] = $fileerror;
                    }
                }
            }
            $validator = AppVersion::makeValidators($data);
            if(!$validator->fails()) {
                $versiondata->updateVersion($data);
                $key = D::memKey('apiGetAppVersionData', ['app_id' => $versiondata->app_id , 'type' => 2, 'version' => $versiondata->version]);
                RedisIO::delete($key);
                $msg[] = Lang::_('success');
                $app->updateApp($data);
            } else {
                    foreach($validator->messages()->all() as $msgx) {
                        $msg[] = $msgx;
                    }
            }
        }
        $messages = $msg;
        View::setVars(compact('messages', 'app', 'versiondata'));
    }

    private function isNewVersion($new, $old) {//$new > $old 返回真
        return ($this->verCompare(trim($new), trim($old)))?true:false;
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

    private function isFileTypeValid() {
        if (Request::hasFiles(true)) {
            $files = Request::getUploadedFiles(true);
            foreach ($files as $file) {
                $fileType = $file->getRealType();
                return in_array($fileType, ['application/jar','application/vnd.android.package-archive', 'application/zip', 'image/jpg', 'image/jpeg', 'image/gif', 'image/png']);
            }
        }
        return true;
    }

    private function isFileUploadEmpty(&$uploadFile, $name) {
        $uploadFile = '';
        if(Request::hasFiles()) {
            $files = Request::getUploadedFiles();
            foreach ($files as $file) {
                if ($file->getKey() == $name) {
                    $error = $file->getError();
                    if (!$error) {
                        $uploadFile = $file;
                    }
                    return ($error)?$error:'';
                }
            }
        }
        return true;
    }
    
    protected function uploadFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/apklogos');
        return $path;

    }
    
    protected function uploadApkFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/apk');
        return $path;
    }


    /**
     * 审核
     */
    public function lockAction() {
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get('user')->channel_id;

        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if($app->channel_id == $channel_id && $versiondata->changeStatus(AppVersion::CHECKED)) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 取消审核
     */
    public function unlockAction() {
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get('user')->channel_id;

        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if($app->channel_id == $channel_id && $versiondata->changeStatus(AppVersion::UNCHECKED)) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 版本发布
     */
    public function publishAction() {
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get('user')->channel_id;

        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if($app->channel_id == $channel_id && $versiondata->changePublish(AppVersion::PUBLISHED)) {
            if($versiondata->type==1) {
                $app->version_android = $versiondata->version;
            }
            else {
                $app->version_ios = $versiondata->version;
            }
            $app->save();
            $key = D::memKey('getAppBySku', ['sku' => $app->sku]);
            RedisIO::delete($key);
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 版本下架
     */
    public function unpublishAction() {
        $app_version_id = $this->request->get('id');
        $versiondata = AppVersion::getOne($app_version_id);
        $channel_id = Session::get('user')->channel_id;

        $app = AppList::getApp($versiondata->app_id, $channel_id);

        if($app->channel_id == $channel_id && $versiondata->changePublish(AppVersion::UNPUBLISHED)) {
            $versions = AppVersion::findAll($versiondata->app_id, $versiondata->type);            
            $max_version = 0;
            foreach ($versions->models as $v) {
                if($v->publish==1&&$this->verCompare($v->version, $max_version)) {
                    $max_version = $v->version;
                }
            }
            if($versiondata->type==1) {
                $app->version_android = $max_version;
            }
            else {
                $app->version_ios = $max_version;
            }
            $app->save();
            $key = D::memKey('getAppBySku', ['sku' => $app->sku]);
            RedisIO::delete($key);
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

}