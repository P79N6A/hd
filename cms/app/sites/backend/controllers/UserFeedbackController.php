<?php

class UserFeedbackController extends \BackendBaseController {

    public function indexAction() {
        $data = UserFeedback::getAll();
        View::setVars(compact('data'));
    }

    public function infoAction() {
        View::setMainView('layouts/add');
        $id = $this->request->getQuery('id');
        $userfeedback = UserFeedback::findFirst(array(
            'id=:id:',
            'bind' => array('id' => $id)
        ));
        View::setVars(compact('userfeedback'));
    }




    public function searchAction() {
        if(Request::getQuery('keyword','string')) {
            $search['keyword']=Request::getQuery('keyword','string');
        }else{
            $search=array('keyword'=>'');
        }
        $data = UserFeedback::search($search);
        View::pick('user_feedback/index');
        View::setVars(compact('data','search'));
    }


}