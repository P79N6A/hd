<?php
/**
 *  奖品管理
 *  controller stations
 *  @author     Zhangyichi
 *  @created    2015-9-16
 */


class RewardController extends \YearBaseController {
    
    public function indexAction() {
        $data=RewardYear::findAll();
        $reward_sum=0;
        $reward_residue=0;
        $reward_num=0;
        if(isset($data->models) && !empty($models = $data->models)){
            foreach($models as $reward){
                $reward_num++;
                $reward_sum+=$reward->sum;
                $reward_residue+=$reward->residue;
            }
        }
        View::setVars(compact('data','reward_num','reward_sum','reward_residue'));
    }

    public function ticketlistAction() {
        $id = Request::getQuery('id','int');
        if(!$id){
            $this->accessDenied();
        }
        $data = TicketYear::findList($id);
        View::setVars(compact('data'));
    }

    public function createAction() {
        $messages = [];
        if (Request::isPost()) {
            $input=Request::getPost();
            $validator = RewardYear::makeValidator($input);
            if(!$validator->fails()) {
                $input['channel_logo'] = $this->validateAndUpload($messages,'0');
                $input['thumb'] = $this->validateAndUpload($messages,'1');
                $input['residue'] = $input['sum'];
                $reward = new RewardYear();
                $return = $reward->createReward($input);
                if($return) {
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

    public function modifyAction() {
        $messages = [];
        $id = Request::get("id", "int");
        $reward = RewardYear::findOneById($id);
        //添加404页面
        if(!$reward){
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $input = Request::getPost();
            $validator = RewardYear::makeValidator($input,$reward->id);
            if(!$validator->fails()) {
                $channel_logo = $this->validateAndUpload($messages,'0');
                $input['channel_logo'] = isset($channel_logo) ? $channel_logo : $input['channel_logo'];
                $thumb = $this->validateAndUpload($messages,'1');
                $input['thumb'] = isset($thumb) ? $thumb : $input['thumb'];
                $input['residue'] = $input['sum'];
                $return = $reward->modifyReward($input);
                if($return) {
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
        View::setVars(compact('messages','reward'));
    }

    public function deleteAction() {
        $id = Request::get('id');
        $data = RewardYear::findOneById($id);
        $return = TicketYear::findAllByRewardId($id);
        if (!empty($data)&&empty($return->toarray())) {
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('reward has ticket')]);
        }
        exit;
    }


    //文件上传的方法
    protected function validateAndUpload(&$messages,$i) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[$i];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {
//                    $name = md5(time());
//                    $return = move_uploaded_file($file->getTempName(),  BASE_PATH.'public/assets/admin/layout/upload/logo/'.$name.'.jpg');
//                    if($return){
//                        $path = '/assets/admin/layout/upload/logo/'.$name.'.jpg';
//                    }
                    $path = Oss::uniqueUpload($ext, $file->getTempName(), Auth::user()->channel_id.'/year');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldlogo');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

}