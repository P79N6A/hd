<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class RewardYear extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'reward';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'sort', 'name', 'thumb', 'sum', 'residue', 'intro', 'channel_name', 'channel_logo',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['sort', 'name', 'thumb', 'sum', 'residue', 'intro', 'channel_name', 'channel_logo',],
            MetaData::MODELS_NOT_NULL => ['id', 'sort', 'name', 'sum', 'residue', 'channel_name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'thumb' => Column::TYPE_VARCHAR,
                'sum' => Column::TYPE_INTEGER,
                'residue' => Column::TYPE_INTEGER,
                'intro' => Column::TYPE_VARCHAR,
                'channel_name' => Column::TYPE_VARCHAR,
                'channel_logo' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'sort', 'sum', 'residue',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'sum' => Column::BIND_PARAM_INT,
                'residue' => Column::BIND_PARAM_INT,
                'intro' => Column::BIND_PARAM_STR,
                'channel_name' => Column::BIND_PARAM_STR,
                'channel_logo' => Column::BIND_PARAM_STR,
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

    public static function findAll(){
        return self::query()
            ->orderBy('sort')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findList(){
        return self::query()
            ->orderBy('sort')
            ->execute()->toarray();
    }

    public static function findOneById($id){
        return self::query()->andCondition('id',$id)->first();
    }

    public function createReward($input){
        $this->assign($input);
        return $this->save()?true:false;
    }

    public function modifyReward($input){
        $this->assign($input);
        return $this->update()?true:false;
    }

    public static function makeValidator($inputs,$condition = null) {
        return Validator::make(
            $inputs,
            [
                'sort' => 'required|numeric',
                'name' => 'required',
                'sum' => 'required',
                'channel_name' => 'required'
            ],
            [
                'sort.required' => '请填写顺序',
                'sort.numeric' => '顺序必须为数字',
                'name.required' => '请填写奖品名称',
                'sum.required' => '请填写奖品数量',
                'channel_name.required' => '请填写频道名称'
            ]
        );
    }
}