<?php
/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2015/12/8
 * Time: 14:04
 */

class SpecialTemplatesController extends \BackendBaseController {

    public function indexAction() {
        $data=SpecialTemplates::findAll();
        View::setVars(compact('data'));
    }

    public function modifyAction(){
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $validator=SpecialTemplates::checkForm($inputs);
            if($validator->passes()){
                $special_templates=new SpecialTemplates();
                if($special_templates->modifyTemplates($inputs)) {
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
        $inputs=Request::getQuery('id','int');
        $data=SpecialTemplates::getOne($inputs);
        View::setMainView('layouts/add');
        View::setVars(compact('data','messages'));
    }

    public function createAction() {
        $messages = [];
        if (Request::isPost()) {
            $inputs=Request::getPost();
            $validator=SpecialTemplates::checkForm($inputs);
            if($validator->passes()){
                $special_templates=new SpecialTemplates();
                if($special_templates->createTemplates($inputs)) {
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
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function deleteAction() {
        $id=$this->request->getQuery("id","int");
        $return=SpecialTemplates::deleteTemplates($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }
}
