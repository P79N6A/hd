<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CreditTransactions extends Model {

    const TRADER_TYPE_API = "api";
    const TRADER_TYPE_SYS = "system";
    const TRADER_TYPE_DUIBA = "duiba";


    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $tradertypeMaps = [
        self::TRADER_TYPE_API => 'API',
        self::TRADER_TYPE_SYS => '系统',
        self::TRADER_TYPE_DUIBA => '兑吧',
    ];

    public function getSource() {
        return 'credit_transactions';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'user_id', 'type', 'credits', 'trader', 'orderNum', 'actualPrice', 'timestamp', 'detail', 'created_at', 'updated_at', 'partition_by', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'type', 'credits', 'trader', 'orderNum', 'actualPrice', 'timestamp', 'detail', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'type', 'credits', 'trader', 'orderNum', 'actualPrice', 'timestamp', 'created_at', 'updated_at', 'partition_by', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_VARCHAR,
                'credits' => Column::TYPE_INTEGER,
                'trader' => Column::TYPE_VARCHAR,
                'orderNum' => Column::TYPE_VARCHAR,
                'actualPrice' => Column::TYPE_INTEGER,
                'timestamp' => Column::TYPE_VARCHAR,
                'detail' => Column::TYPE_TEXT,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'credits', 'actualPrice', 'created_at', 'updated_at', 'partition_by', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
                'credits' => Column::BIND_PARAM_INT,
                'trader' => Column::BIND_PARAM_STR,
                'orderNum' => Column::BIND_PARAM_STR,
                'actualPrice' => Column::BIND_PARAM_INT,
                'timestamp' => Column::BIND_PARAM_STR,
                'detail' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => '',
                'trader' => 'system',
                'orderNum' => '',
                'actualPrice' => '0',
                'timestamp' => '',
                'status' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function listTraderType() {
        return self::$tradertypeMaps;
    }

    public static function getTransactionsByChannelId($channel_id) {
        return CreditTransactions::query()
            ->columns(array('Users.*', 'CreditTransactions.*'))
            ->leftJoin("Users", "CreditTransactions.user_id=Users.uid")
            ->where("CreditTransactions.channel_id={$channel_id}")
            ->orderBy('CreditTransactions.updated_at desc')
            ->paginate(50, 'Pagination');
    }

    public static function getTransactionsByUid($uid, $channel_id) {
        return CreditTransactions::query()
            ->columns(array('Users.*', 'CreditTransactions.*'))
            ->leftJoin("Users", "CreditTransactions.user_id=Users.uid")
            ->where("CreditTransactions.channel_id={$channel_id} and CreditTransactions.user_id={$uid}")
            ->orderBy('CreditTransactions.updated_at desc')
            ->paginate(50, 'Pagination');
    }


    public static function getLastTransactionByType($user_id, $rule_type, $channel_id) {
        $transactiondata = self::query()
            ->andCondition('user_id', $user_id)
            ->andCondition('type', $rule_type)
            ->andCondition('status', 1)
            ->orderBy('updated_at desc')
            ->first();
        return $transactiondata;
    }


    public static function getAllTransactionByType($user_id, $rule_type, $channel_id) {
        return CreditTransactions::query()
            ->where("user_id = {$user_id} and type={$rule_type} and status=1")->execute()->toArray();
    }

    public static function getAllTransactionByTypeToday($user_id, $rule_type, $channel_id) {
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        return CreditTransactions::query()
            ->where("user_id = {$user_id} and type={$rule_type} and status=1 and created_at > {$today_timestamp}")->execute()->toArray();
    }

    public function createTransaction($data) {
        $this->assign($data);
        $this->created_at = time();
        $this->updated_at = time();
        $this->partition_by = date('Y');
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function updateTransaction($data) {
        $this->assign($data);
        $this->updated_at = time();
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }


    public static function commonTransaction($user_id, $rule_type, $channel_id, $trader) {
        if ($rule_type == CreditRules::RULE_TYPE_SHARE) {
            return CreditUrlShare::earnCredit($user_id, $channel_id, $trader);
        }
        if ($rule_type == CreditRules::RULE_TYPE_SIGN) {
            return CreditBySign::earnCredit($user_id, $channel_id, $trader);
        }
        if ($rule_type == CreditRules::RULE_TYPE_COMMENT) {
            return CreditComment::earnCredit($user_id, $channel_id, $trader);
        }
        if (CreditTransactions::limitCheck($user_id, $rule_type, $channel_id)) {
            $rule = CreditRules::getCreditRulesByType($rule_type, $channel_id);
            $creditvalue = $rule['base_credit'];
            $reasonofcredit = $rule['name'];
            if (CreditRules::RULE_TYPE_REGISTER == $rule_type) $reasonofcredit = "恭喜您完成注册，获得" . $creditvalue . "个积分，每天签到会获得更多积分哦。";
            $data = array(
                'channel_id' => $channel_id,
                'user_id' => $user_id,
                'type' => $rule['type'],
                'credits' => $creditvalue,
                'trader' => $trader,
                'orderNum' => 0,
                'actualPrice' => 0,
                'timestamp' => 0,
                'detail' => $reasonofcredit,
                'status' => 1,
            );
            $credit_transaction = new CreditTransactions();
            if ($credit_transaction->createTransaction($data)) {
                Users::changeCredit($user_id, $creditvalue);
                CreditTransactions::setTodayCredit($user_id, $rule_type, $channel_id, $creditvalue);
                $result = array(
                    'credits_option' => $creditvalue,
                    'tips_option' => $reasonofcredit,
                    'sign_in' => 0
                );
            }
        } else {
            $result = array(
                'credits_option' => 0,
                'tips_option' => "超出积分上限",
                'sign_in' => 0
            );
        }
        return $result;
    }

    /**
     * 设置每日累计积分
     * @param $uid
     * @return string
     */
    public static function setTodayCredit($user_id, $rule_type, $channel_id, $credits) {
        $memprefix = app_site()->memprefix;
        $credit_sum = CreditTransactions::getTodayCredit($user_id, $rule_type, $channel_id);
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $credit_sum = (int)$credit_sum + (int)$credits;
        $key = $memprefix . "credit_today_sum_" . $user_id . "_type" . $rule_type . "_" . $channel_id . "_stamp_" . $today_timestamp;
        RedisIO::set($key, $credit_sum, 86400);
        return true;
    }

    /**
     * 获取每日累计积分
     * @param $uid
     * @return string
     */
    public static function getTodayCredit($user_id, $rule_type, $channel_id) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $key = $memprefix . "credit_today_sum_" . $user_id . "_type" . $rule_type . "_" . $channel_id . "_stamp_" . $today_timestamp;
        $data = RedisIO::get($key);
        if ($data) {
            return (int)$data;
        } else {
            return 0;
        }
    }

    public static function limitCheck($user_id, $rule_type, $channel_id) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $key = $memprefix . 'credittransactions.limit.user_id_' . $user_id . 'rule_type_' . $rule_type . 'channel_id_' . $channel_id . "_stamp_" . $today_timestamp;
        $data = MemcacheIO::get($key);
        if (!$data) {
            $credit_rule = CreditRules::getCreditRulesByType($rule_type, $channel_id);
            if ($credit_rule['day_limit']) {//设置了每日获取积分上限
                //查询用户今日在该规则下获取的总积分
                $credit_sum = CreditTransactions::getTodayCredit($user_id, $rule_type, $channel_id);
                if (($credit_sum + $credit_rule['single']) > $credit_rule['day_limit']) {
                    MemcacheIO::set($key, 'day_limit', 86400);
                    return false;
                }
            }
            if ($credit_rule['user_limit']) {//设置了用户获取积分上限
                //查询用户在该规则下获取的总积分
                $transactions = CreditTransactions::getAllTransactionByType($user_id, $rule_type, $channel_id);
                $credit_sum = 0;
                if (count($transactions)) {
                    foreach ($transactions as $v) {
                        $credit_sum = (int)$credit_sum + (int)$v['credits'];
                    }
                }
                if (($credit_sum + $credit_rule['single']) <= $credit_rule['user_limit']) {
                    return true;
                } else {//超出积分上限
                    MemcacheIO::set($key, 'user_limit', 86400);
                    return false;
                }
            }
        } else {
            return ('day_limit' == $data || 'user_limit' == $data) ? false : true;
        }
        return true;
    }

}