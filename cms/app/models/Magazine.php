<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Magazine extends Model {

    public function getSource() {
        return 'magazine';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'image_id', 'hs_area', 'spotpool',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['image_id', 'hs_area', 'spotpool',],
            MetaData::MODELS_NOT_NULL => ['id', 'image_id', 'hs_area', 'spotpool',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'image_id' => Column::TYPE_INTEGER,
                'hs_area' => Column::TYPE_TEXT,
                'spotpool' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'image_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'image_id' => Column::BIND_PARAM_INT,
                'hs_area' => Column::BIND_PARAM_STR,
                'spotpool' => Column::BIND_PARAM_STR,
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