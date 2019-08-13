<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class WxCdkey extends Model {

    public function getSource() {
        return 'wx_cdkey';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'open_id', 'keyword_code', 'cdkey', 'msg_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['open_id', 'keyword_code', 'cdkey', 'msg_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'open_id', 'keyword_code', 'cdkey', 'msg_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'open_id' => Column::TYPE_VARCHAR,
                'keyword_code' => Column::TYPE_VARCHAR,
                'cdkey' => Column::TYPE_VARCHAR,
                'msg_id' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'open_id' => Column::BIND_PARAM_STR,
                'keyword_code' => Column::BIND_PARAM_STR,
                'cdkey' => Column::BIND_PARAM_STR,
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

    public static function findCdkeyByOpenId($open_id, $keyword_code) {
        return self::query()->andCondition('open_id', $open_id)
            ->andCondition('keyword_code', $keyword_code)
            ->first();
    }

    public static function findCdkeyByCdkey($cdkey) {
        return self::query()->andCondition('cdkey', $cdkey)
            ->first();
    }

    public static function createCdkeyByOpenId($open_id, $keyword_code, $msg_id) {
        $wx_cdkey = new WxCdkey();
        $wx_cdkey->open_id = $open_id;
        $wx_cdkey->keyword_code = $keyword_code;
        $wx_cdkey->cdkey = $keyword_code.substr(md5($keyword_code.$open_id), 8, 16);
        $wx_cdkey->msg_id = $msg_id;

        $id = $wx_cdkey->saveGetId();
        if ($id == 0) {
            return false;
        }
        return $wx_cdkey;
    }
}