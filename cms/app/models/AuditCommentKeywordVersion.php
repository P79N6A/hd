<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuditCommentKeywordVersion extends Model {

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'audit_comment_keyword_version';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'version', 'updateTime',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['version', 'updateTime',],
            MetaData::MODELS_NOT_NULL => ['id', 'version',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'version' => Column::TYPE_INTEGER,
                'updateTime' => Column::TYPE_DATETIME,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'version',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'version' => Column::BIND_PARAM_INT,
                'updateTime' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'version' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function updateVersion() {
        $keyword_version = self::query()->andCondition('id', 1)->first();
        $keyword_version->version++;
        $keyword_version->updateTime = date('Y-n-d H:i:s', time());
        return $keyword_version->update();
    }

}