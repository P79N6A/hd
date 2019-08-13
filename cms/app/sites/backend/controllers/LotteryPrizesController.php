<?php

class LotteryPrizesController extends \BackendBaseController {

    /**
     * Index action
     */
    public function indexAction() {
        $data = LotteryPrizes::findAll();
//        $lotteryChannel = LotteryChannels::listLotteryChannel();
//        $lottery = Lotteries::listLottery();
        View::setVars(compact('data','lotteryChannel','lottery'));
    }

    /**
     * Add action
     */
    public function addAction() {
        $model = new LotteryPrizes();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            if($data['goods_id']) {
                $goods = LotteryGoods::findFirst($data['goods_id']);
                $data['is_real'] = $goods->is_real;
            }
            if($data['belong_to'] && $data['type'] == 'lottery_channel') {
                $data['lottery_id'] = 0;
                $data['level'] = 0;
            }
            $data['channel_id'] = Session::get("user")->channel_id;
            $validator = LotteryPrizes::makeValidator($data);
            if (!$validator->fails()) {
                $prize = LotteryPrizes::findOneByLottery($data['belong_to'] , $data['type'] , $data['goods_id']);
                if($prize){
                    $messages[] = '此奖品已存在，请修改';
                }else {
                    $data['rest_number'] = $data['number'];
                    $data['created_at'] = $data['updated_at'] = time();
                    if (!$model->save($data)) {
                        foreach ($model->getMessages() as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                    } else {
                        $messages[] = Lang::_('success');
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $lottery_channel = LotteryChannels::findOne(Request::get('belong_to'));
        $lottery_goods = LotteryGoods::listGoods();
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages','lottery_channel','lottery_goods'));
    }

    /**
     * Edit action
     */
    public function editAction() {
        $model = LotteryPrizes::findFirst(Request::get('id', 'int'));
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            if($data['goods_id']) {
                $goods = LotteryGoods::findFirst($data['goods_id']);
                $data['is_real'] = $goods->is_real;
            }
            if($data['belong_to'] && $data['type'] == 'lottery_channel') {
                $data['lottery_id'] = 0;
                $data['level'] = 0;
            }
            $gap = $model->number-$data['number'];
            if($gap >0 && $gap <= $model->rest_number || $gap<=0) {
                $data['rest_number'] = $model->rest_number-$gap;
            }else{
                $messages[] = '剩余数量不足以减少';
            }
            $validator = LotteryPrizes::makeValidator($data);
            if (!$validator->fails() && empty($messages)) {
                $data['updated_at'] = time();
                if (!$model->update($data)) {
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    // 更新摇一摇缓存
                    LotteryPrizes::getByLottery($model->lottery_id);
                    $messages[] = Lang::_('success');
                }
            } else {
                foreach ($validator->messages()->all() as $key => $value){
                    $messages[] = $value;
                }
            }
        }
        $lottery_channel = LotteryChannels::findOne(Request::get('belong_to'));
        $lottery_goods = LotteryGoods::listGoods();
        View::setMainView('layouts/add');
        View::setVars(compact('model', 'messages','lottery_channel','lottery_goods'));
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        $model = LotteryPrizes::findFirst(Request::get('id', 'int'));
        $code = 200;
        if (empty($model)) {
            $msg = Lang::_('failed');
        } else {
            if (Lotteries::checkStart($model->lottery_id)) {
                $msg = Lang::_('Forbid Edit');
                $code = 400;
            } else {
                $model->delete();
                $msg = Lang::_('success');
            }
        }
        $this->_json([], $code, $msg);
    }

}