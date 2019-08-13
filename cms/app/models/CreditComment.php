<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CreditComment extends Model {
    const COMMENT_TYPE_LIVE = 1;
    /**< 点播 */
    const COMMENT_TYPE_VIDEO = 2;

    /**< 直播 */

    public function getSource() {
        return 'credit_comment';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'user_id', 'video_id', 'comment_type', 'created_at', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'video_id', 'comment_type', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'video_id', 'comment_type', 'created_at', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'video_id' => Column::TYPE_INTEGER,
                'comment_type' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'video_id', 'comment_type', 'created_at', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'video_id' => Column::BIND_PARAM_INT,
                'comment_type' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
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

    public function createCreditComment($data) {
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
        $video_id = (int)Request::getPost('video_id');
        $comment_type = (int)Request::getPost('comment_type');
        if (isset($video_id) && $comment_type > 0) {
            //if(!CreditComment::alreadyCommented($uid, $channel_id, $comment_type, $video_id)) {
            $rule = CreditRules::getCreditRulesByType(CreditRules::RULE_TYPE_COMMENT, $channel_id);
            if (CreditTransactions::limitCheck($uid, CreditRules::RULE_TYPE_COMMENT, $channel_id)) {
                $creditvalue = $rule['base_credit'];
                $reasonofcredit = "评论成功，恭喜您获得" . $creditvalue . "个积分。";
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
                    //CreditComment::setCommented($uid, $channel_id, $comment_type, $video_id);
                    Users::changeCredit($uid, $creditvalue);
                    CreditTransactions::setTodayCredit($uid, CreditRules::RULE_TYPE_COMMENT, $channel_id, $creditvalue);
                }

            } else {
                $result = array(
                    'credits_option' => 0,
                    'tips_option' => "今日已获得" . $rule['day_limit'] . "个积分，明日再来吧",
                    'sign_in' => 0
                );
            }
            //}
            //else {
            //    $result = array(
            //        'credits_option'=>0,
            //        'tips_option'=>"重复评论不获得积分",
            //        'sign_in'=>0
            //    );
            //}
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
     * 查询曾经评论过
     * @param $uid
     * @return string
     */
    private static function alreadyCommented($uid, $channel_id, $comment_type, $video_id) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $key = $memprefix . "comment_" . $channel_id . "_" . $uid . "_" . $comment_type . "_" . $video_id . "_stamp_" . $today_timestamp;
        return RedisIO::get($key);
    }

    /**
     * 标记为已经评论
     * @param $uid
     * @return string
     */
    private static function setCommented($uid, $channel_id, $comment_type, $video_id) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $data = array(
            'channel_id' => $channel_id,
            'user_id' => $uid,
            'video_id' => $video_id,
            'comment_type' => $comment_type
        );
        $comment = new CreditComment();
        $comment->createCreditComment($data);
        $key = $memprefix . "comment_" . $channel_id . "_" . $uid . "_" . $comment_type . "_" . $video_id . "_stamp_" . $today_timestamp;
        return RedisIO::set($key, true);
    }

}