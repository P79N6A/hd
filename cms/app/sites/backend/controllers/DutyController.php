<?php
/**
 *  岗位管理
 *  model duty
 *  @author     Haiquan Zhang
 *  @created    2015-9-24
 *  
 */

class DutyController extends \BackendBaseController {


    public function indexAction() {
        $data = Duty::findAll();
        View::setVars(compact('data'));
    }
    
    /**
     * 添加岗位
     */
    public function addAction() {
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = Session::get('user')->channel_id;
            $validator = Duty::makeValidator($data);
            if (!$validator->fails()) {
                $duty = new Duty();
                $messages = $duty->createDuty($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }
    
    /**
     * 编辑岗位
     */
    public function editAction($dutyid=0) {
        $duty_id = Request::get("id", "int");
        if(!$duty_id) {
            redirect(Url::get("duty/add"));
        }
        $duty = Duty::getOne($duty_id);
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = Session::get('user')->channel_id;
            $validator = Duty::makeValidator($data);
            if (!$validator->fails()) {
                $messages = $duty->modifyDuty($data);
            }
            else {
                $messages = $validator->messages()->all();
            }
        }
        
        View::setMainView('layouts/add');
        View::setVars(compact('duty','messages'));
    }

    /**
     * 删除岗位
     *  @author     zhangyichi
     *  @created    2015-11-30
     * 由于数据库表中没有状态字段，暂时做直接删除处理
     * 删除前判断岗位是否还有占用
     */
    public function deleteAction(){
        $duty_id = Request::get('id','string');
        $duty = Duty::getOne($duty_id);
        $admin = Session::get('user');
        if($admin->channel_id==$duty->channel_id && $this->checkDutyStatus($duty_id)){
            if($duty->deleteDuty()) {
                $arr=array('code'=>200);
            }else {
                $arr=array('msg'=>Lang::_('failed'));
            }
        }else {
            $arr=array('msg'=>Lang::_('duty_failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function checkDutyStatus($duty_id){
        $data=AdminExt::findByDuty($duty_id);
        return empty($data);
    }

}
