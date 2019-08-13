<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Lotteries extends Model {

    static $PAGE_SIZE = 50;

    public function getSource() {
        return 'lotteries';
    }

    public static function getById($id) {
        return MemcacheIO::snippet('lottery.' . $id, 86400 * 30, function () use ($id) {
            return self::findFirst($id);
        });
    }

    public static function getFullById($id) {
        return MemcacheIO::snippet('lottery.full.' . $id, 86400 * 30, function () use ($id) {
            $model = self::query()
                ->andWhere('Lotteries.id = :id:', ['id' => $id])
                ->columns(['Lotteries.*', 'lc.*'])
                ->leftJoin('LotteryChannels', 'lc.id = Lotteries.lottery_channel_id', 'lc')
                ->first();
            $lottery = [];
            if ($model->lotteries) {
                $lottery = $model->lotteries->toArray();
            }
            $lotteryChannel = [];
            if ($model->lc) {
                $channel_name = $model->lc->name;
                $lotteryChannel = $model->lc->toArray();
                $lotteryChannel['channel_name'] = $channel_name;
            }
            $data = array_merge($lotteryChannel, $lottery);
            return $data;
        });
    }

    public static function clearMemById($id) {
        $key = 'lottery.' . $id;
        return MemcacheIO::delete($key);
    }

    public static function lotteryCount($id) {
        $key = 'lottery_people:' . $id;
        return RedisIO::get($key);
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'group_id', 'name', 'lottery_channel_id', 'open_time', 'close_time', 'estimated_people', 'times_limit', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'group_id', 'name', 'lottery_channel_id', 'open_time', 'close_time', 'estimated_people', 'times_limit', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'group_id', 'name', 'lottery_channel_id', 'open_time', 'close_time', 'estimated_people', 'times_limit', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'group_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'lottery_channel_id' => Column::TYPE_INTEGER,
                'open_time' => Column::TYPE_INTEGER,
                'close_time' => Column::TYPE_INTEGER,
                'estimated_people' => Column::TYPE_INTEGER,
                'times_limit' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'group_id', 'lottery_channel_id', 'open_time', 'close_time', 'estimated_people', 'times_limit', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'group_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'lottery_channel_id' => Column::BIND_PARAM_INT,
                'open_time' => Column::BIND_PARAM_INT,
                'close_time' => Column::BIND_PARAM_INT,
                'estimated_people' => Column::BIND_PARAM_INT,
                'times_limit' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'times_limit' => '0'
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
        $params = ['lottery_channel_id', 'open_time', 'close_time'];
        $query = $query->andCondition('channel_id', $channel_id);
        foreach ($params as $v) {
            if ($r = Request::get($v)) {
                if ($v == 'open_time') {
                    $query = $query->andCondition($v, ">", strtotime($r));
                } else if ($v == 'close_time') {
                    $query = $query->andCondition($v, "<", strtotime($r) + 3600 * 24);
                } else {
                    $query = $query->andCondition($v, "=", $r);
                }
            }
        }
        return $query->orderBy('id desc')->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'lottery_channel_id' => "required",
            'name' => "required",
            'open_time' => "required",
            'close_time' => 'required',
            'estimated_people' => 'required',
            'times_limit' => 'required',
        ], [
                'lottery_channel_id.required' => '频道ID必填',
                'name.required' => '活动标识必填',
                'open_time.required' => '开始时间必填',
                'close_time.required' => '结束时间必填',
                'estimated_people.required' => '预计人数必填',
                'times_limit' => '次数限制必填',
            ]
        );
        return $validator;
    }

    public static function listLottery() {
        $channel_id = Session::get('user')->channel_id;
        $data = self::query()->andCondition('channel_id', $channel_id)->orderBy('id desc')->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    /**
     * 已开启的抽奖
     *
     * @param bool $force_reload
     * @return array
     */
    public static function openedLotteries($group_id, $force_reload = false) {
        $now = time();
        $key = 'opened_lotteries:' . $group_id;
        $data = MemcacheIO::get($key);
        //空数组也要缓存
        if ($data === false || $force_reload) {
            $data = self::query()
                ->andCondition('group_id', $group_id)
                ->andCondition('open_time', '<', $now)
                ->andCondition('close_time', '>', $now)
                ->columns(['Lotteries.id', 'lottery_channel_id', 'style', 'background'])
                ->orderBy('sort DESC')
                ->leftJoin('LotteryChannels', 'LotteryChannels.id = Lotteries.lottery_channel_id')
                ->execute()
                ->toArray();
            MemcacheIO::set($key, $data, 1800);
        }
        return $data;
    }

    public static function checkStart($id) {
        $model = self::findFirst($id);
        if ($model) {
            return $model->open_time <= time();
        }
        return true;
    }

    public static function incrLotteryCount($id) {
        RedisIO::incr('lottery_people:' . $id);
    }

    public static function checkGroup($gid) {
        $query = self::query()->andCondition('group_id', $gid)->first();
        return $query ? true : false;
    }

    public static function getLotteryByChannel($lottery_channel_id) {
        return self::query()
            ->andCondition('lottery_channel_id', $lottery_channel_id)
            ->orderBy('open_time desc')->limit(1)->execute()->toArray();
    }

    public static function getLotteryByHuichang($lottery_channel_id) {
        return self::query()
            ->andCondition('lottery_channel_id', $lottery_channel_id)
            ->orderBy('open_time desc')->execute()->toArray();
    }

    public static function getLotteryByChannelAndGroup($channel , $group_id , $lottery_channel_id) {
        return self::query()
            ->andCondition('channel_id',$channel)
            ->andCondition('group_id',$group_id)
            ->andCondition('lottery_channel_id',$lottery_channel_id)
            ->orderBy('id desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

}
