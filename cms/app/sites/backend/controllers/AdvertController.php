<?php
/**
 *  广告管理
 *  model advert
 *  @author     xy
 *  @created    2015-10-29
 *  
 */

class AdvertController extends \BackendBaseController {

    public function listAction($spaceid=0) {
        $spaceid = Request::get("spaceid", "int");
        $data = Advert::findAll($spaceid);
        $spacename = AdvertSpace::findFirst($spaceid)->name;
        View::setVars(compact('data','spaceid','spacename'));
    }
    
    /**
     * 添加广告
     */
    public function addAction() {
        $spaceid = Request::get("spaceid", "int");
        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();
            $path = $this->validateAndUpload($messages);
            foreach ($path as $key => $value) {
                $data['imageurl'][] = Oss::url($value);
            }
            $data['spaceid'] = $spaceid;
            $validator = Advert::makeValidator($data);
            if (!$validator->fails()) {
                $advert = new Advert();
                $messages = $advert->createAdvert($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        $space = AdvertSpace::getOne($spaceid);        
        View::setMainView('layouts/add');
        View::setVars(compact('messages','space'));
    }
    
    /**
     * 编辑广告
     */
    public function editAction($advert_id=0) {
        $advert_id = Request::get("id", "int");
        if(!$advert_id) {
            redirect(Url::get("advert/add"));
        }
        $advert = Advert::getOne($advert_id);
        $channel_id = Session::get('user')->channel_id;
        if($advert->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $messages = [];
            $data_up = Request::getPost();
            $path = $this->validateAndUpload($messages);
            if($path){
                foreach ($path as $key => $value) {
                    $data_up['imageurl'][] = Oss::url($value);
                }
            }
            if($data_up['enddatelimit']) $data_up['enddate'] = 0;
            $data_up['channel_id'] = $channel_id;
            $validator = Advert::editValidator($data_up);
            if (!$validator->fails()) {
                $messages = $advert->modifyAdvert($data_up);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        $space = AdvertSpace::getOne($advert->spaceid);
        $setting = json_decode($advert->setting,true);

        View::setMainView('layouts/add');
        View::setVars(compact('messages','advert','setting','space'));
    }

    /**
     * 审核广告
     */
     public function lockAction() {
        $id = Request::get("id");
        $channel_id = Session::get('user')->channel_id;
        $advert = Advert::getOne($id);
        if($advert->channel_id == $channel_id && $advert->changeStatus(Advert::CHECKED)) {
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
        $id = Request::get("id");
        $channel_id = Session::get('user')->channel_id;
        $advert = Advert::getOne($id);
        if($advert->channel_id == $channel_id && $advert->changeStatus(Advert::UNCHECKED)) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 广告排序
     */
    public function listOrderAction() {
        $spaceid = Request::get("spaceid", "int");
        $data = Request::getPost();       
        if (isset($data['listorder']) && is_array($data['listorder'])) {
            foreach ($data['listorder'] as $k => $v) {
                Advert::updateOrder($k,$v);
            }
        }
        redirect('/advert/list?spaceid='.$spaceid);
    }

    /**
     * 删除广告
     */
    public function deleteAction() {
        $id = Request::get('id');
        $data = Advert::findFirst($id);
        $channel_id = Session::get('user')->channel_id;
        if($data->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (!empty($data) && $data->channel_id==Session::get("user")->channel_id ) {
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }        
        exit;
    }

    protected function validateAndUpload(&$messages) {
        $path = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $files = Request::getUploadedFiles();
            
                foreach ($files as $value) {
                    $file = $value; 
                    $error = $file->getError();
                    if (!$error) {
                        $ext = $file->getExtension();
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                            $path[] = Oss::uniqueUpload($ext, $file->getTempName(), Auth::user()->channel_id.'/advert');
                        } else {
                            $messages[] = Lang::_('please upload valid ad image');
                        }
                    } elseif ($error == 4) {
                        $path = Request::getPost('thumb', null, '');
                        if (!$path) {
                            $messages[] = Lang::_('请选择要上传的广告图片');
                        }
                    } else {
                        $messages[] = Lang::_('unknown error');
                    }
               }   
            
            
        } else {
            $messages[] = Lang::_('请选择要上传的广告图片');
        }
       
        return $path;
    }

}
