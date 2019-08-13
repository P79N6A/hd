<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class WxBirthdaySupport extends Model {

    public function getSource() {
        return 'wx_birthday_support';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'open_id', 'keyword_id', 'keyword_name', 'support_num', 'support_time', 'msg_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['open_id', 'keyword_id', 'keyword_name', 'support_num', 'support_time', 'msg_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'open_id', 'keyword_id', 'support_num', 'support_time', 'msg_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'open_id' => Column::TYPE_VARCHAR,
                'keyword_id' => Column::TYPE_INTEGER,
                'keyword_name' => Column::TYPE_VARCHAR,
                'support_num' => Column::TYPE_INTEGER,
                'support_time' => Column::TYPE_INTEGER,
                'msg_id' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'keyword_id', 'support_num', 'support_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'open_id' => Column::BIND_PARAM_STR,
                'keyword_id' => Column::BIND_PARAM_INT,
                'keyword_name' => Column::BIND_PARAM_STR,
                'support_num' => Column::BIND_PARAM_INT,
                'support_time' => Column::BIND_PARAM_INT,
                'msg_id' => Column::BIND_PARAM_STR,
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

    public static function findByOpenId($open_id, $keyword_id) {
        return self::query()->andCondition('open_id', $open_id)
            ->andCondition('keyword_id', $keyword_id)
            ->first();
    }

    public static function createCdkeyByOpenId($open_id, $keyword, $msg_id) {
        $wx_birthday_support = new WxBirthdaySupport();
        $wx_birthday_support->open_id = $open_id;
        $wx_birthday_support->keyword_id = $keyword->id;
        $wx_birthday_support->keyword_name = $keyword->wx_keyword;
        $wx_birthday_support->support_num = $keyword->keyword_code;
        $wx_birthday_support->support_time = time();
        $wx_birthday_support->msg_id = $msg_id;

        $id = $wx_birthday_support->saveGetId();
        if ($id == 0) {
            return false;
        }
        return $wx_birthday_support;
    }

}