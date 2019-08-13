<?php

/*
 * @filename ModuleController.php 
 * @encoding UTF-8 
 * @author king <347498228@qq.com >
 * @datetime 2015-9-17  10:14:50
 * @version 1.0
  */

class ModuleController extends \BackendBaseController{
    
    public function indexAction(){
        $data = AuthModule::findAll();
        View::setVars(compact('data'));
    }
    
    /**
     * 添加模块
     */    
    public function addAction() {
        $channel_id = Session::get('user')->channel_id;
        $channels = Channel::find();
        View::setMainView('layouts/add');
        View::setVars(compact('channels', 'channel_id'));
    }
    
    /**
     * 编辑模块
     */
    public function editAction() {
        if(empty($moduleid))
            $moduleid = Request::getQuery("id", "int");
        if(!$moduleid) {
            redirect(Url::get("module/index"));
        }

        $module = AuthModule::getOne($moduleid);
        
        View::setMainView('layouts/add');
        View::setVars(compact('module'));
    }

    /**
     * 保存数据
     */
    public function saveAction() {
        $savesuccess = false;
        $messages = [];
        if (Request::isPost()) {
            $msg = [];
            $data = Request::getPost();
            $moduleid = Request::getPost("id", "int");
            $data['name'] =$data['module'];
            $data['css'] =$data['icon'];
            $validator = AuthModule::makeValidator($data);


            if (!$validator->fails()) {
                if($moduleid) {//modify
                    $module = AuthModule::getOne($moduleid);
                }
                else {//add
                    $module = new AuthModule();
                }
                $msg = $module->saveModule($data);
                $savesuccess = true;
            }
            else {
                $msg = $validator->messages()->all();
            }
        }
        $messages = $msg;

        View::setMainView('layouts/add');
        View::setVars(compact('savesuccess', 'messages'));
        View::pick('layouts/save');
    }
    
}