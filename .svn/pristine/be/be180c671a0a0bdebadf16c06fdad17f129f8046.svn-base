<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UgcLiveRoom extends Model {
    CONST PAGE_SIZE = 25;
    CONST RUN_STATUS_PLAY = 1;
    CONST RUN_STATUS_PAUSE = 2;
    CONST RUN_STATUS_STOP = 0;

    public function getSource() {
        return 'ugc_live_room';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'roomname', 'thumb', 'intro', 'tags', 'admin_id', 'create_at', 'online_num', 'showstatus', 'runstatus',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['roomname', 'channel_id', 'thumb', 'intro', 'tags', 'admin_id', 'create_at', 'online_num', 'showstatus', 'runstatus',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'roomname' => Column::TYPE_VARCHAR,
                'thumb' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_TEXT,
                'tags' => Column::TYPE_TEXT,
                'admin_id' => Column::TYPE_INTEGER,
                'create_at' => Column::TYPE_INTEGER,
                'online_num' => Column::TYPE_INTEGER,
                'showstatus' => Column::TYPE_INTEGER,
                'runstatus' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'admin_id', 'createat', 'showstatus', 'runstatus',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'roomname' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'tags' => Column::BIND_PARAM_STR,
                'admin_id' => Column::BIND_PARAM_INT,
                'create_at' => Column::BIND_PARAM_INT,
                'online_num' => Column::BIND_PARAM_INT,
                'showstatus' => Column::BIND_PARAM_INT,
                'runstatus' => Column::BIND_PARAM_INT,
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

    public static function getAll($channel_id) {
        return self::query()->where('channel_id = :channel_id:')->bind(array('channel_id' => $channel_id))->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findAll() {
        return self::query()->paginate(25, 'Pagination');
    }


}