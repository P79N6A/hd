<?php
/**
 * Created by yantengwei.
 * User: Administrator
 * Date: 2015/9/22
 * Time: 15:37
 */
class ApppopupController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;


    public function indexAction() {
        $channel_id = Session::get("user")->channel_id;
        $data = AppList::getAppsByChannelId($channel_id);
        View::setVars(compact('data'));
    }



    public function popuplistAction() {
        $app_id = $this->request->get('id');
        $app_type = $this->request->get('type');

        $channel_id = Session::get("user")->channel_id;
        $app = AppList::getApp($app_id, $channel_id);
        $app_name = $app->name;
        if ($app) {
            View::setVars(compact('app','app_name','app_id','app_type'));
            $type = ($app_type=="android")?1:2;
            $data = AppPopup::findAll($app_id, $type);

            View::setVars(compact('data'));
        } else {
            $savesuccess = true;
            $msg = "APP不存在";
            View::setMainView('layouts/add');
            View::setVars(compact('savesuccess', 'msg'));
            View::pick('layouts/save');
        }
    }

    public function addAction() {
        $app_id = $this->request->get('app_id');
        $app_type = $this->request->get('app_type');
        $msg = "";
        $type = ($app_type=="android")?1:2;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'thumb');
                if (!$isFileEmpty) {
                    $data['thumb'] = $this->uploadFile($uploadFile);
                }
            }
            if(!isset($data['thumb'])){
                $msg[] = Lang::_('dont have picture for popup');
            }else {
                $data['app_id'] = $app_id;
                $data['type'] = $type;
                $validator = AppPopup::makeValidators($data);
                $check_unique = AppPopup::findOne($data);
                if ($check_unique) {
                    $msg[] = Lang::_('have the same popup');
                } else {
                    if ($data['datatype'] == AppPopup::$VideoUnitDetailTypeWeb || $data['datatype'] == AppPopup::$VideoUnitDetailTypeCredit) {
                        $data['popupdata'] = json_encode(array('url' => $data['url']));
                    }
                    if ($data['datatype'] == AppPopup::$VideoUnitDetailTypeLive || $data['datatype'] == AppPopup::$VideoUnitDetailTypeVOD || $data['datatype'] == AppPopup::$VideoUnitDetailTypeSubject || $data['datatype'] == AppPopup::$VideoUnitDetailTypePanoLive || $data['datatype'] == AppPopup::$VideoUnitDetailTypePanoVOD) {
                        $data['popupdata'] = json_encode(array('video_id' => $data['video_id']));
                    }
                    if (!$validator->fails()) {
                        $app = new AppPopup();
                        $app->createAppPopup($data);
                        $msg[] = Lang::_('success');
                    } else {
                        $msg[] = Lang::_('error');
                    }
                }
            }
        }
        $versiondata = AppVersion::findAll($app_id, $type);
        $messages = $msg;
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'versiondata', 'app_type'));
    }

    public function editAction() {
        $apppopup_id = $this->request->getQuery("id","int");
        $apppopup = AppPopup::findFirst($apppopup_id);
        $popupdata = json_decode($apppopup->popupdata,true);
        $app_id = $this->request->get('app_id');
        $app_type = $this->request->get('app_type');
        $type = ($app_type=="android")?1:2;
        $msg = "";
        if ($this->request->isPost()) {        
            $data = $this->request->getPost();
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'thumb');
                if (!$isFileEmpty) {
                    $data['thumb'] = $this->uploadFile($uploadFile);
                }
            }
            $data['app_id'] = $app_id;
            $data['type'] = $type;
            $validator = AppPopup::makeValidators($data);
            $check_unique = AppPopup::findOne($data);
            if($check_unique && $check_unique->id != $apppopup_id){
                $msg[] = Lang::_('have the same popup');
            }else {
                if ($data['datatype'] == AppPopup::$VideoUnitDetailTypeWeb || $data['datatype'] == AppPopup::$VideoUnitDetailTypeCredit) {
                    $data['popupdata'] = json_encode(array('url' => $data['url']));
                }
                if ($data['datatype'] == AppPopup::$VideoUnitDetailTypeLive || $data['datatype'] == AppPopup::$VideoUnitDetailTypeVOD || $data['datatype'] == AppPopup::$VideoUnitDetailTypeSubject || $data['datatype'] == AppPopup::$VideoUnitDetailTypePanoLive || $data['datatype'] == AppPopup::$VideoUnitDetailTypePanoVOD) {
                    $data['popupdata'] = json_encode(array('video_id' => $data['video_id']));
                }
                $popupdata = json_decode($data['popupdata'], true);
                if (!$validator->fails()) {
                    $apppopup->updateAppPopup($data);

                    $app = AppList::getApp($app_id,Session::get('user')->channel_id);
                    if($app){
                        $client_type = $apppopup->type==1?'android':'ios';
                        MemcacheIO::delete("app::popup::popup:".$app->sku.$apppopup->version.$client_type.$apppopup->poptype);
                    }
                    $msg[] = Lang::_('success');
                } else {
                    $msg[] = Lang::_('error');
                }
            }
        }
        $versiondata = AppVersion::findAll($app_id, $type);
        $messages = $msg;
        View::setMainView('layouts/add');
        View::setVars(compact('messages','versiondata','app_type', 'apppopup' ,'popupdata'));
    }

    /**
     * 删除APP
     */
    public function deleteAction() {
        $apppopup_id = $this->request->getQuery("id","int");
        $apppopup = AppPopup::findFirst($apppopup_id);

        if($apppopup->delete()) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
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

    /**
     * 审核
     */
    public function lockAction() {
        $apppopup_id = $this->request->getQuery("id","int");
        $apppopup = AppPopup::findFirst($apppopup_id);

        if($apppopup->changeStatus()) {
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
        $apppopup_id = $this->request->getQuery("id","int");
        $apppopup = AppPopup::findFirst($apppopup_id);

        if($apppopup->changeStatus()) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }


}