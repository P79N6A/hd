<?php
/**
 *  内容管理管理
 *  model station
 *  @author     Haiquan Zhang
 *  @created    2015-9-11
 *  
 *  progress 0:新建(未分配) 1:未接收 2:拒绝 3:重新打开 4:进行中 5:审核驳回 6:提交审核 7:同意完成 8:已评分 9:已撤销
 *  subs_complete  0: 无分拆 1：未完成 2：全部完成
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TaskContents extends Model {

    public function getSource() {
        return 'task_contents';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'task_id', 'isolate_code', 'Lft', 'Rgt', 'title', 'intro', 'content', 'signature', 'encrypt', 'status', 'updated',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['task_id', 'isolate_code', 'Lft', 'Rgt', 'title', 'intro', 'content', 'signature', 'encrypt', 'status', 'updated',],
            MetaData::MODELS_NOT_NULL => ['id', 'task_id', 'isolate_code', 'Lft', 'Rgt', 'title', 'encrypt', 'status', 'updated',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'isolate_code' => Column::TYPE_INTEGER,
                'Lft' => Column::TYPE_INTEGER,
                'Rgt' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_VARCHAR,
                'content' => Column::TYPE_TEXT,
                'signature' => Column::TYPE_VARCHAR,
                'encrypt' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'updated' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'task_id', 'isolate_code', 'Lft', 'Rgt', 'encrypt', 'status', 'updated',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'isolate_code' => Column::BIND_PARAM_INT,
                'Lft' => Column::BIND_PARAM_INT,
                'Rgt' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'signature' => Column::BIND_PARAM_STR,
                'encrypt' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'updated' => Column::BIND_PARAM_INT,
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

    /**
     * 新建任务内容
     */
    public static function getOne($contentid) {
        return TaskContents::findFirst($contentid);
    }

    /**
     * 新建任务内容
     */
    public function addContent($data, $task) {
        $taskid = $task->id;
        $con_title = $task->title;
        $con_content = $data['task_content'];
        $attachids = $data['attachids'];
        $con_signature = md5($con_title.$con_content);/*检测文章改动*/
        $this->task_id = $taskid;
        $this->isolate_code = $taskid;
        $this->title = $con_title;
        $this->intro = $con_title;
        $this->content = $con_content;
        $this->signature = $con_signature;
        $this->encrypt = 0;
        $this->status = 1;
        $this->updated = time();
        $LftRgt = $this->getNewLftRgt(0);
        $this->Lft = $LftRgt[0];
        $this->Rgt = $LftRgt[1];
        if($this->save()) {
            $task->content_id = $this->id;
            $task->save();
            $this->setTaskAttachs($task, $attachids, 3);
        }
        return true;
    }

    /**
     * 新建任务内容
     */
    public function editContent($data, $task) {        
        $taskid = $task->id;
        $con_title = $task->title;
        $con_content = $data['task_content'];
        $attachids = $data['attachids'];
        $con_signature = md5($con_title.$con_content);/*检测文章改动*/
        if($task->progress >= 2) {//$task->progress >= 2 表示进度大于workding
            $newversiontime = -1;
        }
        else {
            $newversiontime = 7200;
        }
        if($this->content&&$con_signature != $this->signature
            &&(time() - $this->updated )> $newversiontime
          ) {/*超两小时且签名改变则新建版本*/
            $task_content = new TaskContents();
            $task_content->task_id = $taskid;
            $task_content->isolate_code = $taskid;
            $task_content->title = $con_title;
            $task_content->content = $con_content;
            $task_content->signature = $con_signature;
            $task_content->encrypt = 0;
            $task_content->status = 1;
            $task_content->updated = time();
            $LftRgt = $this->getNewLftRgt($this->id);
            $task_content->Lft = $LftRgt[0];
            $task_content->Rgt = $LftRgt[1];
            if($task_content->save()) {
                $task->content_id = $task_content->id;
                $task->save();
            }
        }
        else {            
            $this->title = $con_title;
            $this->content = $con_content;
            $this->signature = $con_signature;
            $this->updated = time();
            $this->save();
        }
        $this->setTaskAttachs($task, $attachids, 3);

    }

    public function getContentWhere($cond = "1=1", $orderby="") {
        $parameters = array();
        $parameters['conditions'] = $cond;
        if($orderby) {
           $parameters['order'] = $orderby;
        }
        return TaskContents::find($parameters);
    }

    public function checkContentId($contentid) {
        $contents = $this->getContentWhere("isolate_code=".$this->isolate_code." and id = ".$contentid);
        if(count($contents)<1) {
            echo "content not exist!";
            die();
            return -1;
        }            
        return $contents;
    }

    public function getNewLftRgt($contentid) {
        if(0==$contentid) {
            $Lft = 0;
            $Rgt = 1;
        }
        else {
            $contents = $this->checkContentId($contentid);
            $Lft = $contents[0]->Lft;
            $Rgt = $contents[0]->Rgt;
            $phql = "UPDATE task_contents SET Lft = Lft+2 WHERE Lft > $Rgt and isolate_code=".$this->isolate_code."";
            $this->modelsManager->executeQuery($phql);
            $phql = "UPDATE task_contents SET Rgt = Rgt+2 WHERE Rgt >= $Rgt and isolate_code=".$this->isolate_code."";
            $this->modelsManager->executeQuery($phql);
        }
        $Lft = $Rgt;
        $Rgt = $Lft+1;
        return array($Lft, $Rgt);
    }

    /**
     * 设置任务附件
     */
    public static function setTaskAttachs($task, $attachids, $type) {
        $attcount = $task->attachnum;
        foreach (explode(',', $attachids) as $attach_id) {
            if($attach_id) {
                $attcount ++;
                $task_attachs_relation = new TaskAttachsRelation();
                $task_attachs_relation->task_id = $task->id;
                $task_attachs_relation->attach_id = $attach_id;
                $task_attachs_relation->type = $type; /* 详情附件 3  完成附件 2 */
                $task_attachs_relation->save();
            }
        }
        if($attcount) {
            $task->attachnum = $attcount;
            $task->save();
        }
    }

    /**
     * 获取任务附件
     */
    static public function getAttachs($taskid=0) {
        $filepath_pic = 0;
        $relation_params = array();
        $relation_params['conditions'] = "task_id=".$taskid;
        $task_attachs_relations = TaskAttachsRelation::find($relation_params);        
        $filepath_pics = array();
        $attachids = array();
        foreach ($task_attachs_relations as $key => $value) {
            switch ($value->type) {
            case 2:
            case 3:
                $filepath_pic = AttachmentCommon::findFirstById($value->attach_id);
                if($filepath_pic&&!in_array($filepath_pic->id, $attachids)) {
                    array_push($attachids, $filepath_pic->id);
                    array_push($filepath_pics, $filepath_pic);
                }
                break;
            }
        }
        return array(
              'filepath_pics' => $filepath_pics,
              'attachids' => implode(",", $attachids),
            );
    }

}