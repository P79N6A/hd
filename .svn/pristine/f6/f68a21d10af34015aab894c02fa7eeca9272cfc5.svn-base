<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuditCommentControl extends Model {

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'audit_comment_control';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'global', 'pidlist', 'vidlist', 'ext_field',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['global', 'pidlist', 'vidlist', 'ext_field',],
            MetaData::MODELS_NOT_NULL => ['id', 'global',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'global' => Column::TYPE_INTEGER,
                'pidlist' => Column::TYPE_TEXT,
                'vidlist' => Column::TYPE_TEXT,
                'ext_field' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'global',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'global' => Column::BIND_PARAM_INT,
                'pidlist' => Column::BIND_PARAM_STR,
                'vidlist' => Column::BIND_PARAM_STR,
                'ext_field' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'global' => '0',
                'ext_field' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findOneById($id = 1) {
        return self::query()->andCondition('id', $id)->first();
    }

    public function updateAuditComment() {

        return ($this->update()) ? true : false;
    }

}