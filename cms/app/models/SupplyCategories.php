<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SupplyCategories extends Model {

    public function getSource() {
        return 'supply_categories';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'origin_id', 'origin_father_id', 'origin_name',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'origin_id', 'origin_father_id', 'origin_name',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'origin_id', 'origin_father_id', 'origin_name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'origin_id' => Column::TYPE_INTEGER,
                'origin_father_id' => Column::TYPE_INTEGER,
                'origin_name' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'origin_id', 'origin_father_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'origin_id' => Column::BIND_PARAM_INT,
                'origin_father_id' => Column::BIND_PARAM_INT,
                'origin_name' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'origin_father_id' => '0',
                'origin_name' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}