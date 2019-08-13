<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminGroup extends Model {
    const PAGE_SIZE = 25;

    public function getSource() {
        return 'admin_group';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'indexname', 'name', 'desc',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['indexname', 'name', 'desc',],
            MetaData::MODELS_NOT_NULL => ['id', 'indexname', 'name', 'desc',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'indexname' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'desc' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'indexname' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'desc' => Column::BIND_PARAM_STR,
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
        return self::query()->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findOne($id) {
        return self::findFirst($id);
    }

    public static function getAll() {
        return self::find()->toArray();
    }


}