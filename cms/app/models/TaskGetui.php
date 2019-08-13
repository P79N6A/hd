<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TaskGetui extends Model {
    const PAGE_SIZE = 25;

    public function getSource() {
        return 'task_getui';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'created_at', 'start_time', 'channel_id', 'admin_id', 'client_type', 'getui_range', 'getui_type', 'mess_body', 'mess_title', 'mess_id', 'mess_url', 'ret_status', 'ret_contentId','memo',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['start_time', 'channel_id', 'admin_id', 'client_type', 'getui_range', 'getui_type', 'mess_body', 'mess_title', 'mess_id', 'mess_url', 'ret_status', 'ret_contentId','memo','created_at'],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'start_time' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'client_type' => Column::TYPE_VARCHAR,
                'getui_range' => Column::TYPE_INTEGER,
                'getui_type' => Column::TYPE_INTEGER,
                'mess_body' => Column::TYPE_VARCHAR,
                'mess_title' => Column::TYPE_VARCHAR,
                'mess_id' => Column::TYPE_INTEGER,
                'mess_url' => Column::TYPE_VARCHAR,
                'ret_status' => Column::TYPE_VARCHAR,
                'ret_contentId' => Column::TYPE_VARCHAR,
                'memo' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'start_time', 'channel_id', 'admin_id', 'getui_range', 'getui_type', 'mess_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'start_time' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'client_type' => Column::BIND_PARAM_STR,
                'getui_range' => Column::BIND_PARAM_INT,
                'getui_type' => Column::BIND_PARAM_INT,
                'mess_body' => Column::BIND_PARAM_STR,
                'mess_title' => Column::BIND_PARAM_STR,
                'mess_id' => Column::BIND_PARAM_INT,
                'mess_url' => Column::BIND_PARAM_STR,
                'ret_status' => Column::BIND_PARAM_STR,
                'ret_contentId' => Column::BIND_PARAM_STR,
                'memo' => Column::BIND_PARAM_STR,
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

    public static function findAll($channel_id = 0)
    {
        $criterial = self::query();
        if($channel_id > 0)
        {
            $criterial->andWhere(" channel_id = $channel_id");
        }
        return $criterial->Order('id desc')->paginate(self::PAGE_SIZE, 'Pagination');
    }


    public static function createRecord($data)
    {
        $data['created_at'] = time();
        return self::save($data);
    }




}