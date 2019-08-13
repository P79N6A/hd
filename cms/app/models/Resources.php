<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Resources extends Model {
    const PAGE_SIZE = 50;
    public function getSource() {
        return 'resources';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'title', 'code_version', 'cdn_version', 'path', 'updated_at', 'created_at','intro','sub_title'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['title', 'code_version', 'cdn_version', 'path', 'updated_at', 'created_at','intro','sub_title'],
            MetaData::MODELS_NOT_NULL => ['id', 'title', 'code_version', 'cdn_version', 'path', 'updated_at', 'created_at','intro','sub_title'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'sub_title'=> Column::TYPE_VARCHAR,
                'code_version' => Column::TYPE_VARCHAR,
                'cdn_version' => Column::TYPE_INTEGER,
                'path' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_VARCHAR,
                'updated_at' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'cdn_version', 'updated_at', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'sub_title' => Column::BIND_PARAM_STR,
                'code_version' => Column::BIND_PARAM_STR,
                'cdn_version' => Column::BIND_PARAM_INT,
                'path' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'updated_at' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'title' => '',
                'sub_title' => '',
                'code_version' => '',
                'cdn_version' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        return self::query()
            ->orderBy('id desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }
}