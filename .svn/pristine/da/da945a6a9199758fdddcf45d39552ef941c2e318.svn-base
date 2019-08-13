<?php
/**
 * Created by yantengwei.
 * User: Administrator
 * Date: 2015/9/24
 * Time: 14:20
 */

class HotwordsController extends \BackendBaseController {
    public function indexAction() {
        $data = Hotwords::findorder();
        View::setVars(compact('data'));
    }

    public function addAction() {
        $message = "";
        if(request::isPost()) {
            $data = $this->request->getPost();
            $validator = Hotwords::makeValidators($data);
            if(!$validator->fails()) {
                $hotword = new Hotwords();
                session_start();
                $hotword->user_id = Session::get('user')->id;
                $hotword->user_name = Session::get('user')->name;
                $hotword->createtime = time();
                $hotword->create($data);
            } else {
                $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('message'));
    }

    public function updAction($hotword_id) {
        $message = "";
        $hotword = Hotwords::findFirst($hotword_id);
        View::setVars(compact('hotword'));
        if($this->request->isPost()) {
            $data = $this->request->getPost();
            $validator = Hotwords::makeValidators($data);
            if(!$validator->fails()) {
                session_start();
                $hotword->user_id = Session::get('user')->id;
                $hotword->user_name = Session::get('user')->name;
                $hotword->createtime = time();
                $hotword->update($data);
            } else {
                $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('message'));
    }

    public function delAction($hotword_id) {
        $message = "";
        $hotword = Hotwords::findfirst($hotword_id);
        if($hotword->delete()){
            redirect('/hotwords/index');
        } else {
            $message = Lang::_('delete faild');
        }
        View::setVars(compact('message'));
    }

    public function getOrder() {
        $hotwords = Hotwords::find(array( "order" => "weight DESC"));
        $words = array();
        foreach ($hotwords as $hotword) {
            array_push($words,$hotword->name);
        }
        return $words;
    }
}