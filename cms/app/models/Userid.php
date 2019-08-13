<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Userid extends Model {

    public function getSource() {
        return 'userid';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'uid', 'nickname', 'username',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['uid',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['nickname', 'username',],
            MetaData::MODELS_NOT_NULL => ['uid', 'nickname', 'username',],
            MetaData::MODELS_DATA_TYPES => [
                'uid' => Column::TYPE_INTEGER,
                'nickname' => Column::TYPE_VARCHAR,
                'username' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'uid',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'uid' => Column::BIND_PARAM_INT,
                'nickname' => Column::BIND_PARAM_STR,
                'username' => Column::BIND_PARAM_STR,
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

    public function createUserid($nickname, $username) {
        $this->nickname = $nickname;
        $this->username = $username;
        return $this->saveGetId();
    }


    public static function checkNickname($nickname) {
        return self::query()->andCondition('nickname', $nickname)->first();
    }

    public static function findFirstByUid($uid) {
        return self::query()->andCondition('uid' , $uid)->first();
    }

}