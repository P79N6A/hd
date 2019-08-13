<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Sync extends Model {

    public function getSource() {
        return 'sync';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'old_table', 'new_table', 'type', 'old_id', 'new_id', 'old_url', 'new_url', 'domain', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'old_table', 'new_table', 'type', 'old_id', 'new_id', 'old_url', 'new_url', 'domain', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'old_table', 'new_table', 'type', 'old_id', 'new_id', 'old_url', 'new_url', 'domain', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'old_table' => Column::TYPE_VARCHAR,
                'new_table' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_VARCHAR,
                'old_id' => Column::TYPE_INTEGER,
                'new_id' => Column::TYPE_INTEGER,
                'old_url' => Column::TYPE_VARCHAR,
                'new_url' => Column::TYPE_VARCHAR,
                'domain' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'old_id', 'new_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'old_table' => Column::BIND_PARAM_STR,
                'new_table' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_STR,
                'old_id' => Column::BIND_PARAM_INT,
                'new_id' => Column::BIND_PARAM_INT,
                'old_url' => Column::BIND_PARAM_STR,
                'new_url' => Column::BIND_PARAM_STR,
                'domain' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'new_id' => '0',
                'old_url' => '',
                'new_url' => '',
                'status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}