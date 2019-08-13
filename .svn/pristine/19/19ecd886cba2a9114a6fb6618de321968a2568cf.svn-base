<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class ShowYear extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'show';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'sort', 'name', 'type', 'channel_name', 'channel_logo', 'extra', 'vote', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['sort', 'name', 'type', 'channel_name', 'channel_logo', 'extra', 'vote', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'sort', 'name', 'channel_name', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_VARCHAR,
                'channel_name' => Column::TYPE_VARCHAR,
                'channel_logo' => Column::TYPE_VARCHAR,
                'extra' => Column::TYPE_INTEGER,
                'vote' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'sort', 'extra', 'vote', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_STR,
                'channel_name' => Column::BIND_PARAM_STR,
                'channel_logo' => Column::BIND_PARAM_STR,
                'extra' => Column::BIND_PARAM_INT,
                'vote' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'extra' => '0',
                'vote' => '0',
                'status' => '0'
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

    public static function findList() {
        return self::query()
            ->orderBy('sort')
            ->execute()->toarray();
    }

    public static function findListVote() {
        return self::query()
            ->orderBy('vote desc')
            ->execute()->toarray();
    }
    public static function findListExtra() {
        return self::query()
            ->columns('sum(extra) as extra_sum')
            ->execute()->toArray();
    }

    public static function findOneById($id){
        return self::query()->andCondition('id',$id)->first();
    }

    public function createShow($input){
        $this->assign($input);
        return $this->save()?true:false;
    }

    public function modifyShow($input){
        $this->assign($input);
        return $this->update()?true:false;
    }

    //返回第一个状态为0的节目，用于简单判断是否都关闭
    public static function findStatus() {
        return self::query()->andCondition('status',0)->first();
    }

    public static function openVote() {
        $all = self::query()->andCondition('status',0)->execute();
        foreach($all as $show){
            $show->status=1;
            $show->update();
        }
        return true;
    }

    public static function closeVote() {
        $all = self::query()->andCondition('status',1)->execute();
        foreach($all as $show){
            $show->status=0;
            $show->update();
        }
        return true;
    }

    public static function makeValidator($inputs,$condition = null) {
        return Validator::make(
            $inputs,
            [
                'sort' => 'required|numeric',
                'name' => 'required',
                'channel_name' => 'required'
            ],
            [
                'sort.required' => '请填写顺序',
                'sort.numeric' => '顺序必须为数字',
                'name.required' => '请填写节目名称',
                'channel_name.required' => '请填写频道名称'
            ]
        );
    }
}