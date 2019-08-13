<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CreditRules extends Model {

    const CHECKED = 1;
    const UNCHECKED = 0;


    const RULE_TYPE_MALL = 99;//商城扣积分

    const RULE_TYPE_TOTAL_CREDIT = 1;//总积分

    /*
     * 规则类型
     * 100：注册，110x：认证(111:实名 112:手机号 113:邮箱 114:微信 115:微博、116:qq)，300：邀请，120：签到，130：分享，140：评论，141：评论扣除，15x：游戏（待定）
     */
    const RULE_TYPE_REGISTER = 100;//注册

    //11x：信息完善(111:实名 112:手机号 113:邮箱 114:微信 115:微博、116:qq)
    const RULE_TYPE_SUPPLEMENT_REALNAME = 111;
    const RULE_TYPE_SUPPLEMENT_MOBILE = 112;
    const RULE_TYPE_SUPPLEMENT_EMAIL = 113;
    const RULE_TYPE_SUPPLEMENT_WECHAT = 114;
    const RULE_TYPE_SUPPLEMENT_WEIBO = 115;
    const RULE_TYPE_SUPPLEMENT_QQ = 116;

    const RULE_TYPE_SIGN = 120;//签到
    const RULE_TYPE_SHARE = 130;
    const RULE_TYPE_COMMENT = 140;
    const RULE_TYPE_COMMENT_DELETED = 141;

    //15x：游戏（待定）
    const RULE_TYPE_GAME_DEMO = 151;

    const RULE_TYPE_INVITE = 300;//邀请

    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $typeMaps = [
        self::RULE_TYPE_MALL => '商城',
        self::RULE_TYPE_REGISTER => '注册',
        //self::RULE_TYPE_SUPPLEMENT_REALNAME => '实名',
        //self::RULE_TYPE_SUPPLEMENT_MOBILE => '手机号',
        //self::RULE_TYPE_SUPPLEMENT_EMAIL => '邮箱',
        //self::RULE_TYPE_SUPPLEMENT_WECHAT => '微信',
        //self::RULE_TYPE_SUPPLEMENT_WEIBO => '微博',
        //self::RULE_TYPE_SUPPLEMENT_QQ => 'QQ',
        self::RULE_TYPE_SIGN => '签到',
        self::RULE_TYPE_SHARE => '分享',
        self::RULE_TYPE_COMMENT => '评论',
        //self::RULE_TYPE_COMMENT_DELETED => '评论被删除',
        //self::RULE_TYPE_INVITE => '邀请注册',
        //self::RULE_TYPE_GAME_DEMO => '游戏x',
    ];

    public function getSource() {
        return 'credit_rules';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'type', 'rule_group', 'single', 'single_min', 'single_max', 'range_step', 'range_max', 'day_limit', 'user_limit', 'name', 'ruledesc', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'type', 'rule_group', 'single', 'single_min', 'single_max', 'range_step', 'range_max', 'day_limit', 'user_limit', 'name', 'ruledesc', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'type', 'rule_group', 'single', 'single_min', 'single_max', 'range_step', 'range_max', 'day_limit', 'user_limit', 'name', 'ruledesc', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'rule_group' => Column::TYPE_INTEGER,
                'single' => Column::TYPE_INTEGER,
                'single_min' => Column::TYPE_INTEGER,
                'single_max' => Column::TYPE_INTEGER,
                'range_step' => Column::TYPE_INTEGER,
                'range_max' => Column::TYPE_INTEGER,
                'day_limit' => Column::TYPE_INTEGER,
                'user_limit' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'ruledesc' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'type', 'rule_group', 'single', 'single_min', 'single_max', 'range_step', 'range_max', 'day_limit', 'user_limit', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'rule_group' => Column::BIND_PARAM_INT,
                'single' => Column::BIND_PARAM_INT,
                'single_min' => Column::BIND_PARAM_INT,
                'single_max' => Column::BIND_PARAM_INT,
                'range_step' => Column::BIND_PARAM_INT,
                'range_max' => Column::BIND_PARAM_INT,
                'day_limit' => Column::BIND_PARAM_INT,
                'user_limit' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'ruledesc' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'single' => '0',
                'single_min' => '0',
                'single_max' => '0',
                'range_step' => '0',
                'range_max' => '0',
                'day_limit' => '0',
                'user_limit' => '0',
                'name' => '',
                'status' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required',
            'type' => 'required',
            'channel_id' => 'required',
        ], [
            'name.required' => '请填写规则名称',
            'type.required' => '请填写类型',
            'channel_id.required' => '请填写频道',
        ]);
    }

    public static function listType() {
        return self::$typeMaps;
    }

    public function getBaseCredit() {
        $credit = ($this->single_max) ? rand($this->single_min, $this->single_max) : $this->single;
        return (int)$credit;
    }

    public static function getStepCredit($range_step, $range_max, $keepsigncount) {
        if ($keepsigncount == 1) {
            return 0;
        } else {
            $stepvalue = $range_step * ($keepsigncount - 1);//第二天开始增加积分
            $credit = ($stepvalue > $range_max) ? $range_max : $stepvalue;
            return (int)$credit;
        }
    }

    public static function getRulesByChannelId($channel_id, $client_type = '', $app_version = '0') {
        $memprefix = app_site()->memprefix;
        $allowrules = "120, 100, 130";

        if ('ios' == $client_type || 'android' == $client_type) {
            if ('android' == $client_type && self::verCompare($app_version, '1.4.0')) {
                $allowrules = "120, 100, 130, 140";
            }
            if ('ios' == $client_type && self::verCompare($app_version, '1.3.3')) {
                $allowrules = "120, 100, 130, 140";
            }
        }
        $allowrules = "120, 100, 130";
        //$key = $memprefix.'credit.all_rules.channel_'.$channel_id."_".$client_type."v".$app_version;
        $key = $memprefix . 'credit.all_rules.channel_' . $channel_id . "_" . $client_type;
        $data = MemcacheIO::get($key);
        if (!$data) {
            $creditruledatas = CreditRules::query()
                ->where("channel_id={$channel_id} and type in ({$allowrules})")
                ->paginate(50, 'Pagination');
            $data = [];
            foreach ($creditruledatas->models as $rule) {
                $data[] = $rule->toArray();
            }
            MemcacheIO::set($key, $data, 86400 * 30);
        }
        return $data;
    }

    public static function getCreditRulesByType($rule_type, $channel_id) {
        $memprefix = app_site()->memprefix;
        $key = $memprefix . 'credit.channel_' . $channel_id . '.rule_type_' . $rule_type;
        $data = MemcacheIO::get($key);
        if (!$data) {
            $creditruledata = self::query()
                ->andCondition('channel_id', $channel_id)
                ->andCondition('type', $rule_type)
                ->andCondition('status', 1)
                ->orderBy('id desc')
                ->first();
            if ($creditruledata) {
                $data = $creditruledata->toArray();
                $data['base_credit'] = $creditruledata->getBaseCredit();
                MemcacheIO::set($key, $data, 86400 * 30);
            } else {
                $data = NULL;
            }
        }
        return $data;
    }

    public function createCredit($data) {
        $this->assign($data);
        $this->created_at = time();
        $this->updated_at = time();
        $this->status = 0;
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function changeStatus($status) {
        $this->status = $status;
        return $this->save();
    }

    public function updateCredit($data) {
        $this->assign($data);
        $this->created_at = time();
        $this->updated_at = time();
        $this->status = 0;
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    private static function verCompare($check, $data) {
        $check_arr = explode('.', $check);
        $data_arr = explode('.', $data);

        $check_len = count($check_arr);
        $data_len = count($data_arr);
        $len = $check_len > $data_len ? $check_len : $data_len;

        for ($i = 0; $i < $len; $i++) {
            $check_num = intval($check_arr[$i]);
            $data_num = intval($data_arr[$i]);
            if ($check_num > $data_num) {
                return true;
            } else if ($check_num < $data_num) {
                return false;
            }
        }
        return false;
    }
}