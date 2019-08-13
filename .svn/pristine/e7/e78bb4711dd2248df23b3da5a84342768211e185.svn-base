<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TaskProgress extends Model {

    /**
     * 状态显示
     */
    private $progress_display_array = array(
        Tasks::TASK_NEW => "新建任务成功",
        Tasks::TASK_NOT_RECEIVED => "任务未接收",
        Tasks::TASK_WORKING => "进行中",
        Tasks::TASK_REFUSE => "拒绝接收",
        Tasks::TASK_AFRESH => "任务已修改",
        Tasks::TASK_PRE_COMPLETE => "等待审核",
        Tasks::TASK_DISMISSED => "审核被驳回",
        Tasks::TASK_COMPLETE => "完成任务，未评分",
        Tasks::TASK_COMPLETE_SCORE => "完成任务，已评分",
        Tasks::TASK_CANCLE => "任务已撤销",
      );

    public function getSource() {
        return 'task_progress';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'task_id', 'progress', 'user_id', 'updated', 'content',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['task_id', 'progress', 'user_id', 'updated', 'content',],
            MetaData::MODELS_NOT_NULL => ['id', 'task_id', 'progress', 'user_id', 'updated',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'progress' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'updated' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'task_id', 'progress', 'user_id', 'updated',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'progress' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'updated' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getOne($taskid) {
        $parameters = array();
        $parameters['conditions'] = "task_id = ".$taskid;
        $parameters['order'] = "updated desc";
        return TaskProgress::findFirst($parameters);
    }

    /**
     * 新建进度
     */
    public function setProgress($task_id, $progress, $content="") {
        $this->task_id = $task_id;
        $this->progress = $progress;
        $this->user_id = Session::get('user')->id;
        $this->content = $content;
        $this->updated = time();
        $this->save();
    }

    /**
     * 修改进度
     */
    public function setProgressContent($content="") {
        if($content) {
            $this->content = $content;
            $this->updated = time();
            $this->save();
        }
    }

    public static function getProgressContent($taskid) {
        $parameters = array();
        $parameters['conditions'] = "(progress = ".Tasks::TASK_REFUSE." or progress = ".Tasks::TASK_DISMISSED." or progress = ".Tasks::TASK_PRE_COMPLETE.") and task_id = ".$taskid;
        $parameters['order'] = "updated desc";
        $task_progress = TaskProgress::findFirst($parameters);
        if($task_progress) {
            return array('content'=>$task_progress->content, 'progress'=>$task_progress->progress);
        }
        else {            
            return array('content'=>'empty', 'progress'=>0);
        }
    }

    public function getProgressDisplay() {
        return $this->progress_display_array[$this->progress];
    }

    public static function getProgressHistory($taskid) {
        $progress = TaskProgress::find(array('order'=>'updated asc ', 'conditions' => 'task_id = '.$taskid));
        return $progress;
    }

    /**
     * 获取用户
     */
    public function getName() {
        $admin = Admin::findFirst(array('conditions' => 'id='.$this->user_id));
        if($admin) {
            return $admin->name;
        }else{
            return '';
        }
    }

}