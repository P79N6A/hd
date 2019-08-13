<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnInsertErrorLog extends Model {

    public function getSource() {
        return 'cdn_insert_error_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'error_type', 'task_id', 'msg', 'data', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['error_type', 'task_id', 'msg', 'data', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'error_type', 'data', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'error_type' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'msg' => Column::TYPE_VARCHAR,
                'data' => Column::TYPE_TEXT,
                'create_time' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'error_type', 'task_id', 'create_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'error_type' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'msg' => Column::BIND_PARAM_STR,
                'data' => Column::BIND_PARAM_STR,
                'create_time' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'error_type' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    //增加操作
    //主任务对象进行添加
    public function createCdnInsertErrorLog($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? $this->id:false;
    }
    
}