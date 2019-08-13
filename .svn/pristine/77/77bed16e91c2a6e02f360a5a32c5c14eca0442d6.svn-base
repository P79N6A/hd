<?php

use GenialCloud\Auth\Signature;
/**
 * @RoutePrefix("/credit")
 */
class CreditController extends ApiBaseController {

    public function initialize() {
        $requestdata = $this->request->getQuery();
        if($requestdata['_url']=='/credit') {
            $hash = Request::getPost('hash');
            $type = (int)Request::getPost('type');
            $id = (int)Request::getPost('user_id');
            $result = array(
                'credits_option' => 0,
                'tips_option' => "请勿重复操作",
                'sign_in' => 0
              );

            $channel_id = $this->channel_id;
            $rule = CreditRules::getCreditRulesByType(CreditRules::RULE_TYPE_REGISTER, Users::CHANNEL_ID_OF_ZGLTV);
            if(-1==$id) {
                $result['tips_option'] = "只有手机注册用户，才可以获得积分哦，直接注册获得".$rule['base_credit']."个积分。";
                $this->_jsonzgltv($result);
            }
            if(!$id) {
                $result['tips_option'] = "您未登录!";
                if(CreditRules::RULE_TYPE_REGISTER == $type) $result['tips_option'] = "只有手机注册用户，才可以获得积分哦，直接注册获得".$rule['base_credit']."个积分。";
                if(CreditRules::RULE_TYPE_SHARE == $type) $result['tips_option'] = "温馨提示：登录后再分享可获得积分哦。";
                $this->_jsonzgltv($result);
            }
            if(!isset($hash)||$this->checkHashExist($hash)) {
                switch ($type) {
                case CreditRules::RULE_TYPE_SIGN: $result['sign_in'] = 200; $result['tips_option'] = "已签到"; break;
                case CreditRules::RULE_TYPE_SHARE: $result['tips_option'] = "分享重复内容，不重复获得积分哦。"; break;
                case CreditRules::RULE_TYPE_COMMENT: $result['tips_option'] = "重复评论，不重复获得积分哦。"; break;
                }
                $this->_jsonzgltv($result);
            }
        }
        if($requestdata['_url']!='/credit/orderprocess'
          &&$requestdata['_url']!='/credit/orderresult'
          ) {
            $this->checkSignature();
        }
    }

    /**
     * @Get("/{id:-?[0-9]+}")
     * @param int $id
     * @return json
     */
    public function queryCreditAction($id) {
        $type =  (int)Request::getQuery('type');
        $channel_id = $this->channel_id;
        if(!$id||-1==$id) {
            $result = $this->getTipsByRule(1, $channel_id, 0);
            $this->_jsonzgltv(array_merge($result, array('credits' => 0)));
        }
        if(!$channel_id) $this->_jsonzgltv([], 1, 'Not Found');
        if(Users::CHANNEL_ID_OF_ZGLTV==$channel_id) {
            $user = Users::getUserByTvuid($id);
        }
        else {
            $user = Users::getOne($id);
        }
        if($user) {
            $result = $this->getTipsByRule($type, $channel_id, $user['id']);
            $credits = array('credits' => (int)$user['credits']);
            $this->_jsonzgltv(array_merge($result, $credits));
        }
        else {
            $this->_jsonzgltv([], 1, 'Not Found');
        }
    }

    /**
     * @Get("/rules")
     * @return json
     */
    public function rulesAction() {
        $id = (int)Request::getQuery('user_id');
        $client_type =  Request::getQuery('client_type');
        $app_version = Request::getQuery('app_version');
        $channel_id = $this->channel_id;
        if(!$channel_id) $this->_jsonzgltv([], 1, 'Not Found');
        $data = false;
        if(!$data) {
            $result = [];
            $rulesdata = CreditRules::getRulesByChannelId($channel_id, $client_type, $app_version);
            if($id>0) {
                if(Users::CHANNEL_ID_OF_ZGLTV==$channel_id) {
                    $user = Users::getUserByTvuid($id);
                }
                else {
                    $user = Users::getOne($id);
                    $user= $user->toArray();
                }
                $user_id = ($user)?$user['id']:0;
            }
            else {
                $user_id = 0;
            }
            if($rulesdata) {
                $resultwithgroup = array();
                $resultwithgroup['new'] = array();
                $resultwithgroup['daily'] = array();
                foreach($rulesdata as $rule) {
                    if($rule['rule_group']) {
                       $resultwithgroup['daily'][] = $this->checkCreditByUid($rule, $user_id);
                    }
                    else {
                       $resultwithgroup['new'][] = $this->checkCreditByUid($rule, $user_id);
                    }
                }
                if(count($resultwithgroup['new'])) {
                    $result[] = array('title'=>"新手任务", 'list'=>$resultwithgroup['new']);
                }
                if(count($resultwithgroup['daily'])) {
                    $result[] = array('title'=>"日常任务", 'list'=>$resultwithgroup['daily']);
                }
            }
        }
        else {
            $result = $data;
        }
        if(count($result)) {
            $this->_jsonzgltv($result, 0, "success", true);
        }
        else {
            $this->_jsonzgltv([], 1, 'Not Found');
        }
    }

    /**
     * @Get("/mallurl")
     * @param int $id
     * @return json
     */
    public function mallUrlAction() {
        $id = (int)Request::getQuery('user_id');
        $channel_id = $this->channel_id;
        if(!$channel_id) $this->_jsonzgltv([], 1, 'Not Found');
        if($id>0) {
            if(Users::CHANNEL_ID_OF_ZGLTV==$channel_id) {
                $user = Users::getUserByTvuid($id);
            }
            else {
                $user = Users::getOne($id);
            }
            $user_id = ($user)?$user['id']:0;
            $credits = ($user)?$user['credits']:0;
        }
        else {
            $user_id = 0;
            $credits = 0;
        }
        $creditmall = new CreditByMall();
        $url = $creditmall->getAccessEntry($user_id, $credits);
        $result = array(
            'url' =>$url
            );
        $this->_jsonzgltv($result);
    }

    /**
     * @Post("/malldetailurl")
     * @param int $id
     * @return json
     */
    public function mallDetailUrlAction() {
        $id = Request::getPost('user_id');
        $dbredirect = Request::getPost('url');

        $channel_id = $this->channel_id;
        if(!$channel_id) $this->_jsonzgltv([], 1, 'Not Found');
        if($id>0) {
            if(Users::CHANNEL_ID_OF_ZGLTV==$channel_id) {
                $user = Users::getUserByTvuid($id);
            }
            else {
                $user = Users::getOne($id);
            }
            $user_id = ($user)?$user['id']:0;
            $credits = ($user)?$user['credits']:0;
        }
        else {
            $user_id = 0;
            $credits = 0;
        }
        $creditmall = new CreditByMall();
        $url = $creditmall->getAccessEntry($user_id, $credits, $dbredirect);
        $result = array(
            'url' =>$url
        );
        $this->_jsonzgltv($result);
    }

    /**
     * @Post("/")
     */
    public function indexAction() {
        $id = Request::getPost('user_id');
        $type =  (int)Request::getPost('type');
        $hash = Request::getPost('hash');
        $channel_id = $this->channel_id;
        if(!$channel_id) $this->_jsonzgltv([], 1, 'Not Found');
        if(Users::CHANNEL_ID_OF_ZGLTV==$channel_id) {
            $user = Users::getUserByTvuid($id);
        }
        else {
            $user = Users::getOne($id);
        }
        $rule = CreditRules::getCreditRulesByType($type, $user['channel_id']);
        if($rule) {
            $this->setHash($hash);//设置防刷hash
            $result = CreditTransactions::commonTransaction($user['id'], $type, $user['channel_id'], CreditTransactions::TRADER_TYPE_API);            
            $this->_jsonzgltv($result);
        }
        else {
            $this->_jsonzgltv([], 1, 'Not Found');
        }
    }


    /**
     * @Get("/orderprocess")
     */
    public function OrderProcessAction() {
        $request_array = $this->request->getQuery();
        if(!isset($request_array["appKey"])) $this->_json([], 404, 'Not Found');
        $creditmall = new CreditByMall();
        $result = $creditmall->orderProcess($request_array);
        echo json_encode($result);
        exit;
    }

    /**
     * @Get("/orderresult")
     */
    public function OrderResultAction() {
        $request_array = $this->request->getQuery();
        if(!isset($request_array["appKey"])) $this->_json([], 404, 'Not Found');
        $creditmall = new CreditByMall();
        $result = $creditmall->orderResult($request_array);
        exit;
    }

    private function getTipsByRule($rule_type, $channel_id, $uid) {
        if(CreditRules::RULE_TYPE_TOTAL_CREDIT == $rule_type||!$rule_type) {
            $result = array(
                  'credits_option' => 0,
                  'tips_option' => "总积分",
                  'sign_in' => 0
                );
        }
        else {
            $rule = CreditRules::getCreditRulesByType($rule_type, $channel_id);
            if($rule) {
                $creditvalue = $rule['base_credit'];
                $tips_option = $rule['name'];
                $sign_in = 0;
                if(CreditRules::RULE_TYPE_SIGN == $rule_type) {
                    $signdata = CreditBySign::getSignData($uid, $channel_id);
                    $sign_in = 100;
                    $tips_option = "您今天还没有签到，签到会送积分哦。";
                    if(date("Y-n-d", $signdata['lastsign']) == date("Y-n-d", time())) {//表示今天已签到
                        $sign_in = 200 + $signdata['keepsigncount'];
                        $tips_option = "已签到，继续保持，间断签到会重新开始哦。";
                        $creditvalue = $creditvalue + CreditRules::getStepCredit($rule['range_step'], $rule['range_max'], $signdata['keepsigncount']+1);
                    }
                    else if(date("Y-n-d", $signdata['lastsign']) == date("Y-n-d", time() - 86400)) {//表示昨天签到了
                        if($signdata['keepsigncount'] > $rule['range_max']) {
                            $tips_option = "您今天还没有签到，间断签到会重新开始哦。";
                        }
                        else {
                            $tips_option = "您今天还没有签到，连续签到会送更多积分哦。";
                        }
                        $creditvalue = $creditvalue + CreditRules::getStepCredit($rule['range_step'], $rule['range_max'], $signdata['keepsigncount']+1);
                    }
                }
                else if(CreditRules::RULE_TYPE_REGISTER == $rule_type) {
                    //$tips_option = "您还没有注册，注册会送积分哦。";
                    $lasttransaction = CreditTransactions::getLastTransactionByType($user_id, $rule['type'], $rule['channel_id']);
                    if($lasttransaction) {
                        $tips_option = "已注册";
                    }
                }
                $result = array(
                      'credits_option' => (int)$creditvalue,
                      'tips_option' => $tips_option,
                      'sign_in' => $sign_in,//签到类型 100：未签到, 200：已签到, 201-205：连续多少天签到
                    );
            }
            else {
                $result = array();
            }
        }
        return $result;
    }

    private function checkCreditByUid($rule, $user_id) {
        $result = array(
              'name' => $rule['name'],
              'desc' => $rule['ruledesc'],
              'type' => $rule['type'],
              'status' => 0,
              'tips_option' => "去获得",
            );
        if($user_id) {
            //$lasttransaction = CreditTransactions::getLastTransactionByType($user_id, $rule['type'], $rule['channel_id']);
            switch ($rule['type']) {
            case CreditRules::RULE_TYPE_REGISTER:
                $result['tips_option'] = "已注册";
                $result['status'] = 1;
                break;
            case CreditRules::RULE_TYPE_SIGN:
                $signdata = CreditBySign::getSignData($user_id, $rule['channel_id']);
                if(date("Y-n-d", $signdata['lastsign'])==date("Y-n-d", time())) {
                    $result['tips_option'] = "已签到";
                    $result['status'] = 1;
                }
                break;
            default:
                if(!CreditTransactions::limitCheck($user_id, $rule['type'], $rule['channel_id'])) {
                    $result['tips_option'] = "已领取";
                    $result['status'] = 1;
                }
            }

        }
        return $result;
    }

    protected function _jsonzgltv($data, $code = 0, $msg = "success", $aleradyarray=false) {
        header('Content-type: application/json');		
		$listdata = [];
		if($data!=[]) $listdata[] = $data;
        if($aleradyarray) $listdata = $data;
        echo json_encode([
                'alertMessage' => "数据获取成功",
                'state' => $code,
                'message' => $msg,
                'content' => ['list'=>$listdata],
            ]);
        exit;
    }

    /**
     * 积分提交防刷
     * @param $hash
     * @return string
     */
    private static function checkHashExist($hash) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $key = $memprefix."credit_post_hash_".$hash."_stamp_".$today_timestamp;
        return MemcacheIO::get($key);
    }
    /**
     * 积分提交防刷
     * @param $hash
     * @return string
     */
    private static function setHash($hash) {
        $memprefix = app_site()->memprefix;
        $today_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $key = $memprefix."credit_post_hash_".$hash."_stamp_".$today_timestamp;
        return MemcacheIO::set($key, true, 86400);
    }


}