<?php

class PhoneListController extends \BackendBaseController {

    public function indexAction() {
        $data = PhoneList::getAll();
        View::setVars(compact('data'));
    }

    public function deleteAction() {
        $id=Request::getQuery();
        $return=PhoneList::deletePhoneList($id['id']);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function addAction(){
        $messages= [];
        if(Request::isPost()) {
            $data = Request::getPost();
            $validator=PhoneList::makeValidator($data);
            if(empty($data['phone_num'])||$validator->fails()){
                $messages[] = Lang::_('error');
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }else{
                $data['create_time'] = date('Y-n-d H:i:s');
                $data['audit_name'] = Session::get('user')->name;
                $phone_list = new PhoneList();
                $return = $phone_list->createPhoneList($data);
                if ($return) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('error');
                }
            }

        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }


    public function searchAction() {
        if(Request::getQuery('keyword','string')) {
            $search['keyword']=Request::getQuery('keyword','string');
        }else{
            $search=array('keyword'=>'');
        }
        $data = PhoneList::search($search);
        View::pick('phone_list/index');
        View::setVars(compact('data','search'));
    }

}