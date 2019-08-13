<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TaskExt extends Model {

    public function getSource() {
        return 'task_ext';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'task_id', 'content', 'actual_start', 'actual_end',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['task_id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['content', 'actual_start', 'actual_end',],
            MetaData::MODELS_NOT_NULL => ['task_id', 'actual_start', 'actual_end',],
            MetaData::MODELS_DATA_TYPES => [
                'task_id' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'actual_start' => Column::TYPE_INTEGER,
                'actual_end' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'task_id', 'actual_start', 'actual_end',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'task_id' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'actual_start' => Column::BIND_PARAM_INT,
                'actual_end' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'actual_start' => '0',
                'actual_end' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    public static function getOne($id) {
        $parameters = array();
        $parameters['conditions'] = "task_id=".$id;
        $task_ext = TaskExt::findFirst($parameters);

        return $task_ext;
    }

    public function createTaskExt($data) {
        $this->assign($data);
        return $this->save();
    }

    public function updateTaskExt($data) {
        $this->assign($data);
        return $this->save();
    }



}