<?php

class LotteriesController extends \BackendBaseController {

    /**
     * Index action
     */
    public function indexAction() {
        if (Request::get('export')) {
            $this->exportLotteries();
        } else {
            $user = Auth::user();
            $group_id = Request::get('group_id');
            $lottery_channel_id = Request::get('id');
            $data = Lotteries::getLotteryByChannelAndGroup($user->channel_id , $group_id , $lottery_channel_id );
            $lotteryChannel = LotteryChannels::listLotteryChannel();
            $lotteryGroup = LotteryGroup::listGroups();
            View::setVars(compact('data','lotteryChannel','lotteryGroup'));
        }
    }
    
    private function exportLotteries(){
        set_time_limit(0);
        $models = Lotteries::find();
        #$lottery = Lotteries::listLottery();
        $channel = LotteryChannels::listLotteryChannel();
        $exportData = [];
        if(!empty($models)) {
            foreach($models as $v) {
                $v->channel_name = $channel[$v->lottery_channel_id]['name'];
                $v->open_time = date('Y-m-d H:i:s', $v->open_time);
                $v->close_time = date('Y-m-d H:i:s', $v->close_time);
                $v->people = Lotteries::lotteryCount($v->id) * 100 ? : 0 ;
                $exportData[] = $v;
            }
        }
        \F::createExcel([
            'id' => Lang::_('activity id'),
            'name' => Lang::_('activity name'),
            'lottery_channel_id' => Lang::_('lottery channel id'),
            'channel_name' => Lang::_('channel name'),
            'open_time' => Lang::_('open time'),
            'close_time' => Lang::_('close time'),
            'people' => Lang::_('people')
                ], $exportData, Lang::_('activity statistics'));
        View::disable();
    }

    /**
     * Add action
     */
    public function addAction() {
        $model = new Lotteries();
        $lottery_channel_id = Request::get('lottery_channel_id');
        $group_id = Request::get('group_id');
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id']= Session::get("user")->channel_id;
            $validator = Lotteries::makeValidator($data);
            if (!$validator->fails()) {
                $data['open_time'] = strtotime($data['open_time']);
                $data['close_time'] = strtotime($data['close_time']);
                $data['created_at'] = $data['updated_at'] = time();
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    Lotteries::getById($model->id);
                    if ($data['is_verify']) {
                        $this->saveVerifyRedis($model->id, $data['is_verify']);
                    }
                    $messages[] = Lang::_('success');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $lotteryChannel = LotteryChannels::findOne($lottery_channel_id);
        $lottery_group = LotteryGroup::findOne($group_id);
        View::setMainView('layouts/add');
        View::setVars(compact('model','messages','lotteryChannel','lottery_group'));
    }

    /**
     * Edit action
     */
    public function editAction() {
        $model = Lotteries::findFirst(Request::get('id', 'int'));
        $lottery_channel_id = Request::get('lottery_channel_id');
        $group_id = Request::get('group_id');
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = Lotteries::makeValidator($data);
            if (!$validator->fails()) {
                $data['updated_at'] = time();
                $data['open_time'] = strtotime($data['open_time']);
                $data['close_time'] = strtotime($data['close_time']);
                if (!$model->update($data)) {
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    Lotteries::clearMemById($model->id);
                    if ($data['is_verify']) {
                        $this->saveVerifyRedis($model->id, $data['is_verify']);
                    }
                    $messages[] = Lang::_('success');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $captcha_verify = RedisIO::get(self::LOTTERY_VERIFY . $model->id );
        $lotteryPrize = LotteryPrizes::getAllByLottery($model->id);
        $lotteryChannel = LotteryChannels::findOne($lottery_channel_id);
        $lottery_group = LotteryGroup::findOne($group_id);
        View::setMainView('layouts/add');
        View::setVars(compact('model', 'messages', 'lotteryChannel','lotteryPrize','lottery_group','captcha_verify'));
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        $id = Request::get('id', 'int');
        $model = Lotteries::findFirst($id);
        $code = 200;
        if ($model) {
            $lotteryPrize = LotteryPrizes::getAllByLottery($model->id);
            if(!empty($lotteryPrize->toArray())){
                $msg = '该场次下存在奖品不能删除';
                $code = 400;
            }elseif ($model->open_time <= time() && $model->close_time >= time()){
                $msg = '正在活动时间内，无法删除';
                $code = 400;
            }else {
                $model->delete();
                $msg = Lang::_('success');
            }
        }else{
            $msg = '场次不存在';
            $code = 400;
        }
        $this->_json([], $code, $msg);
    }

    public function allotAction() {
        if(Request::isPost()){
            $input = Request::getPost();
            $id = Request::getQuery('id');
            $lottery_channel_id = Request::getQuery('lottery_channel_id');
            $messages = '';
            if(empty($input) || !is_array($input['choose_num']) || !is_array($input['choose_Level'])){
                $this->_json([], '2001', '提交内容为空');
            }
            foreach ($input['choose_num'] as $key =>$value){
                if(!isset($input['choose_num'][$key]) || $value=='' ){
                    continue;
                }
                if(!isset($input['choose_Level'][$key]) || $input['choose_Level'][$key]==''){
                    continue;
                }
                $prize = LotteryPrizes::findOneByLottery($id,'lottery',$key);
                $channel_prize = LotteryPrizes::findOneByLottery($lottery_channel_id,'lottery_channel',$key);
                if($channel_prize){
                    if($channel_prize->rest_number >= $value) {
                        $channel_prize->rest_number = $channel_prize->rest_number-$value;
                        $channel_prize->update();
                    }else{
                        $this->_json([], '2003', '上级奖品不足');
                    }
                }else{
                    $this->_json([], '2003', '上级奖品不存在');
                }
                if($prize){
                    $prize->number += $value;
                    $prize->rest_number += $value;
                    $prize->level = $input['choose_Level'][$key];
                    $prize->update();
                }else{
                    $goods = LotteryGoods::findOne($key);
                    $prize = new LotteryPrizes();
                    $prize->channel_id = Session::get('user')->channel_id;
                    $prize->lottery_id = $id;
                    $prize->goods_id = $key;
                    $prize->type = 'lottery';
                    $prize->belong_to = $id;
                    $prize->name = '场次'.$id.'奖品'.$goods->goods_name;
                    $prize->level = $input['choose_Level'][$key]?:0;
                    $prize->number = $prize->rest_number = $value;
                    $prize->created_at = $prize->updated_at = time();
                    $prize->is_real = $goods->is_real;
                    $prize->save();
                }
            }
            if ($messages == ''){
                $this->_json([], '200', '成功');
            }else{
                $this->_json([], '2002', $messages);
            }
        }else{
            $id = Request::getQuery('id');
            $lottery_channel_id = Request::get('lottery_channel_id');

            $channel_prize = LotteryPrizes::findAllByType($lottery_channel_id , 'lottery_channel')->toArray();

            $lottery_prize_once = LotteryPrizes::findAllByType($id , 'lottery')->toArray();
            $lottery_prize = array();
            foreach ($lottery_prize_once as $key => $value){
                $lottery_prize[$value['goods_id']]= $value;
            }

            $this->_json(['channel_prize' => $channel_prize , 'lottery_prize' => $lottery_prize], '200', '成功');
        }

    }

    const LOTTERY_VERIFY = 'cztv::lottery::data::verify::';

    protected function saveVerifyRedis($lottery_id , $captcha_verify) {
        $vote = RedisIO::set(self::LOTTERY_VERIFY . $lottery_id , $captcha_verify);
        return $vote;
    }

}