<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VcmSysMsg extends Model {

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'vcm_sys_msg';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_uid', 'content', 'url', 'create_time', 'channel_id', 'icon', 'title', 'play_id', 'share_url', 'sender_name', 'favorite_type',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_uid', 'content', 'url', 'create_time', 'channel_id', 'icon', 'title', 'play_id', 'share_url', 'sender_name', 'favorite_type',],
            MetaData::MODELS_NOT_NULL => ['id', 'admin_uid', 'create_time', 'channel_id', 'icon', 'title', 'play_id', 'share_url', 'sender_name', 'favorite_type',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_uid' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_VARCHAR,
                'url' => Column::TYPE_VARCHAR,
                'create_time' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'icon' => Column::TYPE_VARCHAR,
                'title' => Column::TYPE_VARCHAR,
                'play_id' => Column::TYPE_INTEGER,
                'share_url' => Column::TYPE_VARCHAR,
                'sender_name' => Column::TYPE_VARCHAR,
                'favorite_type' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_uid', 'create_time', 'channel_id', 'play_id', 'favorite_type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_uid' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'url' => Column::BIND_PARAM_STR,
                'create_time' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'icon' => Column::BIND_PARAM_STR,
                'title' => Column::BIND_PARAM_STR,
                'play_id' => Column::BIND_PARAM_INT,
                'share_url' => Column::BIND_PARAM_STR,
                'sender_name' => Column::BIND_PARAM_STR,
                'favorite_type' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'content' => '',
                'url' => '',
                'icon' => '',
                'title' => '',
                'play_id' => '0',
                'share_url' => '',
                'sender_name' => '',
                'favorite_type' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    //获取系统消息列表
    public function listSysMsg($start_time = 0) {
        $max_start_time = time() - 86400 * 3;    //最多获取三天之内的消息

        //如果没传$start_time, 或者所传时间是三天之前, 取三天之内的消息
        if (empty($start_time) || ($start_time < $max_start_time)) {
            $start_time = $max_start_time;
        }
//        $return = json_decode( RedisIO::get("vcm_sys_msg_listSysMsg"), true);

        if (empty($return)) {
            $where = 'create_time >= ' . $start_time;
            $result = self::query()
                ->where($where)
                ->orderBy('create_time desc')
                ->execute()
                ->toArray();
            $return = array('items' => $result);//避免没数据一直访问数据库
            if (!empty($result)) {
                RedisIO::set("vcm_sys_msg_listSysMsg", json_encode($return), 600);
            }
        }

        return $return;
    }

}