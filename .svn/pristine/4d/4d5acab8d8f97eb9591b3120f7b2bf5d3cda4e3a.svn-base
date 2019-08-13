<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CommentCounts extends Model {

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'comment_counts';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'xid', 'pid', 'total', 'type',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['xid', 'pid', 'total', 'type',],
            MetaData::MODELS_NOT_NULL => ['id', 'xid', 'pid', 'total', 'type',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'xid' => Column::TYPE_INTEGER,
                'pid' => Column::TYPE_INTEGER,
                'total' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_CHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'xid', 'pid', 'total',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'xid' => Column::BIND_PARAM_INT,
                'pid' => Column::BIND_PARAM_INT,
                'total' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'xid' => '0',
                'pid' => '0',
                'total' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}