<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class NewsGroup extends Model {

    public function getSource() {
        return 'news_group';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'title', 'keyword', 'intro',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['title', 'keyword', 'intro',],
            MetaData::MODELS_NOT_NULL => ['id', 'title', 'keyword', 'intro',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'keyword' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'keyword' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
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