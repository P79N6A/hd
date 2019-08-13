<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SmsTemplate extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'sms_template';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'create_at', 'content', 'param', 'status', 'channel_id'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'create_at', 'content', 'param', 'status', 'channel_id'],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'status', 'channel_id'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'create_at' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'param' => Column::TYPE_TEXT,
                'status' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'create_at', 'status', 'channel_id'
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'create_at' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'param' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'status' => '0',
                'channel_id' => '0',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll($channel_id = 0) {
        return self::query()->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findList($channel_id = 0) {
        if ($channel_id) {
            return self::query()->where("channel_id = :channel_id:")->addWhere("status = 1")->bind(array('channel_id' => $channel_id))->execute()->toArray();
        }
        return self::query()->where("status = 1")->execute()->toArray();
    }

    public static function getOne($id) {
        return self::findFirst($id);
    }

    public static function getkvList($channel_id = 0) {
        if ($channel_id) {
            return self::query()->columns(array('id', 'name'))->where('status = 1')->execute()->toArray();
        }
        return self::query()->columns(array('id', 'name'))->where('status = 1')->addWhere('channel_id = :channel_id:')
            ->bind(array('channel_id' => $channel_id))->execute()->toArray();
    }


}