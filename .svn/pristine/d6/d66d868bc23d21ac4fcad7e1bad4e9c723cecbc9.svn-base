<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class BaoliaoReply extends Model {

    public function getSource() {
        return 'baoliao_reply';
    }

    /**
     * @param $channel_id
     * @param $id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public function apiGetReplyById($channel_id, $id) {
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('id', $id)
            ->first();
        if ($data) {
            $data = $data->toArray();
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'baoliao_id', 'reply', 'author_id', 'author_name', 'create_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['baoliao_id', 'reply', 'author_id', 'author_name', 'create_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'baoliao_id', 'reply', 'author_id', 'author_name', 'create_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'baoliao_id' => Column::TYPE_INTEGER,
                'reply' => Column::TYPE_TEXT,
                'author_id' => Column::TYPE_INTEGER,
                'author_name' => Column::TYPE_VARCHAR,
                'create_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'baoliao_id', 'author_id', 'create_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'baoliao_id' => Column::BIND_PARAM_INT,
                'reply' => Column::BIND_PARAM_STR,
                'author_id' => Column::BIND_PARAM_INT,
                'author_name' => Column::BIND_PARAM_STR,
                'create_at' => Column::BIND_PARAM_INT,
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

    public static function findAll($baoliao_id) {
        $result = BaoliaoReply::query()->where("baoliao_id='{$baoliao_id}'")->execute()->toarray();
        return $result;
    }
}