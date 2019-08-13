<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TaskAttachsRelation extends Model {

    public function getSource() {
        return 'task_attachs_relation';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'task_id', 'attach_id', 'step',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['task_id', 'attach_id', 'step',],
            MetaData::MODELS_NOT_NULL => ['id', 'task_id', 'attach_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'attach_id' => Column::TYPE_INTEGER,
                'step' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'task_id', 'attach_id', 'step',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'attach_id' => Column::BIND_PARAM_INT,
                'step' => Column::BIND_PARAM_INT,
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

}