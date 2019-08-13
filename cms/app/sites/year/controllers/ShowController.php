<?php
/**
 *  节目管理
 *  controller stations
 *  @author     Zhangyichi
 *  @created    2015-9-16
 */


class ShowController extends \YearBaseController {
    
    public function indexAction() {
        $data = ShowYear::findAll();
        $vote_status = ShowYear::findStatus();
        $vote_switch = $vote_status?'vote_open':'vote_close';
        View::setVars(compact('data','vote_switch'));
    }

    public function createAction() {
        $messages = [];
        if (Request::isPost()) {
            $input = Request::getPost();
            $validator = ShowYear::makeValidator($input);
            if(!$validator->fails()) {
                $input['channel_logo'] = $this->validateAndUpload($messages);
                $input['vote']=0;
                $show = new ShowYear();
                $return = $show->createShow($input);
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
        $show = ShowYear::findOneById($id);
        //添加404页面
        if(!$show){
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $input = Request::getPost();
            $validator = ShowYear::makeValidator($input,$show->id);
            if(!$validator->fails()) {
                $channel_logo = $this->validateAndUpload($messages);
                $input['channel_logo'] = isset($channel_logo) ? $channel_logo : $input['channel_logo'];
                $return = $show->modifyShow($input);
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
        View::setVars(compact('messages','show'));
    }

    public function deleteAction() {
        $id = Request::get('id');
        $data = ShowYear::findOneById($id);
        if (!empty($data)) {
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('reward has ticket')]);
        }
        exit;
    }

    public function voteAction() {
        $vote = Request::getPost('vote');
        $vote = explode(',',$vote);
        $data=ShowYear::findAll()->models;
        if(count($data)==count($vote)){
            $i=0;
            foreach($data as $show){
                $show->vote+=$vote[$i];
                $show->extra+=$vote[$i];
                $i++;
                $show->update();
            }
            echo '200';
        }else{
            echo '404';
        }
        exit;
    }

    public function voteopenAction() {
        ShowYear::openVote();
        redirect('/show/index');
    }

    public function votecloseAction() {
        ShowYear::closeVote();
        redirect('/show/index');
    }

    public function changestatusAction() {
        $id = Request::getQuery('id');
        $show = ShowYear::findOneById($id);
        $show->status==1?$show->status=2:$show->status=1;
        $show->update();
        echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        exit;
    }

    //文件上传的方法
    /**
     * @param $messages
     * @return string
     */
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