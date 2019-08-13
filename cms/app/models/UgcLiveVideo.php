<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UgcLiveVideo extends Model {

    public function getSource() {
        return 'ugc_live_video';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'rate', 'data_id', 'stream_id', 'file_url',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['rate', 'data_id', 'stream_id', 'file_url',],
            MetaData::MODELS_NOT_NULL => ['id', 'stream_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'rate' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'stream_id' => Column::TYPE_INTEGER,
                'file_url' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'rate', 'data_id', 'stream_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'rate' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'stream_id' => Column::BIND_PARAM_INT,
                'file_url' => Column::BIND_PARAM_STR,
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

    public static function findAll() {
        return self::query()->order('id DESC')->paginate(25, 'Pagination');
    }

}