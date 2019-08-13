<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Sobeysupplies extends Model {
    static $PAGE_SIZE = 50;

    public function getSource() {
        return 'sobeysupplies';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'source_id', 'supply_category_id', 'origin_content', 'created_at', 'updated_at', 'status', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'source_id', 'supply_category_id', 'origin_content', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'source_id', 'supply_category_id', 'created_at', 'updated_at', 'status', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'source_id' => Column::TYPE_VARCHAR,
                'supply_category_id' => Column::TYPE_INTEGER,
                'origin_content' => Column::TYPE_TEXT,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'supply_category_id', 'created_at', 'updated_at', 'status', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'source_id' => Column::BIND_PARAM_STR,
                'supply_category_id' => Column::BIND_PARAM_INT,
                'origin_content' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
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
        $channel_id = Session::get('user')->channel_id;
        return self::query()
            ->andCondition('channel_id', $channel_id)
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }
}