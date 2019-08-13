<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class LotteryWinnings extends Model {

    public static $PAGE_SIZE = 50;

    public static $properties = [
        'prize_name' => '奖品名',
        'is_real' => '奖品类型',
        'name' => '姓名',
        'mobile' => '手机',
        'address' => '地址',
        'status' => '状态',
//        'extra_value' => '附加'
    ];

    public function getSource() {
        return 'lottery_winnings';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'lottery_group_id', 'client_id', 'contacts_token', 'prize_id', 'prize_name', 'prize_level', 'prize_is_real', 'lottery_id', 'lottery_channel_id', 'sum', 'extra_value', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'lottery_group_id', 'client_id', 'contacts_token', 'prize_id', 'prize_name', 'prize_level', 'prize_is_real', 'lottery_id', 'lottery_channel_id', 'sum', 'extra_value', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'lottery_group_id', 'client_id', 'prize_id', 'prize_name', 'prize_level', 'prize_is_real', 'lottery_id', 'lottery_channel_id', 'sum', 'extra_value', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'lottery_group_id' => Column::TYPE_INTEGER,
                'client_id' => Column::TYPE_VARCHAR,
                'contacts_token' => Column::TYPE_VARCHAR,
                'prize_id' => Column::TYPE_INTEGER,
                'prize_name' => Column::TYPE_VARCHAR,
                'prize_level' => Column::TYPE_INTEGER,
                'prize_is_real' => Column::TYPE_INTEGER,
                'lottery_id' => Column::TYPE_INTEGER,
                'lottery_channel_id' => Column::TYPE_INTEGER,
                'sum' => Column::TYPE_INTEGER,
                'extra_value' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'lottery_group_id', 'prize_id', 'prize_level', 'prize_is_real', 'lottery_id', 'lottery_channel_id', 'sum', 'extra_value', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'lottery_group_id' => Column::BIND_PARAM_INT,
                'client_id' => Column::BIND_PARAM_STR,
                'contacts_token' => Column::BIND_PARAM_STR,
                'prize_id' => Column::BIND_PARAM_INT,
                'prize_name' => Column::BIND_PARAM_STR,
                'prize_level' => Column::BIND_PARAM_INT,
                'prize_is_real' => Column::BIND_PARAM_INT,
                'lottery_id' => Column::BIND_PARAM_INT,
                'lottery_channel_id' => Column::BIND_PARAM_INT,
                'sum' => Column::BIND_PARAM_INT,
                'extra_value' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'lottery_group_id' => '0',
                'sum' => '1',
                'extra_value' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function ifClientHasWin($group_id, $client_id) {
        return self::query()
            ->andCondition('client_id', $client_id)
            ->andCondition('lottery_group_id', $group_id)
            ->columns('id')
            ->first();
    }

    public static function ifClientHasWinPrize($group_id, $client_id, $prize_id) {
        return self::query()
            ->andCondition('client_id', $client_id)
            ->andCondition('lottery_group_id', $group_id)
            ->andCondition('prize_id', $prize_id)
            ->columns('id')
            ->first();
    }

    public static function findlistByLottery($lottery_id) {
        return self::query()
            ->andCondition('lottery_id', $lottery_id)
            ->columns(array('LotteryWinnings.prize_name', 'LotteryWinnings.created_at', 'LotteryWinnings.client_id', 'LotteryContacts.mobile', 'LotteryContacts.name'))
            ->leftjoin("LotteryContacts", "LotteryWinnings.id=LotteryContacts.id")
            ->execute()->toArray();
    }

    public static function findlistByGroup($lottery_group_id) {
        return self::query()
            ->andCondition('lottery_group_id', $lottery_group_id)
            ->columns(array('LotteryWinnings.prize_name', 'LotteryWinnings.created_at', 'LotteryContacts.mobile', 'LotteryContacts.status'))
            ->leftjoin("LotteryContacts", "LotteryWinnings.id=LotteryContacts.id")
            ->execute()->toArray();
    }

    public static function findNubmer($lottery_id) {
        return self::query()
            ->andCondition('lottery_id', $lottery_id)
            ->execute();
    }

    public static function findAll($pagesize_win = 0) {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->andCondition('channel_id', $channel_id)
            ->columns(array('LotteryWinnings.*', 'LotteryContacts.*'))
            ->leftJoin("LotteryContacts", "LotteryWinnings.id=LotteryContacts.id");
        $params = ['mobile', 'name', 'lottery_id', 'lottery_group_id'];
        foreach ($params as $v) {
            if ($r = Request::get($v)) {
                $query = $query->andCondition($v, $r);
            }
        }
        $prize_is_real = Request::get('prize_is_real');
        if ($prize_is_real == 1 || $prize_is_real === '0') {
            $query = $query->andWhere('LotteryWinnings.prize_is_real = ' . $prize_is_real);
        }
        $prize_name = Request::get('keyword');
        if($prize_name!=''){
            $query = $query->andWhere("LotteryWinnings.prize_name like '%{$prize_name}%' ");
        }
        $query = $query->orderBy('LotteryWinnings.id desc');
        if (Request::get('export')) {
            if ($pagesize_win) {
                $query = $query->paginate($pagesize_win, 'Pagination');
            } else {
                $query = $query->execute();
            }
        } else {
            $query = $query->paginate(self::$PAGE_SIZE, 'Pagination');
        }
        return $query;
    }


    public static function findAllBrand($lottery_id = 0, $pagesize_win = 0) {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->andCondition('channel_id', $channel_id);
        if ($lottery_id) {
            $query = $query->andCondition('lottery_id', $lottery_id);
        }
        $query = $query->columns(array('LotteryWinnings.*', 'LotteryContacts.*'))
            ->leftJoin("LotteryContacts", "LotteryWinnings.id=LotteryContacts.id");
        $params = ['mobile', 'name', 'lottery_id'];
        foreach ($params as $v) {
            if ($r = Request::get($v)) {
                $query = $query->andCondition($v, $r);
            }
        }
        $query = $query->orderBy('extra_value desc, LotteryWinnings.id desc');
        if (Request::get('export')) {
            $query = $query->execute();
        } else if (Request::get('exportbrand')) {
            if ($pagesize_win) {
                $query = $query->paginate($pagesize_win, 'Pagination');
            } else {
                $query = $query->execute();
            }
        } else {
            $query = $query->paginate(self::$PAGE_SIZE, 'Pagination');
        }
        return $query;
    }

    public static function getById($id) {
        $key = 'winning:' . $id;
        $r = MemcacheIO::get($key);
        if (!$r) {
            $r = self::findFirst($id);
            if ($r) {
                MemcacheIO::set($key, $r, 3600);
            }
        }
        return $r;
    }

    public static function getAllForClientAndLottery($lottery_id , $client_id) {
        $query = self::query()
            ->andCondition('lottery_id', $lottery_id)
            ->andCondition('client_id', $client_id)->execute();
        return $query;
    }

    public static function getNotRealForClientAndLottery($lottery_id , $client_id) {
        $query = self::query()
            ->andCondition('lottery_id', $lottery_id)
            ->andCondition('client_id', $client_id)
            ->andCondition('prize_is_real', 0)
            ->andCondition('sum', 1)->first();
        return $query;
    }

    public static function getOneByClientAndToken($client_id, $contacts_token) {
        $query = self::query()
            ->andCondition('client_id', $client_id)
            ->andCondition('contacts_token', $contacts_token)->first();
        return $query;
    }

}