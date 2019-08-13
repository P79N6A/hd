<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AttachmentCommon extends Model {
    public $curr_user; //当前登录用户
    public $curr_time;

    public function initialize() {
        date_default_timezone_set('Asia/Shanghai');
        $this->curr_time = time();
        $adminsession = Session::get("user");
        $this->curr_user = $adminsession->id;
    }

    public function getSource() {
        return 'attachment_common';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'origin_name', 'name', 'created', 'type', 'path', 'ext', 'u_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['origin_name', 'name', 'created', 'type', 'path', 'ext', 'u_id',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'origin_name' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'created' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_VARCHAR,
                'path' => Column::TYPE_VARCHAR,
                'ext' => Column::TYPE_VARCHAR,
                'u_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'created', 'u_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'origin_name' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'created' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
                'path' => Column::BIND_PARAM_STR,
                'ext' => Column::BIND_PARAM_STR,
                'u_id' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'u_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public function createAttach($data) {
        $this->origin_name = $data['origin_name'];
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->path = $data['path'];
        $this->ext = $data['ext'];
        $this->u_id = $this->curr_user;
        $this->created = $this->curr_time;
        $this->save();
        return $this->id;
    }

}