<?php

/*
 *  签到
 */

class CreditBySign {

    public static function earnCredit($uid, $channel_id, $trader) {//赚取积分
        if (CreditTransactions::limitCheck($uid, CreditRules::RULE_TYPE_SIGN, $channel_id)) {
            $signrule = CreditRules::getCreditRulesByType(CreditRules::RULE_TYPE_SIGN, $channel_id);
            $signdata = CreditBySign::getSignData($uid, $channel_id);
            $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
            $sign_in = 0;
            if ($signdata['lastsign'] == $today_timestamp) {
                $sign_in = (($signdata['keepsigncount'] > 5) ? 5 : $signdata['keepsigncount']) + 200;
                $result = array(
                    'credits_option' => 0,
                    'tips_option' => "已签到",
                    'sign_in' => $sign_in
                );
            } else {
                if ($signdata['keepsigncount'] > 0 && date("Y-n-d", $signdata['lastsign']) == date("Y-n-d", time() - 86400)) {//判断昨天是否登录
                    $signdata['keepsigncount'] = $signdata['keepsigncount'] + 1;
                } else {
                    $signdata['keepsigncount'] = 1;
                }

                $creditvalue = $signrule['base_credit'] + CreditRules::getStepCredit($signrule['range_step'], $signrule['range_max'], $signdata['keepsigncount']);

                if ($signdata['keepsigncount'] > $signrule['range_max']) {
                    $reasonofcredit = "恭喜您成功签到，获得" . $creditvalue . "个积分，继续保持，间断签到会重新开始哦。";
                    $sign_in = 200 + (int)$signrule['range_max'] + 1;
                } else {
                    $reasonofcredit = "恭喜您成功签到，获得" . $creditvalue . "个积分，明天继续签到会获得更多积分哦。";
                    $sign_in = 200 + $signdata['keepsigncount'];
                }
                $data = array(
                    'channel_id' => $channel_id,
                    'user_id' => $uid,
                    'type' => $signrule['type'],
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
                    CreditBySign::setSignData($uid, $signdata['keepsigncount']);
                    Users::changeCredit($uid, $creditvalue);
                    CreditTransactions::setTodayCredit($uid, CreditRules::RULE_TYPE_SIGN, $channel_id, $creditvalue);
                    $result = array(
                        'credits_option' => $creditvalue,
                        'tips_option' => $reasonofcredit,
                        'sign_in' => $sign_in
                    );
                }
            }
        } else {
            $sign_in = (($signdata['keepsigncount'] > 5) ? 5 : $signdata['keepsigncount']) + 200;
            $result = array(
                'credits_option' => 0,
                'tips_option' => "已签到",
                'sign_in' => $sign_in
            );
        }
        return $result;
    }

    /**
     * 获取签到数据 from RedisIO
     * @param $uid
     * @return string
     */
    public static function getSignData($uid, $channel_id) {
        $memprefix = app_site()->memprefix;
        $key = $memprefix . "credit_sign_" . $uid;
        $data = RedisIO::get($key);
        if ($data) {
            $signdataobj = json_decode($data);
            $signdata = array(
                'lastsign' => $signdataobj->lastsign,
                'keepsigncount' => $signdataobj->keepsigncount
            );
            return $signdata;
        } else {
            $signdata = array(
                'lastsign' => 0,
                'keepsigncount' => 0,
            );
        }
        return $signdata;
    }

    /**
     * 设置签到数据 to RedisIO
     * @param $uid
     * @return string
     */
    private static function setSignData($uid, $keepsigncount) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $data = array(
            'lastsign' => $today_timestamp,
            'keepsigncount' => $keepsigncount,
        );
        $key = $memprefix . "credit_sign_" . $uid;
        RedisIO::set($key, json_encode($data));
        return true;
    }

}