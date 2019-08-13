<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TaskHistory extends Model {

    public function getSource() {
        return 'task_history';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'task_id', 'progress', 'step', 'author_id', 'created_at', 'message',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['task_id', 'progress', 'step', 'author_id', 'created_at', 'message',],
            MetaData::MODELS_NOT_NULL => ['id', 'task_id', 'progress', 'step', 'author_id', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'progress' => Column::TYPE_INTEGER,
                'step' => Column::TYPE_INTEGER,
                'author_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'message' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'task_id', 'progress', 'step', 'author_id', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'progress' => Column::BIND_PARAM_INT,
                'step' => Column::BIND_PARAM_INT,
                'author_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'message' => Column::BIND_PARAM_STR,
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



    public function createHistory($data) {
        $this->assign($data);
        return $this->save();
    }

}