<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VoteOption extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'vote_option';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'vote_id', 'number', 'content', 'picture', 'video_url', 'other', 'sum', 'actual_sum',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['vote_id', 'number', 'content', 'picture', 'video_url', 'other', 'sum', 'actual_sum',],
            MetaData::MODELS_NOT_NULL => ['id', 'vote_id', 'number', 'other', 'sum', 'actual_sum',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'vote_id' => Column::TYPE_INTEGER,
                'number' => Column::TYPE_VARCHAR,
                'content' => Column::TYPE_TEXT,
                'picture' => Column::TYPE_VARCHAR,
                'video_url' => Column::TYPE_VARCHAR,
                'other' => Column::TYPE_INTEGER,
                'sum' => Column::TYPE_INTEGER,
                'actual_sum' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'vote_id', 'other', 'sum', 'actual_sum',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'vote_id' => Column::BIND_PARAM_INT,
                'number' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'picture' => Column::BIND_PARAM_STR,
                'video_url' => Column::BIND_PARAM_STR,
                'other' => Column::BIND_PARAM_INT,
                'sum' => Column::BIND_PARAM_INT,
                'actual_sum' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'other' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findOptionById($id){
        return self::query()->andCondition('id',$id)->first();
    }

    public static function findOptionsByVoteId($vote_id){
        return self::query()->andCondition('vote_id',$vote_id)->orderby('sum desc, id asc')->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function getOptionsByVoteId($vote_id) {
        return self::query()->andCondition('vote_id',$vote_id)->orderby('sum desc, id asc')->execute()->toArray();
    }

    public static function getOptionsByVoteIdNew($vote_id, $limit = 10, $offset = 0) {
        return self::query()->andCondition('vote_id',$vote_id)->orderby('sum desc')->limit($limit, $offset)->execute()->toArray();
    }

}