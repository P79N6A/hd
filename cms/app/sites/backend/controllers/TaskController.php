<?php
/**
 *  任务管理
 *  model tasks
 *  @author     Haiquan Zhang
 *  @created    2015-9-11
 *  
 *  progress 0:新建(未分配) 1:未接收 2:拒绝 3:重新打开 4:进行中 5:审核驳回 6:提交审核 7:同意完成 8:已评分 9:已撤销
 *  subs_complete  0: 无分拆 1：未完成 2：全部完成
 */

class TaskController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;

    public $ignore = [
        'attachupload'
    ];


    public $curr_user; //当前登录用户

    public function initialize() {
        parent::initialize();
        $adminsession = Session::get("user");
        $this->curr_user = $adminsession->id;
    }

    public function indexAction() {
        $data = Tasks::findAll();
        //$tree = TaskTree::getTree(1);
        //dd($tree);
        View::setVars(compact('data', 'other'));
    }

    /**
     * 任务详情查看
     */
    public function detailAction() {
        $id = (int)Request::getQuery("id", "int");
        if($id) {
            $task = Tasks::getOne($id);
            $tree = TaskTree::getTree($id);
            
            View::setVars(compact('task','tree'));
        }
        else {
            redirect(Url::get("task/index"));
        }
    }
    
    /**
     * 新建任务
     */
    public function newAction() {
        $msg = "";
        if(Request::isPost()) {
            $data = $this->request->getPost();
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $fileerror = $this->isFileUploadEmpty($uploadFile, 'attachfile');
                if(self::FILE_NOT_UPLOAD != $fileerror) {
                    if (!$fileerror) {
                        $data['attachfile'] = $this->uploadFile($uploadFile);
                    }
                    else {
                        $msg[] = $fileerror;
                    }
                }
            }
            $data['start'] = strtotime($data['start']);
            $data['end'] = strtotime($data['end']);
            $data['channel_id'] = Auth::user()->channel_id;
            $data['type'] = 0;
            $data['notify'] = ($data['receiver'])?Tasks::NOTIFY_TO_RECEIVER:Tasks::NOTIFY_TO_NOBODY;
            $data['progress'] = ($data['receiver'])?Tasks::PROGRESS_NOT_RECEIVED:Tasks::PROGRESS_NEW;
            $validator = Tasks::makeValidators($data);
            if(!$validator->fails()) {
                $task = new Tasks();
                $task->createTask($data);
                $msg[] = Lang::_('success');
            } else {
                    foreach($validator->messages()->all() as $msgx) {
                        $msg[] = $msgx;
                    }
            }
        }
        $admins = Admin::findAll();
        $messages = $msg;
        View::setMainView('layouts/add');
        View::setVars(compact('admins','messages'));
    }    
    
    /**
     * 任务修改
     */
    public function editAction() {
        $id = (int)Request::getQuery("id", "int");
        if(!$id) {
            redirect(Url::get("task/new"));
        }
        $task = Tasks::getOne($id);
        if(!$task->allowedOperation(Tasks::TASK_MODIFY)) {
            //redirect(Url::get("task/detail?id=".$id));
        }

        if(Request::isPost()) {
            $data = $this->request->getPost();
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $fileerror = $this->isFileUploadEmpty($uploadFile, 'attachfile');
                if(self::FILE_NOT_UPLOAD != $fileerror) {
                    if (!$fileerror) {
                        $data['attachfile'] = $this->uploadFile($uploadFile);
                    }
                    else {
                        $msg[] = $fileerror;
                    }
                }
            }
            $data['start'] = strtotime($data['start']);
            $data['end'] = strtotime($data['end']);
            $validator = Tasks::modifyValidators($data);
            if(!$validator->fails()) {
                $task->updateTask($data);
                $task = Tasks::getOne($task->id);
                $msg[] = Lang::_('success');
            } else {
                    foreach($validator->messages()->all() as $msgx) {
                        $msg[] = $msgx;
                    }
            }
        }
        $admins = Admin::findAll();
        View::setMainView('layouts/add');
        View::setVars(compact('task', 'admins'));
    }

    /**
     * 任务状态转换
     */
    public function changeTaskAction() {
        $taskid = Request::getPost("taskid", "int");
        $new_progress = Request::getPost("new_progress", "int");
        if($taskid <=0 ) {
            echo "error1";
        }
        $taskmodel = Tasks::getOne($taskid);
        $new_task_desc = "";
        $attachids = "";
        switch($new_progress) {
        case self::TASK_WORKING:/* 2 */ break;
        case self::TASK_REFUSE:/* 3 */
            $new_task_desc = ""; //refuse_reason
            break;
        case self::TASK_AFRESH:/* 4 */ break;
        case self::TASK_PRE_COMPLETE:/* 5 */
            $new_task_desc = ""; //complete_desc
            $attachids = "";
            break;
        case self::TASK_DISMISSED:/* 6 */
            $new_task_desc = ""; //dismissed_reason
            break;
        case self::TASK_COMPLETE:/* 7 */ break;
        case self::TASK_COMPLETE_SCORE:/* 8 */
            $score_quality = "";
            $score_speed = "";
            $score_attitude = "";
            $score = array(
                self::SCORE_QUALITY => ($score_quality)?$score_quality:0,
                self::SCORE_SPEED => ($score_speed)?$score_speed:0,
                self::SCORE_ATTITUDE => ($score_attitude)?$score_attitude:0,
                );
            if(self::TASK_COMPLETE_SCORE != $taskmodel->progress && $score[self::SCORE_QUALITY]&&$score[self::SCORE_SPEED]&&$score[self::SCORE_ATTITUDE]) {
                $taskmodel->score = json_encode($score);
                $taskmodel->save();
            }
            break;
        case self::TASK_CANCLE:/* 9 */ break;
        case self::TASK_WORKING:/* 2 */ break;
        case self::TASK_WORKING:/* 2 */ break;
        }
        $result = "error2";
        if($taskmodel->setTaskStatus(array(
              'taskid' => $taskid,
              'new_progress' => $new_progress,
              'new_task_desc' => $new_task_desc,
              'attachids' => $attachids
            ))) {
            $result = "ok";
        }
        echo $result;
        exit;
    }

    public function receiversAction() {
        $data_admins = array(
            array('id'=>1,'name'=>'test user1'),
            array('id'=>2,'name'=>'test user2'),
            array('id'=>3,'name'=>'test user3'),
            array('id'=>4,'name'=>'test user4'),
          );
        echo json_encode($data_admins);
        exit;
    }

    

    private function isFileTypeValid() {
        if (Request::hasFiles(true)) {
            $files = Request::getUploadedFiles(true);
            foreach ($files as $file) {
                $fileType = $file->getRealType();
                return in_array($fileType, ['application/jar','application/vnd.android.package-archive', 'application/zip', 'image/jpg', 'image/jpeg', 'image/gif', 'image/png']);
            }
        }
        return true;
    }

    private function isFileUploadEmpty(&$uploadFile, $name) {
        $uploadFile = '';
        if(Request::hasFiles()) {
            $files = Request::getUploadedFiles();
            foreach ($files as $file) {
                if ($file->getKey() == $name) {
                    $error = $file->getError();
                    if (!$error) {
                        $uploadFile = $file;
                    }
                    return ($error)?$error:'';
                }
            }
        }
        return true;
    }
    
    protected function uploadFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/task');
        return $path;

    }

    public function historyAction($taskid=0) {
        if(empty($taskid))
            $taskid = Request::getQuery("taskid", "int");
        if(!$taskid) {
            redirect(Url::get("task/index"));
        }
        $progress = TaskProgress::getProgressHistory($taskid);
        View::setMainView('layouts/add');
        View::setVars(compact('progress', 'taskid'));
    }
    //创建，分拆任务时发送消息,
    public function sendMessageAction() {
        $message = '方法法兰克福的了快了地方该地块浪费了';
        $task = Tasks::findFirst('id=370');
        $messageTask = new MessageTask;
        $messageTask->sendMessage($task,$message);
    }
}