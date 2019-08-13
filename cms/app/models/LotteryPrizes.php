<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class LotteryPrizes extends Model {

    static $PAGE_SIZE = 50;

    public function getSource() {
        return 'lottery_prizes';
    }

    public static function getByLottery($lottery_id) {
        $data = MemcacheIO::get('lottery.prizes.' . $lottery_id);
        if (!$data) {
            $query = function () use ($lottery_id) {
                return self::query()->andCondition('lottery_id', $lottery_id);
            };
            $sum = (int)$query()->columns('sum(number) AS _sum')->first()->_sum;
            //鉴于中奖采用循环算法, 一旦中奖就跳出循环, 因此数量越大的奖品, 跳出概率越大, 循环效率越高
            $rs = $query()->orderBy('number DESC')->execute();
            $data = [$sum, $rs];
            MemcacheIO::set('lottery.prizes.' . $lottery_id, $data, 86400 * 365);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'lottery_id', 'goods_id', 'type', 'belong_to', 'name', 'level', 'number', 'rest_number', 'created_at', 'updated_at', 'is_real',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'lottery_id', 'goods_id', 'type', 'belong_to', 'name', 'level', 'number', 'rest_number', 'created_at', 'updated_at', 'is_real',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'lottery_id', 'goods_id', 'type', 'name', 'level', 'number', 'rest_number', 'created_at', 'updated_at', 'is_real',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'lottery_id' => Column::TYPE_INTEGER,
                'goods_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'belong_to' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'level' => Column::TYPE_INTEGER,
                'number' => Column::TYPE_INTEGER,
                'rest_number' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'is_real' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'lottery_id', 'goods_id', 'type', 'belong_to', 'level', 'number', 'rest_number', 'created_at', 'updated_at', 'is_real',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'lottery_id' => Column::BIND_PARAM_INT,
                'goods_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'belong_to' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'level' => Column::BIND_PARAM_INT,
                'number' => Column::BIND_PARAM_INT,
                'rest_number' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'is_real' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => 'lottery'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query();
        $query = $query->andCondition('channel_id', $channel_id);
        $params = ['lottery_id','belong_to','type'];//允许查询字段
        foreach ($params as $v) {
            if ($r = Request::get($v)) {
                $query = $query->andCondition($v, "=", $r);
            }
        }
        if ($r = Request::get('goods_id')) {
            $query = $query->andCondition("goods_id", "=", $r);
        }
        return $query->orderBy('id desc')->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function findAllByType($belong_to , $type) {
        $query = self::query();
        return $query->andCondition('belong_to',$belong_to)
            ->andCondition('type',$type)->execute();
    }

    public static function getAllByLottery($lottery_id) {
        return self::query()->andCondition('lottery_id', $lottery_id)->orderBy('level asc')->execute();
    }

    public static function findOneByLottery($belong_to , $type , $goods_id) {
        return self::query()->andCondition('belong_to',$belong_to)
            ->andCondition('type',$type)
            ->andCondition('goods_id',$goods_id)->first();
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'lottery_id' => "required",
            'goods_id' => "required",
            'name' => "required",
            'level' => 'required',
            'number' => 'required',
            'is_real' => 'required',
        ], [
                'lottery_id.required' => '活动ID必填',
                'goods_id.required' => '奖品ID必填',
                'name.required' => '奖品名必填',
                'level.required' => '奖品等级必填',
                'number.required' => '奖品数量必填',
                'is_real' => '奖品是否实物必填'
            ]
        );
        return $validator;
    }

    public static function checkGoods($gid) {
        $query = self::query()->andCondition('goods_id', $gid)->first();
        return $query ? true : false;
    }

    public static function findById($id) {
        $query = self::query()->andCondition('id', $id)->first();
        return $query;
    }

}
