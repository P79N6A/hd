<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;


class CreditUrlShare extends Model {
    const PLATFORM_TYPE_SinaWeibo = 1;
    /**< 新浪微博 */
    const PLATFORM_TYPE_TencentWeibo = 2;
    /**< 腾讯微博 */
    const PLATFORM_TYPE_QQSpace = 6;
    /**< QQ空间 */
    const PLATFORM_TYPE_WeixiSession = 22;
    /**< 微信好友 */
    const PLATFORM_TYPE_WeixiTimeline = 23;
    /**< 微信朋友圈 */
    const PLATFORM_TYPE_QQ = 24;
    /**< QQ好友 */
    const PLATFORM_TYPE_Other = -1;
    /**< > */
    const PLATFORM_TYPE_Any = 99;
    /**< 任意平台 */

    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $typeMaps = [
        self::PLATFORM_TYPE_WeixiSession => '微信好友',
        self::PLATFORM_TYPE_WeixiTimeline => '微信朋友圈',
        self::PLATFORM_TYPE_SinaWeibo => '新浪微博',
        self::PLATFORM_TYPE_TencentWeibo => '腾讯微博',
        self::PLATFORM_TYPE_QQSpace => 'QQ空间',
        self::PLATFORM_TYPE_QQ => 'QQ好友',
        self::PLATFORM_TYPE_Other => '',
        self::PLATFORM_TYPE_Any => '任意平台',
    ];

    public function getSource() {
        return 'credit_url_share';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'user_id', 'url', 'platform_type', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'url', 'platform_type', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'url', 'platform_type', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'url' => Column::TYPE_VARCHAR,
                'platform_type' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'platform_type', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'url' => Column::BIND_PARAM_STR,
                'platform_type' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
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

    public function createCreditUrlShare($data) {
        $this->assign($data);
        $this->created_at = time();
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


    public static function earnCredit($uid, $channel_id, $trader) {//赚取积分
        $url = Request::getPost('url');
        $platform_type = (int)Request::getPost('platform_type');
        if (isset($url) && $platform_type > 0) {
            if (!CreditUrlShare::alreadyShared($uid, $channel_id, $url, $platform_type)) {
                $rule = CreditRules::getCreditRulesByType(CreditRules::RULE_TYPE_SHARE, $channel_id);
                if (CreditTransactions::limitCheck($uid, CreditRules::RULE_TYPE_SHARE, $channel_id)) {
                    $creditvalue = $rule['base_credit'];

                    //2017/8/7针对新浪分享积分翻倍处理，临时方法
                    if ($platform_type == 1) {
                        $creditvalue = $creditvalue*2;
                    }

                    $reasonofcredit = $rule['name'];
                    $reasonofcredit = "分享成功，恭喜您获得" . $creditvalue . "个积分。";
                    $data = array(
                        'channel_id' => $channel_id,
                        'user_id' => $uid,
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
                        $result = array(
                            'credits_option' => $creditvalue,
                            'tips_option' => $reasonofcredit,
                            'sign_in' => 0
                        );
                        CreditUrlShare::setShared($uid, $channel_id, $url, $platform_type);
                        Users::changeCredit($uid, $creditvalue);
                        CreditTransactions::setTodayCredit($uid, CreditRules::RULE_TYPE_SHARE, $channel_id, $creditvalue);
                    }
                } else {
                    $result = array(
                        'credits_option' => 0,
                        'tips_option' => "今日已获得" . $rule['day_limit'] . "个积分，明日再来吧",
                        'sign_in' => 0
                    );
                }
            } else {
                $result = array(
                    'credits_option' => 0,
                    'tips_option' => "重复分享不获得积分",
                    'sign_in' => 0
                );
            }
        } else {
            $result = array(
                'credits_option' => 0,
                'tips_option' => "参数错误",
                'sign_in' => 0
            );

        }
        return $result;
    }

    /**
     * 查询曾经分享过
     * @param $uid
     * @return string
     */
    private static function alreadyShared($uid, $channel_id, $url, $platform_type) {
        $memprefix = app_site()->memprefix;
        $key = $memprefix . "url_share_" . $channel_id . "_" . $uid . "_" . $platform_type . "_" . md5($url);
        return RedisIO::get($key);
    }

    /**
     * 标记为已经分享
     * @param $uid
     * @return string
     */
    private static function setShared($uid, $channel_id, $url, $platform_type) {
        $memprefix = app_site()->memprefix;
        $data = array(
            'channel_id' => $channel_id,
            'user_id' => $uid,
            'url' => $url,
            'platform_type' => $platform_type
        );
        $urlshare = new CreditUrlShare();
        $urlshare->createCreditUrlShare($data);
        $key = $memprefix . "url_share_" . $channel_id . "_" . $uid . "_" . $platform_type . "_" . md5($url);
        return RedisIO::set($key, true);
    }
}