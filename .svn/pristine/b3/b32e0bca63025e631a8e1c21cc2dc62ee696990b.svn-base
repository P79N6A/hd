<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class TicketYear extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'ticket';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'number', 'reward_id', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['number', 'reward_id', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'number', 'reward_id', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'number' => Column::TYPE_INTEGER,
                'reward_id' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'number', 'reward_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'number' => Column::BIND_PARAM_INT,
                'reward_id' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'reward_id' => '0',
                'status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll($condition = 'number'){
        return self::query()
            ->columns(['TicketYear.id','TicketYear.number','TicketYear.reward_id','TicketYear.status','RewardYear.name','RewardYear.sort'])
            ->leftjoin('RewardYear', 'TicketYear.reward_id = RewardYear.id')
            ->orderBy('TicketYear.'.$condition)
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findList($reward_id , $condition = 'number'){
        return self::query()
            ->columns(['TicketYear.id','TicketYear.number','TicketYear.reward_id','TicketYear.status','RewardYear.name','RewardYear.sort'])
            ->leftjoin('RewardYear', 'TicketYear.reward_id = RewardYear.id')
            ->andwhere('reward_id='.$reward_id)
            ->orderBy('TicketYear.'.$condition)
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findAllByRewardId($reward_id){
        return self::query()
            ->andCondition('reward_id',$reward_id)
            ->orderBy('number')
            ->execute();
    }

    public static function findAllNoReward(){
        return self::query()
            ->andCondition('reward_id',0)->orderBy('number')->execute();
    }

    public static function findOneById($id){
        return self::query()->andCondition('id',$id)->first();
    }

    public static function findOneByNumber($number){
        return self::query()
            ->columns(['TicketYear.id','TicketYear.number','TicketYear.reward_id','TicketYear.status','RewardYear.name','RewardYear.sort'])
            ->leftjoin('RewardYear', 'TicketYear.reward_id = RewardYear.id')
            ->andWhere('TicketYear.number='.$number)
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findMaxNumber(){
        return self::query()
            ->orderBy('number Desc')
            ->first();
    }

    public function createTicket($input){
        $this->assign($input);
        return $this->save()?true:false;
    }

    public function modifyTicket($input){
        $this->assign($input);
        return $this->update()?true:false;
    }

    public static function makeValidator($inputs,$condition = null) {
        return Validator::make(
            $inputs,
            [
                'number' => 'required|numeric'
            ],
            [
                'number.required' => '请填写奖号',
                'number.numeric' => '奖号必须为数字'
            ]
        );
    }
}