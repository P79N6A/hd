<?php
/**
 *  任务模型
 *  model tasks
 *  @author     Haiquan Zhang
 *  @created    2015-1-28
 *  type 0:常规任务 1:需求单 2:测试单
 *  progress 0:新建(未分配) 1:分拆 2:未接收 3:拒绝 4:重新打开 5:进行中 6:审核驳回 7:提交审核 8:同意完成 9:已评分 10:已撤销
 *  subs_complete  0：分拆任务未完成 1：分拆任务已完成  2:无分拆任务
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Tasks extends Model {
    const PAGE_SIZE = 50;

    //任务进程常量
    const PROGRESS_NEW = 0;
    const PROGRESS_PARTITION = 1;
    const PROGRESS_NOT_RECEIVED = 2;
    const PROGRESS_REFUSE = 3;
    const PROGRESS_AFRESH = 4;
    const PROGRESS_WORKING = 5;
    const PROGRESS_DISMISSED = 6;
    const PROGRESS_PRE_COMPLETE = 7;

    const PROGRESS_COMPLETE = 98;
    const PROGRESS_COMPLETE_SCORE = 99;
    const PROGRESS_CANCLE = 100;
    
    const TASK_MODIFY = 20;

    const NOTIFY_TO_NOBODY = 0;
    const NOTIFY_TO_RECEIVER = 1;
    const NOTIFY_TO_CREATOR = 2;
    const NOTIFY_TO_ALL = 3;

    public $curr_user = 0; //当前操作用户
    public $curr_time = 0; //当前时间
    public $content = '';
    public $actual_start = 0;
    public $actual_end = 0;


    public function getSource() {
        return 'tasks';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'father', 'isolate', 'type', 'name', 'receiver', 'creator', 'progress', 'subs_complete', 'notify', 'start', 'end', 'status', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'father', 'isolate', 'type', 'name', 'receiver', 'creator', 'progress', 'subs_complete', 'notify', 'start', 'end', 'status', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'father', 'isolate', 'type', 'name', 'receiver', 'creator', 'progress', 'notify', 'start', 'end', 'status', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'father' => Column::TYPE_INTEGER,
                'isolate' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'receiver' => Column::TYPE_INTEGER,
                'creator' => Column::TYPE_INTEGER,
                'progress' => Column::TYPE_INTEGER,
                'subs_complete' => Column::TYPE_INTEGER,
                'notify' => Column::TYPE_INTEGER,
                'start' => Column::TYPE_INTEGER,
                'end' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'father', 'isolate', 'type', 'receiver', 'creator', 'progress', 'subs_complete', 'notify', 'start', 'end', 'status', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'father' => Column::BIND_PARAM_INT,
                'isolate' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'receiver' => Column::BIND_PARAM_INT,
                'creator' => Column::BIND_PARAM_INT,
                'progress' => Column::BIND_PARAM_INT,
                'subs_complete' => Column::BIND_PARAM_INT,
                'notify' => Column::BIND_PARAM_INT,
                'start' => Column::BIND_PARAM_INT,
                'end' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'father' => '0',
                'isolate' => '0',
                'type' => '0',
                'receiver' => '0',
                'creator' => '0',
                'progress' => '0',
                'subs_complete' => '2'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll(){
        $channel_id = Session::get('user')->channel_id;
        $query = Tasks::query()
                ->andWhere("Tasks.channel_id={$channel_id}");
        $data = $query->paginate(self::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function listTasks($task_id) {
        $channel_id = Session::get('user')->channel_id;
        $data = Tasks::query()
                ->andWhere("channel_id={$channel_id} and (id={$task_id} or isolate={$task_id})")
                ->execute()
                ->toArray();
        $return = [];
        if(!empty($data)){
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    public function getReceiver() {
        return Admin::getOne($this->receiver);
    }

    public function getCreator() {
        return Admin::getOne($this->creator);
    }

    public function getProgress() {
        $curr_user = Session::get('user')->id;
        $display = $this->progress;
        if($curr_user==$this->creator) {
            $display = $this->progress_display_array[$this->progress]['A'];
        }
        else if($curr_user==$this->receiver) {
            $display = $this->progress_display_array[$this->progress]['B'];
        }
        else {
            $display = $this->progress_display_array[$this->progress]['C'];
        }
        return $display;
    }

    public static function getOne($id) {
        $parameters = array();
        $parameters['conditions'] = "id=".$id;

        $task = Tasks::findFirst($parameters);
        $task->curr_user = Session::get('user')->id;
        $task->curr_time = time();
        $task_ext = $task->getContent();
        $task->content =$task_ext->content;
        $task->actual_start =$task_ext->actual_start;
        $task->actual_end =$task_ext->actual_end;
        return $task;
    }

    public function allowedOperation($new_progress) {
        $allowoperation = false;
        if($this->legalRequest($new_progress)) {
            if($this->isCompleteSubsTask($this->id)) {
                $allowoperation = true;
            }
        }
        return $allowoperation;
    }




    // 部门列表
    public static function listTask($channel_id) {
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->orderBy('created_at desc')
            ->execute()
            ->toArray();
        $return = [];
        if(!empty($data)){
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    /**
     * 获取任务详情
     */
    public function getContent() {
        return TaskExt::getOne($this->id);
    }
    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required',
            'father' => 'required',
            'type' => 'required',
            'notify' => 'required',            
            'start' => 'required',
            'end' => 'required',
            'receiver' => 'required',
            'progress' => 'required',
        ], [
            'name.required' => '请填写任务名',
            'father.required' => '请填任务父id',
            'type.required'=>'请填写任务类型',
            'notify.required'=>'请填写任务通知方式',
            'start.required' => '请填写开始时间',
            'end.required' => '请填写结束时间',
            'receiver' => '请填写任务接受人',
            'progress.required' => '请填写任务进程',
        ]);
    }
    public static function modifyValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required',           
            'start' => 'required',
            'end' => 'required',
        ], [
            'name.required' => '请填写任务名',
            'start.required' => '请填写开始时间',
            'end.required' => '请填写结束时间',
        ]);
    }

    public function createTask($data) {
        $this->assign($data);
        $this->creator = Session::get('user')->id;
        $this->channel_id = Session::get('user')->channel_id;
        $this->subs_complete = 2;
        $this->status = 1;
        $this->created_at = time();
        $this->step = 0;      
        if($this->save()) {
            $task_ext = new TaskExt();
            $data['task_id'] = $this->id;
            $data['step'] = $this->step;
            $task_ext->createTaskExt($data);
            $task_history = new TaskHistory();
            $data['author_id'] = $this->creator;
            $data['created_at'] = time();
            $task_history->createHistory($data);
        }
        return true;
    }

    public function updateTask($data) {
        $this->assign($data);
        if($this->save()) {
            $task_ext = $this->getContent();
            $task_ext->updateTaskExt($data);
        }
        return true;
    }

    public static function isCompleteSubsTask($father) {
        $channel_id = Session::get('user')->channel_id;
        $data = Tasks::find(array(
            'father = :father:',
            'bind' => array('father' => $father)
        ));
        $iscomplete = true;

        foreach ($data as $subtask) {
            if($subtask->progress==0) {
                $iscomplete = false;
                break;
            }
        }
        return $iscomplete;
    }

    /**
     * 核心逻辑，任务进程转换
     */
    public function setTaskProgress($params) {
        $this->curr_user = Session::get('user')->id;
        $new_progress = $params['new_progress'];
        $new_task_desc = $params['new_task_desc'];
        $attachids = $params['attachids'];
        
        $all_branch_complete = $this->isCompleteSubsTask();
        if(self::PROGRESS_CANCLE == $new_progress) {//撤销时，一并撤销全部子任务
            $all_branch_complete = true;
        }
        $set_progress_success = false;
        if($this->legalRequest($new_progress)) {//检查进程转换合法性
            if($all_branch_complete) {
                if($this->progress == self::PROGRESS_NOT_RECEIVED && self::PROGRESS_WORKING == $new_progress) {
                   $this->actual_start = time();/*设置任务接收时间为任务实际开始时间*/
                }
                else if(self::PROGRESS_PRE_COMPLETE == $new_progress) {
                    $this->actual_end = time();/*设置任务提交时间为任务实际完成时间*/
                    if($this->creator == $this->receiver) $new_progress = self::PROGRESS_COMPLETE;
                }

                if(self::PROGRESS_CANCLE == $new_progress) {//撤销时，一并撤销全部子任务
                    $this->cancleAllSubs();
                }

                if(self::PROGRESS_PRE_COMPLETE == $new_progress && "" != $attachids) {
                    TaskContents::setTaskAttachs($this, $attachids, 2);//type 表示完成附件
                }

                if(self::PROGRESS_COMPLETE <= $new_progress && $this->checkTaskBrothersOk()) {//检查兄弟节点都完成后，设置父任务的分拆完成状态
                    $parent = $this->getParentTask();
                    $parent->subs_complete = 2;
                    $parent->save();
                }
                $this->progress = $new_progress;
                $notify = 0;
                
                if($this->creator == $this->curr_user) {//设置需要通知创建者的任务
                    if(self::PROGRESS_REFUSE == $new_progress
                        || self::PROGRESS_PRE_COMPLETE == $new_progress
                        || self::PROGRESS_COMPLETE == $new_progress
                        ) {
                        $notify = self::NOTIFY_TO_CREATOR;
                    }
                }
                
                if($this->receiver == $this->curr_user) {//设置需要通知接收者的任务
                    if(self::PROGRESS_NOT_RECEIVED == $new_progress
                        || self::PROGRESS_AFRESH == $new_progress
                        || (self::PROGRESS_WORKING == $new_progress && $this->subs_complete == 2)//子任务都完成后通知主任务
                        ) {
                        $notify = (self::NOTIFY_TO_CREATOR==$notify)?self::NOTIFY_TO_RECEIVER:self::NOTIFY_TO_CREATOR;
                    }
                    if($this->creator == $this->receiver&&self::NOTIFY_TO_RECEIVER==$notify) $notify = self::NOTIFY_TO_NOBODY;
                }
                $this->notify = $notify;
                if($this->save()) {
                    $task_content = TaskProgress::getOne($this->id);
                    if($task_content) {
                        if($task_content->progress != $this->progress) {
                            $task_content = new TaskProgress();
                            $task_content->setProgress($this->id, $this->progress, $new_task_desc);
                        }
                        else if($new_task_desc) {
                            $task_content->setProgressContent($new_task_desc);
                        }
                    }
                    else {
                        $task_content = new TaskProgress();
                        $task_content->setProgress($this->id, $this->progress, $new_task_desc);
                    }
                    $set_progress_success = true;
                }
            }
        }
        return $set_progress_success;
    }

    /**
     * 检查状态转换的合法性
     */    
    public function legalRequest($new_progress) {
        $legalRequestTag = false;
        $legalRequestA = array(/* creator */
            self::PROGRESS_NEW=>array(self::PROGRESS_NOT_RECEIVED, self::PROGRESS_WORKING, self::PROGRESS_CANCLE, self::PROGRESS_PARTITION),
            self::PROGRESS_NOT_RECEIVED=>array(self::PROGRESS_CANCLE),
            self::PROGRESS_REFUSE=>array(self::PROGRESS_AFRESH, self::PROGRESS_CANCLE),
            self::PROGRESS_WORKING=>array(self::PROGRESS_CANCLE),
            self::PROGRESS_AFRESH=>array(self::PROGRESS_CANCLE),
            self::PROGRESS_CANCLE=>array(self::PROGRESS_CANCLE),
            self::PROGRESS_DISMISSED=>array(self::PROGRESS_CANCLE, self::PROGRESS_DISMISSED),
            self::PROGRESS_PRE_COMPLETE=>array(self::PROGRESS_DISMISSED, self::PROGRESS_COMPLETE, self::PROGRESS_COMPLETE_SCORE),
            self::PROGRESS_COMPLETE=>array(self::PROGRESS_COMPLETE_SCORE),
            );
        $legalRequestB = array(/* receiver */
            self::PROGRESS_NOT_RECEIVED=>array(self::PROGRESS_WORKING, self::PROGRESS_REFUSE),
            self::PROGRESS_WORKING=>array(self::PROGRESS_PRE_COMPLETE, self::PROGRESS_PARTITION),
            self::PROGRESS_PRE_COMPLETE=>array(self::PROGRESS_PRE_COMPLETE),
            self::PROGRESS_REFUSE=>array(self::PROGRESS_REFUSE),
            self::PROGRESS_AFRESH=>array(self::PROGRESS_WORKING, self::PROGRESS_REFUSE),
            self::PROGRESS_DISMISSED=>array(self::PROGRESS_WORKING, self::PROGRESS_PRE_COMPLETE),
            );
        if($this->creator == $this->curr_user) {/* !!!creator */
            if(in_array($new_progress, $legalRequestA[$this->progress])) {
                $legalRequestTag = true;
            }
        }
        if($this->receiver == $this->curr_user) {/* !!!receiver */
            if(in_array($new_progress, $legalRequestB[$this->progress])) {
                $legalRequestTag = true;
            }
        }
        return $legalRequestTag;
    }

    /**
     * 状态显示串数组，A：creator B: receiver C：other
     */
    private $progress_display_array = array(
        self::PROGRESS_NEW => array("A"=>"新建任务成功", "B"=>"新建任务成功", "C"=>"新建任务成功"),
        self::PROGRESS_NOT_RECEIVED => array("A"=>"任务未接收", "B"=>"等待接收", "C"=>"任务未接收"),
        self::PROGRESS_WORKING => array(
            "A"=>"任务已接收",
            "B"=>"正在处理中",
            "C"=>"进行中"),
        self::PROGRESS_REFUSE => array("A"=>"拒绝接收", "B"=>"已拒绝任务", "C"=>"拒绝接收"),
        self::PROGRESS_AFRESH => array("A"=>"任务已修改", "B"=>"任务已修改，等待接收", "C"=>"任务已修改"),
        self::PROGRESS_PRE_COMPLETE => array("A"=>"等待审核", "B"=>"完成任务，未审核", "C"=>"等待审核"),
        self::PROGRESS_DISMISSED => array("A"=>"已驳回审核", "B"=>"审核被驳回", "C"=>"审核被驳回"),
        self::PROGRESS_COMPLETE => array("A"=>"等待评分", "B"=>"完成任务，未评分", "C"=>"完成任务，未评分"),
        self::PROGRESS_COMPLETE_SCORE => array("A"=>"评分完成", "B"=>"完成任务，已评分", "C"=>"完成任务，已评分"),
        self::PROGRESS_CANCLE => array("A"=>"任务已撤销", "B"=>"任务已被撤销", "C"=>"任务已撤销"),
      );

    private function secondsToTimeString($seconds) {
        $day = floor($seconds/86400);
        $hour = floor(($seconds-$day*86400)/3600);
        $min = floor(($seconds-$day*86400-$hour*3600)/60);
        $day_dis_str = ($day)?$day."天 ":"";
        $hour_dis_str = ($hour)?$hour."小时 ":"";
        $min_dis_str = ($min)?$min."分 ":"";
        return $day_dis_str.$hour_dis_str.$min_dis_str;
    }
    
    public static function time2text($time) {
        $t = $time - time();
        $suffix = ($t < 0 ? '前' : '后');
        $t = abs($t);
        $h = 3600;
        $d = $h * 24;
        $w = $d * 7;
        $m = $d * 30;
        $y = $m * 12;
        if ($t < $h) $s = ceil ($t/60)."分钟";
        if ($t < $d && $t >= $h)  $s = intval($t/$h)."小时";;
        if ($t < $w && $t >= $d ) $s = intval($t/$d)."天";;
        if ($t < $m && $t >= $w) $s = intval ($t/$w)."周";
        if ($t < $y && $t >= $m) $s = intval($t/$m)."月";
        if ($t >= $y) $s = date('很久', $t);
        return $s . $suffix;
    }

}