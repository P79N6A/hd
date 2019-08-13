<?php

/*
 *  商城积分扣除
 */

class CreditByMall {
    public $appkey = "tVWbqPehQMC7LpyrggAKPzwwxeA";
    public $appsecret = "37qW78Cak5uHwdBAsS19qBbcDzwP";

    /**
     * 获取商城免登录URI
     * @param $uid
     * @return string
     */
    public function getAccessEntry($uid, $credits, $dbredirect = '') {
        $uidduiba = ($uid) ? $uid : "not_login";
        $url = $this->buildCreditAutoLoginRequest($this->appkey, $this->appsecret, $uidduiba, $credits, $dbredirect);
        return $url;
    }

    /**
     * 订单处理
     * @param $uid
     * @return string
     */
    public function orderProcess($request_array) {
        $ret = $this->parseCreditConsume($this->appkey, $this->appsecret, $request_array);
        $user = Users::getOne($ret['uid']);
        $credit_transaction = CreditTransactions::findFirst(array(
            'orderNum=:orderNum:',
            'bind' => array('orderNum' => $ret['orderNum'])
        ));
        if ($credit_transaction) {//该订单已处理
            $result = array("status" => "ok", "errorMessage" => "交易成功", "bizId" => $credit_transaction->id, "credits" => $user->credits);
        } else {
            if ($user) {
                if ($user->credits >= $ret['credits']) {
                    $credit_balance = $user->credits - $ret['credits'];
                    $creditvalue = -1 * $ret['credits'];
                    $reasonofcredit = $ret['description'];
                    $data = array(
                        'channel_id' => $user->channel_id,
                        'user_id' => $ret['uid'],
                        'type' => CreditRules::RULE_TYPE_MALL,
                        'credits' => $creditvalue,
                        'trader' => CreditTransactions::TRADER_TYPE_DUIBA,
                        'orderNum' => $ret['orderNum'],
                        'actualPrice' => $ret['actualPrice'],
                        'timestamp' => $ret['timestamp'],
                        'detail' => $reasonofcredit,
                        'status' => 0,
                    );
                    $credit_transaction = new CreditTransactions();
                    if (Users::changeCredit($ret['uid'], $creditvalue) && $credit_transaction->createTransaction($data)) {
                        $result = array("status" => "ok", "errorMessage" => "交易成功", "bizId" => $credit_transaction->id, "credits" => $credit_balance);
                    } else {
                        $result = array("status" => "fail", "errorMessage" => "系统错误", "credits" => 0);
                    }
                } else {
                    $result = array("status" => "fail", "errorMessage" => "积分不足", "credits" => $user->credits);
                }
            } else {
                $result = array("status" => "fail", "errorMessage" => "用户不存在", "credits" => 0);
            }
        }
        return $result;
    }

    /**
     * 兑换结果处理
     * @param $uid
     * @return string
     */
    public function orderResult($request_array) {
        $ret = $this->parseCreditNotify($this->appkey, $this->appsecret, $request_array);
        $credit_transaction = CreditTransactions::findFirst(array(
            'id=:id:',
            'bind' => array('id' => $ret['bizId'])
        ));
        if ($credit_transaction && $credit_transaction->status == 0) {
            if ($ret['success']) {
                $credit_transaction->updateTransaction(array('status' => 1));//确认交易成功，将交易设置为成功
            } else {
                Users::changeCredit($credit_transaction->user_id, -1 * $credit_transaction->credits);//返还用户被扣的积分
            }
        }
        echo "ok";
        die();
    }

    /*
    *  md5签名，$array中务必包含 appSecret
    */
    private function sign($array) {
        ksort($array);
        $string = "";
        while (list($key, $val) = each($array)) {
            $string = $string . $val;
        }
        return md5($string);
    }

    /*
    *  签名验证,通过签名验证的才能认为是合法的请求
    */
    private function signVerify($appSecret, $array) {
        $newarray = array();
        $newarray["appSecret"] = $appSecret;
        reset($array);
        while (list($key, $val) = each($array)) {
            if ($key != "sign") {
                $newarray[$key] = $val;
            }
        }
        $sign = $this->sign($newarray);
        if ($sign == $array["sign"]) {
            return true;
        }
        return false;
    }

    /*
    *  生成自动登录地址
    *  通过此方法生成的地址，可以让用户免登录，进入积分兑换商城
    */
    private function buildCreditAutoLoginRequest($appKey, $appSecret, $uid, $credits, $dbredirect = "") {
        $url = "http://www.duiba.com.cn/autoLogin/autologin?";
        $timestamp = time() * 1000 . "";
        $array = array("uid" => $uid, "credits" => $credits, "appSecret" => $appSecret, "appKey" => $appKey, "timestamp" => $timestamp);
        if ($dbredirect) {
            $array['redirect'] = $dbredirect;
        }
        $sign = $this->sign($array);
        $url = $url . "uid=" . $uid . "&credits=" . $credits . "&appKey=" . $appKey . "&sign=" . $sign . "&timestamp=" . $timestamp;
        if ($dbredirect) {
            $url .= '&redirect=' . urlencode($dbredirect);
        }
        return $url;
    }

    /*
    *  生成订单查询请求地址
    *  orderNum 和 bizId 二选一，不填的项目请使用空字符串
    */
    private function buildCreditOrderStatusRequest($appKey, $appSecret, $orderNum, $bizId) {
        $url = "http://www.duiba.com.cn/status/orderStatus?";
        $timestamp = time() * 1000 . "";
        $array = array("orderNum" => $orderNum, "bizId" => $bizId, "appKey" => $appKey, "appSecret" => $appSecret, "timestamp" => $timestamp);
        $sign = sign($array);
        $url = $url . "orderNum=" . $orderNum . "&bizId=" . $bizId . "&appKey=" . $appKey . "&timestamp=" . $timestamp . "&sign=" . $sign;
        return $url;
    }

    /*
    *  兑换订单审核请求
    *  有些兑换请求可能需要进行审核，开发者可以通过此API接口来进行批量审核，也可以通过兑吧后台界面来进行审核处理
    */
    private function buildCreditAuditRequest($appKey, $appSecret, $passOrderNums, $rejectOrderNums) {
        $url = "http://www.duiba.com.cn/audit/apiAudit?";
        $timestamp = time() * 1000 . "";
        $array = array("appKey" => $appKey, "appSecret" => $appSecret, "timestamp" => $timestamp);
        if ($passOrderNums != null && !empty($passOrderNums)) {
            $string = null;
            while (list($key, $val) = each($passOrderNums)) {
                if ($string == null) {
                    $string = $val;
                } else {
                    $string = $string . "," . $val;
                }
            }
            $array["passOrderNums"] = $string;
        }
        if ($rejectOrderNums != null && !empty($rejectOrderNums)) {
            $string = null;
            while (list($key, $val) = each($rejectOrderNums)) {
                if ($string == null) {
                    $string = $val;
                } else {
                    $string = $string . "," . $val;
                }
            }
            $array["rejectOrderNums"] = $string;
        }
        $sign = sign($array);
        $url = $url . "appKey=" . $appKey . "&passOrderNums=" . $array["passOrderNums"] . "&rejectOrderNums=" . $array["rejectOrderNums"] . "&sign=" . $sign . "&timestamp=" . $timestamp;
        return $url;
    }

    /*
    *  积分消耗请求的解析方法
    *  当用户进行兑换时，兑吧会发起积分扣除请求，开发者收到请求后，可以通过此方法进行签名验证与解析，然后返回相应的格式
    *  返回格式为：
    *  {"status":"ok","message":"查询成功","data":{"bizId":"9381"}} 或者
    *  {"status":"fail","message":"","errorMessage":"余额不足"}
    */
    private function parseCreditConsume($appKey, $appSecret, $request_array) {
        if ($request_array["appKey"] != $appKey) {
            throw new Exception("appKey not match");
        }
        if ($request_array["timestamp"] == null) {
            throw new Exception("timestamp can't be null");
        }
        unset($request_array['_url']);
        $verify = $this->signVerify($appSecret, $request_array);
        if (!$verify) {
            throw new Exception("sign verify fail");
        }
        $ret = array("appKey" => $request_array["appKey"], "uid" => $request_array["uid"], "credits" => $request_array["credits"], "timestamp" => $request_array["timestamp"], "description" => $request_array["description"], "orderNum" => $request_array["orderNum"], "actualPrice" => $request_array["actualPrice"]);
        return $ret;
    }

    /*
    *  兑换订单的结果通知请求的解析方法
    *  当兑换订单成功时，兑吧会发送请求通知开发者，兑换订单的结果为成功或者失败，如果为失败，开发者需要将积分返还给用户
    */
    private function parseCreditNotify($appKey, $appSecret, $request_array) {
        if ($request_array["appKey"] != $appKey) {
            throw new Exception("appKey not match");
        }
        if ($request_array["timestamp"] == null) {
            throw new Exception("timestamp can't be null");
        }
        unset($request_array['_url']);
        $verify = $this->signVerify($appSecret, $request_array);
        if (!$verify) {
            throw new Exception("sign verify fail");
        }
        $ret = array("success" => $request_array["success"], "errorMessage" => $request_array["errorMessage"], "bizId" => $request_array["bizId"]);
        return $ret;
    }

}