<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnPushErrorLog extends Model {

    public function getSource() {
        return 'cdn_push_error_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'cdn_id', 'content', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['cdn_id', 'content', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'cdn_id', 'content', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'cdn_id' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'create_time' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'cdn_id', 'create_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'cdn_id' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'create_time' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'cdn_id' => '0',
                'create_time' => '0'
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
    public function createCdnPushErrorLog($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? $this->id:false;
    }
}