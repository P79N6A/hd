<?php

class LotteryWinningsController extends \BackendBaseController {

    /**
     * Index action
     */
    public function indexAction() {
        if(Request::get('export')) {
            $this->exportWin();
        }
        else if(Request::get('exportbrand')) {
            $this->exportBrandWin();
        } else {
            $data = LotteryWinnings::findAll();
            $lottery_group_id = Request::get('lottery_group_id');
            $lottery_group = LotteryGroup::findOne($lottery_group_id);
        }
        View::setVars(compact('data', 'lottery_group'));
    }

    private function exportWin() {
        $inputs = Request::getPost();
        if(empty($inputs)){
            $models = LotteryWinnings::findAll();
            $lottery = Lotteries::listLottery();
            $exportData = [];
            if(!empty($models)) {
                foreach($models as $v) {
                    $w = $v->lotteryWinnings;
                    $v = $v->lotteryContacts;
                    $v->prize_level = $w->prize_level;
                    $v->prize_name = $w->prize_name;
                    $v->lottery_id = $lottery[$w->lottery_id]['name'];
                    $v->is_real = $v->prize_is_real? Lang::_('real'): Lang::_('virtual');
                    $v->created_at = date('Y-m-d H:i:s', $v->created_at);
                    $v->address = $v->province.$v->city.$v->area.$v->address;
                    $v->status = LotteryContacts::getStatusName($v->status);
                    $exportData[] = $v;
                }
            }
            \F::createExcel(LotteryWinnings::$properties, $exportData, Lang::_('shake result'));
        }else{//选择导出暂时未支持

        }
        View::disable();
    }


    private function exportBrandWin() {
        error_reporting(0);
        $pagesize = intval(Request::get('pagesize'));
        $lottery_id = intval(Request::get('lottery_id'));
        $pagesize_win = 50;
        if($pagesize) {
            $pagesize_win = $pagesize;
        }
        $modelsd = LotteryWinnings::findAllBrand($lottery_id, $pagesize_win);
        $lottery = Lotteries::listLottery();
        $exportData = [];
        if(!empty($modelsd)) {
            foreach($modelsd->models as $v) {
                $w = $v->lotteryWinnings;
                $v = $v->lotteryContacts;
                $v->prize_level = $w->prize_level;
                $v->prize_name = $w->prize_name;
                $v->extra_value = $w->extra_value;
                $v->lottery_id = $lottery[$w->lottery_id]['name'];
                $v->is_real = $v->prize_is_real? Lang::_('real'): Lang::_('virtual');
                $v->created_at = date('Y-m-d H:i:s', $v->created_at);
                $v->address = $v->province.$v->city.$v->area.$v->address;
                $v->status = LotteryContacts::getStatusName($v->status);
                $exportData[] = $v;
            }
        }
        \F::createExcel(LotteryWinnings::$properties, $exportData, Lang::_('shake result'));
        View::disable();
    }

    /**
     * Add action
     */
    public function addAction() {
    }

    /**
     * Edit action
     */
    public function editAction() {
        $model = LotteryContacts::findFirst(Request::get('id', 'int'));
        if(!$model->status) {
            $this->alert(Lang::_('please wait for user filling info'),'danger');
            return true;
        }
        $messages = [];
        if(Request::isPost()) {
            $data = Request::getPost();
            $validator = LotteryContacts::makeValidator($data);
            if(!$validator->fails()) {
                $data['updated_at'] = time();
                $data['address_modify_admin'] = Session::get('user')->mobile;
                if(!$model->update($data)) {
                    foreach($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $this->saveRedis($model);
                    $messages[] = Lang::_('success');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $lottery = Lotteries::listLottery();
        View::setMainView('layouts/add');
        View::setVars(compact('model', 'messages', 'lottery'));
    }

    private function saveRedis($v) {
        $win = LotteryWinnings::findFirst($v->id);
        if($win) {
            LotteryContacts::saveRedisWinners($v->id, $v->lottery_group_id, $v->mobile, $v->name, $win->prize_name, $win->prize_level, $v->status);
        }
    }

    /**
     * Delete action
     */
    public function deleteAction() {
    }

}