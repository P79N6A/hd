<?php
/**
 * Name: 积分流水
 * Author: zhanghaiquan
 * Date: 2016/01/14
 */
class CreditTransactionsController extends \BackendBaseController {

    public function indexAction() {
        $channel_id = Session::get("user")->channel_id;
        $data = CreditTransactions::getTransactionsByChannelId($channel_id);
        $listtype = CreditRules::listType();
        $listtradertype = CreditTransactions::listTraderType();
        View::setVars(compact('data', 'listtype', 'listtradertype'));
    }
}