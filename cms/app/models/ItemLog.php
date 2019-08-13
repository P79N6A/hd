<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class ItemLog extends Model {

    public function getSource() {
        return 'item_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'create_day', 'channel_id', 'title', 'type', 'item_id', 'editor_id', 'total_hits', 'total_valid_hits', 'total_web_hits', 'total_app_hits', 'total_wap_hits',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['create_day', 'channel_id', 'title', 'type', 'item_id', 'editor_id', 'total_hits', 'total_valid_hits', 'total_web_hits', 'total_app_hits', 'total_wap_hits',],
            MetaData::MODELS_NOT_NULL => ['id', 'create_day', 'channel_id', 'item_id', 'editor_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'create_day' => Column::TYPE_DATETIME,
                'channel_id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'item_id' => Column::TYPE_INTEGER,
                'editor_id' => Column::TYPE_INTEGER,
                'total_hits' => Column::TYPE_INTEGER,
                'total_valid_hits' => Column::TYPE_INTEGER,
                'total_web_hits' => Column::TYPE_INTEGER,
                'total_app_hits' => Column::TYPE_INTEGER,
                'total_wap_hits' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'type', 'item_id', 'editor_id', 'total_hits', 'total_valid_hits', 'total_web_hits', 'total_app_hits', 'total_wap_hits',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'create_day' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'item_id' => Column::BIND_PARAM_INT,
                'editor_id' => Column::BIND_PARAM_INT,
                'total_hits' => Column::BIND_PARAM_INT,
                'total_valid_hits' => Column::BIND_PARAM_INT,
                'total_web_hits' => Column::BIND_PARAM_INT,
                'total_app_hits' => Column::BIND_PARAM_INT,
                'total_wap_hits' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'create_day' => 'CURRENT_TIMESTAMP',
                'channel_id' => '1',
                'type' => '1',
                'item_id' => '0',
                'editor_id' => '0',
                'total_hits' => '1',
                'total_valid_hits' => '1',
                'total_web_hits' => '1',
                'total_app_hits' => '1',
                'total_wap_hits' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}