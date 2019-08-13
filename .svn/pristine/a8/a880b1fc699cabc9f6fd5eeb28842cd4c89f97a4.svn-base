<?php
/**
 * Created by PhpStorm.
 * User: cyc
 * Date: 2015/11/12
 * Time: 9:56
 */
class NoticeController extends \BackendBaseController
{

    public function indexAction() {
        redirect('/message_center/index');
    }

//单条变更已读状态
    public function oneredAction(){
        $user = Session::get('user')->toarray();
        if (Request::getPost()) {
            $data = Request::getPost();
            $notice = Announ::findOne($data['notice_id']);
            echo json_encode($notice);
        }
        $status = AnnounStatus::findOne($user['id'],$data['notice_id']);
       if($status[0]['status']==1) {
           $ann = Announ::findOneObj($data['notice_id']);
           $ann->rednum++;
           $ann->save();
       }
        $nots=AnnounStatus::findOneO($data['notice_id'],$user['id']);
        $data['status'] = 2;//已读
        $result = $nots->modifyNotice($data);
        exit;
    }

//全部变更已读
    public function allredAction(){
        $user = Session::get('user')->toarray();
        $nots = AnnounStatus::modifyStatusAll($user['id']);
        exit;
    }

    //清空已读
    public function clearAction(){
        $user = Session::get('user')->toarray();
        $notice = AnnounStatus::findAll($user['id']);
        foreach($notice as $value){
            $value->status=2;
            if($value->save()==false){
                echo 'error';
                exit;
            }
        }
    }

//发送消息
    public function sendmessAction(){
        $message = '';
       if(Request::getPost()){
           $data_post = Request::getPost();
       }
        $user = Session::get('user')->toarray();
        $data = Announ::findOne($data_post['notice_id']);
        $message[] = array('r_time'=>time(),
                         'r_mess'=>$data_post['message'],
                         'r_name'=>$user['name'],
                         'img'=>$user['avatar'],
                         'id'=>$user['id']);
        if($data[0]['return']){
            $arr = json_decode($data[0]['return'],true);
            if(count($arr)>=20){unset($arr[0]);}
            $arr= array_merge($arr,$message);
        }else{
            $arr = $message;
        }
        $newcon = json_encode($arr);
        $data['return']= $newcon;
        $not =Announ::findOneObj($data_post['notice_id']);
        if($not->modifyNotice($data)){
            echo 200;exit;
        }else{
        echo 303;exit;
        }
    }

    //新增公告
    public function addAction(){
        $data = '';
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $user = Session::get('user')->toarray();
            $data['time'] = date(time());
            $data['user']=$user['id'];
            $data['name']= $user['name'];
            $data['rednum'] = 0;
            $data['pic'] = $user['avatar'];
            $validator = Announ::makeValidator($data);
            if (!$validator->fails()) {
                $notice= new Announ();
                $result = $notice->createNotice($data);
                if ($result) {
                    $messages[] = Lang::_('success');
                    $this->send($result);
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

//发送公告
    public function send($id){
        $user = Session::get('user')->toarray();
        $admin = Admin::findAllByChannel($user['channel_id']);
        foreach($admin as $value){
            $ns =new AnnounStatus();
            $data=array('notice_id'=>$id,
                        'admin_id'=>$value['id'],
                        'status'=>1
                    );
            if(!$ns->addNotice($data)){
                echo 'error';
                exit;
            };
        }
    }

}