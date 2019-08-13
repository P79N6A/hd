<?php
/**
 *  电视广播台管理
 *  controller stations
 *  @author     Zhangyichi
 *  @created    2015-9-16
 *
 *  @param id,is_system,channel_id,code,name,type,logo,channel_name,customer_name,epg_path
 */


class StationsController extends \BackendBaseController {
    
    public function indexAction() {
        $data = Stations::findAll();
        View::setVars(compact('data'));
    }
    
    public function createAction() {
        $this->initFormView();
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $inputs['channel_id'] = Session::get("user")->channel_id;
            $validator=Stations::makeValidator($inputs);
            if($validator->passes()){
                if($thumb = $this->validateAndUpload($messages)) {
                    $inputs['logo'] = $thumb;
                }
                $stations=new Stations();
                if($stations->createStations($inputs)) {
                    $this->deleteMemcache(Session::get("user")->channel_id,'tv');
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        $vmsData = StationsSet::findAll();
        View::setVars(compact('messages','vmsData'));
    }

    public function deleteAction() {
        $id=$this->request->getQuery("id","int");
        $return=Stations::deleteStations($id);
        if($return){
            $this->deleteMemcache(Session::get("user")->channel_id,'tv');
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function modifyAction() {
        $id = Request::getQuery('id','int');
        $data = Stations::getOne($id);
        $this->initFormView();        
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $validator=Stations::changeValidator($inputs);
            if($validator->passes()){
                if($logo = $this->validateAndUpload($messages)) {
                    $inputs['logo'] = $logo;
                } 
                if($data->modifyStations($inputs)) {
                    $this->deleteMemcache(Session::get("user")->channel_id,'tv');
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        $vmsData = StationsSet::findAll();
        View::setVars(compact('messages','data','vmsData'));
    }

    //文件上传的方法
    /**
     * @param $messages
     * @return string
     */
    protected function validateAndUpload(&$messages) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/logos');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldlogo');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

    protected function deleteMemcache($channel_id, $type){
        $sites = Site::findSiteByChannel($channel_id);
        $type = ($type == 'tv'? 1: 2);
        if ($sites) {
            $site_arr = $sites->toArray();
            foreach ($site_arr as $key => $value) {
                $key = D::memKey('apiGetStationsByType', ['site_id' => $value['id'], 'type' => $type]);
                MemcacheIO::delete($key);
            }
        }
    }
}