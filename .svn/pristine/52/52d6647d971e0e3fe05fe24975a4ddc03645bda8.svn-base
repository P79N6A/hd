<?php
/**
 *  APP菜单管理
 *  controller menu
 *  @author     Zhangyichi
 *  @created    2015-12-21
 *
 *  @param id,channel_id,icon,name,type,sort,category_id,status
 */


class MenuController extends \BackendBaseController {
    
    public function indexAction() {
        $data = Menu::findAll();
        View::setVars(compact('data'));
    }

    public function createAction() {
        $messages = [];
        if(Request::isPost()){
            $input = Request::getPost();
            $validator=Menu::checkForm($input);
            if($validator->passes()){
                if($icon = $this->validateAndUpload($messages)) {
                    $input['icon'] = $icon;
                }
                if($input['icon']){
                    $input['channel_id'] = Session::get('user')->channel_id;
                    $menu = new Menu();
                    $input['sort'] = intval($input['sort']);
                    $return = $menu->createMenu($input);
                    if($return) {
                        $messages[] = Lang::_('success');
                    }else{
                        $messages[] = Lang::_('failed');
                    }
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function modifyAction(){
        $messages = [];
        $id = Request::getQuery('id','int');
        $menu = Menu::findOne($id);
        if(Request::isPost()){
            $input = Request::getPost();
            $validator=Menu::checkForm($input);
            if($input['menu_json']&&null==json_decode($input['menu_json'])) {
                $messages[] = Lang::_('wrong format');
            }
            else if($validator->passes()){
                if($icon = $this->validateAndUpload($messages)) {
                    $input['icon'] = $icon;
                }
                if($input['icon']){
                    $input['sort'] = intval($input['sort']);
                    $return = $menu->modifyMenu($input);
                    if($return) {
                        $messages[] = Lang::_('success');
                    }else{
                        $messages[] = Lang::_('failed');
                    }
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','menu'));
    }

    public function deleteAction() {
        $id = Request::get('id');
        $data = Menu::findOne($id);
        $channel_id = Session::get('user')->channel_id;
        if (!empty($data) && $data->channel_id==$channel_id ) {
            if($data->deleteMenu()) {
                echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            }
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }

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
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/icon');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldicon');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }
}