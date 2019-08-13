<?php
/**
 * Created by yantengwei.
 * User: cztv
 * Date: 2015/9/10
 * Time: 9:55
 */

class SiteController extends \BackendBaseController {

    public function indexAction() {
        $channel_id =Session::get('user')->channel_id;
        $data = Site::findAll($channel_id);
        $stations_data=Stations::getStationsByChannel($channel_id);
        $stations_data2 = array_refine($stations_data, 'id');


        View::setVars(compact('data', 'stations_data2'));
    }

    public function addAction() {
        $messages = [];
        if(request::isPost()) {
            $data = $this->request->getPost();

            if($logo = $this->validateAndUpload($messages)) {
                $data['logo']=$logo;
            }
            $data['stations'] = implode(',', $data['stations']);
            $validator = Site::makeValidators($data);
            if(!$validator->fails()) {
                $site = new Site();
                $site->channel_id = Session::get('user')->channel_id;
                $data['app_id']=md5(time());
                $data['app_secret']=md5(time()+1);
                if($site->create($data)) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            } else {
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function updAction() {
        $channel_id = Session::get('user')->channel_id;
        $messages = [];
        $site_id=$this->request->getQuery("id","int");
        $site = Site::getOne($site_id,$channel_id);
        if(request::isPost()) {
            $data = $this->request->getPost();
            if($logo = $this->validateAndUpload($messages)) {
                $data['logo']=$logo;
            }
            $stations = $data['stations'];
            $data['stations'] = implode(',', $stations);
            $validator = Site::makeValidators($data);
            if(!$validator->fails()) {
                $site = Site::getOne($data['id'], $channel_id);
                if($site->update($data)) {
                    $key = D::memKey('SiteInfo',['app_id'=>$site->app_id]);
                    MemcacheIO::set($key, false, 86400*30);
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            } else {
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }

        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','site'));
    }

    /*
     * 站点删除修改为修改状态，暂时不实现真正删除
     */
    public function delAction() {
        $site_id=$this->request->getQuery("id","int");
        $site = site::findFirst($site_id);
        $site->status=0;
        if($site->update()){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
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

}