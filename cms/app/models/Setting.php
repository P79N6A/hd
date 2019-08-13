<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Setting extends Model {

    use HasChannel;

    const PAGE_SIZE = 50;

    protected static $keyTypes = [
        'is.login.message' => '是否通过短信登录',
        'qq.oauth' => 'QQ 登录设置',
        'weibo.oauth' => '微博登录设置',
        'getui.push' => '个推推送',
        'cdn_refresh' => 'CDN刷新',
        'domain_config' => '站点域名配置',
        'salary_bill_email'=>'员工邮箱表',
        'baoliao_mail' => '爆料邮箱',
        'BosonNLP' => '玻森语义分析',
        'zgltv_wechat'=>'微信公众号',
        'oauth_weixin'=>'微信开放平台',
        'oauth_tv_qq'=>'qqtv',
        'oauth_tv_app_qq'=>'qqtvapp',
        'oauth_qq'=>'qq第三方登录',
        'oauth_sina'=>'sina第三方登录',
        'getui.push'=>'个推推送',
        'ugcLive' => '手机直播',
        'oss_conf' => '阿里云oss',
        'app_category' =>'APP栏目id',
        'tencent_cloud' => '腾讯云服务'
    ];

    public function getSource() {
        return 'setting';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'key', 'value', 'channel_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'key', 'value', 'channel_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'key', 'value', 'channel_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'key' => Column::TYPE_VARCHAR,
                'value' => Column::TYPE_TEXT,
                'channel_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'key' => Column::BIND_PARAM_STR,
                'value' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
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

    public static function getByKey($key, $channel_id=0) {
        if($channel_id) {
            return self::query()->andCondition('key', $key)->andCondition('channel_id', $channel_id)->first();
        }
        else {
        return self::query()->andCondition('key', $key)->first();
        }
    }

    public static function getSettings() {
        return self::query()
            ->where('channel_id=' . Session::get('user')->channel_id)
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /**
     * @param $inputs
     * @param int $excluded_id
     * @return \Illuminate\Validation\Validator
     */
    public static function makeValidator($inputs, $excluded_id = 0) {
        $channel_id = Auth::user()->channel_id;
        return Validator::make($inputs, [
            'key' => 'required|max:30|in:' . implode(',', array_keys(self::$keyTypes)) . '|unique:setting,key,' . $excluded_id . ',id,channel_id,' . $channel_id,
            'value' => 'required',
        ], [
                'key.required' => '请填写键值',
                'key.in' => '非法的配置',
                'key.max' => '键值不得多于255个字',
                'key.unique' => '键值已存在',
                'value.required' => '请填写有效的配置值',
            ]
        );
    }

    public static function getKeyTypes() {
        return self::$keyTypes;
    }

    public function createSetting($data) {
        return $this->saveAndGetMessage($data, null);
    }

    public function modifySetting($data) {
        return $this->saveAndGetMessage($data, ['value']);
    }

    public function saveAndGetMessage(&$data, $whiteList) {
        $data['name'] = self::$keyTypes[$data['key']];
        $messages = [];
        if ($this->save($data, $whiteList)) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public static function processValue(&$data) {
        $value = '';
        $values = [];
        $size = count($data['value']);
        for ($i = 0; $i < $size; $i++) {
            $val = $data['value'][$i];
            if ("" !== $val) {
                $key = $data['setting'][$i];
                if ("" !== $key) {
                    $values[$key] = $val;
                } else {
                    $values[] = $val;
                }
            }
        }
        if (!empty($values)) {
            $value = json_encode($values);
        }
        return $value;
    }

    /**
     * 返回参数
     *
     * @param $channel_id
     * @param $key
     * @return array|mixed
     */
    public static function getByChannel($channel_id, $key) {
        $r = self::channelQuery($channel_id)
            ->andCondition('key', $key)
            ->columns(['value'])
            ->first();
        $v = [];
        if ($r) {
            $v = json_decode($r->value, true);
        }
        return $v;
    }

}