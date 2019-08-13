<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SmsLog extends Model {

    public function getSource() {
        return 'sms_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'partition_by', 'terimaltype', 'mob', 'is_success', 'code', 'scence_id', 'piple_id', 'template_id', 'request_time', 'response_time', 'receipt_status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['terimaltype', 'mob', 'is_success', 'code', 'scence_id', 'piple_id', 'template_id', 'request_time', 'response_time', 'receipt_status',],
            MetaData::MODELS_NOT_NULL => ['id', 'partition_by', 'terimaltype', 'mob', 'is_success', 'code', 'scence_id', 'piple_id', 'template_id', 'request_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'terimaltype' => Column::TYPE_INTEGER,
                'mob' => Column::TYPE_VARCHAR,
                'is_success' => Column::TYPE_INTEGER,
                'code' => Column::TYPE_VARCHAR,
                'scence_id' => Column::TYPE_INTEGER,
                'piple_id' => Column::TYPE_INTEGER,
                'template_id' => Column::TYPE_INTEGER,
                'request_time' => Column::TYPE_INTEGER,
                'response_time' => Column::TYPE_INTEGER,
                'receipt_status' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'partition_by', 'terimaltype', 'is_success', 'scence_id', 'piple_id', 'template_id', 'request_time', 'response_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'terimaltype' => Column::BIND_PARAM_INT,
                'mob' => Column::BIND_PARAM_STR,
                'is_success' => Column::BIND_PARAM_INT,
                'code' => Column::BIND_PARAM_STR,
                'scence_id' => Column::BIND_PARAM_INT,
                'piple_id' => Column::BIND_PARAM_INT,
                'template_id' => Column::BIND_PARAM_INT,
                'request_time' => Column::BIND_PARAM_INT,
                'response_time' => Column::BIND_PARAM_INT,
                'receipt_status' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'terimaltype' => 'web'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}