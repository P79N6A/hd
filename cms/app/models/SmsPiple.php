<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SmsPiple extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'sms_piple';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'rest', 'alarm', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'rest', 'alarm', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'rest', 'alarm', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'rest' => Column::TYPE_INTEGER,
                'alarm' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'rest', 'alarm', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'rest' => Column::BIND_PARAM_INT,
                'alarm' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'rest' => '0',
                'alarm' => '1000'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        return SmsPiple::query()->paginate(SmsPiple::PAGE_SIZE, 'Pagination');
    }

    public static function getOne($id) {
        return SmsPiple::findFirst($id);
    }

    public static function getKvPipleList() {
        return SmsPiple::query()->where("status = 1")->columns("id,name")->execute()->toArray();
    }


}