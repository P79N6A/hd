<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VoteYear extends Model {

    public function getSource() {
        return 'vote';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'show_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['show_id',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'show_id' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'show_id' => Column::BIND_PARAM_STR,
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

    public static function findOneById($id) {
        return self::query()->andCondition('id',$id)->first();
    }

    public function createVote($input){
        $this->assign($input);
        return $this->save()?true:false;
    }

    public static function findVoteHas() {
        return self::query()->andwhere('show_id is not null')->execute();
    }
}