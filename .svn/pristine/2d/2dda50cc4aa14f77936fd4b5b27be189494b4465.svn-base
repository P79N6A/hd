<?php
/**
 *  角色管理
 *  model authrole
 *  @author     Haiquan Zhang
 *  @created    2015-9-24
 *  
 */


class RoleController extends \BackendBaseController{
    
    public function indexAction() {
        $data = AuthRole::findAll();
        View::setVars(compact('data'));
    }
    
    /**
     * 添加角色
     */    
    public function addAction() {
        $messages = [];
        $authElement = [];
        $model = new AuthRole();
        if (Request::isPost()) {
            $data = Request::getPost();
            $element_id_hidden = array();
            if (!empty($data['element_id'])) {
                foreach($data['element_id'] as $eid) {
                    foreach(AuthElement::getDependenceElement($eid) as $e) {
                        array_push($element_id_hidden, $e['id']);
                    }
                }
                foreach(AuthElement::getAuthHiddenElement() as $e) {
                    array_push($element_id_hidden, $e['id']);
                }
                $data['element_id'] = array_merge ($data['element_id'], $element_id_hidden);
                $data['element'] = implode(',', $data['element_id']);
            }
            else {
                $data['element'] = "";
            }
            $validator = AuthRole::makeValidator($data);
            if (!$validator->fails()) {
                if ($model->save([
                    'channel_id'=>Session::get("user")->channel_id,
                    'name'=>  trim($data['name']),
                    'element'=>$data['element']
                        ])) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setVars(compact('model','messages', 'authElement'));
        View::setMainView('layouts/add');
    }
    
    /**
     * 编辑角色
     */
    public function editAction() {
        $id = Request::getQuery("id", "int");
        $model = AuthRole::findFirst($id);
        if (!$model) {
            abort(404);
        }
        if (Request::isPost()) {
            $data = Request::getPost();

            $element_id_hidden = array();
            if (!empty($data['element_id'])) {
                foreach($data['element_id'] as $eid) {
                    foreach(AuthElement::getDependenceElement($eid) as $e) {
                        array_push($element_id_hidden, $e['id']);
                    }
                }
                foreach(AuthElement::getAuthHiddenElement() as $e) {
                    array_push($element_id_hidden, $e['id']);
                }
                $data['element_id'] = array_merge ($data['element_id'], $element_id_hidden);

                $data['element'] = implode(',', $data['element_id']);
            }

            $validator = AuthRole::makeValidator($data);
            if (!$validator->fails()) {
                if ($model->update([
                    'name'=>  trim($data['name']),
                    'element'=>$data['element']
                        ])) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $authElement = AuthRole::getRoleElement($id);
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages', 'authElement'));
    }

}
