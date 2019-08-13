<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SupplyRsync extends Model {

    public function getSource() {
        return 'supply_rsync';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'channel_id', 'origin_type', 'origin_id', 'data_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['channel_id', 'origin_type', 'origin_id', 'data_id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['',],
            MetaData::MODELS_NOT_NULL => ['channel_id', 'origin_type', 'origin_id', 'data_id',],
            MetaData::MODELS_DATA_TYPES => [
                'channel_id' => Column::TYPE_INTEGER,
                'origin_type' => Column::TYPE_INTEGER,
                'origin_id' => Column::TYPE_VARCHAR,
                'data_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'channel_id', 'origin_type', 'data_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'channel_id' => Column::BIND_PARAM_INT,
                'origin_type' => Column::BIND_PARAM_INT,
                'origin_id' => Column::BIND_PARAM_STR,
                'data_id' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'origin_type' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}