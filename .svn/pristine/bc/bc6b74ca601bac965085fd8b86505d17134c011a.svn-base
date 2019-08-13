<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SpecialExtras extends Model {

    public function getSource() {
        return 'special_extras';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'special_id', 'name', 'value',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['special_id', 'name', 'value',],
            MetaData::MODELS_NOT_NULL => ['id', 'special_id', 'name', 'value',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'special_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'value' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'special_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'special_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'value' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'name' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function createSpecialExtras($data) {
        return ($data->save()) ? true : false;
    }

    public static function findSpecialExtras($special_id) {
        $data = self::query()
            ->andWhere('special_id=' . $special_id)
            ->execute()->toarray();
        return $data;
    }

    public static function deleteAll($special_id) {
        return self::query()
            ->andWhere('special_id=' . $special_id)
            ->execute()->delete();
    }

    public static function tplBySpecial($special_id) {
        $extras = self::query()
            ->andWhere('special_id=' . $special_id)
            ->execute()
            ->toArray();
        return array_refine($extras, 'name', 'value');
    }
}