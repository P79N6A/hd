<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SmsScene extends Model {
    const  PAGE_SIZE = 20;

    public function getSource() {
        return 'sms_scene';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'user_id', 'channel_id', 'template_id', 'create_at', 'username', 'pwd', 'terminal', 'status', 'rules', 'codelength', 'major_piple', 'minor_piple',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'user_id', 'channel_id', 'template_id', 'create_at', 'username', 'pwd', 'terminal', 'status', 'rules', 'codelength', 'major_piple', 'minor_piple',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'user_id', 'channel_id', 'template_id', 'create_at', 'username', 'pwd', 'codelength',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'user_id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'template_id' => Column::TYPE_INTEGER,
                'create_at' => Column::TYPE_INTEGER,
                'username' => Column::TYPE_VARCHAR,
                'pwd' => Column::TYPE_VARCHAR,
                'terminal' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'rules' => Column::TYPE_TEXT,
                'codelength' => Column::TYPE_INTEGER,
                'major_piple' => Column::TYPE_INTEGER,
                'minor_piple' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'user_id', 'channel_id', 'template_id', 'create_at', 'terminal', 'status', 'codelength', 'major_piple', 'minor_piple',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'user_id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'template_id' => Column::BIND_PARAM_INT,
                'create_at' => Column::BIND_PARAM_INT,
                'username' => Column::BIND_PARAM_STR,
                'pwd' => Column::BIND_PARAM_STR,
                'terminal' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'rules' => Column::BIND_PARAM_STR,
                'codelength' => Column::BIND_PARAM_INT,
                'major_piple' => Column::BIND_PARAM_INT,
                'minor_piple' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'user_id' => '0',
                'terminal' => 'app,pc,wap',
                'codelength' => '4'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        return SmsScene::query()->paginate(SmsScene::PAGE_SIZE, 'Pagination');
    }

    public static function getItem($id) {
        return SmsScene::findFirst($id)->toArray();
    }

}