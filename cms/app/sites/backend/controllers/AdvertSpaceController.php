<?php
/**
 *  广告位
 *  model advertspace
 *  @created    2015-10-29
 *  
 */

class AdvertSpaceController extends \BackendBaseController {

    public function indexAction() {
        $data = AdvertSpace::findAll();
        $advert_space_type = AdvertSpace::typeList();
        View::setVars(compact('data','advert_space_type'));
    }
    
    /**
     * 添加广告位
     */
    
    public function addAction() {
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = AdvertSpace::makeValidator($data);
            if (!$validator->fails()) {
                $advertspace = new AdvertSpace();
                $messages = $advertspace->createSpace($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        $advert_space_type = AdvertSpace::typeList();
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'advert_space_type'));
    }

    /**
     * 编辑广告位
     */
    public function editAction($AdvertSpace_id=0) {
        $AdvertSpace_id = Request::get("id", "int");
        if(!$AdvertSpace_id) {
            redirect(Url::get("advert_space/add"));
        }
        $channel_id = Session::get('user')->channel_id;
        $AdvertSpace = AdvertSpace::getOne($AdvertSpace_id);
        if($AdvertSpace->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();
            $data['channel_id'] = $channel_id;         
            $validator = AdvertSpace::editValidator($data);
            if (!$validator->fails()) {
                $messages = $AdvertSpace->modifySpace($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        $advert_content_exit = false;
        $setting = json_decode($AdvertSpace->setting);
        $advert = Advert::getAdvert($AdvertSpace_id);
        if($advert) $advert_content_exit = true;
        $advert_space_type = AdvertSpace::typeList();
        View::setMainView('layouts/add');
        View::setVars(compact('messages','AdvertSpace','advert_space_type','setting','advert_content_exit'));
    }
    
    /**
     * 审核广告位
     */
     public function lockAction(){
        $id = Request::get("id");
        $msg = AdvertSpace::lock($id);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        echo $msg;
        exit;
    }

    /**
     * 广告位调用代码
     */
    public function spaceCallAction() {
        $id = Request::get("id", "int");
        if (!$id) {
            redirect(Url::get("advert_space/index"));
        }        
        $space = AdvertSpace::getOne($id);
        View::setMainView('layouts/add');
        View::setVars(compact('space'));
    }

    /**
     * 广告预览
     */
    public function spacePreviewAction() {
        $id = Request::get("id", "int");
        if (!$id) {
            redirect(Url::get("advert_space/index"));
        }        
        $space = AdvertSpace::getOne($id);
        $setting = '';
        if($space->type == 'code') {
            $advert = Advert::createJs($id);
            $couple = true;
            foreach($advert as $kx=>$x) {
                if('advert' === $kx) {
                    $couple = false;
                    break;
                }
            }
            if($couple) {
                $advert = $advert[0];
            }

            $setting = json_decode($advert['advert']->setting,true);
            header( 'Content-Type:text/javascript;charset=utf-8');
            echo $setting[0]['code'];
            exit;
        }
        else {
            View::setMainView('layouts/advert');
            View::setVars(compact('space','setting'));
        }        
    }

    /**
     * 更新js
     */
    public function createJsAction() {
        $data = Request::getPost();
        if (isset($data['spacecode']) && is_array($data['spacecode'])) {
            foreach ($data['spacecode'] as $k => $v) {
                if($v == AdvertSpace::ADVERT_SAPCE_TYPE_CODE) continue;
                $typename = AdvertSpace::getTypeCode($v);
                $advert = Advert::createJs($k);
                if(is_array($advert)) {
                    $couple = true;
                    foreach($advert as $kx=>$x) {
                        if('advert' === $kx) {
                            $couple = false;
                            break;
                        }
                    }
                    if($couple) {
                        $advert_second = $advert[1];
                        $advert = $advert[0];
                        $img_second = json_decode($advert_second->advert->setting, true);
                        $img = array_merge($img_second, json_decode($advert->advert->setting, true));
                    }
                    else {
                        $img = json_decode($advert['advert']->setting, true);
                    }

                    $padd = json_decode($advert['advertSpace']->setting,true);
                    $files = $k.".js" ;
                    $output = "space_js/".$files;
                    ob_start();
                    View::setMainView('layouts/advert');
                    View::partial('advert_space/partials/'.$typename, ['advert'=> $advert ,'img'=>$img,'padd'=>$padd]);
                    $string = ob_get_contents();
                    file_put_contents($output, $string);
                    ob_clean();
                    Oss::uploadFile("static/".Auth::user()->channel_id."/adverts/".$output, $output);
                }
            }
        }
        redirect(Url::get("advert_space/index"));
    }

    /**
     * 清空js
     */
    public function deleteAction() {
        $id = Request::get("id", "int");
        $data = AdvertSpace::findFirst($id);
        if (!empty($data) && $data->channel_id==Session::get("user")->channel_id ) {
            $output = $data->path;
            if(is_file($output)) {
                file_put_contents($output, '');
                Oss::uploadFile("static/".Auth::user()->channel_id."/adverts/".$output, $output);
            }
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }        
        exit;
    }

    public function searchAction(){
        $channel_id = Session::get('user')->channel_id;
        if($mess = Request::getPost()){
            $data = AdvertSpace::search($mess,$channel_id);
            $banner_type = AdvertSpace::typeList();
            View::pick('advert_space/index');
            View::setVars(compact('mess','data','banner_type'));
        }
    }
}
