<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class LotteryContacts extends Model {

    public function getSource() {
        return 'lottery_contacts';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'token', 'prize_is_real', 'mobile', 'name', 'province', 'city', 'area', 'address', 'address_modify_admin', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['token', 'prize_is_real', 'mobile', 'name', 'province', 'city', 'area', 'address', 'address_modify_admin', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'token', 'prize_is_real', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'token' => Column::TYPE_VARCHAR,
                'prize_is_real' => Column::TYPE_INTEGER,
                'mobile' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'province' => Column::TYPE_VARCHAR,
                'city' => Column::TYPE_VARCHAR,
                'area' => Column::TYPE_VARCHAR,
                'address' => Column::TYPE_VARCHAR,
                'address_modify_admin' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'prize_is_real', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'token' => Column::BIND_PARAM_STR,
                'prize_is_real' => Column::BIND_PARAM_INT,
                'mobile' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'province' => Column::BIND_PARAM_STR,
                'city' => Column::BIND_PARAM_STR,
                'area' => Column::BIND_PARAM_STR,
                'address' => Column::BIND_PARAM_STR,
                'address_modify_admin' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 初始化中奖数据
     *
     * @param string $id
     * @param string $token
     * @param int $is_real
     * @return bool
     */
    public static function dataInit($id, $token, $is_real) {
        $now = time();
        $contact = new self;
        $contact->id = $id;
        $contact->token = $token;
        $contact->prize_is_real = $is_real;
        $contact->mobile = '';
        $contact->name = '';
        $contact->province = '';
        $contact->city = '';
        $contact->area = '';
        $contact->address = '';
        $contact->address_modify_admin = '';
        $contact->created_at = $now;
        $contact->updated_at = $now;
        $contact->status = 0;
        return $contact->save();
    }

    /**
     * 通过 Token 获得数据
     *
     * FIXME 中奖本身需要修改状态, 所以没有缓存 - 待定
     *
     * @param $token
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function getByToken($token) {
        //join 后无调用模型的 save 方法, 因此分开查询
        $r = self::query()
            ->andCondition('token', $token)
            ->first();
        if ($r) {
            $winning = LotteryWinnings::getById($r->id);
            $r->prize_name = $winning->prize_name;
            $r->prize_level = $winning->prize_level;
            $r->lottery_group_id = $winning->lottery_group_id;
        }
        return $r;
    }

    public static function getOneByToken($token) {
        $r = self::query()
            ->andCondition('token', $token)
            ->first();
        return $r;
    }

    public static function makeValidator($input) {
        $validator = Validator::make($input, [
            'status' => "required",
        ], [
            'status.required' => '状态必填',
        ]);
        return $validator;
    }

    /**
     * 获取最新中奖数据
     *
     * @param int $group_id
     * @param bool $force_reload
     * @return array
     */
    public static function latestWinners($group_id, $force_reload = false) {
        $key = 'latest_winners:' . $group_id;
        $rs = MemcacheIO::get($key);
        if (!$rs || $force_reload) {
            $rs = self::query()
                ->andCondition('lottery_group_id', $group_id)
                ->andCondition('status', '>', '0')
                ->columns(['LotteryContacts.mobile', 'LotteryWinnings.prize_name', 'LotteryWinnings.prize_level', 'LotteryContacts.name'])
                ->orderBy('LotteryContacts.id DESC')
                ->limit(30)
                ->leftJoin('LotteryWinnings', 'LotteryWinnings.id = LotteryContacts.id')
                ->execute()->toArray();
            if (!empty($rs)) {
                foreach ($rs as $idx => $r) {
                    $mobile = $r['mobile'];
                    $rs[$idx]['mobile'] = substr($mobile, 0, 3) . '****' . substr($mobile, -4);
                }
            }
            MemcacheIO::set($key, $rs, 600);
        }
        return $rs;
    }

    public static function getWinnersKey($group_id, $mobile) {
        return 'winners:' . $group_id . ':' . $mobile;
    }

    /**
     * 根据手机号最新中奖数据
     * @param $mobile
     * @return array
     */
    public static function searchWinners($group_id, $mobile) {
        $key = self::getWinnersKey($group_id, $mobile);
        $rs = RedisIO::zRevRange($key, 0, -1);
        $data = [];
        foreach ($rs as $idx => $r) {
            $r = json_decode($r, true);
            $data[$idx]["level"] = $r['level'];
            $data[$idx]["prize_name"] = $r['prize_name'];
            $data[$idx]["status"] = $r['status'];
        }
        return $data;
    }

    /**
     * 保存 redis 优化项目
     * @param int $id
     * @param string $mobile
     * @param string $name
     * @param string $prize_name
     */
    public static function saveRedisWinners($id, $group_id, $mobile, $name, $prize_name, $level, $status) {
        $key = self::getWinnersKey($group_id, $mobile);
        $data = [
            'name' => $name,
            'prize_name' => $prize_name,
            'level' => $level,
            'status' => $status,
        ];
        $data = json_encode($data);
        RedisIO::multi();
        RedisIO::zRemRangeByScore($key, $id, $id);
        RedisIO::zAdd($key, $id, $data);
        RedisIO::exec();
    }

    /**
     * 获取发货状态的名字
     *
     * @param $s
     * @return string
     */
    public static function getStatusName($s) {
        $status = [
            '无人认领', '未发奖', '已发奖', '已取消'
        ];
        $r = '未知';
        if (isset($status[$s])) {
            $r = $status[$s];
        }
        return $r;
    }
}